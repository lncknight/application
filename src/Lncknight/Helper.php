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
	
	public static function rrmdir($src) {
		$dir = opendir($src);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				$full = $src . '/' . $file;
				if ( is_dir($full) ) {
					self::rrmdir($full);
				}
				else {
					unlink($full);
				}
			}
		}
		closedir($dir);
		rmdir($src);
	}
	
}