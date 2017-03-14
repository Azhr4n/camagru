<?php

class CSRFToken
{

	private $_token;
	private $_name;

	function __construct($name='') {
		$this->_name = $name;
		$this->_token = uniqid(rand(), TRUE);
		$_SESSION[$name.'_token'] = $this->_token;
		$_SESSION[$name.'_token_time'] = time();
	}

	function __destruct() {}

	function getValue() {
		return ($this->_token);
	}

	function __toString() {
		return ($this->getValue());
	}

	static function verifyToken($time, $token, $referer, $name='') {
		if (isset($_SESSION[$name.'_token']) && isset($_SESSION[$name.'_token_time'])) {
			if ($_SESSION[$name.'_token'] == null)
				return FALSE;
			if ($token == $_SESSION[$name.'_token']) {
				if ($time != null) {
					if ($_SESSION[$name.'_token_time'] < (time() - $time))
						return FALSE;
				}
				$serv_ref = explode('?', $_SERVER['HTTP_REFERER']);
				if ($serv_ref[0] == $referer) {
					$_SESSION[$name.'_token'] = null;
					setcookie($name.'_token', '');
					return (TRUE);
				}
			}
		}
		return (FALSE);
	}

}

?>