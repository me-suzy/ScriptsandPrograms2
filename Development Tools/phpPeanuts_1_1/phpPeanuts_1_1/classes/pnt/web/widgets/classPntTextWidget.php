<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntFormWidget', 'pnt/web/widgets');

/** FormWidget that generates html specifying an input type=Text.
*
* This abstract superclass provides behavior for the concrete
* subclass TextWidget in the widgets classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class PntTextWidget extends PntFormWidget {

	function PntTextWidget(&$whole, &$requestData, $formText=null)
	{
		$this->PntFormWidget($whole, $requestData, $formText);
	}

	function getName() {
		return 'TextWidget';
	}

	//setters that extend the public interface

	function printBody()
	{
?>
		<INPUT TYPE='TEXT' NAME='<?php $this->printFormKey() ?>' SIZE="40" MAXLENGTH="<?php //$this->printFormProp("maxLength"); ?>" VALUE='<?php $this->printValue() ?>'>
<?php
	}


}