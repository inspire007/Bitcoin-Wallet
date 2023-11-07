<?php
$login_required = 1;
include(dirname(__FILE__).'/loader.php');

$title = _('Bitcoin C-Lightning Wallet');
include(dirname(__FILE__).'/templates/header.php');
include(dirname(__FILE__).'/templates/home.php');
include(dirname(__FILE__).'/templates/footer.php');
