<?php
/**
 * Function to connect to mysql
 */
function sql_conn()
{			
	global $mysqli;	
	if($mysqli)@mysqli_close($mysqli);
	
	$mysqli = @mysqli_connect(DB_HOST, DB_USER, DB_PASS);
	if(!$mysqli)return false;
	
	$db = @mysqli_select_db($mysqli, DB_NAME);
	if(!$db)return false;
	
	$charset = @mysqli_set_charset($mysqli, 'utf8mb4');
	if(!$charset)$charset = @mysqli_set_charset($mysqli, 'utf8');
	if(!$charset)return false;
	
	return $mysqli;
}

/**
 * Mysql functions
 */
function sql_connect($db_host, $db_user, $db_pwd)
{
	return @mysqli_connect($db_host, $db_user, $db_pwd);
}

function sql_connect_error(){
	return mysqli_connect_error();	
}

function sql_select_db($db_name)
{
	global $mysqli;
	return @mysqli_select_db($mysqli, $db_name);
}

function sql_num_rows($resource)
{
	global $mysqli;
	return mysqli_num_rows($resource);
}

function sql_query($query)
{
	global $mysqli;
	return mysqli_query($mysqli, $query);
}

function sql_fetch_assoc($resource)
{
	global $mysqli;
	return mysqli_fetch_assoc($resource);
}

function sql_fetch_row($resource)
{
	global $mysqli;
	return mysqli_fetch_row($resource);
}

function sql_affected_rows()
{
	global $mysqli;
	return mysqli_affected_rows($mysqli);
}

function sql_error()
{
	global $mysqli;
	return mysqli_error($mysqli);
}

function sql_escape_string($string)
{
	global $mysqli;
	return mysqli_escape_string($mysqli, $string);
}

function sql_real_escape_string($string)
{
	global $mysqli;
	return mysqli_real_escape_string($mysqli, $string);
}

function sql_insert_id()
{
	global $mysqli;
	return mysqli_insert_id($mysqli);
} 

function sql_close()
{
	global $mysqli;
	return mysqli_close($mysqli);
}

function sql_data_seek($result, $offset)
{
	return mysqli_data_seek($result, $offset);	
}

function spl_autoloader($class)
{
	if(file_exists(dirname(__FILE__)."/classes/{$class}.class.php")){
		include(dirname(__FILE__)."/classes/{$class}.class.php");
	}
	else{
		$class = strtolower($class);
		if(file_exists(dirname(__FILE__)."/classes/{$class}.class.php")){
			include(dirname(__FILE__)."/classes/{$class}.class.php");
		}
	}
}

function load_settings()
{
	$settings = array();
	$data = @file_get_contents(SETTINGS_FILE_PATH);
	if(!empty($data))$settings = json_decode($data, true);
	
	if(empty($settings['currency']))$settings['currency'] = 'USD';
	if(empty($settings['lang']))$settings['lang'] = 'EN';
	
	$settings['exchange_rates'] = array();
	
	$rate_file = dirname(__FILE__).'/cache/btc_rate.json';
	if(file_exists($rate_file)){
		$d = file_get_contents($rate_file);
		$d = json_decode($d, true);
		$settings['exchange_rates'] = $d;
	}
	return $settings;
}

function rand_string($length = 5)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function btc_to_msatoshi($amount)
{
	return $amount*10000000000;
}

function btc_to_satoshi($amount)
{
	return $amount*100000000;
}

function msatoshi_to_btc($amount)
{
	return $amount/10000000000;
}

function satoshi_to_btc($amount)
{
	return $amount/100000000;
}

function mshatoshi_to_satoshi($mst)
{
	return $mst/1000;	
}

function create_ajax_nonce()
{
	return encrypt(TRANSPORT_API_SECRET."|".time()."|".rand(1111111111,999999999));
}

function validate_ajax_nonce($s)
{
	$s = decrypt($s);
	$s = explode("|");
	if($s[0] != TRANSPORT_API_SECRET)return false;
	if(time() - $s[1] > 120)return false;
	return true;
}

function get_transaction_history_table_html($limit = 0, $small_table = 0)
{
	global $cached_data;
	
	$html = '';
	
	$i = 0;
	
	foreach($cached_data['history']['data'] as $history){
		
		if(++$i > $limit && $limit)break;
		
		$icon = empty($history['bolt11']) ? 'up' : 'down';
		$date = date('d-M-y', $history['time']);
		$time = date('H:i:s', $history['time']);
		
		if($icon == 'up')$btc = msatoshi_to_btc($history['msatoshi_sent']);
		else $btc = msatoshi_to_btc($history['msatoshi_received']);
				
		$html .= '
		<table class="ui basic table tr_table">
		  <tbody>
			<tr>
			  <td><i class="angle '.$icon.' icon"></i></td>
			  <td class="tr_table_date">
				<span class="tr_table_date_date">'.$date.'</span>'.($small_table ? '<br/>' : '').'
				<span class="tr_table_date_time">'.$time.'</span>
			  </td>
			  <td class="tr_table_amount '.(!$small_table ? 'tr_table_amount_big' : '').'">'.($icon == 'up' ? '-' : '+').$btc.' BTC</td>
			  <td class="tr_table_details_btn"><i class="plus square outline icon large show_tr_details"></i><i class="minus square outline icon large hide_tr_details"></i></td>
			</tr>
			<tr class="tr_details">
				<td colspan="100">                    
					<div class="ui list tr_details_div">
						<div class="item">
							<div class="header">'._('Payment Hash').'</div>
							'.$history['payment_hash'].'
						</div>
						<div class="item">
							<div class="header">'._($icon == 'up' ? 'Destination' : 'Invoice bolt11').'</div>
							'.($icon == 'up' ? $history['destination'] : $history['bolt11']).'
						</div>
						<div class="item">
							<div class="header">'._('Amount').'</div>
							'.$btc.'BTC
						</div>
						<div class="item">
							<div class="header">'._('Status').'</div>
							'._($history['status']).'
						</div>
					</div>
				</td>
			</tr>
		  </tbody>
		</table>';
		
	}
	
	return $html;
}

