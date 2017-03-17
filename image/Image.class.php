<?php

require_once(dirname(dirname(__FILE__)).'/urls/Urls.class.php');

require_once(Urls::getPath('user', 'User.class.php'));

class Image {
	
	private $_name;
	private $_path;
	private $_username;
	private $_created;


	function __construct($name, $path, $username=null, $created=null) {
		$this->_name = $name;
		$this->_path = $path;
		$this->_username = $username;
		$this->_created = $created;
	}

	function __destruct() {}

	function getName() {
		return $this->_name;
	}

	function getUsername() {
		return $this->_username;
	}

	function getPath() {
		return $this->_path;
	}

	function getDate() {
		return $this->_created;
	}

	function show($id=false) {
		$handle = fopen($this->_path, 'r');
		$contents = fread($handle, filesize($this->_path));
		fclose($handle);
		$b64_contents = base64_encode($contents);
		$ret = '<img';
		if ($id)
			$ret = $ret.' id=\''.$id.'\'';
		$ret = $ret.' alt=\''.$this->_name.'\' src=\'data:image/png;base64,'.$b64_contents.'\' />';
		echo $ret;
	}

}

?>
