<?php
require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
session_start();
if (!isset($_SESSION['User']))
	$_SESSION['User'] = new CurrentUser($DB_DSN);
else
	$_SESSION['User']->refreshUser();
?>