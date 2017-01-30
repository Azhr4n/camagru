<?php

class Database
{
	private $_db;
	private $_hash_machine;

	function __construct($db_path) {
		$this->_hash_machine = 'whirlpool';
		$this->_db = $this->connect($db_path);
	}

	function __destruct() {}

	private function connect($path) {
		try {
			$db = new PDO($path);
			$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			return (0);
		}
		return ($db);
	}

	private function fetchAll($type) {
		try {
			$stmt = $this->_db->prepare('SELECT * FROM '.$type);
			$stmt->execute();
			$ret = $stmt->fetchAll();
		} catch (Exception $e) {
			return (-1);
		}
		return ($ret);
	}

	function getHashMachine() {
		return ($this->_hash_machine);
	}

	function getDB() {
		return ($this->_db);
	}

	function userExist($username) {
		if (($ret = $this->fetchAll('Users')) == -1)
			return (-1);
		foreach ($ret as $user) {
			if ($user['name'] == $username)
				return (1);
		}
		return (0);
	}

	//Forbidden functions !

	function printUsers() {
		$ret = $this->fetchAll('Users');
		foreach ($ret as $user) {
			print_r($user);
		}
	}

	//End of forbidden magic !

	function createAccount($username, $password) {
		if ($this->userExist($username) == 1)
			return (0);
		$hashed_password = hash($this->_hash_machine, $password);
		try {
			$stmt = $this->_db->prepare('INSERT INTO Users (name, passwd, created) VALUES (:name, :passwd, :created)');
			$result = $stmt->execute(array(
				'name'		=>	$username,
				'passwd'	=>	$hashed_password,
				'created'	=>	date('Y-m-d H:i:s')
			));			
		} catch (Exception $e) {
			return (-1);
		}
		return (1);
	}
}

?>