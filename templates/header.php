<?php if(!defined('__ROOT__'))exit();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title?></title>
<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="assets/css/semantic.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/custom.css?t=<?php echo time()?>"">
<script>var ajax_url = '<?php echo makeuri('ajax.php')?>';var ajax_nonce = '<?php echo create_ajax_nonce()?>';var lang = '<?php echo $settings['lang']?>';var currency = '<?php echo $settings['currency']?>'</script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="<?php echo makeuri('assets/js/semantic.min.js')?>"></script>
<script src="<?php echo makeuri('assets/js/custom.js')?>?t=<?php echo time()?>"></script>
<script type="text/javascript" src="<?php echo makeuri("assets/js/js_lang.php")?>"></script>
</head>

<body>

<?php if(empty($hide_menu)):?>
<!-- Following Menu -->
<div class="ui vertical center aligned">
    <div class="ui container">
    	<div class="ui large secondary pointing menu">
    		<a class="toc item">
      			<i class="sidebar icon"></i>
    		</a>	
    		<a class="home item" href="<?php echo makeuri('index.php')?>"><?php echo _('Home')?></a>
    		<a class="history item" href="<?php echo makeuri('history.php')?>"><?php echo _('History')?></a>
    		<a class="send item" href="<?php echo makeuri('pay.php')?>"><?php echo _('Send')?></a>
    		<a class="receive item" href="<?php echo makeuri('receive.php')?>"><?php echo _('Receive')?></a>
    		<div class="right item">
            		<?php if($logged_in):?>
            		<span class="ui blue basic label header_btc_rate">B to $ <span class="header_btc_rate_num"><?php echo $settings['exchange_rates']['ask']?></span></span>
                    <span class="ui blue basic label header_btc_rate">Balance <span class="header_btc_rate_num"><?php echo satoshi_to_btc($cached_data['balance']['data'])?> BTC</span></span>&nbsp; &nbsp;
                    <span class="alias_name" style="color:#<?php echo $cached_data['info']['data']['color']?>">
						<?php echo $cached_data['info']['data']['alias']?>
                    </span>&nbsp; &nbsp;
                    <a class="ui button" href="<?php echo makeuri('logout.php')?>"><?php echo _('Logout')?></a>
                	<?php else:?>
      				<a class="ui orange button" href="<?php echo makeuri('login.php')?>">Log in</a>
                    <?php endif;?>
    		</div>
    	</div>
    </div>
</div>
<!-- Sidebar Menu -->
<div class="ui vertical sidebar menu">
	<a class="active item"><?php echo _('Home')?></a>
    <a class="item home" href="<?php echo makeuri('index.php')?>"><?php echo _('History')?></a>
    <a class="item pay" href="<?php echo makeuri('pay.php')?>"><?php echo _('Send')?></a>
    <a class="item receive" href="<?php echo makeuri('receive.php')?>"><?php echo _('Receive')?></a>
    <?php if($logged_in):?>
    <a class="item logout" href="<?php echo makeuri('logout.php')?>"><?php echo _('Log out')?></a>
    <?php else:?>
    <a class="item login" href="<?php echo makeuri('login.php')?>"><?php echo _('Log in')?></a>
    <?php endif;?>
</div>
<p></p>
<?php endif;?>