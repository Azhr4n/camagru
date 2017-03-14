<?php
require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');
require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('security', 'CSRFToken.class.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
require_once(Urls::getPath('database', 'UsersDB.class.php'));
session_start();

if (isset($_POST['register']) && $_POST['register'] == 'ok')
{
	if (!empty($_POST['username']))
	{
		if (!empty($_POST['password']))
		{
			if (!empty($_POST['confirmation']))
			{
				if ($_POST['password'] == $_POST['confirmation'])
				{
					if (!empty($_POST['email']))
					{
						if (CSRFToken::verifyToken(300, $_POST['csrf_token'], Urls::getUrl('register.php'), 'csrf_register'))
						{
							$username = htmlentities($_POST['username']);
							$password = hash('whirlpool', htmlentities($_POST['password']));
							$email = htmlentities($_POST['email']);

							$database = new UsersDB($DB_DSN);
							$user = $database->get(['username'=>$username]);
							if (!$user) {
								$user = $database->get(['email'=>$email]);
								if (!$user) {
									$ret = $_SESSION['User']->register($username, $password, $email);
									$_SESSION['Message'] = $ret;
									Header('Location: '.Urls::getUrl('index.php'));
								}
								else
									$_SESSION['Message'] = 'This email is already taken.';
							}
							else
								$_SESSION['Message'] = 'This username already exist.';
						}
						else
							$_SESSION['Message'] = 'Session expired.';
					}
					else
						$_SESSION['Message'] = 'Email required.';
				}
				else
					$_SESSION['Message'] = 'Password and confirmation does not match.';
			}
			else
				$_SESSION['Message'] = 'Confirmation required.';
		}
		else
			$_SESSION['Message'] = 'Password required.';
	}
	else
		$_SESSION['Message'] = 'Username required.';
}
Header('Location: '.Urls::getUrl('register.php'));

?>