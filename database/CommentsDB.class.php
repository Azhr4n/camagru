<?php

require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('database', 'Database.class.php'));
require_once(Urls::getPath('image', 'Comment.class.php'));

class CommentsDB extends Database
{

	function __construct($db_path) {
		parent::__construct($db_path);
	}

	protected function createObject(array $data) {
		return (new Comment($data['id'], $data['username'], $data['image_name'], $data['value'],
			$data['target'], $data['created']));
	}

	function get(array $data=null) {
		if ($data) {
			if (array_key_exists('image_name', $data)) {
				if (array_key_exists('target', $data))
					$req = 'SELECT * FROM Comments WHERE image_name=\''.$data['image_name'].'\'
						 AND target=\''.$data['target'].'\'';
				else if (array_key_exists('value', $data) && array_key_exists('username', $data))
					$req = 'SELECT * FROM Comments WHERE image_name=\''.$data['image_name'].'\'
						 AND username=\''.$data['username'].'\' AND value=\''.$data['value'].'\'';
				else
					$req = 'SELECT * FROM Comments WHERE image_name=\''.$data['image_name'].'\'';
			}
			else
				$req = 'SELECT * FROM Comments';
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

	function set($comment, array $data) {
		$req = 'UPDATE Comments SET ';
		$i = 0;
		foreach ($data as $key => $value) {
			if ($i++ > 0)
				$req = ', '.$req.$key.'=\''.$value.'\' ';
			else
				$req = $req.$key.'=\''.$value.'\'';
		}
		$req = $req.'WHERE id=\''.$comment->getID().'\'';
		if ($this->request($req))
			return TRUE;
		return FALSE;
	}

	function del($comment) {
		if ($this->request('DELETE FROM Comments WHERE id=\''.$comment->getID().'\''))
			return TRUE;
		return FALSE;
	}

	function createComment(Comment $comment) {
		$req = 'INSERT INTO Comments (username, image_name, value, target, created)
			VALUES (:username, :image_name, :value, :target, :created)';
		$array = array(
			'username'			=>	$comment->getUser(),
			'image_name'		=>	$comment->getImage(),
			'value'				=>	$comment->getValue(),
			'target'			=>	$comment->getTarget(),
			'created'			=>	date('Y-m-d H:i:s')
		);
		return $this->request($req, $array);
	}

}

?>