<?php
require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');
require_once(Urls::getPath('database', 'CommentsDB.class.php'));

function createCommentsTable($db_path) {
	$database = new CommentsDB($db_path);
	$req = 'CREATE TABLE IF NOT EXISTS Comments (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		username		TEXT,
		image_name		TEXT,
		value			TEXT,
		target			TEXT,
		created			DATETIME
		);';
	if ($database->request($req) == FALSE)
		return FALSE;
	return TRUE;
}

?>