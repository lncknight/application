<?php

namespace Lncknight;


class Command{
	
	/**
	 * @var Config
	 */
	public $config;
	
	public $parts = [];
	
	/**
	 * Command constructor.
	 * @param $config
	 */
	public function __construct(Config $config)
	{
		$this->config = $config;
	}
	
	public static function php(){
		$config = Application::getDefault()->config;
		$c = new static($config);
		
		$c->parts[] = 'php';
		return $c;
	}
	
	public static function system(){
		$config = Application::getDefault()->config;
		$c = new static($config);
		
		$c->parts[] = $config->get('php.bin', 'php');
		$c->parts[] = BASE_PATH . 'console.php';
		$c->parts[] = $config->get('console_keys.0');
		return $c;
	}
	
	public static function wkhtmltoimage(){
		
		$config = Application::getDefault()->config;
		$c = new static($config);
		
		$c->parts[] = $config->get('wkhtmltoimage.bin');
		
		foreach ($config->get('wkhtmltoimage.default_options') as $k => $v) {
			if (strlen($k) > 1){
				$tmp = "--{$k}";
			}
			else{
				$tmp = "-{$k}";
			}
			
			if ($v){
				$tmp .= " {$v}";
			}
			
			$c->parts[] = $tmp;
		}
		
		return $c;
	}
	
	public function buildCommand(){

		$parts = [];
		
		foreach ($this->parts as $part) {
			$parts[] = "{$part}";
//			$parts[] = "'{$part}'";
		}
		
		return $command = implode(' ', $parts);
	}
	
	public function run(){
		$o2 = exec($this->buildCommand(), $o);
//		\G::log([
//			$this->buildCommand(),
//			$o,
//			$o2
//		]);
		return implode(chr(10), $o);
	}
	
}