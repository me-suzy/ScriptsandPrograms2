<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');

/** Page showing property labels and editing property values of a single object
* By default shows properties specified by  getUiFieldPaths method 
* on the class of the shown object. Layout can be specialized, 
* @see http://www.phppeanuts.org/site/index_php/Pagina/150
*
* This abstract superclass provides behavior for the concrete
* subclass ObjectDetailsPage in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
* @package pnt/web/pages
*/
class PntObjectDetailsPage extends PntPage {

	var $object;
	var $formTexts;

	function getName() {
		return 'Details';
	}

	/** Polymorpism support: forward to proper page if requestedObject is of 
	* type different of pntType
	*/
	function handleRequest()
	{
		$this->useClass($this->getType(), $this->getDomainDir());
		$obj =& $this->getRequestedObject();
		if ($this->getType() == $obj->getClass()) 
			return parent::handleRequest(); // normal handling by this object
	
		//forward to another page
		$requestData = $this->requestData;
		$requestData['pntType'] = $obj->getClass();
		$handler =& $this->getRequestHandler($requestData);
	
		$handler->setRequestedObject($obj); //so that the page does not have to retrieve it again
		
		//the following may have been set by another handler
		$handler->setInformation($this->information); 
		$handler->setFormTexts($this->formTexts);
	 	$handler->setInfoStyle($this->infoStyle);
	 	
		$handler->handleRequest();
	}

	function initForHandleRequest() 
	{
		// initializations
		parent::initForHandleRequest();
		$this->getRequestedObject();
		$this->getFormTexts();

	}

	function getButtonsList() 
	{
		// only used if menu, info and buttons
		$actButs = array();
		$type = $this->getType();
		$id = $this->requestData['id'];

		$navButs=array();
		$this->addContextButtonTo($navButs);
		$navButs[]=$this->getButton('Details', "document.location.href='index.php?pntType=$type&id=$id';");
		$this->addMultiValuePropertyButtons($navButs);
		$navButs[]=$this->getButton('Report', "popUpWindowAutoSizePos('index.php?pntType=$type&id=$id&pntHandler=ReportPage');");

		return array($actButs, $navButs);
	}
	
	function addContextButtonTo(&$buttons)
	{
		if (!isSet($this->requestData['pntContext'])) return;
		$context = $this->requestData['pntContext'];
		
		$arr = explode('*', $context);
		$type = $arr[0];
		$id = isSet($arr[1]) ? $arr[1] : null;
		$idSpec = $id ? "&id=$id" : '';
		$propName = isSet($arr[2]) ? $arr[2] : null;;
		$handlerSpec= $propName 
			? "&pntHandler=PropertyPage&pntProperty=$propName" 
			: '';
		$buttons[]=$this->getButton('Context',	"document.location.href='index.php?pntType=$type$idSpec$handlerSpec';");
	}

	function willBeInput(&$text)
	{ 
		return false; //no input widgets 
	}
	
	function printLabelPart()
	{
		$obj =& $this->getRequestedObject();
		print '<H1>'.$obj->getLabel().'</H1>';;	
	}

	function includeOrPrintDetailsTable() {

		$object =& $this->getRequestedObject();
		if (!$object) return;

		$type = $this->getType();
		$filePath = "skin$type".'DetailsTable.php';
//print $filePath;
		if (file_exists($filePath))
			include($filePath);
		else
			$this->printPart('DetailsTablePart');
	}

	function printDetailsRows($readOnly, $printSkin=false)
	{
		$formTexts =& $this->getFormTexts();
		reset($formTexts);
		while (list($formKey) = each($formTexts)) {
			$current =& $formTexts[$formKey];
			if ($current->isReadOnly() == $readOnly) {
				if ($printSkin)
					$this->printSkinDetailsRow($formKey);
				else
					$this->printDetailsRow($formKey);
			}
		}
	}
	
