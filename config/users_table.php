<?php
require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');
require_once(Urls::getPath('database', 'UsersDB.class.php'));

function createUsersTable($db_path) {
	$database = new UsersDB($db_path);
	$req = 'CREATE TABLE IF NOT EXISTS Users (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		username		TEXT,
		password		TEXT,
		email			TEXT,
		admin			INTEGER,
		user			INTEGER,
		token			TEXT,
		token_timeout	DATETIME,
		created			DATETIME
		);';
	if ($database->request($req) == FALSE)
		return FALSE;
	return TRUE;
}

function createAdmin($db_path) {
	$database = new UsersDB($db_path);
	echo "Enter admin username : ";
	$fd = fopen("php://stdin", "r");
	$line = fgets($fd);
	$admin_name = trim($line);
	fclose($fd);

	echo "Enter admin password : ";
	system('stty -echo'); //Hiding password in unix
	$fd = fopen("php://stdin", "r");
	$line = fgets($fd);
	system('stty echo');//reset echo
	$admin_pass = trim($line);
	fclose($fd);
	echo PHP_EOL;

	echo "Enter admin email : ";
	$fd = fopen("php://stdin", "r");
	$line = fgets($fd);
	$admin_email = trim($line);
	fclose($fd);

	$req = 'INSERT INTO Users (username, password, email, admin, user, created)
			VALUES (:username, :password, :email, :admin, :user, :created)';
	$array = array(
			'username'		=>	$admin_name,
			'password'		=>	hash('whirlpool', $admin_pass),
			'email'			=>	$admin_email,
			'admin'			=>	TRUE,
			'user'			=>	TRUE,
			'created'		=>	date('Y-m-d H:i:s'));
	if ($database->request($req, $array) == FALSE)
		return FALSE;
	return TRUE;
}

?>