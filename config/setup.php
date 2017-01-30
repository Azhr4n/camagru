<?php

	require_once('database/database.php');

	$database = new Database($DB_DSN);
	$database->getDB()->query('CREATE TABLE IF NOT EXISTS Users (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		name			VARCHAR(15),
		passwd			VARCHAR(250),
		admin			INTEGER,
		created			DATETIME
		);');

	echo "Table created.".PHP_EOL;

	echo "Enter admin username : ";
	$fd = fopen("php://stdin", "r");
	$line = fgets($fd);
	$admin_name = trim($line);
	fclose($fd);

	echo "Enter admin password : ";
	$fd = fopen("php://stdin", "r");
	$line = fgets($fd);
	$admin_pass = trim($line);
	fclose($fd);

	try {
		$stmt = $database->getDB()->prepare('INSERT INTO Users (name, passwd, admin, created) VALUES (:name, :passwd, :admin, :created)');
		$result = $stmt->execute(array(
			'name'		=>	$admin_name,
			'passwd'	=>	hash('whirlpool', $admin_pass),
			'admin'		=>	1,
			'created'	=>	date('Y-m-d H:i:s')
		));		
	} catch (Exception $e)
	{
		echo 'Error : '.$e->getMessage().PHP_EOL;
		die();
	}
	echo "Database set.".PHP_EOL;

?>