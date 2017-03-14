<?php
require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
require_once(Urls::getPath('database', 'ImagesDB.class.php'));
require_once(Urls::getPath('security', 'CSRFToken.class.php'));
session_start();

if (!isset($_SESSION['User']) || !$_SESSION['User']->isLogged())
	Header('Location: '.Urls::getUrl('index.php'));

if (isset($_POST['images'])) {
	$database = new ImagesDB($DB_DSN);
	$images = $database->get(['username'=>$_SESSION['User']->getName()]);

	if ($images) {
		if ($_POST['images'] == 'get') {
			$count = 0;
			foreach ($images as $image) {
				$path = $image->getPath();
				$handle = fopen($path, 'r');
				$contents = fread($handle, filesize($path));
				fclose($handle);
				$b64_contents = base64_encode($contents);
				if ($count > 0)
					echo ';';
				$count++;
				echo 'Image: '.$b64_contents;
			}
		}
		else if ($_POST['images'] == 'delete' && isset($_POST['id'])) {
			if (CSRFToken::verifyToken(null, $_POST['csrf_token'], Urls::getUrl('lab.php'), 'csrf_lab')) {
				$ids = explode('.', $_POST['id']);
				foreach ($ids as $id) {
					if ($database->del($images[$id]))
						unlink($images[$id]->getPath());
				}
			}
		}
	}
}



?>