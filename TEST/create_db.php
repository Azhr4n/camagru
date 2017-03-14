<?php

function request($db, $request, array $array=null) {
	try {
		$connection = connect($db);
		if ($connection == null)
			return (FALSE);
		$stmt = $connection->prepare($request);
		$stmt->execute($array);
		$ret = TRUE;
	}
	catch (Exception $e) {
		$ret = FALSE;
	}
	$connection = null;
	return ($ret);
}

function connect($db) {
	try {
		$connection = new PDO($db);
		$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		return (null);
	}
	return ($connection);
}

$tmp_db = 'sqlite:'.DIRNAME(DIRNAME(__FILE__)).'/TEST/test.sqlite3';


/*
***USER
*/
$req = 'CREATE TABLE IF NOT EXISTS Users (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		username		VARCHAR(16)
	);';

if (request($tmp_db, $req) == FALSE) {
	echo 'Failed to create Users table.'.PHP_EOL;
	die();
}
$req = 'INSERT INTO Users (username) VALUES (:username)';
$array = array(
	'username'			=>	'Bob'
);
if (request($tmp_db, $req, $array) == FALSE) {
	echo 'Failed to create user : '.$array['username'].PHP_EOL;
	die();
}
$req = 'INSERT INTO Users (username) VALUES (:username)';
$array = array(
	'username'			=>	'Maurice'
);
if (request($tmp_db, $req, $array) == FALSE) {
	echo 'Failed to create user : '.$array['username'].PHP_EOL;
	die();
}
/*
***USER
*/

/*
***IMAGE
*/
$req = 'CREATE TABLE IF NOT EXISTS Images (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		image_name		VARCHAR(32),
		username		VARCHAR(16)
	);';
if (request($tmp_db, $req) == FALSE) {
	echo 'Failed to create Images table.'.PHP_EOL;
	die();
}
$req = 'INSERT INTO Images (image_name, username) VALUES (:image_name, :username)';
$array = array(
	'image_name'		=>	'Bob_image1.png',
	'username'			=>	'Bob'
);
if (request($tmp_db, $req, $array) == FALSE) {
	echo 'Failed to create image : '.$array['image_name'].PHP_EOL;
	die();
}
$req = 'INSERT INTO Images (image_name, username) VALUES (:image_name, :username)';
$array = array(
	'image_name'		=>	'Bob_image2.png',
	'username'			=>	'Bob'
);
if (request($tmp_db, $req, $array) == FALSE) {
	echo 'Failed to create image : '.$array['image_name'].PHP_EOL;
	die();
}
$req = 'INSERT INTO Images (image_name, username) VALUES (:image_name, :username)';
$array = array(
	'image_name'		=>	'Maurice_image1.png',
	'username'			=>	'Maurice'
);
if (request($tmp_db, $req, $array) == FALSE) {
	echo 'Failed to create image : '.$array['image_name'].PHP_EOL;
	die();
}
/*
***IMAGE
*/

/*
***COMMENTS
*/
$req = 'CREATE TABLE IF NOT EXISTS Comments (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		username		VARCHAR(16),
		image_name		VARCHAR(32),
		comment			VARCHAR(512)
	);';
if (request($tmp_db, $req) == FALSE) {
	echo 'Failed to create Comments table.'.PHP_EOL;
	die();
}
$req = 'INSERT INTO Comments (image_name, username, comment) VALUES (:image_name, :username, :comment)';
$array = array(
	'image_name'		=>	'Bob_image1.png',
	'username'			=>	'Bob',
	'comment'			=>	'Wouah c\'est joli !'
);
if (request($tmp_db, $req, $array) == FALSE) {
	echo 'Failed to comment image : '.$array['image_name'].PHP_EOL;
	die();
}
/*
***COMMENTS
*/

/*
***LIKES
*/
$req = 'CREATE TABLE IF NOT EXISTS Likes (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		username		VARCHAR(16),
		image_name		VARCHAR(32),
		like			INTEGER	
	);';

if (request($tmp_db, $req) == FALSE) {
	echo 'Failed to create Likes table.'.PHP_EOL;
	die();
}
$req = 'INSERT INTO Likes (image_name, username, like) VALUES (:image_name, :username, :like)';
$array = array(
	'image_name'		=>	'Bob_image1.png',
	'username'			=>	'Bob',
	'like'				=>	TRUE
);
if (request($tmp_db, $req, $array) == FALSE) {
	echo 'Failed to like image : '.$array['image_name'].PHP_EOL;
	die();
}

?>