	function printDetailsRow($formKey)
	{
?>
                                <TR vAlign=top> 
                                	<TD class=pntHeader><?php $this->printFormLabel($formKey) ?></TD>
                                	<TD ><?php $this->printDetailsLink($formKey) ?></TD>
                                	<TD class=pntNormal><?php $this->printFormWidget($formKey) ?></TD>
                                </TR>
<?php				
	}

	function printSkinDetailsRow($formKey)
	{
		print "
                                <TR vAlign=top> 
                                	<TD class=pntHeader><?php \$this->printFormLabel('$formKey') ?></TD>
                                	<TD ><?php \$this->printDetailsLink('$formKey') ?></TD>
                                	<TD class=pntNormal><?php \$this->printFormWidget('$formKey') ?></TD>
                                </TR>
";	
	}

	function &getFormWidget(&$text)
	{
		return arrayWith($text); //asArray
	}
	
	function getMarkupFromFormText($formKey)
	{
		$formTexts =& $this->getFormTexts();
		$text =& $formTexts[$formKey];
		if ($text === null) {
			trigger_error("no formText for key: $formKey", E_USER_WARNING);
			return null;
		}
		return $text->getMarkupWith($this->getRequestedObject());
	}
	
	function printFormText($formKey) 
	{
		print $this->getMarkupFromFormText($formKey);		 
	}

	function printFormLabel($formKey) 
	{
		$formTexts =& $this->getFormTexts();
		$text =& $formTexts[$formKey];
		if ($text === null) return print "error no: $formKey";

		$label = $text->getPathLabel();
		$nav =& $text->getNavigation();
		if (!in_array($nav->getResultType(), PntPropertyDescriptor::primitiveTypes()))
			tryIncludeClass($nav->getResultType(), $nav->getResultClassDir());
		if (!is_subclassOr($nav->getResultType(), 'PntDbObject')) 
			print $label;
		else
			print $this->getDetailsLinkFromNavText($text, $label);
	}

	function printDetailsLink($formKey)
	{
		$formTexts =& $this->getFormTexts();
		$text =& $formTexts[$formKey];
		if ($text === null) return print "error no: $formKey";

		$nav =& $text->getNavigation();
		if (!in_array($nav->getResultType(), PntPropertyDescriptor::primitiveTypes()))
			tryIncludeClass($nav->getResultType(), $nav->getResultClassDir());
		if (!is_subclassOr($nav->getResultType(), 'PntDbObject')) 
			return;

		$clsDes =& PntClassDescriptor::getInstance($nav->getResultType());
		$twin =& $clsDes->getTwinOf_type($nav->getPath(), $nav->getItemType());		
		if (!$twin)
			return;
		else
			$twinName = $twin->getName();

		print $this->getDetailsLinkFromNavText(
			$text
			, '<IMG SRC="../images/openfolder.gif" WIDTH="18" HEIGHT="16" ALIGN="TOP" BORDER="0">'
			, '../'. $this->getLinkDirFromNav($nav). 'index.php?pntType='. $nav->getResultType(). "&pntProperty=$twinName&pntHandler=PropertyPage&id="
		);
	}

	function getDetailsLinkFromNavText(&$text, $content, $hrefNoId=null)
	{
		$nav =& $text->getNavigation();
		if (!$hrefNoId)
			$hrefNoId = $this->getDetailsHref(
				$this->getLinkDirFromNav($nav), 
				$nav->getResultType()
			);

		$idPath = $nav->getIdPath();
		if (!$idPath)
			$idPath = $nav->getKey() . '.id';
		$idNav =& PntNavigation::_getInstance($idPath, $nav->getItemType());
		if (is_ofType($idNav, 'PntError')) {
			trigger_error($idNav->getLabel(), E_USER_WARNING);
			return;
		}
		$id = $idNav->_evaluate($this->getRequestedObject());
		if (is_ofType($id, 'PntError')) {
			trigger_error($id->getLabel(), E_USER_WARNING);
			return;
		}

		return "<A HREF=\"$hrefNoId$id\">$content</A>";
	}

	//only here for compatibilty with older skins
	function printFormWidget($formKey) 
	{
		return $this->printFormText($formKey);
	}
}
?>