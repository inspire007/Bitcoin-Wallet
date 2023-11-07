<?php
include(dirname(__FILE__).'/loader.php');

$currency = @$settings['currency'];
if(empty($currency))$currency = 'USD';

session_write_close();

$delay = 30;

$lock = dirname(__FILE__).'/cache/rate_updater.lock';
$stop = dirname(__FILE__).'/cache/rate_updater.stop';
$log = dirname(__FILE__).'/cache/rate_updater.log';
$save = dirname(__FILE__).'/cache/btc_rate.json';

if(file_exists($stop))exit(_('Process stopped by file'));

if(check_running_file($lock))exit(_('Process already running'));
$fp = lock_file($lock);

do{
	$url = "https://www.bitstamp.net/api/v2/ticker/BTC".$currency."/";
	$data = curl_single($url);
	
	$d = json_decode($data, true);
	
	if(!empty($d['ask']))file_put_contents($save, $data);
	else{
		//error handler	
		do_log("Request failed : ".$data, $log);
	}
	
	$t = time();
	do{
		if(file_exists($stop))exit(_('Process stopped by file'));
		sleep(1);
		if((time() - $t) > $delay)break;
	}while(1);
}while(1);

unlock_file($fp);

?>