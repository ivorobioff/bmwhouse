<?php
namespace Model\Modules\Admin;

class Modules
{
	public function getModules4Grid()
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

					$modules[] = array(
						'info' => $info,
						'menu' => $menu
					);
				}
			}
		}

		return $modules;
	}
}