<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntFormWidget', 'pnt/web/widgets');

/** FormWidget that generates html specifying a TextArea.
*
* This abstract superclass provides behavior for the concrete
* subclass TextAreaWidget in the widgets classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class PntTextAreaWidget extends PntFormWidget {

	function PntTextAreaWidget(&$whole, &$requestData, $formText=null)
	{
		$this->PntFormWidget($whole, $requestData, $formText);
	}

	function getName() {
		return 'TextAreaWidget';
	}

	//setters that extend the public interface

	function printBody()
	{
?> 
		<TEXTAREA NAME='<?php $this->printFormKey() ?>' ROWS='7' COLS='39'><?php $this->printValue() ?></TEXTAREA>
<?php
	}

	
}