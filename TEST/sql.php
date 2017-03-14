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

$connection = connect($tmp_db);
if ($connection == null)
	return (FALSE);

$stmt = $connection->prepare('SELECT * FROM Users');
$stmt->execute();
$uret = $stmt->fetchAll();

$stmt = $connection->prepare('SELECT * FROM Images');
$stmt->execute();
$iret = $stmt->fetchAll();

$stmt = $connection->prepare('SELECT * FROM Comments');
$stmt->execute();
$cret = $stmt->fetchAll();

$stmt = $connection->prepare('SELECT * FROM Likes');
$stmt->execute();
$lret = $stmt->fetchAll();

// print_r($uret);
// print_r($iret);
// print_r($cret);
// print_r($lret);

$stmt = $connection->prepare('SELECT Images.*, Comments.*, Likes.* FROM Images INNER JOIN Comments INNER JOIN Likes WHERE Images.image_name =\'Bob_image1.png\'');// WHERE username=\'Bob\' INNER JOIN Comments ON Images.username = Comments.username');
$stmt->execute(); 
$ret = $stmt->fetchAll();

print_r($ret);

?>