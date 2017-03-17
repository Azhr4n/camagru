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
$database = new CommentsDB($DB_DSN);

function echoCommentBlock($username, $value, $likes_str) {
	echo '	<div>';
	echo '		<div>';
	echo '			<div class=ucname>'.$username.'</div>';
	echo '			<div class=ucomment>'.$value.'</div>';
	echo '		</div>';
	echo '		<div class="Links">';
	echo '				<a class="likeLink" href="#" role="button">Like</a>';
	echo '				<a class="replyLink" href="#" role="button">Reply</a>';
	if ($_SESSION['User']->getName() == $username)
		echo '					<a class="deleteLink" href="#" role="button">Delete</a>';
	echo '		</div>';
	echo '		<div class="Like">'.$likes_str.'</div>';
	echo '	</div>';
}

function likeString($ldatabase, $target) {
	$likes = $ldatabase->get(['target'=>$target]);
	if (!$likes)
		return '';
	$username = $_SESSION['User']->getName();
	$count = 0;
	$self = FALSE;
	foreach ($likes as $like) {
		if ($like->getUser() == $username)
			$self = true;
		$count++;
	}
	$str = '';
	if ($self) {
		$str = 'You ';
		$count--;
	}
	if ($count) {
		if ($self)
			$str = $str.' and ';
		$str = $str.$count;
		if ($count == 1)
			$str = $str.' other ';
		else if ($count > 1)
			$str = $str.' others ';
	}
	$str = $str.'like dis.';
	return $str;
}

function createCommentSection($database, $ldatabase, $comments) {
	foreach ($comments as $comment) {
		$target = $comment->getID();
		echo '<div class=bcomment>';
		echoCommentBlock($comment->getUser(), $comment->getValue(), likeString($ldatabase, $target));
		$replies = $database->get(['image_name'=>$comment->getImage(), 'target'=>$target]);
		if ($replies)
			createCommentSection($database, $ldatabase, $replies);
		echo '</div>';
	}
}

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (CSRFToken::verifyToken(null, $_POST['csrf_token'], Urls::getUrl('image.php'), 'csrf_comment'))
	{
		if (isset($_POST['send']) && $_POST['send'] == 'ok') {
			if (isset($_POST['image_name']) && isset($_POST['value']) && isset($_POST['target'])) {
				$target = htmlentities($_POST['target']);
				$image_name = htmlentities($_POST['image_name']);
				$tab = explode(':', $target);
				if (count($tab) == 2) {
					$target = $database->get(['image_name'=>$image_name, 'username'=>$tab[0], 'value'=>$tab[1]]);
					if ($target)
						$target = $target[0]->getID();
					else {
						echo 'Error: This comment does not exist';
						return ;
					}
				}
				$comment = new Comment(null, $user->getName(),
					$image_name, htmlentities($_POST['value']), $target);
				if (!$database->createComment($comment))
					echo 'Error: Database error.'.PHP_EOL;

			}
		} else if (isset($_POST['delete']) && $_POST['delete'] == 'ok') {
			if (isset($_POST['image_name']) && isset($_POST['target'])) {
				$image_name = htmlentities($_POST['image_name']);
				$target = htmlentities($_POST['target']);
				$tab = explode(':', $target);
				if (count($tab) == 2) {
					$comments = $database->get(['image_name'=>$image_name, 'username'=>$tab[0], 'value'=>$tab[1]]);
					if ($comments) {
						$ldb = new LikesDB($DB_DSN);
						delComments($database, $ldb, $comments);
					}
				}
			}
		}
	} else
		echo 'Error: Session expired.';
}
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['image_name'])) {
		$image_name = htmlentities($_GET['image_name']);
		$comments = $database->get(['image_name'=>$image_name, 'target'=>$image_name]);

		$ldatabase = new LikesDB($DB_DSN);
		echo 'Comment: ';
		echo '<div>';
		echo '	<input class="icinput" type="text" />';
		echo '	<a href="#" class="likeLink" role="button">Like</a>';
		echo '	<div>'.likeString($ldatabase, $image_name).'</div>';
		echo '</div>';
		if ($comments)
			createCommentSection($database, $ldatabase, $comments);
	}
}


?>