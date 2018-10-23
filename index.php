<?php
error_reporting(0);

define('SKL', true);
define('INC', '/var/opt/inc/');

include('functions.php');
include(INC.'lib/sessionBlocksCheck.php');

maybeCSSRequest();

$users = getUsers();
$check = checkRememberSession();
$login = (!$check && isset($_POST['login']));
$user  = ($login ? userLogin($_POST['username'], $_POST['password'], $users) : $check);

if(isset($user['userid']) && in_array($user['userid'], getAuthorized()))
	include(getPrefix().'_project.php');
else
	include('login.php');
?>
