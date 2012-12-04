<?php
namespace Controller\Modules\Admin;

use Model\Modules\Admin\Grid as ModulesGrid;
use Controller\Common\Admin\Layout;
use \System\Lib\Http;
use \Model\Modules\Admin\Modules as ModelModules;

class Index extends Layout
{
	public function pins()
	{
		$this->_view->page_title = 'Важные модули';

		$this->_render('modules/admin/pins.phtml');
	}

	public function managment()
	{
		$this->_view->page_title = 'Управление модулями';

		$this->_render('modules/admin/managment.phtml');
	}

	public function readModules()
	{
		$this->_mustBeAjax();

		$grid = new ModulesGrid();
		$this->_sendSimpleResponse($grid->getData());

	}

	public function install()
	{
		$id = Http::post('id');
		$modules = new ModelModules();
	}

	public function uninstall()
	{

	}

	public function refresh()
	{

	}

	public function pin()
	{

	}
}