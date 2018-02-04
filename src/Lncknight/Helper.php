<?php

namespace Lncknight;

class Helper {
	
	/**
	 * folder /var/folder/
	 *
	 * @param $dir_path
	 * @return bool
	 */
	public static function createDir($dir_path){
		if (!file_exists($dir_path)){
			$umask = umask(0);
			mkdir($dir_path, 0777, true);
			umask($umask);
		}
		
		return true;
	}
}