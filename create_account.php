<?php
	include('templates/header.php');
?>

	<form action="<?php $ret = createAccount($database); ?>" method="post">
		username: <input type='text' name='username'>
		passwd: <input type='password' name='passwd'>
		confirm: <input type='password' name='confirm'>
		<input type='submit' name='submit' value='Ok'>
	</form>

	<p><?php echo $ret ?></p>

<?php

	include('templates/footer.php');
?>	
