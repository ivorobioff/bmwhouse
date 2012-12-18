<?php
namespace Controller\Home;

use \Controller\Common\Layout;

class Index extends Layout
{
	public function Index()
	{
		$this->_setLabels(array(
			'test' => _t('Это тест')
		));

		$this->_render('home/index.phtml');
	}
}