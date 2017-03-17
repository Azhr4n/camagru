<?php
require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');
require_once(Urls::getPath('security', 'CSRFToken.class.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
session_start();

if (isset($_POST['login']) && $_POST['login'] == 'ok')
{
	if (!empty($_POST['username']))
	{
		if (!empty($_POST['password']))
		{
			if (CSRFToken::verifyToken(300, $_POST['csrf_token'], Urls::getUrl('login.php'), 'csrf_login'))
			{
				$username = htmlentities($_POST['username']);
				$password = hash('whirlpool', htmlentities($_POST['password']));
				$ret = $_SESSION['User']->login($username, $password);
				$_SESSION['Message'] = $ret;
				Header('Location: '.Urls::getUrl('index.php'));
			}
			else
				$_SESSION['Message'] = 'Session expired.';
		}
		else
			$_SESSION['Message'] = 'Password required.';
	}
	else
		$_SESSION['Message'] = 'Username required.';
}
Header('Location: '.Urls::getUrl('login.php'));

?>