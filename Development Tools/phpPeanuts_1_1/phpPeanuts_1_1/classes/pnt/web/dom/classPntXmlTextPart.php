<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntXmlPart', 'pnt/web/dom');

class PntXmlTextPart extends PntXmlPart {

	var $content;
	var $markup;

	function PntXmlTextPart( &$whole, $markup=null, $content=null, $contentType='string', $decimalPrecision=2)
	{
		$this->PntXmlPart($whole);
		
		$this->setMarkup($markup);
		$this->setContent($content);
		$this->setContentType($contentType);
		$this->setDecimalPrecision($decimalPrecision);
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		$this->addFieldProp('content', 'string');
		$this->addFieldProp('markup', 'string');

	}

	function getLabel() {
		return $this->getMarkup();
	}

	function getContent() {
		return $this->content;
	}

	function getContentWith(&$item) {
		if ($this->content==null)
			return $item;
		else
			return $this->content;
	}
	
	function setContent($value) {
		$this->content = $value;
	}

	function getMarkupWith(&$item) {
		$content =& $this->getContentWith($item);
		$conv =& $this->getConverter();
		$conv->decimalPrecision = $this->getDecimalPrecision();
		$conv->type = $this->getContentType();

		$contentLabel = $conv->toLabel($content, $this->getContentType());
		$result = $this->merge($this->markup, $contentLabel);
		$result .= $this->getMarkupContent($item); //add elements markup?
		return $result;
	}

/*	function getMarkupContent(&$item) 
	{
		return " ". get_class($this) . count($this->getElements()). " elements ";
	}
*/	
	function setMarkup($value) {
		$this->markup = $value;
	}

	function merge(&$template, &$content)
	{
		$conv =& $this->getConverter();
		$contentHtml = $conv->toHtml($content);
		
		if (!empty($template))
			return str_replace('$content', $contentHtml, $template);
		else
			return $contentHtml;
	}
	
	function getContentType()
	{
		return $this->contentType;
	}
	
	function setContentType($value)
	{
		$this->contentType = $value;
	}
	
	function getDecimalPrecision()
	{
		return $this->decimalPrecision;
	}
	
	function setDecimalPrecision($value)
	{
		$this->decimalPrecision = $value;
	}
}
?>