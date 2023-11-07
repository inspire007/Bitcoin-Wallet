<?php
$login_required = 1;
include(dirname(__FILE__).'/loader.php');

$title = _("Send Payment");
include(dirname(__FILE__).'/templates/header.php');
include(dirname(__FILE__).'/templates/pay.php');
include(dirname(__FILE__).'/templates/footer.php');
