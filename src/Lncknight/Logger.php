<?php

namespace Lncknight;

class Logger {
	
	public $request_id;
	
	/**
	 * @var Config
	 */
	public $config;
	
	/**
	 * Logger constructor.
	 * @param $request_id
	 */
	public function __construct(Config $config, $request_id = null)
	{
		$this->request_id = $request_id;
		$this->config = $config;
	}
	
	public function logInline($rs){
		$message = [];
		foreach ($rs as $key => $r) {
			$message[] = "{$key}: {$r}";
		}
		
		$this->log(implode('; ', $message));
	}
	
	public function logTmp($i){
		$this->log($i, 'tmp');
	}
	
	/**
	 * @param $message
	 * @param null $folder
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function log($message, $folder = null){
		
		$msg = $message;
		
		$request_id = $this->request_id;
		$date = date('Y-m-d H:i:s');
		$prepand_content = "{$date}";
		if ($this->request_id){
			$prepand_content .= " - [{$request_id}]";
		}
		if (is_array($msg) || is_object($msg)) {
			$msg = $prepand_content . "\r\n" . print_r($msg,1);
		}
		else {
			$msg = $prepand_content . ' - ' . $msg;
		}
		$msg .= "\r\n";
		
		// Write $somecontent to our opened file.
		//if (fwrite($handle, date('Y-m-d H:i:s ').$_SERVER['REQUEST_URI'].': '.$msg."\$_SESSION=".serialize($_SESSION)."\r\n\$_GET=".serialize($_GET)."\r\n\$_POST=".serialize($_POST)."\r\n") === FALSE) {
		
		
		
		$section_name = strlen($folder) ? $folder : 'default';
		
		
		if (!$this->config->get('base_path')){
			throw new Exception('`base_path` not found');
		}
		
		$log_dir = $this->config->get('base_path') . 'logs' . DIRECTORY_SEPARATOR;
		$log_file = $log_dir . $section_name . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
		
		Helper::createDir(dirname($log_file));
		
		// write file
		if (!file_exists($log_file)) {
			@touch($log_file);
			@chmod($log_file, 0777);
		}
		
		if (is_writable($log_file))
		{
			if (!$handle = fopen($log_file, 'a')) {
				return;
			}
		}
		else
			return;
		
		if (fwrite($handle, $msg) === FALSE) {
			return;
		}
		fclose($handle);
		
		return ;
	}
	
}