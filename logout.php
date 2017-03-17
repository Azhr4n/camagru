<?php
require_once(dirname(__FILE__).'/urls/Urls.class.php');
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
session_start();

$_SESSION['User']->logout();
Header('Location: index.php');

?>