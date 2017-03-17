<?php
	require_once(dirname(__FILE__).'/urls/Urls.class.php');
	include(Urls::getPath('templates', 'default.php'));

	if (!$_SESSION['User']->isLogged())
		Header('Location: '.Urls::getUrl('index.php'));

	require_once(Urls::getPath('security', 'CSRFToken.class.php'));
?>
<!doctype html>
<html lang="fr">

	<?php include('templates/head.php'); ?>

	<body>

		<?php include('templates/header.php'); ?>

		<?php $csrf_token = new CSRFToken('csrf_account'); ?>

		<p class='message'><?php if (isset($_SESSION['Message'])) echo $_SESSION['Message']; ?></p>
		
		<div class='formWrapper'>
			<div class='formDiv'>
				<form action='<?php echo Urls::getUrl('call/call_account.php');?>' method='post'>
					<div class='inputField'>
						<label>Username
						</label><?php echo $_SESSION['User']->getName(); ?>
					</div>
					<div class='inputField'>
						<label>Password
						</label><input type='text' name='password' />
					</div>

					<div class='separator'>
						<div class='inputField'>
							<label>New password
							</label><input type='password' name='new_password' />
						</div>
						<div class='inputField'>
							<label>Comfirmation
							</label><input type='password' name='confirmation' />
						</div>
					</div>

					<div class='separator'>
						<div class='inputField'>
							<label>Email
							</label><input type='email' name='email' value='<?php echo $_SESSION['User']->getEmail();?>' \>
						</div>
					</div>

					<div class='centeringField'>
						<input type='hidden' name='csrf_token' value='<?php echo $csrf_token ?>'/>
						<button class='buttonForm' type='submit' name='save' value='ok'>Save</button>
					</div>
				</form>
			</div>
		</div>

		<?php include('templates/footer.php'); ?>

	</body>
</html>