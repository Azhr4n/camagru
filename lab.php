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

		<div class='Wrapper'>
			<div class='LabWrapper'>
				<div>
					<div class='Camera'>
						<div class='VideoPlace'>
							<video id='camera' ></video>
						</div>
						<div class='ButtonHolder'>
							<button class='LabButton' id='camera_button' disabled>Take</button>
						</div>
					</div>
					<div class='Image'>
						<div class='ImagePlace'>
							<img id='image' src='<?php echo Urls::getUrl('static/images/white.png'); ?>'/>
							<canvas class='Canvas' id='camera_filter' style='position:absolute;z-index:1;'></canvas>
						</div>
						<div class='ButtonHolder'>
							<input type='hidden' name='csrf_token' value='<?php echo $_SESSION['User']->CSRFToken("csrf_lab") ?>'/>
							<button type='submit' class='LabButton' id='save' name='save' value='ok' onclick='ImageHandler.saveImage("<?php echo Urls::getUrl("call/call_image.php"); ?>");' disabled>Save</button>
							<input class='LabButton' id='import_button' type='file' accept='image/png' />
							<button type='submit' class='LabButton' id='import' onclick='document.getElementById("import_button").click()'>Import</button>
						</div>
					</div>
				</div>
				<div class='FilterHolder' id='filters_holder'>
					<label class='Void'>
						<input class='RadioFilter' type='radio' name='filter' value='none' checked>
						<div class='ImageFilter'></div>
					</label><?php
					$iterator = new FileSystemIterator(Urls::getPath('static/images', 'filters'), FilesystemIterator::SKIP_DOTS);
					while ($iterator->valid())
					{?>
						<label>
							<input type='radio' class='RadioFilter' name='filter' value='<?php echo $iterator->getBasename('.png')?>'>
							<img class='ImageFilter' src='<?php echo Urls::getUrl('static/images/filters/'.$iterator->getFilename()); ?>' />
						</label><?php
						$iterator->next();
					}?>
				</div>
			</div>
			<div class='SavedWrapper'>
				<div class='SavedImage' id='saved_images'>
				</div>
				<div class='ButtonHolder ButtonFiller'>
					<button class='LabButton DelButton'>Delete</button>
				</div>
			</div>
			
		</div>

		 <?php include('templates/footer.php'); ?>

 		<script type="text/javascript" src='<?php echo Urls::getUrl('static/js/camera.js')?>'></script>

	</body>
</html>