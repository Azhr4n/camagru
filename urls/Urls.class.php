<?php

class Urls
{
	static $root = 'F:/Prog/PhpServer/wamp64/www';
	static $server = 'localhost';
	static $server_name = 'camagru';

	static function getPath($dir, $file)
	{
		return self::$root.'/'.self::$server_name.'/'.$dir.'/'.$file;
	}

	static function getUrl($url)
	{
		return 'http://'.self::$server.'/'.self::$server_name.'/'.$url;
	}
}

?>