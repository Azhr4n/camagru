<?php

require_once('database/database.php');

class User
{
	private $_name;
	private $_password;

	function __construct($name, $password) {
		$this->_name = $name;
		$this->_password = $password;
	}

	function __destruct() {}

	function getName() {
		return ($this->_name);
	}

	function getPassword() {
		return ($this->_password);
	}

	function __toString() {
		return ($this->_name);
	}
}

?>