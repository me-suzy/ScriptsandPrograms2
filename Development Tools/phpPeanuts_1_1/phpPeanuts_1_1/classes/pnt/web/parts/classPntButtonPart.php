<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPagePart', 'pnt/web/parts');

/** Part that outputs a button in html. Used by ButtonsPanel.
*
* This abstract superclass provides behavior for the concrete
* subclass ButtonPart in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
* @package pnt/web/parts
*/
class PntButtonPart extends PntPagePart {

	var $caption;
	var $script;
	var $ghost;
	var $width;
	
	var $minLength = 12;
	var $baseWidth = 28;
	var $widthMultiplier = 6;
	var $cssClass = 'funkyButton'; //extended with 'Ghost' if the button is ghosted

	function PntButtonPart(&$whole, &$requestData, $caption, $script, $ghost=false, $width=null)
	{
		$this->PntPage($whole, $requestData);
		$this->caption = $caption;
		$this->script = $script;
		$this->ghost = $ghost;
		$this->width = $width;
	}

	function setMinLength($value)
	{
		$this->minLength = $value;
	}

	function setBaseWidth($value)
	{
		$this->baseWidth = $value;
	}

	function setWidthMultiplier($value)
	{
		$this->widthMultiplier = $value;
	}
	
	/** Sets the value for the class= in the button tag. 
	* will be extended with 'Ghost' if the button is ghosted
	*/
	function setCssClass($value)
	{
		$this->cssClass = $value;
	}

	function getName() {
		return 'ButtonPart';
	}

	function printBody($args, $type)
	{
		$class = $this->getButtonClass($type); 
		$width = $this->width;
		if ($width===null) {
			$len = strlen($this->caption);
			if ($len < $this->minLength) 
				$len=$this->minLength;
			$width = $len * $this->widthMultiplier + $this->baseWidth;
		}		
		
		if ($this->ghost)
			$disabled="disabled";
		else 
			$disabled="";
		
		$this->printButton($disabled, $width, $class, $this->script, $this->caption, $type);
	}
	
	function printButton($disabled, $width, $cssClass, $script, $caption, $type)
	{
		print "<input type=button $disabled style=\"width: ".$width."px;\" class=\"$cssClass\" onClick=\"$script\" value=\"$caption\">\n";
	}
		
	/** override this method to get different css class for different type
	*/
	function getButtonClass($type)
	{
		if ($this->ghost)
			return $this->cssClass. 'Ghost';
		else
			return $this->cssClass;
	}
	
}
?>