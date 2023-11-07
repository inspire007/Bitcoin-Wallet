<?php
if(empty($_POST['login']))$login_required = 1;
include(dirname(__FILE__).'/loader.php');

$response = array();
$response['error'] = '';

if(!empty($_POST['login'])){
	$password = $_POST['password'];
	if(sha1(sha1($password)) == ADMIN_PWD_SHA1){
		set_login();
		output();	
	}
	$response['error'] = _('Wrong password');
	output();
}
else if(!empty($_POST['request_pay'])){
	$amount = trim($_POST['amount']);
	$currency = trim($_POST['currency']);
	$label = trim($_POST['label']);
	$desc = trim($_POST['description']);
	
	if(preg_match('/[^0-9\.]/i', $amount) || !is_numeric($amount)){
		$response['error'] = _("Invalid amount");
		output();		
	}
	if(!in_array($currency, array('usd', 'btc'))){
		$response['error'] = _("Invalid currency");
		output();		
	}
	if(preg_match('/[^a-zA-Z0-9\s]/i', $label)){
		$response['error'] = _("Invalid label. Only a-z A-Z 0-9 and spaces are allowed");
		output();		
	}
	if(preg_match('/[^a-zA-Z0-9\s]/i', $desc)){
		$response['error'] = _("Invalid description. Only a-z A-Z 0-9 and spaces are allowed");
		output();		
	}
	
	if($currency == 'btc'){
		$amount = btc_to_msatoshi($amount);	
	}
	else if($currency == 'usd'){
		//usd to btc 
		//btc to mshatoshi	
	}
	
	$api = new cltd();
	$data = $api->receivePay($amount, $currency, $label, $desc);
	if(!empty($data['bolt11'])){
		$response['bolt11'] = $data['bolt11'];
		$response['raw_data'] = $data;
		$response['qr'] = generate_qr_code($data['bolt11'], 1); 
		$response['html_code'] = '<h3>'._('Copy the bolt11 invoice ID below:').'</h3><span class="bwrap">'.$data['bolt11'].'</span><h3>Or Scan the QR code below:</h3><img src="'.$response['qr'].'"/>';	
		output();
	}
	else {
		$response['error'] = _('Error').' : '.(empty($data['message']) ? $data['error'] : $data['message']);
		$response['raw_data'] = $data;
		output();	
	}
}
else if(!empty($_POST['decode_pay'])){
	$bolt11 = trim($_POST['bolt11']);
	
	if(preg_match('/[^a-zA-Z0-9]/i', $bolt11)){
		$response['error'] = _("Invalid bolt11 ID");
		output();		
	}
	
	$api = new cltd();
	$data = $api->decodePay($bolt11);
	if(!empty($data['payment_hash'])){
		$api = new cltd();
		$response['expires_at'] = $data['created_at'] + $data['expiry'];
		$response['raw_data'] = $data;
		$response['amount'] = msatoshi_to_btc($data['msatoshi']);
		$response['currency'] = 'BTC';
		$response['pay_ref_code'] = $api->encryptRequest(array('bolt11' => $bolt11, 'time' => time(), 'rand' => rand(11111111111, 999999999999), 'api_key' => TRANSPORT_API_SECRET), $api->public_key);
		output();
	}
	else {
		$response['error'] = _('Error').' : '.(empty($data['message']) ? $data['error'] : $data['message']);
		$response['raw_data'] = $data;
		output();	
	}
}
else if(!empty($_POST['pay_now'])){
	$pay_ref = trim($_POST['pay_ref']);
	$amount_btc = trim($_POST['amount_btc']);
	$fixed_amount = (int)$_POST['fixed_amount'];
	
	if(preg_match('/[^0-9\.]/i', $amount_btc)){
		$response['error'] = _("Invalid amount");
		output();		
	}
	
	$api = new cltd();
	$data = $api->decryptRequest($pay_ref, $api->private_key);
	$data = json_decode($data, true);
	
	if($data['api_key'] != TRANSPORT_API_SECRET){
		$response['error'] = _("Invalid request");
		output();	
	}
	
	
	if(time() - $data['time'] > 60){
		$response['error'] = _("Request expired. Please try again. For security reasons pay request must be placed within a minute of decodepay.");
		output();	
	}
	
	$result = $api->sendPay($data['bolt11'], $amount_btc, $fixed_amount);
	$response['raw_data'] = $result;
	if(!empty($result['payment_hash'])){
		output();
	}
	else {
		$response['error'] = _('Error').' : '.(empty($result['message']) ? $result['error'] : $result['message']);
		output();	
	}
}
else if(!empty($_POST['open_fund_channel'])){
	$node_addr = trim($_POST['node_addr']);
	$amount = trim($_POST['amount']);
	$currency = trim($_POST['currency']);
	
	if(preg_match('/[^a-zA-Z0-9\.\@\:]/i', $node_addr)){
		$response['error'] = _("Invalid node address");
		output();		
	}
	
	if(preg_match('/[^0-9\.]/i', $amount)){
		$response['error'] = _("Invalid amount");
		output();		
	}
	
	if($currency == 'usd'){
		//usd	
	}
	else {
		$amount = btc_to_satoshi($amount);	
	}
	
	$api = new cltd();
	$result = $api->connChannel($node_addr);
	if(empty($result['id'])){
		$response['error'] = _('Error').' : '.(empty($result['message']) ? $result['error'] : $result['message']);
		output();
	}
	
	list($node_id) = explode('@', $node_addr);
	$result = $api->fundChannel($node_id, $amount);
	$response['raw_data'] = $result;
	if(!empty($result['tx'])){
		output();
	}
	else {
		$response['error'] = _('Error').' : '.(empty($result['message']) ? $result['error'] : $result['message']);
		output();	
	}
}
else{
	$response['error'] = "Unknown request";
	output();	
}