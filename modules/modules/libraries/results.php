<?php
namespace Lib\Modules;

use \Plugins\Utils\SmartArray;
use \Plugins\Utils\Massive;
use \Lib\Modules\Rows;

class Results extends SmartArray
{
	private $_saved_modules = array();

	public function __construct($data, $saved_modules)
	{
		parent::__construct($data);

		$this->_saved_modules = Massive::setKeyFromValue($saved_modules, 'guid');
	}

	public function offsetGet($offset)
	{
		$item = $this->_data[$offset];

		$saved_module = always_set($this->_saved_modules, always_set($item, 'guid'), array());

		return new Rows($item, $saved_module);
	}
}