function curl_single($url, $post = array(), $timeout = 30, $headers = array())
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	@curl_setopt($ch, CURLOPT_SAFE_UPLOAD, 0);
	if(!empty($post)){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if(!empty($headers)){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:31.0) Gecko/20100101 Firefox/31.0');
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	$data = curl_exec($ch);
	return $data;
}

function load_cache()
{
	$data = @file_get_contents(CACHE_FILE_PATH);
	if(empty($data))return false;
	
	$iv = @file_get_contents(CACHE_IV_FILE_PATH);	
	if(empty($iv))return false;
	
	$data = decrypt($data, $iv);
	$data = json_decode($data, true);
	
	return $data;
}

function save_cache($data_array)
{
	$iv = bin2hex(openssl_random_pseudo_bytes(8));
	$data = json_encode($data_array);
	$data = encrypt($data, $iv);
	
	file_put_contents(CACHE_FILE_PATH, $data);
	file_put_contents(CACHE_IV_FILE_PATH, $iv);
}

function update_cache()
{
	global $cached_data;
	
	$to_update = array(
		'balance' => 0,
		'info' => 0,
		'history' => 0,
		'limits' => 0
	);
	
	if(empty($cached_data)){
		$to_update = array(
			'balance' => 1,
			'info' => 1,
			'history' => 1,
			'limits' => 1
		);
	}
	if(empty($cached_data['balance'])){
		$to_update['balance'] = 1;
	}
	if(empty($cached_data['info'])){
		$to_update['info'] = 1;
	}
	if(empty($cached_data['history'])){
		$to_update['history'] = 1;
	}
	if(empty($cached_data['limits'])){
		$to_update['limits'] = 1;
	}
	
	/*
	if($data['listfunds']['lastupdate'] > ){
		
	}
	*/
	$save = 0;
	
	if($to_update['balance'] == 1){
		$save = 1;
		$api = new cltd();
		$balance = $api->getBalance();	
		
		$cached_data['balance'] = array(
			'data' => $balance,
			'last_update' => time()
		);
	}
	if($to_update['info'] == 1){
		$save = 1;
		$api = new cltd();
		$info = $api->getInfo();	
		
		$info['node_addr'] = $info['id'].'@'.$info['address'][0]['address'].':'.$info['address'][0]['port'];
		
		$cached_data['info'] = array(
			'data' => $info,
			'last_update' => time()
		);
	}
	if($to_update['history'] == 1){
		$save = 1;
		$api = new cltd();
		$info = $api->getHistory();	
		
		$cached_data['history'] = array(
			'data' => $info,
			'last_update' => time()
		);
	}
	if($to_update['limits'] == 1){
		$save = 1;
		$api = new cltd();
		$info = $api->getLimits();	
		
		$cached_data['limits'] = array(
			'data' => $info,
			'last_update' => time()
		);
	}
	
	if($save)save_cache($cached_data);
}

function encrypt($string, $nonce = '')
{
	$string = @openssl_encrypt($string, TRANSPORT_API_ENC_METHOD, TRANSPORT_API_SECRET, 0, $nonce);
	$string = base64_encode($string);
	return $string;
}

function decrypt($string, $nonce = '')
{
	$string = base64_decode($string);
	$string = @openssl_decrypt($string, TRANSPORT_API_ENC_METHOD, TRANSPORT_API_SECRET, 0, $nonce);
	return $string;
}

function check_login_status()
{
	if(empty($_SESSION['logged_in']))return false;
	return true;	
}

function set_login()
{
	$_SESSION['logged_in'] = 1;	
}


function logout()
{
	unset($_SESSION['logged_in']);	
}

function redirect($url)
{
	header('location: '.$url);
	exit();
}

function generate_qr_code($text, $pay = 0)
{
	if($pay)$n = 'images/qr/payments/'.sha1($text).'.png';
	else $n = 'images/qr/'.sha1($text).'.png';
	
	$path = dirname(__FILE__).'/'.$n;
	if(file_exists($path))return $n;
	
	include(dirname(__FILE__).'/assets/third-party/phpqrcode/qrlib.php');
	QRcode::png($text, $path, QR_ECLEVEL_L, 6);
	
	return $n;
}

function makeuri($url)
{
	return $url;	
}

function check_running_file($file)
{
	$running=0;
	if(file_exists($file)){
		$fp=fopen($file,"r");
		if(!flock($fp,LOCK_EX | LOCK_NB))$running=1;
		fclose($fp);
	}
	return $running;
}

function lock_file($file)
{
	$fp = fopen($file,"w");
	flock($fp, LOCK_EX);
	return $fp;
}

function unlock_file($fp)
{
	@flock($fp, LOCK_UN);
	@fclose($fp);
}

function do_log($str, $log_file = '', $clear = 0)
{
	$master_log_file = dirname(__FILE__).'/logs/log.txt';
	if(!empty($log_file))$master_log_file = $log_file;
	if($clear)$fp = fopen($master_log_file, "w");
	else $fp = fopen($master_log_file, "a");
	fwrite($fp,date('[d-M-Y H:i:s]')." $str\r\n");
	fclose($fp);
}

function output()
{
	global $response;
	echo json_encode($response);
	exit;
}

?>