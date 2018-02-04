<?php

/**
 * @property array $args
 */
class PrinterExample {
	
	public function perform()
	{
		
		$job = \Lncknight\LabelPrinter\Job::fromArray($this->args);
		
		$server = new \Lncknight\LabelPrinter\Server();
		$server->run($job);
	}
	
}