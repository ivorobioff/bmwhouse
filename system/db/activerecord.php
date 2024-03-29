<?php
namespace System\Db;

use System\Lib\Config;

abstract class ActiveRecord
{
	const MATCH_IN_BOOLEAN_MODE = 'IN BOOLEAN MODE';
	const MATCH_IN_NATURAL_LANGUAGE_MODE = 'IN NATURAL LANGUAGE MODE';
	const MATCH_IN_NATURAL_LANGUAGE_MODE_WITH_QUERY_EXPANSION = 'IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION';
	const MATCH_WITH_QUERY_EXPANSION = 'WITH QUERY EXPANSION';

	protected $_display_errors = true;

	/**
	 * Настройки таблицы
	 */
	protected $_table_name;
	protected $_table_alias = '';
	protected $_order_by = 'id ASC';
	protected $_primary_key = 'id';

	/**
	 * Прототип буфера запросов
	 * @var array
	 */
	private $_init_query = array(
		'select' => array(),
		'where' => array(),
		'orderBy' => array(),
		'groupBy' => array(),
		'duplicate' => '',
		'limit' => '',
		'join' => array()
	);

	private $_query_buffer = array();

	private $_last_query = '';

	static private $_db;

	public function __construct()
	{
		$_config_db = Config::getInstance()->getSettings('DB');

		if (!self::$_db)
		{
			self::$_db = new \mysqli(
				$_config_db['host'],
				$_config_db['username'],
				$_config_db['password'],
				$_config_db['dbname']
			);
		}

		self::$_db->set_charset('utf8');

		$this->clear();
	}

	/**
	 * $table->duplicate('c=c+1');
	 * $table->duplicate('c=c+', 1);
	 * $table->duplicate('c', 1);
	 */
	public function duplicate($q = '', $value = null)
	{
		if (is_null($value))
		{
			$this->_query_buffer['duplicate'] = 'ON DUPLICATE KEY UPDATE '.$q;
			return $this;
		}

		$eq = strpos($q, '=') ? '' : '=';

		$this->_query_buffer['duplicate'] = 'ON DUPLICATE KEY UPDATE '.$q.$eq.'\''.$this->escape($value).'\'';
		return $this;
	}

	/**
	 * $table->select('col1, col2, col3');
	 */
	public function select($q = '')
	{
		$this->_query_buffer['select'][] = $q;
		return $this;
	}

	public function either($q, $value = null)
	{
		$this->_where('OR', $q, $value);
		return $this;
	}

	public function where($q, $value = null)
	{
		$this->_where('AND', $q, $value);
		return $this;
	}

	/**
	 * Автофильтрация по правилам.
	 *
	 * @example
	 *
	 * $data['name'] = 2
	 *
	 * array('name' => true); //будет след. квери: 'name=2'
	 *
	 * array( 'name' => 'num'); //будет след. квери: 'name=2'
	 *
	 * array( 'name' => array('num')); //будет след. квери: 'name=2'
	 *
	 * array( 'name' => array('num', '>=')); //будет след. квери: 'name>=2'
	 *
	 * array( 'name' => array('num', 'LIKE', '%{value}%')); //будет след. квери: 'name LIKE %2%'
	 *
	 * @param ActiveRecord $table
	 * @param array $rules
	 * @return ActiveRecord
	 */
	public function filter(array $data, array $rules)
	{
		foreach ($rules as $key => $rule)
		{
			if (!isset($data[$key]))
			{
				continue ;
			}

			if ($rule === true)
			{
				$field = $key;
				$sign = '=';
				$value = $data[$field];
			}

			if (is_string($rule))
			{
				if (trim($rule) == '')
				{
					continue ;
				}

				$field = $rule;
				$sign = '=';
				$value = $data[$key];
			}

			if (is_array($rule))
			{
				if (!isset($rule[0]))
				{
					continue ;
				}

				$field = $rule[0];
				$sign = always_set($rule, 1, '');
				$temp = always_set($rule, 2, '{value}');

				$value = str_replace('{value}', $data[$key], $temp);
			}

			if (!isset($field, $sign, $value))
			{
				continue ;
			}

			$this->where($field.' '.$sign, $value);
		}
	}


