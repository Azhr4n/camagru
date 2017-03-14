<?php
require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
session_start();

if (isset($_POST['send']) && $_POST['send'] == 'ok') {
	if (isset($_POST['token_name'])) {
		$token_name = htmlentities($_POST['token_name']);
		echo $_SESSION['User']->CSRFToken($token_name);
	}
}

?>