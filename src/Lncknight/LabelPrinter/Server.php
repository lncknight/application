<?php

namespace Lncknight\LabelPrinter;

class Server
{
	
	public $tmp_path;
	
	/**
	 * @var Log
	 * TODO not using+
	 */
	public $log;
	
	/**
	 * Server constructor.
	 */
	public function __construct()
	{
		$this->log = new Log();
	}
	
	/**
	 * @param Job $job
	 * @return bool
	 * @throws InvalidException
	 */
	public function run(Job $job)
	{
		
		// $tmp = $this->args;
		// unset($tmp['content']);
		// echo implode(' ', $tmp);
		
		$date_string = date('Y-m-d H:i:s');
		echo "[{$date_string}]" . chr(10);
		
		echo 'received job, processing' . chr(10);
		
		$content = $job->content;
		$printer = $job->printer;
		if (!$content || !$printer) {
			echo 'empty content, skipped.' . chr(10);
			return false;
		}
		
		$filename = $job->filename ?: uniqid();
		$num = $job->num ?: 1;
		if ($this->tmp_path) {
			$tmp_path = $this->tmp_path;
		} else if (defined('BASE_PATH')) {
			$tmp_path = BASE_PATH . 'tmp/';
		}
		else{
			throw new InvalidException('tmp path not defined');
		}
		$file = $tmp_path . $filename;
		
		if ($job->extension == 'png') {
			file_put_contents($file, base64_decode($content));
		} else {
			file_put_contents($file, $content);
		}
		
		// todo move to config
		
		$command_r = [];
		$command_r[] = '/usr/bin/lp -d';
		$command_r[] = $printer;
		if ($job->extension == 'zpl') {
			$command_r[] = '-o raw';
		}
		if ($job->options) {
			$command_r[] = implode(' ', $job->options);
		}
		$command_r[] = '-n ' . $num;
		$command_r[] = $file;
		$command = implode(' ', $command_r);
		exec($command);
		
		echo $command . chr(10);
		
		$data = [
			__CLASS__,
			$job,
			date('Y-m-d H:i:s'),
			$command,
//			$content
		];

//		echo print_r($data, 1) . chr(10);
		$time = date('Y-m-d H:i:s');
		echo "[$time] done {$filename}" . chr(10);
		
		return true;
		
	}
	
}