<?php
require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
session_start();

$_SESSION['User']->logout();
Header('Location: index.php');

?>