<?php
namespace Lib\Modules;

use Plugins\Utils\SmartArray;
use Plugins\Utils\Massive;

class GridResults extends SmartArray
{
	private $_saved_modules = array();

	public function offsetGet($offset)
	{
		$item = $this->_data[$offset];

		return array(
			'title' => $item['info']['title'],
			'description' => $item['info']['description'],
			'pin' => $item['pin'],
			'name' => $item['name'],
			'guid' => $item['guid']
		);
	}
}