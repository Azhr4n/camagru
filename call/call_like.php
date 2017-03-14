<?php
require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
require_once(Urls::getPath('database', 'CommentsDB.class.php'));
require_once(Urls::getPath('database', 'LikesDB.class.php'));
session_start();

if (!isset($_SESSION['User']) || !$_SESSION['User']->isLogged())
	Header('Location: '.Urls::getUrl('index.php'));

$user = $_SESSION['User'];
$database = new LikesDB($DB_DSN);
$cdatabase = new CommentsDB($DB_DSN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (CSRFToken::verifyToken(null, $_POST['csrf_token'], Urls::getUrl('image.php'), 'csrf_like'))
	{
		if (isset($_POST['like']) && $_POST['like'] == 'ok') {
			if (isset($_POST['image_name']) && isset($_POST['target'])) {
				$target = htmlentities($_POST['target']);
				$image_name = htmlentities($_POST['image_name']);
				$tab = explode(':', $target);
				if (count($tab) == 2) {
					$target = $cdatabase->get(['image_name'=>$image_name, 'username'=>$tab[0], 'value'=>$tab[1]]);
					if ($target)
						$target = $target[0]->getID();
					else {
						echo 'Error: This comment does not exist';
						return ;
					}
				}
				$ret = $database->get(['username'=>$user->getName(), 'target'=>$target]);
				if (!$ret) {
					$like = new Like($user->getName(), $target);
					if (!$database->createLike($like))
						echo 'Error: Database error.'.PHP_EOL;
				} else
					$database->del($ret[0]);
			}
		}
	} else
		echo 'Error: Session expired.';
}


?>