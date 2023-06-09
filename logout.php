<?php 

include('connect.php');

checkFalse($_SESSION['log']);

header('Location: login_and_sign_up.php');
$_SESSION['log'] = false;
exit;

?>