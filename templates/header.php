<header>
	<nav class='navbar'>
		<ul>
			<li><a href='<?php echo Urls::getUrl('index.php')?>'>Home</a></li
<?php if ($_SESSION['User']->isLogged()) { ?>
			><li><a href='<?php echo Urls::getUrl('lab.php')?>'>Lab</a></li
			><li><a href='<?php echo Urls::getUrl('account.php')?>'>Account</a></li
			><li><a href='<?php echo Urls::getUrl('logout.php')?>'>Log out</a></li>
<?php } else { ?>
			><li><a href='<?php echo Urls::getUrl('register.php')?>'>Register</a></li
			><li><a href='<?php echo Urls::getUrl('login.php')?>'>Log In</a></li>
<?php } ?>
		</ul>
	</nav>
</header>