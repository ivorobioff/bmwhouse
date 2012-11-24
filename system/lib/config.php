<?php
namespace System\Lib;

class Config
{
	static public function getSettings($key)
	{
		include '/config/settings.php';

		return $config[$key];
	}
}