<?php

require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('database', 'LikesDB.class.php'));

function createLikesTable($db_path) {
	$database = new LikesDB($db_path);
	$req = 'CREATE TABLE IF NOT EXISTS Likes (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		username		TEXT,
		target			TEXT,
		created			DATETIME
		);';
	if ($database->request($req) == FALSE)
		return FALSE;
	return TRUE;
}

?>