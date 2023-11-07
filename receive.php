<?php
$login_required = 1;
include(dirname(__FILE__).'/loader.php');

$title = _("Receive Payment");
include(dirname(__FILE__).'/templates/header.php');
include(dirname(__FILE__).'/templates/receive.php');
include(dirname(__FILE__).'/templates/footer.php');
