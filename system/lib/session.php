<?php
namespace System\Lib;

use Plugins\Utils\MasterArray;

class Session
{
	/**
	 * @param string $key
	 * @return \Plugins\Utils\MasterArray|mixed
	 */
	static public function get($key)
	{
		$data = $_SESSION[$key];

		if (is_array($data))
		{
			return new MasterArray($data);
		}

		return $data;
	}

	static public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	static public function delete($key)
	{
		unset($_SESSION[$key]);
	}

	static public function exists($key)
	{
		return isset($_SESSION[$key]);
	}
}