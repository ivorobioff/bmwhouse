<?php
namespace Plugins\Utils;

use \Plugins\Utils\SmartArray;

class MasterArray extends SmartArray
{
	public function keys()
	{
		return array_keys($this->_data);
	}
}