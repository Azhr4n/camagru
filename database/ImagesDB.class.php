<?php

require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');

require_once(Urls::getPath('database', 'Database.class.php'));
require_once(Urls::getPath('image', 'Image.class.php'));

class ImagesDB extends Database
{

	function __construct($db_path) {
		parent::__construct($db_path);
	}

	protected function createObject(array $data) {
		return (new Image($data['image_name'], $data['image_path'], $data['username'],
			$data['created']));
	}

	function get(array $data=null) {
		if ($data) {
			if (array_key_exists('image_name', $data))
				$req = 'SELECT * FROM Images WHERE image_name=\''.$data['image_name'].'\'';
			else if (array_key_exists('username', $data))
				$req = 'SELECT * FROM Images WHERE username=\''.$data['username'].'\' ORDER BY created DESC';
			else
				return FALSE;
		} else
			$req = 'SELECT * FROM Images ORDER BY created DESC';
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

	function set($image, array $data) {
		if ($image instanceof Image) {
			$req = 'UPDATE Images SET ';
			$i = 0;
			foreach ($data as $key => $value) {
				if ($i++ > 0)
					$req = ', '.$req.$key.'=\''.$value.'\' ';
				else
					$req = $req.$key.'=\''.$value.'\'';
			}
			$req = $req.'WHERE image_name=\''.$image->getName().'\'';
			if ($this->request($req))
				return TRUE;
		}
		return FALSE;
	}

	function del($image) {
		if ($image instanceof Image) {
			if ($image->getUsername()) {
				$req = 'DELETE FROM Images WHERE image_name=\''.$image->getName().'\' AND username=\''.$image->getUsername().'\'';
			} else
				$req = 'DELETE FROM Images WHERE image_name=\''.$image->getName().'\'';
			if ($this->request($req))
				return TRUE;
		}
		return FALSE;
	}

	function createImage(Image $image) {
		$req = 'INSERT INTO Images (image_name, image_path, username, created)
			VALUES (:image_name, :image_path, :username, :created)';
		$array = array(
			'image_name'		=>	$image->getName(),
			'image_path'		=>	$image->getPath(),
			'username'			=>	$image->getUsername(),
			'created'			=>	date('Y-m-d H:i:s')
		);
		return $this->request($req, $array);
	}

}

?>