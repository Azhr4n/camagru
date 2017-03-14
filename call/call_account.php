<?php
require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');
require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('security', 'CSRFToken.class.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
require_once(Urls::getPath('database', 'UsersDB.class.php'));
session_start();

if (isset($_POST['save']) && $_POST['save'] == 'ok')
{
	if (CSRFToken::verifyToken(300, $_POST['csrf_token'], Urls::getUrl('account.php'), 'csrf_account'))
	{
		if (!empty($_POST['password']))
		{
			$upassword = hash('whirlpool', htmlentities($_POST['password']));
			if ($_SESSION['User']->getPassword() == $upassword)
			{
				if (!empty($_POST['new_password']))
				{
					if (!empty($_POST['confirmation']))
					{
						if ($_POST['new_password'] == $_POST['confirmation'])
						{
							$password = hash('whirlpool', htmlentities($_POST['new_password']));
							if ($_SESSION['User']->setData(['password'=>$password]))
								$_SESSION['Message'] = 'Password changed.';
							else
								$_SESSION['Message'] = 'Error.';
						}
						else
							$_SESSION['Message'] = 'Password and confirmation does not match.';
					}
					else
						$_SESSION['Message'] = 'Confirmation required.';
				}
				else if ($_POST['email'] != $_SESSION['User']->getEmail())
				{
					$email = htmlentities($_POST['email']);
					$database = new UsersDB($DB_DSN);
					$user = $database->get(['email'=>$email]);
					if (!$user) {
						if ($_SESSION['User']->setData(['email'=>$email]))
							$_SESSION['Message'] = 'Email changed.';
						else
							$_SESSION['Error.'];
					} else
						$_SESSION['Message'] = 'Email invalid.';
				}
			}
			else
				$_SESSION['Message'] = 'Password invalid.';
		}
		else
			$_SESSION['Message'] = 'Password required.';
	}
	else
		$_SESSION['Message'] = 'Session expired.';
}
Header('Location: '.Urls::getUrl('account.php'));
?>