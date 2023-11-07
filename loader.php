<?php

@session_start();
@ob_start();

if(!file_exists(dirname(__FILE__).'/config.php')){
	echo json_encode(array("error" => "Server configuration error. Please contact webmaster."));
	exit();	
}

include(dirname(__FILE__).'/functions.php');
include(dirname(__FILE__).'/config.php');

$settings = load_settings();

spl_autoload_register('spl_autoloader');
date_default_timezone_set('UTC');

$logged_in = check_login_status();

if(empty($logged_in) && !empty($login_required)){
	redirect('login.php');
	exit;	
}

if(!empty($logged_in) && !empty($logout_required)){
	redirect('index.php');
	exit;	
}

$cached_data = load_cache();
update_cache();

?>