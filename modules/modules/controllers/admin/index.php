<?php
namespace Controller\Modules\Admin;

use Controller\Common\Admin\Layout;

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

		$res = array();

		for ($i = 0; $i < 10; $i ++)
		{
			$row = array(
				'id' => $i,
				'cell' => array($i, 'Hey', 'You')
			);

			$res[] = $row;
		}

		$data = array(
			'total' => '2',
			'page' => '1',
			'records' => '20',
			'rows' => $res,
		);

		$this->_sendSimpleResponse($data);

	}

	public function install()
	{

	}

	public function uninstall()
	{

	}
}