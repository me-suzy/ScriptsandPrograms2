<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntFormWidget', 'pnt/web/widgets');

/** FormWidget that generates html specifying 
* a SELECT tag, by default as a dropdown list
* with values that are options for the property.
* @see http://www.phppeanuts.org/site/index_php/Pagina/128
*
* Limitation: PntSelectWidget assumes the id in the form, the idProperty
* and the id of the selected object in the property options to be equal.
* If the derived property's getter and setter behave differently,
* The SelectWidget may not be able to find the selected object in the list.
*
* This abstract superclass provides behavior for the concrete
* subclass SelectWidget in the widgets classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class PntSelectWidget extends PntFormWidget {
	
	var $settedCompulsory = true;
	var $autoSelectFirst = false;

	function PntSelectWidget(&$whole, &$requestData, $formText=null)
	{
		$this->PntFormWidget($whole, $requestData, $formText);
	}

	function getName() {
		return 'SelectWidget';
	}

	function initialize(&$text)
	{
		parent::initialize($text);
		if ($text) {
			$reqObj =& $this->getRequestedObject();
			$nav =& $text->getNavigation();

			$this->setSettedCompulsory($nav->isSettedCompulsory());
			if ($text->markup !== null) {
				$text->setItem($reqObj);
				$text->setConvertMarkup($text->markup);
			}
			$this->setSelectedId($text->getContentWith($reqObj));
	
			$optionObjects =& $nav->_getOptions($this->getRequestedObject());
			if (is_ofType($optionObjects, 'PntError')) {
				trigger_error($optionObjects->getLabel(), E_USER_WARNING);
				$options = array();
			}
			$this->setOptionsFromObjects($optionObjects, $nav->getItemType());
		}
	}

	//setters that extend the public interface
	function setSelectedId($value)
	{
		$this->selectedId = $value;
	}
	
	function setSettedCompulsory($value)
	{
		$this->settedCompulsory = $value;
	}

	function setAutoSelectFirst($value)
	{
		$this->autoSelectFirst = $value;
	}

	function setOptions(&$value)
	{
		$this->options = $value;
	}

	function setOptionsFromObjects(&$objects, $type)
	{
		$options = array();
		$cnv = $this->getConverter();
		reset($objects);
		while (list($key, ) = each($objects)) {	
			$id = $objects[$key]->get('id');
			$options[$id] = $cnv->toHtml(
				$cnv->toLabel($objects[$key], $type)
			);
		}
		$this->setOptions($options);
	}

	function printBody()
	{
?> 
		<SELECT NAME="<?php $this->printFormKey() ?>">
			<?php $this->printUnselectOption() ?> 
			<?php $this->printSelectOptions() ?> 
		</SELECT>
<?php
	}

	//print- and getter methods used by printBody
	function printUnselectOption()
	{
		if ($this->getSettedCompulsory() &&
				($this->getSelectedId() || $this->getAutoSelectFirst()) )
			return;
			
		$selected = $this->getOptionSelected(null);
		print "
			<OPTION VALUE='' $selected>&nbsp</OPTION>";
	}
	
	function printSelectOptions()
	{
		$options =& $this->getOptions();
		reset($options);
		while (list($value, $label) = each($options)) {
		$selected = $this->getOptionSelected($value);
		print "
			<OPTION $selected VALUE='$value'>$label</OPTION>";
		}
	}
	
	function getOptionSelected($id)
	{
		if ($id == $this->getSelectedId())
			return 'SELECTED';
		else
			return '';
	}

	function &getOptions()
	{
		return $this->options;
	}

	function getSelectedId()
	{
		return $this->selectedId;
	}
	
	function getSettedCompulsory()
	{
		return $this->settedCompulsory;
	}
	
	function getAutoSelectFirst()
	{
		return $this->autoSelectFirst;
	}

}