	public function match($match, $against, $mode = self::MATCH_IN_BOOLEAN_MODE)
	{
		$q = 'MATCH ('.$match.') AGAINST(\''.$this->escape($against).'\' '.$mode.')';

		return $this->where($q);
	}

	/**
	 * $table->where('col1', 10);
	 * $table->where('col1 = 10');
	 * $table->where('col1!=', '10');
	 * $table->where('col1', array(1, 2, 4));
	 */
	private function _where($type, $q, $value = null)
	{
		$ch = array('like', '=', '>', '<');

		if (is_array($value))
		{
			$this->_query_buffer['where'][] = $type.' '.$q.' IN ('.$this->_prepareValues($value).')';
			return $this;
		}

		if (is_null($value))
		{
			$this->_query_buffer['where'][] = $type.' '.$q;
			return $this;
		}

		$eq = $this->_getSignsCond($q)  ? '' : '=';

		$this->_query_buffer['where'][] = $type.' '.$q.$eq.'\''.$this->escape($value).'\'';
	}

	private function _getSignsCond($q)
	{
		return strpos($q, '=')
			|| strpos(strtolower($q), 'like')
			|| strpos($q, '>')
			|| strpos($q, '<');
	}

	public function clear()
	{
		$this->_query_buffer = $this->_init_query;
	}

	public function db()
	{
		return self::$_db;
	}

	public function setAlias($alias)
	{
		$this->_table_alias = $alias;
		return $this;
	}

	public function getAlias()
	{
		return $this->_table_alias;
	}

	public function prepareAlias()
	{
		return  $this->_table_alias ? 'AS '.$this->_table_alias : '';
	}

	public function getTableName()
	{
		return $this->_table_name;
	}

	public function escape($str)
	{
		return self::$_db->escape_string($str);
	}

	public function limit($param1, $param2 = null)
	{
		$this->_query_buffer['limit'] = 'LIMIT '.intval($param1);

		if ($param2)
		{
			$this->_query_buffer['limit'] .= ', '.intval($param2);
		}

		return $this;
	}

	public function orderBy($field, $direction = 'DESC')
	{
		$this->_query_buffer['orderBy'][] = $this->escape($field).' '.$this->escape($direction);

		return $this;
	}

	public function groupBy($field)
	{
		$this->_query_buffer['groupBy'][] = $this->escape($field);
		return $this;
	}

	public function join(\System\Db\ActiveRecord $table, $cond, $type = 'LEFT JOIN')
	{
		$this->_query_buffer['join'][] = $type.' '.$table->getTableName().' '.$table->prepareAlias().' ON '.$cond;

		return $this;
	}

	/**
	 * $table->update('c=c+2');
	 * $this->update('c=c+', 2);
	 * $this->update(array('c', 2));
	 * @return int
	 */
	public function update($data, $value = null)
	{
		if (!$data)
		{
			return false;
		}

		if (!is_null($value))
		{
			$data = array($data => $value);
		}

		$sql = 'UPDATE '.$this->_table_name.
			' SET '.$this->_prepareUpdates($data).
			' '.$this->_prepareWheres();

		$this->query($sql);

		$this->clear();

		return self::$_db->affected_rows;
	}

	public function insert(array $data)
	{
		$sql = 'INSERT INTO '.$this->_table_name.' ('.$this->_prepareKeys($data).')
				VALUES('.$this->_prepareValues($data).') '.$this->_query_buffer['duplicate'];

		$res = $this->query($sql);

		$this->clear();

		return $res ? self::$_db->insert_id : false;
	}

	public function insertAll(array $data)
	{
		$values = '';
		$d = '';

		foreach ($data as $row)
		{
			$values .= $d.'('.$this->_prepareValues($row).')';
			$d = ',';
		}

		$sql = 'INSERT INTO '.$this->_table_name.' ('.$this->_prepareKeys($data[0]).')
				VALUES'.$values.' '.$this->_query_buffer['duplicate'];

		$this->query($sql);

		$this->clear();

		return self::$_db->affected_rows;
	}

