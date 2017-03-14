<?php

require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('user', 'User.class.php'));
require_once(Urls::getPath('database', 'UsersDB.class.php'));
require_once(Urls::getPath('security', 'CSRFToken.class.php'));

class CurrentUser extends User
{
	private	$_logged_in;
	private $_db_path;

	function __construct($db_path) {
		// setcookie('logged', '');
		$this->_db_path = $db_path;
		$this->_logged_in = 0;
		$this->_name = '';
	}

	function __destruct() {}

	private function createToken($length=128) {
		return bin2hex(openssl_random_pseudo_bytes($length / 2));
	}

	function CSRFToken($name) {
		return new CSRFToken($name);
	}

	// private function _setMail() {
	// 	$mail = new PHPMailer;

	// 	$mail->isSMTP();
	// 	$mail->SMTPAuth = true;
	// 	$mail->SMTPSecure = 'tls';

	// 	$mail->Host = 'smtp.gmail.com';
	// 	$mail->Port = 587;

	// 	$mail->Username = 'imagein.entreprise@gmail.com';
	// 	$mail->Password = 'p9K%qmIP8w&@';

	// 	$mail->isHTML(true);

	// 	return $mail;
	// }

	// private function _sendNoreplyMail($target, $subject, $body) {
		
	// 	$mail = $this->_setMail();

	// 	$mail->setFrom('imagein.entreprise@gmail.com', 'noreply');
		
	// 	$mail->Subject = $subject;
	// 	$mail->Body = $body;
	// 	$mail->AltBody = $body;
	// 	$mail->addAddress($target);

	// 	if ($mail->send())
	// 		return TRUE;
	// 	return $mail->ErrorInfo;
	// }

	function isLogged() {
		return ($this->_logged_in);
	}

	function register($username, $password, $email) {
		$user = new User($username, $password, $email, null, null,
			['token'=>$token, 'timeout'=>date('Y-m-d H:i:s')], null);
		$database = new UsersDB($this->_db_path);
		$ret = $database->createAccount($user);
		if ($ret) {
			if (mail('firefrai@gmail.com', 'test', 'Hey this is a test !'))
				return 'An email has been sent.';
			else {
				// $database->delUser($user);
				return 'Error sending email.';
			}
		}
		else if ($ret == FALSE)
			return 'Error creating account.';
		else
			return 'Database error.';
	}

	function refreshUser() {
		if ($this->_logged_in) {
			$database = new UsersDB($this->_db_path);
			$user = $database->get(['username'=>$this->_name])[0];
			if ($user instanceof User) {
				$this->morph($user);
			}
			else
				$this->_logged_in = FALSE;
		}
	}

	function logIn($username, $password) {
		$database = new UsersDB($this->_db_path);
		$user = $database->auth($username, $password);
		if ($user instanceof User) {
			$this->morph($user);
			$this->_logged_in = 1;
			return '';
			// setcookie('logged', $this->_name);
		} else
			return 'Invalid username or password.'.PHP_EOL;
	}

	function setData(array $data) {
		if (!empty($this->_name)) {
			$database = new UsersDB($this->_db_path);
			return $database->set($this, $data);
		}
		return false;
	}

	function logOut() {
		// setcookie('logged', '');
		$this->_logged_in = 0;
		$this->clean();
	}

}

?>
