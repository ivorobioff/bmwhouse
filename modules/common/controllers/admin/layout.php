<?php
namespace Controller\Common\Admin;

use System\Lib\Config;
use System\Lib\Http;
use Lib\Common\QuickMin;
use \System\Mvc\Controller as SystemController;
use Facade\Auth\Index as FacadeAuth;

class Layout extends SystemController
{
	protected $_default_layout = 'admin/layout.phtml';

	public function _initPage()
	{
		if (!FacadeAuth::getInstance()->isAuth())
		{
			Http::redirect('/'.get_admin_name().'/auth/');
		}

		QuickMin::run();

		$this->_view->title = 'BMWHouse admin';
		$this->_view->username = FacadeAuth::getInstance()->getUser()->get('username');
	}
}