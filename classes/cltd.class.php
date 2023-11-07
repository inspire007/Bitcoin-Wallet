<?php

class cltd
{
	public $remote_api_public_key;
	
	public $public_key;
	public $private_key;
	public $nonce;

	public function __construct()
	{
		$this->remote_api_public_key = file_get_contents(REMOTE_API_PUBLIC_KEY_FILE_PATH);
		
		$this->public_key = file_get_contents(PUBLIC_KEY_FILE_PATH);
		$this->private_key = file_get_contents(PRIVATE_KEY_FILE_PATH);
	}
	
	public function getInfo()
	{
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = "getinfo";
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'getinfo';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		return $response;
	}
	
	//returns milishatoshi
	public function getBalance()
	{
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = "listfunds";
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'listfunds';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		
		if(empty($response['outputs']))return false;
		$fund = 0;
		foreach($response['outputs'] as $item){
			$fund += $item['value'];	
		}
		return $fund;
	}
	
	public function getLimits()
	{
		$limits = array(
			'sendable' => 0,
			'receivable' => 0,
			'total' => 0
		);
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = "listpeers";
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'listpeers';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		
		if(empty($response['peers']))return false;
		foreach($response['peers'] as $item){
			foreach($item['channels'] as $ch){
				$limits['sendable'] += $ch['spendable_msatoshi'];	
				$limits['receivable'] += ($ch['msatoshi_total'] - $ch['spendable_msatoshi']);
				$limits['total'] += $ch['msatoshi_total'];
			}
		}
		return $limits;
	}
	
	public function getHistory()
	{
		$history = array();
		
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = "listinvoices";
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'listinvoices';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		
		if(!empty($response['invoices'])){
			foreach($response['invoices'] as $pay){
				if(!in_array($pay['status'], array('complete', 'paid')))continue;
				$pay['time'] = $pay['paid_at'];
				$history[] = $pay;	
			}	
		}
		
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = "listpayments";
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'listpayments';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		
		if(!empty($response['payments'])){
			foreach($response['payments'] as $pay){
				if(!in_array($pay['status'], array('complete', 'paid')))continue;
				$pay['time'] = $pay['created_at'];
				$history[] = $pay;	
			}	
		}
		
		array_multisort(array_column($history, 'time'), SORT_DESC, $history);
		return $history;
	}
	
	public function sendPay($bolt11, $amount_btc, $fixed_amount)
	{
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$amount = btc_to_msatoshi($amount_btc);
		$request['cmd'] = 'pay '.escapeshellarg($bolt11).' '.escapeshellarg($fixed_amount ? '' : $amount);
				
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'pay';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		return $response;
	}
	
	public function receivePay($amount, $currency, $label, $desc)
	{
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = 'invoice '.escapeshellarg($amount).' '.escapeshellarg($label).' '.escapeshellarg($desc);
				
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'invoice';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		return $response;
	}	
	
	
	public function decodePay($bolt11)
	{
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = 'decodepay '.escapeshellarg($bolt11);
		
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'decodepay';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		return $response;
	}	
	
	public function connChannel($node_addr)
	{
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = 'connect '.escapeshellarg($node_addr);
		
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'connect';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		return $response;
	}
	
	public function fundChannel($node_id, $sathoshi)
	{
		$request = array();
		$request['api_key'] = TRANSPORT_API_SECRET;
		$request['cmd'] = 'fundchannel '.escapeshellarg($node_id).' '.escapeshellarg($sathoshi);
		
		$request['timestamp'] = time();
		
		$encryptedRequest = $this->encryptRequest($request);
		
		$postData = array();
		$postData['request'] = $encryptedRequest;
		$postData['method'] = 'fundchannel';
		
		$this->generateSignature($postData);
		
		$url = API_BASE_URL;
		$response = curl_single($url, $postData);
		
		$response = json_decode($response, true);
		return $response;
	}	
	
	public function encryptRequest($request, $public_key = '')
	{
		$string = json_encode($request);
		$sym_key = bin2hex(openssl_random_pseudo_bytes(8)); 
	    
	    $string = $this->sig_encrypt($string, $sym_key);
	    $sym_key = openssl_public_encrypt($sym_key, $sym_key_output, $public_key ? $public_key : $this->remote_api_public_key, OPENSSL_PKCS1_OAEP_PADDING);
		$sym_key_output = base64_encode($sym_key_output);
		
	    $len = strlen($sym_key_output); 
	    $len = dechex($len); 
	    $len = str_pad($len, 3, '0', STR_PAD_LEFT); 
			
	    $message = $len.$sym_key_output.$string;

	    return $message;
	}
	
	public function decryptRequest($string, $private_key)
	{
	    $len = substr($string, 0, 3); 
	    $len = hexdec($len); 
		                       
	    $sym_key = substr($string, 3, $len);
	    $ciphertext = substr($string, 3);
		$ciphertext = substr($ciphertext, $len);
		
	    $sym_key = base64_decode($sym_key);
		$sym_key = openssl_private_decrypt($sym_key, $sym_key_output, openssl_pkey_get_private($private_key ? $private_key : $this->private_key, PRIV_KEY_PASS), OPENSSL_PKCS1_OAEP_PADDING);	                     
	    $plaintext = $this->sig_decrypt($ciphertext, $sym_key_output);                                                 
		return $plaintext;
		
	}
	
	public function sig_encrypt($string, $nonce = '')
	{
		$string = openssl_encrypt($string, TRANSPORT_API_ENC_METHOD, TRANSPORT_API_SECRET, 0, !empty($nonce) ? $nonce : $this->nonce);
		$string = base64_encode($string);
		return $string;
	}
	
	public function sig_decrypt($string, $nonce = '')
	{
		$string = base64_decode($string);
		$string = openssl_decrypt($string, TRANSPORT_API_ENC_METHOD, TRANSPORT_API_SECRET, 0, !empty($nonce) ? $nonce : $this->nonce);
		return $string;
	}
	
	public function generateSignature(& $postData)
	{
		$iv = bin2hex(openssl_random_pseudo_bytes(8));
		$data = $postData;
		ksort($data);
		$signature = http_build_query($data);
		$signature = openssl_encrypt($signature, 'AES-256-CBC', TRANSPORT_API_SECRET, 0, $iv);
		$signature = base64_encode($signature);
		$signature = sha1($signature);
	
		$postData['nonce'] = $iv;
		$postData['signature'] = $signature;
	}
}

?>