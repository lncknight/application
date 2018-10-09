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
//		rrmdir($path);

		return $rs;
	}
	
	/**
	 * @param $file
	 * @return false|string
	 */
	public function getTimestamp($file){
		preg_match('/.*-(\d+).*/', $file, $matches);
		if ($matches){
			$date = date('YmdHis', strtotime(@$matches[1]));
		}
		
		if (!isset($date)){
			$ps = explode('_', $file);
			$date = date('YmdHis', $ps[0]);
		}
		
		return $date;
	}

}