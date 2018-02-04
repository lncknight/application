<?php

namespace Lncknight\LabelPrinter;

class Job {
	public $content;
	public $printer;
	public $filename;
	public $num;
	public $extension;
	public $options = [];
	
	public static function fromArray($data){
		$o = new static();
		foreach ($data as $k => $datum) {
			if (!property_exists($o, $k)){
				continue;
			}
			$o->{$k} = $datum;
		}
		
		return $o;
	}
	
}