	public function delete($q = null, $value = null)
	{
		if (!is_null($q))
		{
			$this->where($q, $value);
		}

		$sql = 'DELETE FROM '.$this->_table_name.' '.$this->_prepareWheres();

		$this->query($sql);

		$this->clear();

		return self::$_db->affected_rows;
	}

	public function getValue($key, $default = false)
	{
		$res = $this->fetchOne();

		return always_set($res, $key, $default);
	}

	public function getHash($key, $value, $default = array())
	{
		$return = array();

		if (!$res = $this->fetchAll())
		{
			return $default;
		}

		foreach ($res as $values)
		{
			$return[$values[$key]] = $values[$value];
		}

		return $return;
	}

	public function fetchOne($key = null, $value = null)
	{
		$res = $this->limit(1)->_fetch($key, $value);

		return $res ? $res[0] : array();
	}

	public function fetchAll($key = null, $value = null)
	{
		return $this->_fetch($key, $value);
	}

	private function _fetch($key = null, $value = null)
	{
		if (!is_null($key))
		{
			$this->where($key, $value);
		}

		$sql = 'SELECT '.$this->_prepareSelects().
			' FROM '.$this->_table_name.' '.$this->prepareAlias().
			' '.$this->_prepareJoins().
			' '.$this->_prepareWheres().
			' '.$this->_prepareGroupBys().
			' '.$this->_prepareOrderBys().
			' '.$this->_query_buffer['limit'];

		$res = $this->_select($sql);

		$this->clear();

		return $res;
	}


	public function getPrimaryKey()
	{
		return $this->_primary_key;
	}

	public function getLastQuery()
	{
		return $this->_last_query;
	}

	public function query($sql)
	{
		$this->_last_query = $sql;

		if(!$res = self::$_db->query($sql))
		{
			if($this->_display_errors)
			{
				die(self::$_db->error);
			}

			return false;
		}

		return $res;
	}

	private function _select($sql)
	{
		$data = array();

		$res = $this->query($sql);

		while($row = $res->fetch_assoc())
		{
			$data[]=$row;
		};

		return $data;
	}

	private function _prepareUpdates($data)
	{
		if (is_string($data))
		{
			return $data;
		}

		$updates = '';
		$d = '';

		foreach ($data as $k => $v)
		{
			$eq = strpos($k, '=') ? '' : '=';

			$updates .=$d.$k.$eq.'\''.$this->escape($v).'\'';
			$d = ',';

			$eq = '';
		}

		return $updates;
	}

	private function _prepareJoins()
	{
		return implode(' ', $this->_query_buffer['join']);
	}

	private function _prepareGroupBys()
	{
		if (!$this->_query_buffer['groupBy'])
		{
			return '';
		}

		return 'GROUP BY '.implode(',', $this->_query_buffer['groupBy']);
	}

	private function _prepareOrderBys()
	{
		if (!$this->_query_buffer['orderBy'])
		{
			return	'ORDER BY '.$this->_order_by;
		}

		return 'ORDER BY '.implode(',', $this->_query_buffer['orderBy']);
	}
	private function _prepareSelects()
	{
		if (!$this->_query_buffer['select'])
		{
			return '*';
		}

		return implode(',', $this->_query_buffer['select']);
	}

	private function _prepareWheres()
	{
		$wheres = '1=1';

		foreach ($this->_query_buffer['where'] as $value)
		{
			$wheres .= ' '.$value;
		}

		return 'WHERE '.$wheres;
	}

	private function _prepareValues(array $data)
	{
		$d = '';
		$values = '';

		foreach ($data as $value)
		{
			$values .= $d.'\''.$this->escape($value).'\'';
			$d = ',';
		}

		return $values;
	}

	private function _prepareKeys(array $data)
	{
		$d = '';
		$keys = '';

		foreach ($data as $key => $value)
		{
			$keys .= $d.$key;
			$d = ',';
		}

		return $keys;
	}
}