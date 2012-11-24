<?php
namespace Controller\Admin\Admin;

use \Controller\Admin\Admin\Layout as AdminLayout;

class Index extends AdminLayout
{
	public function index()
	{
		$this->_render('admin/index.phtml');
	}
}