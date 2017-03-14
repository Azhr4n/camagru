<?php
	require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');
	include(Urls::getPath('templates', 'default.php'));

	if (!$_SESSION['User']->isLogged())
		Header('Location: '.Urls::getUrl('index.php'));

?>
<!doctype html>
<html lang="fr">

	<?php include('templates/head.php'); ?>

	<body>

		<script type="text/javascript" src='<?php echo Urls::getUrl('static/js/ErrorHandler.js'); ?>'></script>
		<script type="text/javascript" src='<?php echo Urls::getUrl('static/js/ImageHandler.js'); ?>'></script>

		<?php include('templates/header.php'); ?>

		<p class='message' id='error_field'><?php if (isset($_SESSION['Message'])) echo $_SESSION['Message']; ?></p>

		<div>
			<div>
				<div>
					<div>
						<video id='camera' ></video>
						<button id='camera_button' disabled>Take</button>
					</div>
					<div >
						<div >
							<img id='image' src='<?php echo Urls::getUrl('static/images/white.png'); ?>'/>
							<canvas id='camera_filter' style='position:absolute;z-index:1;'></canvas>
						</div>
						<div>
							<input type='hidden' name='csrf_token' value='<?php echo $_SESSION['User']->CSRFToken("csrf_lab") ?>'/>
							<button type='submit' id='save' name='save' value='ok' onclick='ImageHandler.saveImage("<?php echo Urls::getUrl("call/call_image.php"); ?>");' disabled>Save</button>
							<input id='import_button' type='file' accept='image/png' />
						</div>
					</div>
				</div>
				<div id='filters_holder'>
					<input type='radio' name='filter' value='none' checked>
<?php
					$iterator = new FileSystemIterator(Urls::getPath('static/images', 'filters'), FilesystemIterator::SKIP_DOTS);
					while ($iterator->valid())
					{
?>
					<label>
						<input type='radio' name='filter' value='<?php echo $iterator->getBasename('.png')?>'>
						<img src='<?php echo Urls::getUrl('static/images/filters/'.$iterator->getFilename()); ?>' style='width:150px;' />
					</label>
<?php
						$iterator->next();
					}
?>
				</div>
			</div>

			<div id='saved_images'>
			</div>
		</div>

		 <?php include('templates/footer.php'); ?>

 		<script type="text/javascript" src='<?php echo Urls::getUrl('static/js/camera.js')?>'></script>

	</body>
</html>