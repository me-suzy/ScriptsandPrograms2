<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntFormWidget', 'pnt/web/widgets');

/** FormWidget that generates html specifying a checkbox. 
*
* This abstract superclass provides behavior for the concrete
* subclass CheckboxWidget in the widgets classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class PntCheckboxWidget extends PntFormWidget {

	var $checkedValue;

	function PntCheckboxWidget(&$whole, &$requestData, $formText=null)
	{
		$this->PntFormWidget($whole, $requestData, $formText);
	}

	function getName() {
		return 'CheckboxWidget';
	}

	//setters that extend the public interface
	function setCheckedValue($checkedValue)
	{
		$this->checkedValue = $checkedValue;
	}

	function printBody()
	{
?> 
		<INPUT TYPE='CHECKBOX' NAME='<?php $this->printFormKey() ?>' VALUE='<?php print $this->getCheckedValue() ?>' <?php $this->printChecked() ?> >
<?php
	}

	//print- and getter methods used by printBody
	function printChecked()
	{
		if ($this->value == $this->getCheckedValue())
			print 'CHECKED';
	}
	
	function getCheckedValue()
	{
		if ($this->checkedValue)
			return $this->checkedValue;
			
		$cnv =& $this->getConverter();
		$true = true;
		return $cnv->toLabel($true, 'boolean');
	}
	
	
}