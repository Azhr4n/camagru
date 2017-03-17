<?php

class Like {
	
	private $_username;
	private $_target;
	private $_created;

	function __construct($username, $target, $created=null) {
		$this->_username = $username;
		$this->_target = $target;
		$this->_created = $created;
	}

	function __destruct() {}

	function getTarget() {
		return $this->_target;
	}

	function getUser() {
		return $this->_username;
	}

}

?>
