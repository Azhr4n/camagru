<?php

require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');

abstract class Database
{
	protected $_db;

	function __construct($db_path) {
		$this->_db = $db_path;
	}

	function __destruct() {}

	protected function connect() {
		try {
			$connection = new PDO($this->_db);
			$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			return (null);
		}
		return ($connection);
	}

	protected function disconnect(PDO $connection) {
		$connection = null;
	}

	function request($request, array $array=null, $fetchAll=false, $fetch=false) {
		try {
			$connection = $this->connect();
			if ($connection == null)
				return (FALSE);
			$stmt = $connection->prepare($request);
			$stmt->execute($array);
			if ($fetchAll)
				$ret = $stmt->fetchAll();
			else if ($fetch)
				$ret = $stmt->fetch();
			else
				$ret = TRUE;
		}
		catch (Exception $e) {
			echo $e->getMessage();
			$ret = FALSE;
		}
		$this->disconnect($connection);
		return ($ret);
	}

	function createTable($name, array $rows) {
		$req = 'CREATE TABLE IF NOT EXISTS '.$name.' (';
		$size = count($rows);
		for ($i = 0; $i < $size; $i++) {
			if ($i > 0)
				$req = $req.', '.$rows[$i];
			else
				$req = $req.$rows[$i];
		}
		$req = $req.');';
		return $this->request($req);
	}

	function deleteTable($name) {
		$req = 'DROP TABLE '.$name;
		return $this->request($req);
	}

	abstract protected function createObject(array $data);
	abstract protected function get(array $data);
	abstract protected function set($object, array $data);
	abstract protected function del($object);

}

?>