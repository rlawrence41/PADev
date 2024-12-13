<?php

class progressBar extends webObj{
	public $template = "progressBar.html";
	public $action   = "setProgress()" ;
	public $progress = 0;
	public $progressMax = 4;
	
	public function render($record=null) {

		$html = parent::render() ;
		$html = str_replace("{progress}", strval($this->progress), $html);
		return $html;
		
		
	}
}

