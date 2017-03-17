<?php
require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');

require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
require_once(Urls::getPath('database', 'UsersDB.class.php'));
session_start();

if (isset($_POST['reset']) && $_POST['reset'] == 'ok') {
	if (CSRFToken::verifyToken(300, $_POST['csrf_token'], Urls::getUrl('login.php'), 'csrf_reset'))
	{
		$database = new UsersDB($DB_DSN);
		if (isset($_POST['email'])) {
			$email = htmlentities($_POST['email']);
			$user = $database->get(['email'=>$email]);
			if ($user) {
				$user = $user[0];
				$token = $_SESSION['User']->createToken();
				$timeout = time();
				$database->set($user, ['token'=>$token, 'token_timeout'=>$timeout]);
				if ($user->sendResetMail($token)) {
					$_SESSION['Message'] = 'Email sent.';
					Header('Location: '.Urls::getUrl('index.php'));
				} else
					$_SESSION['Message'] = 'Error sending email. Try again.';
			} else
				$_SESSION['Message'] = 'Invalid email.';
		}
		else if (isset($_POST['password']) && isset($_POST['confirmation'])) {
			$password = htmlentities($_POST['password']);
			$confirmation = htmlentities($_POST['confirmation']);
			$token = htmlentities($_POST['token']);
			if ($password == $confirmation) {
				if (!empty($password) && !empty($confirmation)) {
					$password = hash('whirlpool', $password);
					$user = $database->get(['token'=>$token]);
					if ($user) {
						$user = $user[0];
						if (time() - $user->getToken()['timeout'] < 600) {
							$database->set($user, ['password'=>$password, 'token'=>null]);
							$_SESSION['Message'] = 'Password reseted.';
							Header('Location: '.Urls::getUrl('login.php'));
						} else
							$_SESSION['Message'] = 'Link expired.';
					}
					else
						Header('Location: '.Urls::getUrl('login.php'));
				}
				else
					$_SESSION['Message'] = 'Password and confirmation required.';
			}
			else
				$_SESSION['Message'] = 'Password must match.';
		}
	} else
		$_SESSION['Message'] = 'Session expired.';
}

?>