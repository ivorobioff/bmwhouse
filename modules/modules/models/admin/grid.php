<?php
namespace Model\Modules\Admin;

use Model\Modules\Admin\Modules as ModelModules;
use Model\Common\Admin\Grid as CommonGrid;

class Grid extends CommonGrid
{
	protected function _prepareSource()
	{
		$modules = new ModelModules();
		$this->_source = $modules->getAll()->toArray();
	}

	protected function _count()
	{
		return count($this->_source);
	}

	protected function _getRows()
	{
		$res = array();

		foreach ($this->_source as $module)
		{
			$res[] = array_merge(array('id' => $module['name']), $module['info']);
		}

		return $res;
	}
}
