<?php

namespace Lncknight;

class Application {
	
	protected static $_instance;
	
	public static $request_id;
	
	/**
	 * @var Config
	 */
	public $config;
	
	public function __construct()
	{
		self::$_instance = $this;
		
		
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
//        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);
		
		for ($i = 0, $result = ''; $i < 3; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}
		
		self::$request_id = date('ymd_His_') . $result;
	}
	
	public static function getDefault(){
		return self::$_instance;
	}
	
}