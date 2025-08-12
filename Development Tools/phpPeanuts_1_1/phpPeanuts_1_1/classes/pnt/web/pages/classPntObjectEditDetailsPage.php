<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectDetailsPage', 'pnt/web/pages');

/** Page showing property labels and editing property values of a single object
* By default shows properties specified by  getUiFieldPaths method 
* on the class of the shown object. Layout can be specialized, 
* @see http://www.phppeanuts.org/site/index_php/Pagina/150
*
* This abstract superclass provides behavior for the concrete
* subclass ObjectEditDetailsPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/pages
*/
class PntObjectEditDetailsPage extends PntObjectDetailsPage {

	var $object;
	var $formTexts;

	function PntObjectEditDetailsPage(&$whole, &$requestData)
	{
		$this->PntPage($whole, $requestData);
	}

	function getName() {
		if (isSet($this->requestData['id']) && $this->requestData['id'])
			return 'Update';
		else
			return 'Create';
	}

	function printInformationPart() {

		print $this->getInformation();

		$formTexts =& $this->getFormTexts();
		if (empty($formTexts))
			return;

		reset($formTexts);
		while (list($formKey) = each($formTexts)) {
			$current =& $formTexts[$formKey];
			$error = $current->getError();
			if ($error) {
				print '<BR><B>'.$current->getPathLabel().'</B>';
				print '<BR>'.$error;
			}
		}
	}

	/** If no other information, Return the editInformation from the requestedObject
	*/
	function getInformation()
	{
		$info = parent::getInformation();
		if ($info)
			return $info;

		$obj =& $this->getRequestedObject();
		if ($obj)
			return $obj->getEditInfo();

		return '<B>'.getOriginalClassName(get_class($this)).' Error:</B><BR>Item not found: id='.$this->requestData['id'];
	}

	function printMainPart() {
		$this->printPart('DetailsPart');
	}

	function &getButtonsList()
	{
		$type = $this->getType();
		$id = isSet($this->requestData['id']) ? $this->requestData['id'] : null;
		$actButs = array();

		$actButs[]=$this->getButton($this->getName(), "document.detailsForm.submit();");
		if ($id) {
			$actButs[]=$this->getButton('Create New', "document.detailsForm.id.value='0'; document.detailsForm.submit();");
			$actButs[]=$this->getButton('Delete', "document.detailsForm.pntHandler.value='DeleteAction'; document.detailsForm.submit();");
		}
		$navButs=array();
		$this->addContextButtonTo($navButs);
		$this->addMultiValuePropertyButtons($navButs);
		$navButs[]=$this->getButton('Report', "popUpWindowAutoSizePos('index.php?pntType=$type&id=$id&pntHandler=ReportPage');");

		return array($actButs, $navButs);
	}

	function includeOrPrintDetailsTable() {

		$object =& $this->getRequestedObject();
		if (!$object) return;

		print "
<SCRIPT>
			 func"."tion openPageFor(formKey, urlNoId) {
				objectId = document.detailsForm[formKey].value;
				str = urlNoId+objectId;
				document.location.href=str;
			}
</SCRIPT>";
		parent::includeOrPrintDetailsTable();
	}

	function printFormWidget($formKey)
	{
		includeClass('PntXmlElement', 'pnt/web/dom');
		$formTexts =& $this->getFormTexts();

		$text =& $formTexts[$formKey];
		if ($text === null) return print "error no: $formKey";

		if (!$this->willBeInput($text))
			return $this->printFormText($formKey);

		$widget =& $this->getFormWidget($text);
		if ($widget)
			return $widget->printBody();
	}

	function &getFormWidget(&$text)
	{
		$nav =&  $text->getNavigation();
		if ($nav->getResultType() == 'boolean')
			 return $this->getCheckboxWidget($text);

		$dialogClass = $nav->getResultType().'Dialog';
		if ($this->tryUseClass($dialogClass, $this->getDir()))
			return $this->getDialogWidget($text);

		if ($text->usesIdProperty()) {
			$clsDes =& PntClassDescriptor::getInstance($nav->getResultType());
			$obj =& $this->getRequestedObject();
			$prop =& $obj->getPropertyDescriptor($nav->getKey());
			if (is_subclassOr($nav->getResultType(), 'PntDbObject')
					&& $clsDes->getPeanutsCount() >= $this->getDialogTreshold($text)
					&& !$prop->hasOptionsGetter($obj)) {
				return $this->getDialogWidget($text);
			} else {
				return $this->getSelectWidget($text);
			}
		}
				$maxLength = $text->prop->getMaxLength();
		if (is_integer($maxLength) && $maxLength > $this->getTextAreaTreshold())
			return $this->getTextAreaWidget($text);
		//else
			return $this->getTextWidget($text);
	}

	function getTextAreaTreshold() {
		return 210;
	}

	function getDialogTreshold() {
		return 40;
	}

	function &getCheckboxWidget(&$text)
	{
		includeClass('CheckboxWidget', 'widgets');
		return new CheckboxWidget($this, $this->requestData, $text);
			}


	function &getTextWidget(&$text)
	{
		includeClass('TextWidget', 'widgets');
		return new TextWidget($this, $this->requestData, $text);
	}

	function &getTextAreaWidget(&$text)
	{
		includeClass('TextAreaWidget', 'widgets');
		return new TextAreaWidget($this, $this->requestData, $text);
	}

	function &getSelectWidget(&$text)
	{
		includeClass('SelectWidget', 'widgets');
		return new SelectWidget($this, $this->requestData, $text);
	}

	//prerequisite: resultType has already been included (happens in getDetailsLinkFromNavText)
	function &getDialogWidget(&$text)
	{
		includeClass('DialogWidget', 'widgets');
		return new DialogWidget($this, $this->requestData, $text);
	}

	function getDetailsLinkFromNavText(&$text, $content, $hrefNoId=null)
	{
		if (!$this->willBeInput($text))
			return parent::getDetailsLinkFromNavText($text, $content, $hrefNoId);

		$formKey = $text->getFormKey();
		if (!$hrefNoId) {
			$nav =& $text->getNavigation();
			$hrefNoId = $this->getDetailsHref(
				$this->getLinkDirFromNav($nav),
				$nav->getResultType()
			);
		}

		$url = "javascript:openPageFor('$formKey', '$hrefNoId');";

		return "<A HREF=\"$url\">$content</A>";
	}

	function willBeInput(&$text)
	{
		return !$text->isReadOnly();
	}

}
?>