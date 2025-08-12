<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntFormWidget', 'pnt/web/widgets');

/** FormWidget that generates html specifying textfield
* and a button. Both will react to a click by open a Dialog.
* When the dialog is closed it calls a funcion specified by
* this Widget to set the new value and label in this Widget. 
*
* This abstract superclass provides behavior for the concrete
* subclass DialogWidget in the widgets classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class PntDialogWidget extends PntFormWidget {

	var $labelValue;

	function PntDialogWidget(&$whole, &$requestData, $formText=null)
	{
		$this->PntFormWidget($whole, $requestData, $formText);
	}

	function initialize(&$text)
	{
		parent::initialize($text);
		if ($text) {
			$reqObj =& $this->getRequestedObject();
			$nav =&  $text->getNavigation();
			$attType = $nav->getResultType();

			$this->setLabelKey($text->getNavKey());
			if ($text->markup !== null) {
				// markup is set by requestHandler, but not converted
				$text->setItem($reqObj);
				$text->setConvertMarkup($text->markup);
				$defaultId = $text->getContentWith($reqObj);
				$attTypeDesc =& PntClassDescriptor::getInstance($attType);
				$defaultObject =& $attTypeDesc->_getPeanutWithId($defaultId);
				if (is_ofType($defaultObject, 'PntError'))
					trigger_error($defaultObject->getLabel(), E_USER_WARNING);
				if ($defaultObject)
					$this->setLabelValue($defaultObject->getLabel());
			} else {
				$defaultId = $text->getContentWith($reqObj);
				$this->setValue($defaultId);
				$labelNav =& PntNavigation::_getInstance($text->getNavKey().'.label', $nav->getItemType());
				if (is_ofType($labelNav, 'PntError')) {
					trigger_error($labelNav->getLabel(), E_USER_WARNING);
				} else {
					$label = $labelNav->_evaluate($this->getRequestedObject());
					if (is_ofType($label, 'PntError')) 
						trigger_error($label->getLabel(), E_USER_WARNING);
					else
						$this->setLabelValue($label);
				}
			}
			$this->setShowClearButton(!$nav->isSettedCompulsory());
	
			$attTypeDir = $this->getLinkDirFromNav($nav);
			$dialogType = 'Dialog';
			$this->setDialogUrlNoId("../$attTypeDir"."index.php?pntHandler=$dialogType&pntType=$attType&pntProperty=$this->formKey&id=");
			
			$dialogClass = $attType.$dialogType;
			if ( !$this->tryUseClass($dialogClass, $this->getDir()) ) {
				$dialogClass = "Object$dialogType";
				$this->useClass($dialogClass, $this->getDir());
			}
			$this->setDialogClass($dialogClass);
	//print "dialogClass $dialogClass";
			$this->setDialogSize( eval("return $dialogClass::getMinWindowSize();") );
			
			$textSize = 33;
			if (!$this->showClearButton)
				$textSize += 4;
			$this->setTextSize($textSize);
		}
			
	}

	function getName()
	{
		return 'DialogWidget';
	}

	//setters that form the public interface
	function setLabelKey($value)
	{
		$this->labelKey = $value;
	}
	
	function setLabelValue($value)
	{
		$this->labelValue = $value;
	}
	
	function setTextSize($value)
	{
		$this->textSize = $value;
	}
	
	function setShowClearButton($value)
	{
		$this->showClearButton = $value;
	}

	function setDialogUrlNoId($value)
	{
		$this->dialogUrlNoId = $value;
	}
	
	function setDialogClass($value)
	{
		$this->dialogClass = $value;
	}

	function setDialogSize($value)
	{
		$this->dialogSize = $value;
	}

	function printBody()
	{
?> 
		<INPUT TYPE='HIDDEN' NAME='<?php $this->printFormKey() ?>' VALUE='<?php $this->printValue() ?>'>
		<INPUT TYPE='TEXT' NAME='<?php $this->printLabelKey() ?>' VALUE='<?php $this->printLabelValue() ?>' READONLY='true' SIZE='<?php $this->printTextSize() ?>' STYLE='cursor:hand' onClick="openPopUpFor<?php $this->printFormKey() ?>();">
		<A HREF="javascript:openPopUpFor<?php $this->printFormKey() ?>();">
			<IMG style='position:relative; left:-6px; top:3px;' BORDER='0' SRC='../images/buttonpopup.gif' ALT='Item selection dialog'>
		</A>
		<?php $this->printClearButton() ?> 
		<SCRIPT>
			 function openPopUpFor<?php $this->printFormKey() ?>() {
			 	objectId = document.detailsForm.<?php $this->printFormKey() ?>.value;
			 	str = '<?php $this->printDialogUrlNoId() ?>'+objectId;
			 	popUp(str, <?php $this->printDialogSize() ?>,100,75);
			}
			<?php $this->printReplyScriptPiece() ?>
			document.detailsForm.<?php $this->printFormKey() ?>.value = pId;
			if (pLabel=='')
				document.detailsForm.<?php $this->printLabelKey() ?>.value = pId;
			else
				document.detailsForm.<?php $this->printLabelKey() ?>.value = pLabel;
			}
		</SCRIPT>
<?php 
	}
	
	//print- and getter methods used by printBody
	function printClearButton()
	{
		if ($this->showClearButton) {
?>
		<A HREF="javascript:clrDialogWidget('<?php $this->printFormKey() ?>', '<?php $this->printLabelKey() ?>');" style='cursor:hourglass;'>
			<IMG SRC='../images/delete.gif' ALT='Clear' BORDER='0' HEIGHT='20' style='position:relative; left:-9px; top:4px; cursor:arrow;'>
		</A>
<?php	
		}
	}	

	function printReplyScriptPiece()
	{
		print eval("return $this->dialogClass::getReplyScriptPiece('$this->formKey');");
	}
	
	function printDialogSize()
	{
		$dialogSize = $this->dialogSize;
		print "$dialogSize->x,$dialogSize->y";
	}

	function printLabelKey()
	{
		print $this->labelKey;
	}
	
	function printLabelValue()
	{
		print $this->labelValue;
	}
	
	function printTextSize()
	{
		print $this->textSize;
	}
	
	function printDialogUrlNoId()
	{
		print $this->dialogUrlNoId;
	}
}