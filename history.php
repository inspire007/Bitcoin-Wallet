<?php
$login_required = 1;
include(dirname(__FILE__).'/loader.php');

$title = _("Transaction History");
include(dirname(__FILE__).'/templates/header.php');
include(dirname(__FILE__).'/templates/history.php');
include(dirname(__FILE__).'/templates/footer.php');
