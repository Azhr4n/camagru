<?php

require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('database', 'Database.class.php'));
require_once(Urls::getPath('image', 'Like.class.php'));

class LikesDB extends Database
{

	function __construct($db_path) {
		parent::__construct($db_path);
	}

	protected function createObject(array $data) {
		return (new Like($data['username'], $data['target']));
	}

	function get(array $data) {
		if ( array_key_exists('target', $data))
		{
			if (array_key_exists('username', $data))
				$req = 'SELECT * FROM Likes WHERE username=\''.$data['username'].'\'
						 AND target=\''.$data['target'].'\'';
			else
				$req = 'SELECT * FROM Likes WHERE target=\''.$data['target'].'\'';
		}
		else
			return FALSE;
		$objects = $this->request($req, null, true);
		if ($objects) {
			$ret = [];
			foreach ($objects as $object) {
				array_push($ret, $this->createObject($object));
			}
			return $ret;
		}
		return (FALSE);
	}

	function set($name, array $data) {
		return FALSE;
	}

	function del($like) {
		if ($this->request('DELETE FROM Likes WHERE username=\''.$like->getUser().'\' AND target=\''.$like->getTarget().'\''))
			return TRUE;
		return FALSE;
	}

	function createLike(Like $like) {
		$req = 'INSERT INTO Likes (username, target, created)
			VALUES (:username, :target, :created)';
		$array = array(
			'username'			=>	$like->getUser(),
			'target'			=>	$like->getTarget(),
			'created'			=>	date('Y-m-d H:i:s')
		);
		return $this->request($req, $array);
	}

}

?>