<?php
$login_required = 1;

include(dirname(__FILE__).'/loader.php');
logout();

header('location:'.makeuri('login.php'));
?>