<?php
namespace Controller\Index\Admin;

use Controller\Common\Admin\Layout;
use Facade\Auth\Index as FacadeAuth;
use System\Lib\Http;

class Index extends Layout
{
	public function Index()
	{
		$this->_render('admin/index.phtml');
	}
}