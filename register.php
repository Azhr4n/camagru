<?php
	require_once(dirname(__FILE__).'/urls/Urls.class.php');
	include(Urls::getPath('templates', 'default.php'));

	if ($_SESSION['User']->isLogged())
		Header('Location: '.Urls::getUrl('index.php'));
?>
<!doctype html>
<html lang="fr">

	<?php include(Urls::getPath('templates', 'head.php')); ?>

	<body>

		<?php include(Urls::getPath('templates', 'header.php')); ?>

		<p class='message'><?php if (isset($_SESSION['Message'])) echo $_SESSION['Message']; ?></p>

		<div class='formWrapper'>
			<div class='formDiv'>
				<form action='<?php echo Urls::getUrl('call/call_register.php');?>' method='post'>
					<div class='inputField'>
						<label>Username</label>
						<input type='text' name='username' />
					</div>
					<div class='inputField'>
						<label>Password</label>
						<input type='password' name='password' />
					</div>
					<div class='inputField'>
						<label>Confirmation</label>
						<input type='password' name='confirmation' />
					</div>
					<div class='inputField'>
						<label>Email</label>
						<input type='email' name='email' />
					</div>
					<div class='centeringField'>
						<input type='hidden' name='csrf_token' value='<?php echo $_SESSION['User']->CSRFToken("csrf_register"); ?>'/>
						<button class='buttonForm' type='submit' name='register' value='ok'>Create your account</button>
					</div>
				</form>
			</div>
		</div>


		<?php include(Urls::getPath('templates', 'footer.php')); ?>

	</body>
</html>
<?php $_SESSION['Message'] = '' ?>