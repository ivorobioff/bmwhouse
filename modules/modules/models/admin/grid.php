<?php
namespace Model\Modules\Admin;

use Model\Modules\Admin\Modules as ModelModules;
use Model\Common\Admin\Grid as CommonGrid;

class Grid extends CommonGrid
{
	protected function _prepareSource()
	{
		$modules = new ModelModules();

		return $modules->fetchAll();
	}

	protected function _count()
	{
		return count($this->_source);
	}

	protected function _rows()
	{
		$res = array();

		foreach ($this->_source as $id => $module)
		{
			$res[] = array_merge(array('id' => $id), $module['info']);
		}

		return $res;
	}
}
