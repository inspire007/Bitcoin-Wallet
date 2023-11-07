<?php
$login_required = 1;
include(dirname(__FILE__).'/loader.php');

$title = _("Open Channel");
include(dirname(__FILE__).'/templates/header.php');
include(dirname(__FILE__).'/templates/fundchannel.php');
include(dirname(__FILE__).'/templates/footer.php');
