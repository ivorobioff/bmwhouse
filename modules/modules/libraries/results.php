<?php
namespace Lib\Modules;

use Plugins\Utils\SmartArray;
use Plugins\Utils\Massive;

class Results extends SmartArray
{
	private $_saved_modules = array();

	private $_id = '';

	private $_real_data;

	public function __construct(array $data, $saved_modules)
	{
		parent::__construct($data);

		$this->_real_data = $data;

		$this->_id = $data['name'];

		$this->_saved_modules = Massive::setKeyFromValue($saved_modules, 'guid');
	}

	public function offsetGet($offset)
	{
		$item = $this->_data[$offset];

		$guid = $item['guid'];

		if ($guid && isset($this->_saved_modules[$guid]))
		{
			return $this->_prepareItem($this->_saved_modules[$guid]);
		}

		return $this->_prepareItem($item);
	}

	private function _prepareItem(array $item)
	{
		return array(
			'id' => $this->_id,
			'title' => $item['title'],
			'description' => $item['description'],
			'pin' => always_set($item, 'pin', 0),
			'guid' => $item['guid']
		);
	}
}