<?php
require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');
require_once(Urls::getPath('database', 'ImagesDB.class.php'));

function createImagesTable($db_path) {
	$database = new ImagesDB($db_path);
	$req = 'CREATE TABLE IF NOT EXISTS Images (
		id				INTEGER			PRIMARY KEY AUTOINCREMENT,
		image_name		TEXT,
		image_path		TEXT,
		username		TEXT,
		created			DATETIME
		);';
	if ($database->request($req) == FALSE)
		return FALSE;
	return TRUE;
}

?>