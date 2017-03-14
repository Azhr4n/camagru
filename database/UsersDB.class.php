<?php

require_once('F:/Prog/PhpServer/wamp64/www/camagru/urls/Urls.class.php');

require_once(Urls::getPath('database', 'Database.class.php'));
require_once(Urls::getPath('user', 'User.class.php'));

class UsersDB extends Database
{

	function __construct($db_path) {
		parent::__construct($db_path);
	}

	protected function createObject(array $data) {
		return (new User($data['username'], $data['password'], $data['email'],
			['admin'=> $data['admin'], 'user'=> $data['user']], $data['created'],
			['token'=>$data['token'], 'timeout'=>$data['token_timeout']]));
	}

	function get(array $data) {
		if (array_key_exists('username', $data))
			$req = 'SELECT * FROM Users WHERE username=\''.$data['username'].'\'';
		else if (array_key_exists('token', $data))
			$req = 'SELECT * FROM Users WHERE token=\''.$data['token'].'\'';
		else if (array_key_exists('email', $data))
			$req = 'SELECT * FROM Users WHERE email=\''.$data['email'].'\'';
		else
			$req = 'SELECT * FROM Users';
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

	function set($user, array $data) {
		if ($user instanceof User) {
			$req = 'UPDATE Users SET ';
			$i = 0;
			foreach ($data as $key => $value) {
				if ($i > 0)
					$req = $req.', '.$key.'=\''.$value.'\' ';
				else
					$req = $req.$key.'=\''.$value.'\'';
				$i++;
			}
			$req = $req.'WHERE username=\''.$user->getName().'\'';
			if ($this->request($req))
				return TRUE;
		}
		return FALSE;
	}

	function del($user) {
		if ($user instanceof User) {
			if ($this->request('DELETE FROM Users WHERE username=\''.$user->getName().'\''))
				return TRUE;
		}
		return FALSE;
	}

	function createAccount(User $user) {
		if ($this->get(['username'=>$user->getName()])[0] != FALSE)
			return FALSE;
		$req = 'INSERT INTO Users (username, password, email, admin, user, created, token, token_timeout)
			VALUES (:username, :password, :email, :admin, :user, :created, :token, :token_timeout)';
		$array = array(
			'username'			=>	$user->getName(),
			'password'			=>	$user->getPassword(),
			'email'				=>	$user->getEmail(),
			'admin'				=>	FALSE,
			'user'				=>	TRUE,
			'created'			=>	date('Y-m-d H:i:s'),
			'token'				=>	$user->getToken()['token'],
			'token_timeout'		=>	$user->getToken()['timeout']
		);
		return $this->request($req, $array);
	}

	function auth($username, $password) {
		$user = $this->get(['username'=>$username])[0];
		if ($user && $user->getPassword() == $password && $user->getRights()['user'])
			return $user;
		return FALSE;
	}

}

?>