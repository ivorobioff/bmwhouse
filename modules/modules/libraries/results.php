<?php
namespace Lib\Modules;

use Plugins\Utils\SmartArray;
use Plugins\Utils\Massive;

class Results extends SmartArray
{
	private $_saved_modules = array();

	public function __construct(array $data, $saved_modules)
	{
		parent::__construct($data);

		$this->_saved_modules = Massive::setKeyFromValue($saved_modules, 'guid');
	}

	public function offsetGet($offset)
	{
		$item = $this->_data[$offset];

		$guid = $item['guid'];

		if ($guid && isset($this->_saved_modules[$guid]))
		{
			$item = $this->_mergeItems($item, $this->_saved_modules[$guid]);
		}

		return $item;
	}

	private function _mergeItems(array $item, array $saved_item)
	{
		$item['info']['title'] = $saved_item['title'];
		$item['info']['description'] = $saved_item['description'];
		$item['pin'] = $saved_item['pin'];
		$item['menu'] = unserialize($saved_item['menu']);

		return $item;
	}
}