<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');

	require_once(Urls::getPath('config', 'database.php'));
	require_once(Urls::getPath('config', 'users_table.php'));
	require_once(Urls::getPath('config', 'images_table.php'));
	require_once(Urls::getPath('config', 'comments_table.php'));
	require_once(Urls::getPath('config', 'likes_table.php'));

	if (createUsersTable($DB_DSN)) {
		if (createAdmin($DB_DSN)) {
			if (createImagesTable($DB_DSN)) {
				if (createCommentsTable($DB_DSN)) {
					if (createLikesTable($DB_DSN)) {
						echo 'Database is now ready.'.PHP_EOL;
						return ;
					}
					else
						echo 'Failed to create Likes table.'.PHP_EOL;
				} else
					echo 'Failed to create Comments table.'.PHP_EOL;
			} else
				echo 'Failed to create Image table.'.PHP_EOL;
		} else
			echo 'Failed to create admin.'.PHP_EOL;
	} else
		echo 'Failed to create Users table.'.PHP_EOL;
	$path = Urls::getPath('database', 'database.sqlite3');
	if (file_exists($path))
		unlink($path);
?>
