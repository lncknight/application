<?php

namespace Lncknight\Backup;

use Lncknight\Helper;

class RotateBackups {

	public $config = [
		'daily' => 0,
		'weekly' => 0,
		'monthly' => 0,
		'yearly' => 0,
	];
	
	public $virtualPath;

	public function getTimestampNow(){
		return time();
	}

	public function setConfig($config) {
		$this->config = array_merge($this->config, $config);
		return $this;
	}

	public function checkFiles($files){
		$path = $this->virtualPath;
		
		Helper::createDir($path);

		$filesMap = [];
		foreach ($files as $file) {
			$newFilename = $this->getTimestamp(basename($file));
			$filesMap[$newFilename] = $file;
			Helper::createDir(dirname($path.$newFilename));
// print_r($path.$newFilename . "\n");			
			file_put_contents($path.$newFilename, '');
		}
		
		// process rotate-backups
		// https://pypi.org/project/rotate-backups/#customizing-the-rotation-algorithm
		$command = [];
		$command[] = 'rotate-backups';
		foreach ($this->config as $k => $item) {
			$command[] = "--{$k}=$item";
		}
		$command[] = $path;
//	$command = "rotate-backups --config /var/www/html/lovestruck.com/service/public/data/test.ini";
		$commandStr = implode(' ', $command);
//\App::getDefault()->logTmp($commandStr);
		exec($commandStr);
// print_r([
// 	$commandStr
// ]);		
		$files2 = scandir($path, 1);
		
		$files2 = array_filter($files2, function($v){
			return !in_array($v, [
				'.', '..'
			]);
		});
		
		$rs = [];
		foreach ($filesMap as $virtualFileKey => $s3FileKey) {
			$rs[$s3FileKey] = in_array($virtualFileKey, $files2) ? 'keep' : 0;
		}

		// clear up virtual folder
		Helper::rrmdir($path);

		return $rs;
	}
	
	/**
	 * @param $file
	 * @return false|string
	 */
	public function getTimestamp($file){
		// preg_match('/.*-(\d+).*/', $file, $matches);

		preg_match('/.*(\d{10}+)_.*gitlab/', $file, $matches);
		if ($matches){
			$file = date('Ymd_His', @$matches[1]);
		}
		
// print_r([
// 	$matches
// ]);
		return $file;
		
		if (!isset($date)){
			$ps = explode('_', $file);
			if (is_numeric($ps[0])){
                $date = date('YmdHis', $ps[0]);
            }
		}
		
		if (!isset($date)){
		    return $file;
        }
		
		return $date;
	}

}