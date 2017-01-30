<?php

function isAdmin($db_path, $name) {
	try {
		$db = new PDO($db_path);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		return (-1);
	}
	try {
		$stmt = $db->prepare('SELECT * FROM Users WHERE admin=1');
		$stmt->execute();
		$result = $stmt->fetchAll();
	} catch (Exception $e) {
		return (-1);
	}
	foreach ($result as $admin) {
		if ($admin['name'] == $name)
			return (1);
	}
	return (0);
}

?>