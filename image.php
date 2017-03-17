<?php
	require_once(dirname(__FILE__).'/urls/Urls.class.php');
	include(Urls::getPath('templates', 'default.php'));

	require_once(Urls::getPath('config', 'database.php'));
	require_once(Urls::getPath('database', 'ImagesDB.class.php'));
	require_once(Urls::getPath('database', 'CommentsDB.class.php'));
	require_once(Urls::getPath('database', 'LikesDB.class.php'));

	if (!$_SESSION['User']->isLogged())
		Header('Location: '.Urls::getUrl('index.php'));

	require_once(Urls::getPath('security', 'CSRFToken.class.php'));
?>
<!doctype html>
<html lang="fr">

	<?php include('templates/head.php'); ?>

	<body>

		<?php include('templates/header.php'); ?>

		<?php $csrf_token = new CSRFToken('csrf_image'); ?>

		<p class='message'><?php if (isset($_SESSION['Message'])) echo $_SESSION['Message']; ?></p>

		<div class='ImageWrapper'>
			<div class='SubImageWrapper'>
		<?php 
			if (isset($_GET['image']) && !empty($_GET['image'])) {
				$image_db = new ImagesDB($DB_DSN);
				$image = $image_db->get(['image_name'=>$_GET['image']])[0];
			?>
				<div>
			<?php
				if ($image) {
					$image->show('image');
			?>
					<input type='hidden' name='csrf_token' value='<?php echo $csrf_token ?>'/>
				</div>

				<div class='comments'>
				</div>
			<?php
				}
			}
		?>
			</div>
		</div>

		<script type="text/javascript" src='<?php echo Urls::getUrl('static/js/comment.js'); ?>'></script>

		<?php include('templates/footer.php'); ?>

	</body>
</html>