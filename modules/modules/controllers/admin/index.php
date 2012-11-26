<?php
namespace Controller\Modules\Admin;

use Controller\Common\Admin\Layout;

class Index extends Layout
{
	public function pins()
	{
		$this->_view->page_title = 'Важные модуля';

		$this->_render('modules/admin/pins.phtml');
	}

	public function managment()
	{
		$this->_view->page_title = 'Управление модулями';

		$this->_render('modules/admin/managment.phtml');
	}

	public function install()
	{

	}

	public function uninstall()
	{

	}
}