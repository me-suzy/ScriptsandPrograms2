<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPagePart', 'pnt/web/parts');

/** Abstract FormWidget superclass that generates html 
* specifying an input type=Text.
*
* There is no concrete subclass for this class, 
* if you need one, please add it to the widgets classFolder.
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class PntFormWidget extends PntPagePart {

	function PntFormWidget(&$whole, &$requestData, &$formText)
	{
		$this->PntPagePart($whole, $requestData);
		$this->initialize($formText);
	}

	function initialize(&$formText)
	{
		if ($formText) {
			$this->setFormKey($formText->getFormKey());
			$this->setValue($formText->getMarkupWith($this->getRequestedObject()));
		}
	}

	//getName

	//setters that form the public interface
	function setFormKey($value)
	{
		$this->formKey = $value;
	}

	function setValue($value)
	{
		$this->value = $value;
	}

	//just demonstrating...
	function printBody()
	{
?>
		<INPUT TYPE='HIDDEN' NAME='<?php $this->printFormKey() ?>' VALUE='<?php $this->printValue() ?>'>
<?php
	}


	//print- and getter methods used by printBody
	function printFormKey()
	{
		print $this->formKey;
	}

	/*8-3-2004 14:50
	prints a fieldproperty from the propertydescriptor e.g. maxLength
BAD DESIGN: no support of encapsulation, buggy
NIET INSCHAKELEN ZONDER OVEREENSTEMMING in HET BESTUUR4
	function printFormProp($which) {
		print $this->whole->formTexts[$this->formKey]->prop->$which;
	}
*/
	function printValue()
	{
		print $this->value;
	}

}