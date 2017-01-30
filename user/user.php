<?php

/*
-1	Db error,
0	Existing,
1	Ok,
2	Submit value invalid,
3	Missing username,
4	Missing passwd or confirmation,
5	Password and confirmation not equals.
*/

function createAccount($database) {
	if ($_POST['submit'] == 'Ok') {
		if ($_POST['username'] != "") {
			if ($_POST['passwd'] != "") {
				if ($_POST['passwd'] == $_POST['confirm']) {
					$ret = $database->createAccount($_POST['username'], $_POST['passwd']);
					if ($ret == 1)
						return ('Account created.');
					else if ($ret == 0)
						return ('This username is not available.');
					else
						return ('Database error.');
				}
				return ('Password and confirmation are not equals.');
			}
			return ('Password and confirmation required.');
		}
		return ('Username required.');
	}
}

/*

function logIn($db_path) {
	if ($_POST['submit'] == 'Ok')
	{
		if ($_POST['username'] != "" && $_POST['passwd'] != ""
			&& $_POST['passwd'] == $_POST['confirm'])
		{
			session_start();
			$ret = userExist($db_path, $_POST['username'], $_POST['passwd']);
			if ($ret < 0)
				return ("Error with db.");
			else if ($ret == 0)
				return ("Invalid user.");
			else
			{
				$_SESSION['loggued_in'] = $_POST['username'];
				return ("Login.");
			}
		}
	}
	return ("Form error.");
}
*/

?>