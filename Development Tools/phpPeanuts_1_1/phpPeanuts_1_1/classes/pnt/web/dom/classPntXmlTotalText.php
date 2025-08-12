<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntXmlTextPart', 'pnt/web/dom');

class PntXmlTotalText extends PntXmlTextPart {

	var $sum = 0;
	var $count = 0;
	
	function totalize($value)
	{
		$this->sum += $value;
		$this->count += 1;
		$this->totalizeContent($value);
	}
	
	function totalizeContent($value)
	{
		$this->content = $this->sum;
	}
		
}
?>