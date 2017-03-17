<?php
require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');

require_once(Urls::getPath('config', 'database.php'));
require_once(Urls::getPath('user', 'CurrentUser.class.php'));
require_once(Urls::getPath('database', 'ImagesDB.class.php'));
require_once(Urls::getPath('security', 'CSRFToken.class.php'));
session_start();

if (!isset($_SESSION['User']) || !$_SESSION['User']->isLogged())
	Header('Location: '.Urls::getUrl('index.php'));

function encodeBase64Image ($image) {
	ob_start();
		imagepng($image);
		$ret = ob_get_contents();
	ob_end_clean();
	return (base64_encode($ret));
}

function resizeImage($image, $new_width, $new_height) {
	$width = imagesx($image);
	$height = imagesy($image);
	$new_image = imagecreatetruecolor($new_width, $new_height);
	imagealphablending($new_image, false);
	imagesavealpha($new_image, true);
	$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
	imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	return $new_image;
}

function getImageName($images, $prefix) {
	$count = 1;
	if ($images) {
		$size = count($images);
		$continue = TRUE;
		while ($continue) {
			$continue = FALSE;
			for ($i = 0; $i < $size; $i++) {
				if ($prefix.'_image'.$count == $images[$i]->getName()) {
					$continue = TRUE;
					break ;
				}
			}
			if ($continue)
				$count++;
		}
	}
	return ($prefix.'_image'.$count);
}

if (isset($_POST['save']) && $_POST['save'] == 'ok')
{
	if (CSRFToken::verifyToken(null, $_POST['csrf_token'], Urls::getUrl('lab.php'), 'csrf_lab'))
	{
		$str = "data:image/png;base64,";

		$image = str_replace($str, '', $_POST['image']);
		$image = base64_decode($image);
		$image = @imagecreatefromstring($image);

		if ($image) {
			$width = imagesx($image);
			$height = imagesy($image);

			if ($_POST['filter']) {
				$filter = str_replace($str, '', $_POST['filter']);
				$filter = base64_decode($filter);
				$filter = @imagecreatefromstring($filter);

				$filter = resizeImage($filter, $width, $height);

				if (!imagecopy($image, $filter, 0, 0, 0, 0, imagesx($image), imagesy($image))) {
					echo 'Failed to merge';
					return ;
				}
			}
			$image = resizeImage($image, 640, 480);

			$user = $_SESSION['User'];
			$path = Urls::getPath('static/images', $user->getName());
			if (!file_exists($path) && !is_dir($path))
				mkdir($path);

			if (is_dir($path)) {
				$database = new ImagesDB($DB_DSN);
				$images = $database->get(['username'=>$user->getName()]);
				$image_name = getImageName($images, $user->getName());
				$fullpath = $path.'/'.$image_name.'.png';
				if (imagepng($image, $fullpath)) {
					$image = new Image($image_name, $fullpath, $user->getName());
					if (!$database->createImage($image) 
						|| !$database->createTable($image_name, ['id INTEGER PRIMARY KEY AUTOINCREMENT',
							'username TEXT', 'value TEXT', 'created DATETIME'])) {
						unlink($fullpath);
						echo 'Error: Database error.';
					}
				} else
					echo 'Error: Could not save image.';
			}
			else
				echo 'Error: Could not save image.';
		}
	}
	else
		echo 'Error: Session expired.';
}

?>