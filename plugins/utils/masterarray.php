<?php
namespace Plugins\Utils;

use \Plugins\Utils\SmartArray;

class MasterArray extends SmartArray
{
	public function keys()
	{
		return array_keys($this->_data);
	}

	public function exists($key)
	{
		return array_key_exists($key, $this->_data);
	}

	public function get($key)
	{
		return $this->_data[$key];
	}
}