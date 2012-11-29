<?php
namespace Model\Modules\Admin;

class Modules
{
	public function get4Grid()
	{
		return $this->getAvailable();
	}

	public function getAvailable()
	{
		$modules = array();

		$dirs = new \DirectoryIterator(root_path().'/modules');

		foreach ($dirs as $dir)
		{
			if ($dir->isDir())
			{
				$path = $dir->getPathname();

				$conf = $path.'/config.php';

				if (file_exists($conf))
				{
					include $conf;

					$guid = $this->_getGUID($path);

					$modules[] = array(
						'info' => $info,
						'menu' => $menu,
						'guid' => $guid,
						'name' => $dir->getFilename(),
					);
				}
			}
		}

		return $modules;
	}

	private function _getGUID($path)
	{
		$guid = '';

		$guid_file = $path.'/.guid';

		if (file_exists($guid_file))
		{
			$guid = trim(file_get_contents($guid_file));
		}

		return $guid;
	}

	private function getSaved()
	{

	}
}