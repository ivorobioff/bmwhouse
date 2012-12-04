<?php
namespace Model\Modules\Admin;

use Db\Modules\Modules as TableModules;
use System\Exceptions\Exception as SystemException;
use System\Mvc\Model as SystemModel;
use Lib\Modules\Results;
use Lib\Modules\GridResults;

class Modules extends SystemModel
{
	const MODULE_NOT_INSTALLED = 1;
	const MODULE_GUID_NOT_SAVED = 2;

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
		$files_modules = $this->_getAllFromFiles();
		$saved_modules = $this->_table->fetchAll();

		return new Results($files_modules, $saved_modules);
	}

	public function getAll4Grid()
	{
		return new GridResults($this->getAll());
	}

	public function install()
	{
		$name = $this->_id;

		$module = $this->_getFromFile($name);

		$data = array(
				'title' => $module['info']['title'],
				'description' => $module['info']['description'],
				'menu' => serialize($module['menu'])
		);

		if (!$this->_table->insert($data))
		{
			throw new SystemException('Модуль "'.$name.'" небыл установлен', self::MODULE_NOT_INSTALLED);
		}

		$this->_saveGUID(root_path().'/modules/'.$name);
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

		$conf = $path.'/config.php';

		if (!file_exists($conf))
		{
			return false;
		}

		include $conf;

		$guid = $this->_fetchGUID($path);

		return array(
			'info' => $info,
			'menu' => $menu,
			'guid' => $guid,
			'name' => $name,
			'pin' => 0,
		);
	}

	private function _fetchGUID($path)
	{
		$guid = '';

		$guid_file = $path.'/.guid';

		if (file_exists($guid_file))
		{
			$guid = trim(file_get_contents($guid_file));
		}

		return $guid;
	}

	private function _saveGUID($path)
	{
		$guid_path =  $path.'/.guid';
		$guid = gen_guid();

		if(file_put_contents($guid_path, $guid) === false)
		{
			throw new SystemException('GUID модуля "'.$name.'" небыл сохранен', self::MODULE_GUID_NOT_SAVED);
		}
	}
}