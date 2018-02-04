<?php

namespace Lncknight;

class Config {
	public $_data;
	
	/**
	 * @param $file
	 * @return Config
	 * @throws Exception
	 */
	public static function fromFile($file){
		if (!file_exists($file)){
			throw new Exception('config.json not found');
		}
		$data = file_get_contents($file);
		$json = json_decode($data, 1);
		if (is_null($json)){
			throw new Exception('incorrect json format');
		}
		return self::fromArray($json);
	}
	
	/**
	 * @param $key
	 * @param null $default
	 * @return mixed|null
	 */
	public function get($key, $default = null, $data = null){
		
		$data = is_null($data) ? $this->_data : $data;

//		$data = is_null($data) ? [] : $data;
//		$config = new \Illuminate\Support\Collection($data);
//		$o = $config->get($key, $default);
////		App::debug([
////			$key,
////			$o
////		]);
//
//		if (is_object($o)){
//			return $o;
//		}
//		else{
//			return $o;
//		}
		
		
		if (strpos($key, ".") && is_array($data)) {
			$parts = explode(".", $key);
			$part = array_shift($parts);
			
			$data = isset($data[$part]) ? $data[$part] : $default;
//		    \App::log2([
//		    	$part,
//			    $data,
//			    implode('.', $parts)
//		    ], 'tmp');
			
			if (is_array($data)){
				return $this->get(implode('.', $parts), $default, $data);
			}
			else{
				return $data;
			}
		} else {
			return isset($data[$key]) ? $data[$key] : $default;
		}
	}
	
	public static function fromArray($data){
		$config = new Config();
		$config->_data = $data;
		
		return $config;
	}
	
	public function setConfig($k, $v){
		$this->_data[$k] = $v;
		return $this;
	}
	
	public function toArray(){
		return $this->_data;
	}
	
}