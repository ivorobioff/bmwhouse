<?php
namespace Model\Modules\Admin;

use Model\Modules\Admin\Modules as ModelModules;
use Model\Common\Admin\Grid as CommonGrid;

class Grid extends CommonGrid
{
	protected function _prepareSource()
	{
		$modules = new ModelModules();

		return $modules->getModules4Grid();
	}

	protected function _count()
	{
		return count($this->_source);
	}

	protected function _rows()
	{
		$res = array();

		$counter = 1;

		foreach ($this->_source as $module)
		{
			$res[] = array_merge(array('id' => $counter), $module['info']);

			$counter ++;
		}

		return $res;
	}
}
