<?php

require_once('Database.class.php');

$DB_DSN = 'sqlite:'.DIRNAME(DIRNAME(__FILE__)).'/database/database.sqlite3';
$DB_USER = '';
$DB_PASSWORD = '';

$database = new Database($DB_DSN);

?>