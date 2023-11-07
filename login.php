<?php

$logout_required = 1;
$hide_menu = 1;

include(dirname(__FILE__).'/loader.php');

$title = _("Login");
include(dirname(__FILE__).'/templates/header.php');
include(dirname(__FILE__).'/templates/login.php');
include(dirname(__FILE__).'/templates/footer.php');
