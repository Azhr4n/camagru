<?php

require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

class Comment {
	
	private $_id;
	private $_username;
	private $_image_name;
	private $_value;
	private $_target;
	private $_created;

	function __construct($id, $username, $image_name, $value, $target, $created=null) {
		$this->_id = $id;
		$this->_username = $username;
		$this->_image_name = $image_name;
		$this->_value = $value;
		$this->_target = $target;
		$this->_created = $created;
	}

	function __destruct() {}

	function getID() {
		return $this->_id;
	}

	function getImage() {
		return $this->_image_name;
	}

	function getUser() {
		return $this->_username;
	}

	function getValue() {
		return $this->_value;
	}

	function getTarget() {
		return $this->_target;
	}

	function show() {
		echo "<div class='bcomment'>
			<div>
				<div>
					<div class='ucname'>".$this->_username."</div>
					<div class='ucomment'>".$this->_value."</div>
				</div>
				<a class='replylink' href='#' role='button'>Reply</a>
			</div>
		</div>";
	}

}

?>
