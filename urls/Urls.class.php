<?php

class Urls
{
	static $server_name = 'camagru';

	static function getPath($dir, $file) {
		return dirname(dirname(__FILE__)).'/'.$dir.'/'.$file;
	}

	static function getUrl($url) {
		if ($_SERVER['SERVER_PORT'])
			return 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.self::$server_name.'/'.$url;
		else
			return 'http://'.$_SERVER['SERVER_NAME'].'/'.self::$server_name.'/'.$url;
	}
}

?>