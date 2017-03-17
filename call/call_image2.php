<?php
require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');

require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
require_once(Urls::getPath('database', 'ImagesDB.class.php'));
require_once(Urls::getPath('database', 'CommentsDB.class.php'));
require_once(Urls::getPath('database', 'LikesDB.class.php'));
require_once(Urls::getPath('security', 'CSRFToken.class.php'));
session_start();

if (!isset($_SESSION['User']) || !$_SESSION['User']->isLogged())
	Header('Location: '.Urls::getUrl('index.php'));

function delComments($database, $ldb, $comments) {
	foreach ($comments as $comment) {
		$replies = $database->get(['image_name'=>$comment->getImage(), 'target'=>$comment->getID()]);
		if ($replies)
			delComments($database, $ldb, $replies);
		$likes = $ldb->get(['target'=>$comment->getID()]);
		if ($likes)
			$ldb->del($likes[0]);
		$database->del($comment);
	}
}

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
				$cdatabase = new CommentsDB($DB_DSN);
				foreach ($ids as $id) {
					$comments = $cdatabase->get(['image_name'=>$images[$id]->getName()]);
					if ($comments) {
						$ldb = new LikesDB($DB_DSN);
						delComments($cdatabase, $ldb, $comments);
					}
					if ($database->del($images[$id]))
						unlink($images[$id]->getPath());
				}
			}
		}
	}
}



?>