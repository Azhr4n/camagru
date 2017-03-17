<?php
	require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');
	include(Urls::getPath('templates', 'default.php'));

	require_once(Urls::getPath('config', 'database.php'));
	require_once(Urls::getPath('database', 'ImagesDB.class.php'));
?>
<!doctype html>
<html lang="fr">

	<?php include('templates/head.php'); ?>

	<body>

		<?php include('templates/header.php'); ?>

		<p class='message'><?php if (isset($_SESSION['Message'])) echo $_SESSION['Message']; ?></p>

		<div class='wrapper'>
			<div class='imageSection'>
		<?php 

			$database = new ImagesDB($DB_DSN);

			$images = $database->get();
			if ($images) {
				foreach($images as $image) {
		?>
				<div class='imageHandler'>
					<a href='<?php echo Urls::getUrl('image.php').'?image='.$image->getName(); ?>'>
		<?php
						$image->show();
		?>
					</a>
				</div>
		<?php
				}
			}
		?>
			</div>
		</div>

		<?php include('templates/footer.php'); ?>

	</body>
</html>