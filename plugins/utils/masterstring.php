<?php
namespace Plugins\Utils;

class MasterString
{
	private $_string = '';

	public function __construct($string)
	{
		$this->_string = (string) $string;
	}

	public function ucfirst()
	{
		$this->_string = ucfirst($this->_string);

		return $this;
	}

	public function __toString()
	{
		return $this->_string;
	}
}