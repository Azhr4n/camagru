<head>
	<meta charset="utf-8">

	<title>ImageIn</title>
	
	<link rel="stylesheet" href="<?php echo Urls::getUrl('static/css/navbar.css');?>">
	<link rel="stylesheet" href="<?php echo Urls::getUrl('static/css/form.css');?>">
	<link rel="stylesheet" href="<?php echo Urls::getUrl('static/css/footer.css');?>">
<?php
	if ($_SERVER['REQUEST_URI'] == '/camagru/index.php') {
?>
		<link rel="stylesheet" href="<?php echo Urls::getUrl('static/css/index.css');?>">
<?php
	} else if ($_SERVER['REQUEST_URI'] == '/camagru/lab.php') {
?>
		<link rel="stylesheet" href="<?php echo Urls::getUrl('static/css/lab.css');?>">
<?php
		} else if (preg_match('#^/camagru/image.php[?]image=.*#', $_SERVER['REQUEST_URI'])) {
?>
				<link rel="stylesheet" href="<?php echo Urls::getUrl('static/css/image.css');?>">
<?php
		}
?>
</head>