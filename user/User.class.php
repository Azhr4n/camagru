<?php

class User
{
	protected $_name;
	protected $_password;
	protected $_email;
	protected $_rights;
	protected $_created;
	protected $_token;

	function __construct($name, $password, $email=null, array $rights=null, $created=null,
		array $token=null) {
		$this->_name = $name;
		$this->_password = $password;
		$this->_email = $email;
		if ($rights == null)
			$this->_rights = array(
				'admin'	=> FALSE,
				'user'	=> FALSE
			);
		else
			$this->_rights = $this->handleRights($rights);
		$this->_created = $created;
		$this->_token = $token;
	}

	function __destruct() {}

	protected function morph(User $object) {
		$this->_name = $object->getName();
		$this->_password = $object->getPassword();
		$this->_email = $object->getEmail();
		$this->_rights = $object->getRights();
		$this->_created = $object->getCreationTime();
		$this->_token = $object->getToken();
	}

	protected function clean() {
		$this->_name = '';
		$this->_password = '';
		$this->_email = '';
		$this->_rights = $this->handleRights();
	}

	private function handleRights(array $rights=null) {
		if (isset($rights['admin']))
			$admin_value = $rights['admin'];
		else
			$admin_value = FALSE;
		if (isset($rights['user']))
			$user_value = $rights['user'];
		else
			$user_value = FALSE;
		$array = array(
			'admin'	=>	$admin_value,
			'user'	=>	$user_value
		);
		return ($array);
	}

	function getName() {
		return ($this->_name);
	}

	function getPassword() {
		return ($this->_password);
	}

	function getEmail() {
		return ($this->_email);
	}

	function getRights() {
		return ($this->_rights);
	}

	function getCreationTime() {
		return ($this->_created);
	}

	function getToken() {
		return $this->_token;
	}

	function setPassword($new_password) {
		$this->_password = $value;
	}

	function setEmail($new_email) {
		$this->_email = $new_email;
	}

	function sendResetMail($token) {
		return TRUE;
		/*
		*** Send mail reset.
		*/
	}

	function __toString() {
		return ('Name : '.$this->_name.'<br \>Password : '.$this->_password.'<br />Email : '.$this->_email.'<br />Admin : '.strval($this->_rights['admin']).'
			<br />User : '.strval($this->_rights['user']).'<br />Created : '.$this->_created.'<br /><br />');
	}

}

?>