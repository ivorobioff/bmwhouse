<?php
namespace Model\Common\Admin;

use System\Lib\Http;

abstract class Grid
{
	protected $_current_page;
	protected $_total_pages;
	protected $_rows_per_page;
	protected $_order_direction;
	protected $_active_field;

	protected $_source;

	public function __construct()
	{
		$this->_current_page = Http::request('page', 1);
		$this->_rows_per_page = Http::request('rows', 10);
		$this->_order_direction = Http::request('sord', 'ASC');
		$this->_active_field = Http::request('sidx', 'id');

		$this->_source = $this->_prepareSource();
	}

	abstract protected function _prepareSource();
	abstract protected function _count();
	abstract protected function _rows();

	protected function _getTotalPages()
	{
		$count = $this->_count();

		if (!$count)
		{
			return 1;
		}

		return ceil($count / $this->_rows_per_page);
	}

	protected function _getRange()
	{
		$offset = ($this->_current_page * $this->_rows_per_page) - $this->_rows_per_page;
		$limit = $this->_rows_per_page;

		return array($offset, $limit);
	}

	public function getData()
	{
		return array(
			'total' => $this->_getTotalPages(),
			'page' => $this->_current_page,
			'records' => $this->_count(),
			'rows' => $this->_rows(),
		);
	}
}