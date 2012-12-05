<?php
namespace Model\Modules\Admin;

use Lib\Modules\GridRows;
use Lib\Modules\Rows;
use Db\Modules\Modules as TableModules;
use System\Exceptions\Exception as SystemException;
use System\Mvc\Model as SystemModel;
use Lib\Modules\Results;
use Lib\Modules\GridResults;

class Modules extends SystemModel
{
	const MODULE_GUID_NOT_SAVED = 1;

	protected function _getTable()
	{
		return new TableModules();
	}

	/**
	 * получаем данные о модуле из файла конфигурации смерджаные с сохранеными данными
	 * @see System\Mvc.Model::getAll()
	 */
	public function getAll()
	{
		if (!$files_modules = $this->_getAllFromFiles())
		{
			return array();
		}

		$saved_modules = $this->_table->fetchAll();

		return new Results($files_modules, $saved_modules);
	}

	public function getAll4Grid()
	{
		return new GridResults($this->getAll());
	}

	/**
	 * (non-PHPdoc)
	 * @see System\Mvc.Model::get()
	 */
	public function get()
	{
		if (!$files_model = $this->_getFromFile($this->_id))
		{
			return array();
		}

		$saved_module = array();

		if ($guid = always_set($files_model, 'guid'))
		{
			$saved_module = $this->_table->fetchOne('guid', $guid);
		}

		return new Rows($files_model, $saved_module);
	}

	public function get4Grid()
	{
		return new GridRows($this->get());
	}

	public function install($data)
	{
		$guid = gen_guid();

		$item = array(
			'title' => $data['info']['title'],
			'description' => $data['info']['description'],
			'menu' => serialize($data['menu']),
			'guid' => $guid
		);

		$this->_table->insert($item);

		$this->_saveGUID($guid, root_path().'/modules/'.$data['name']);
	}

	public function uninstall()
	{
		if (!$guid = always_set($this->get(), 'guid'))
		{
			return false;
		}

		$this->_table->delete('guid', $guid);
	}

	public function pin()
	{
		if (!$guid = always_set($this->get(), 'guid'))
		{
			return false;
		}

		$this->_table
			->where('guid', $guid)
			->update('pin=IF(pin=1, 0, 1)');
	}

	private function _getAllFromFiles()
	{
		$modules = array();

		$dirs = new \DirectoryIterator(root_path().'/modules');

		foreach ($dirs as $dir)
		{
			if ($dir->isDir())
			{
				$module = $this->_getFromFile($dir->getFilename());

				if ($module)
				{
					$modules[] = $module;
				}
			}
		}

		return $modules;
	}

	private function _getFromFile($name)
	{
		$path = root_path().'/modules/'.$name;

		if (!file_exists($path))
		{
			return array();
		}

		$conf = $path.'/config.php';

		if (!file_exists($conf))
		{
			return array();
		}

		include $conf;

		$guid = $this->_fetchGUID($path);

		$res = array(
			'info' => $info,
			'menu' => $menu,
			'name' => $name,
			'pin' => 0,
		);

		if ($guid)
		{
			$res['guid'] = $guid;
		}

		return $res;
	}

	private function _fetchGUID($path)
	{
		$guid = false;

		$guid_file = $path.'/.guid';

		if (file_exists($guid_file))
		{
			$guid = trim(file_get_contents($guid_file));
		}

		return $guid;
	}

	private function _saveGUID($guid, $path)
	{
		$guid_path =  $path.'/.guid';

		if(file_put_contents($guid_path, $guid) === false)
		{
			throw new SystemException('GUID модуля "'.$name.'" небыл сохранен', self::MODULE_GUID_NOT_SAVED);
		}
	}
}