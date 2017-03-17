<?php
	require_once(dirname(__FILE__).'/urls/Urls.class.php');
	include(Urls::getPath('templates', 'default.php'));

	if ($_SESSION['User']->isLogged())
		Header('Location: '.Urls::getUrl('index.php'));

	require_once(Urls::getPath('database', 'UsersDB.class.php'));

	if (isset($_GET['reset_token'])) {
		$database = new UsersDB($DB_DSN);
		$token = htmlentities($_GET['reset_token']);
		$user = $database->get(['token'=>$token]);
		if (!$user)
			Header('Location: '.Urls::getUrl('login.php'));
	}

?>
<!doctype html>
<html lang="fr">

	<?php include('templates/head.php'); ?>

	<body>

		<?php include('templates/header.php'); ?>

		<p class='message' id='error_field'><?php if (isset($_SESSION['Message'])) echo $_SESSION['Message']; ?></p>
		
		<?php
			if (isset($_GET['reset']) && $_GET['reset'] == 'true') {
		?>
			<div class='formWrapper'>
				<div class='formDiv'>
					<form action='<?php echo Urls::getUrl('call/call_reset.php');?>' method='post'>
						<div class='inputField'>
							<label>Email</label>
							<input type='email' name='email' />
						</div>
						<div class='centeringField'>
							<input type='hidden' name='csrf_token' value='<?php echo $_SESSION['User']->CSRFToken("csrf_reset") ?>'/>
							<button class='buttonForm' type='submit' name='reset' value='ok'>Send reset email</button>
						</div>
					</form>
				</div>
			</div>
		<?php
			}
			else if (isset($_GET['reset_token'])) {
		?>
		<div class='formWrapper'>
			<div class='formDiv'>
				<form action='<?php echo Urls::getUrl('call/call_reset.php');?>' method='post'>
					<div class='inputField'>
						<label>Password</label>
						<input type='password' name='password' />
					</div>
					<div class='inputField'>
						<label>Confirmation</label>
						<input type='password' name='confirmation' />
					</div>
					<div class='centeringField'>
						<input type='hidden' name='token' value='<?php echo $_GET['reset_token'] ?>'/>
						<input type='hidden' name='csrf_token' value='<?php echo $_SESSION['User']->CSRFToken("csrf_reset") ?>'/>
						<button class='buttonForm' type='submit' name='reset' value='ok'>Save</button>
					</div>
				</form>
			</div>
		</div>
		<?php
			} else {
		?>
		<div class='formWrapper'>
			<div class='formDiv'>
				<form action='<?php echo Urls::getUrl('call/call_login.php');?>' method='post'>
					<div class='inputField'>
						<label>Username</label>
						<input type='text' name='username' />
					</div>
					<div class='inputField'>
						<label>Password</label>
						<input type='password' name='password' />
					</div>
					<div class='centeringField'>
						<input type='hidden' name='csrf_token' value='<?php echo $_SESSION['User']->CSRFToken("csrf_login") ?>'/>
						<button class='buttonForm' type='submit' name='login' value='ok'>Log in</button>
					</div>
				</form>
				<a href='?reset=true'>Forgot your password ?</a>
			</div>
		</div>
		
		<?php
			}
		?>

		<?php include('templates/footer.php'); ?>

	</body>
</html>