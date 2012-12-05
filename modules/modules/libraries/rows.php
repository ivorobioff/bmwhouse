<?php
namespace Lib\Modules;

use Plugins\Utils\SmartArray;

class Rows extends SmartArray
{
	private $_saved_modules = array();

	public function __construct($data, $saved_module)
	{
		unset($data['guid']);

		$data = $this->_mergeItems($data, $saved_module);

		parent::__construct($data);
	}

	private function _mergeItems($item, $saved_item)
	{
		if (!$saved_item)
		{
			return $item;
		}

		$item['info']['title'] = $saved_item['title'];
		$item['info']['description'] = $saved_item['description'];
		$item['pin'] = $saved_item['pin'];
		$item['menu'] = unserialize($saved_item['menu']);
		$item['guid'] = $saved_item['guid'];

		return $item;
	}
}