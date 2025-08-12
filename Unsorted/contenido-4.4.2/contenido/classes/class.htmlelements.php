<?php

/*****************************************
* File      :   $RCSfile: class.htmlelements.php,v $
* Project   :   Contenido
* Descr     :   HTML Elements
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   21.08.2003
* Modified  :   $Date: 2003/09/24 16:10:19 $
*
* © four for business AG, www.4fb.de
*
* $Id: class.htmlelements.php,v 1.9 2003/09/24 16:10:19 timo.hummel Exp $
******************************************/

require_once($cfg['path']['pear'] . "HTML/Common.php");

/**
 * Base class for all Contenido HTML classes
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTML extends HTML_Common
{
	/**
	 * Storage of the open SGML tag template
     * @var string 
     * @access private
	 */
	var $_skeleton_open;
	
	/**
	 * Storage of the close SGML tag
     * @var string 
     * @access private
	 */	
	var $_skeleton_close;
	
	/**
	 * Defines which tag to use
     * @var string 
     * @access private
	 */	
	var $_tag;

	/**
     * Constructor Function
	 * Initializes the SGML open/close tags
     * @param none
     */	
	function cHTML ()
	{
		HTML_Common::HTML_Common();
		$this->_skeleton_open = '<%s%s>';
		$this->_skeleton_close = '</%s>';
		
		$this->setClass("text_medium");
	}
	
	/**
     * setAlt: sets the alt and title attributes
	 *
	 * Sets the "alt" and "title" tags for mouse overs.
	 * "title" is required for Mozilla browsers as they
	 * don't show "alt" tags properbly.
	 *
     * @param $alt string Text to set as the "alt" attribute
     */		
	function setAlt ($alt)
	{
		$attributes = array(
						"alt" => $alt,
						"title" => $alt);
						
		$this->updateAttributes($attributes);
	}

	/**
     * sets the CSS class
	 *
     * @param $class string Text to set as the "alt" attribute
     */		
	function setClass ($class)
	{
		$this->updateAttributes(array("class" => $class));
	}
	
	/**
     * sets the CSS style
	 *
     * @param $class string Text to set as the "alt" attribute
     */		
	function setStyle ($style)
	{
		$this->updateAttributes(array("style" => $style));
	}

	/**
     * adds an "onXXX" javascript event handler
	 *
     * example:
     * $item->setEvent("change","document.forms[0].submit");
	 *
     * @param $event string Type of the event
	 * @param $action string Function or action to call (JavaScript Code)
     */		
	function setEvent ($event, $action)
	{
		$this->updateAttributes(array("on".$event => $action));
	}
			
	/**
     * fillSkeleton: Fills the open SGML tag skeleton
	 * 
	 * fillSkeleton fills the SGML opener tag with the
	 * specified attributes. Attributes need to be passed
	 * in the stringyfied variant.
	 *
     * @param $attributes string Attributes to set
	 * @return string filled SGML opener skeleton
     */		
	function fillSkeleton ($attributes)
	{
		return sprintf($this->_skeleton_open, $this->_tag, $attributes);	
	}

	/**
     * fillCloseSkeleton: Fills the close skeleton
	 *
     * @param none
	 * @return string filled SGML closer skeleton
     */	
	function fillCloseSkeleton ()
	{
		return sprintf($this->_skeleton_close, $this->_tag);	
	}
	
	/**
     * render(): Alias for toHtml
	 *
     * @param none
	 * @return string Rendered HTML
     */		
    function render()
	{
		return $this->toHtml();	
	}
}

/**
 * HTML Form element class
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLFormElement extends cHTML
{
	
	/**
     * Constructor. This is a generic form element, where
	 * specific elements should be inherited from this class.
	 *
     * @param $name string Name of the element 
	 * @param $id string ID of the element
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */
	function cHTMLFormElement ($name = "", $id = "", $disabled = "", $tabindex = "", $accesskey = "")
	{
		cHTML::cHTML();
		
		$this->updateAttributes(array("name" => $name));
		
		if (is_string($id) && !empty($id))
		{
			$this->updateAttributes(array("id" => $id));
		}
		
		$this->setDisabled($disabled);
		$this->setTabindex($tabindex);
		$this->setAccessKey($accesskey);
		

	}
	
	/**
     * Sets the "disabled" attribute of an element. User Agents
	 * usually are showing the element as "greyed-out". 
 	 *
	 * Example:
	 * $obj->setDisabled("disabled");
	 * $obj->setDisabled("");
	 * 
	 * The first example sets the disabled flag, the second one
	 * removes the disabled flag.
	 *
     * @param $disabled string Sets the disabled-flag if non-empty
	 * @return none
     */
	function setDisabled ($disabled)
	{
		if (!empty($disabled))
		{
			$this->updateAttributes(array("disabled" => "disabled"));
		} else {
			$this->removeAttribute("disabled");	
		}
	}

	/**
     * sets the tab index for this element. The tab
	 * index needs to be numeric, bigger than 0 and smaller than 32767.
 	 *
     * @param $tabindex int desired tab index
	 * @return none
     */	
	function setTabindex ($tabindex)
	{
		if (is_numeric($tabindex) && $tabindex >= 0 && $tabindex <= 32767)
		{
			$this->updateAttributes(array("tabindex" => $tabindex));
		}		
	}

	/**
     * sets the access key for this element.
 	 *
     * @param $accesskey string The length of the access key. May be A-Z and 0-9.
	 * @return none
     */	
	function setAccessKey ($accesskey)
	{
		if ((strlen($accesskey) == 1) && is_alphanumeric($accesskey))
		{
			$this->updateAttributes(array("accesskey" => $accesskey));
		} else {
			$this->removeAttribute("accesskey");
		}
	}	
}

/**
 * HTML Button class
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLButton extends cHTMLFormElement
{

	/**
     * Constructor. Creates an HTML button.
	 *
	 * Creates a submit button by default, can be changed
     * using setMode.
     *
     * @param $name string Name of the element
     * @param $title string Title of the button
	 * @param $id string ID of the element
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */	
	function cHTMLButton ($name, $title = "", $id = "", $disabled = false, $tabindex = null, $accesskey = "")
	{
		cHTMLFormElement::cHTMLFormElement($name, $id, $disabled, $tabindex, $accesskey);
		$this->_tag = "input";
		$this->setTitle($title);
		$this->setMode("submit");
	}
	
	/**
     * Sets the title (caption) for the button
 	 *
     * @param $title string The title to set
	 * @return none
     */		
	function setTitle ($title)
	{
		$this->updateAttributes(array("value" => $title));
	}


	/**
     * Sets the mode (submit or reset) for the button
 	 *
     * @param $mode string Either "submit" or "reset".
	 * @return boolean Returns false if failed to set the mode
     */			
	function setMode ($mode)
	{

		switch ($mode)
		{
			case "submit":
			case "reset":
				$this->updateAttributes(array("type" => $mode));
				break;
			default:
				return false;
		}
	}

	/**
     * Renders the button
 	 *
     * @param none
	 * @return string Rendered HTML
     */			
	function toHtml ()
	{
		$attributes = $this->getAttributes(true);
		return $this->fillSkeleton($attributes);
	}

}

/**
 * HTML Textbox
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLTextbox extends cHTMLFormElement
{
	
	/**
     * Constructor. Creates an HTML text box.
	 *
	 * If no additional parameters are specified, the
	 * default width is 20 units.
     *
     * @param $name string Name of the element
     * @param $initvalue string Initial value of the box
	 * @param $width int width of the text box
	 * @param $maxlength int maximum input length of the box
	 * @param $id string ID of the element
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */		
	function cHTMLTextbox ($name, $initvalue = "", $width = "", $maxlength = "", $id = "", $disabled = false, $tabindex = null, $accesskey = "")
	{
		cHTMLFormElement::cHTMLFormElement($name, $id, $disabled, $tabindex, $accesskey);
		$this->_tag = "input";
		$this->setValue($initvalue);
		
		$this->setWidth($width);
		$this->setMaxLength($maxlength);
		
		$this->updateAttributes(array("type" => "text"));
	}


	/**
     * sets the width of the text box.
	 *
     * @param $width int width of the text box
	 *
     * @return none
     */			
	function setWidth ($width)
	{
		$width = intval($width);
		
		if ($width <= 0)
		{
			$width = 20;
		}
		
		$this->updateAttributes(array("size" => $width));
		
	}

	/**
     * sets the maximum input length of the text box.
	 *
     * @param $maxlen int maximum input length
	 *
     * @return none
     */		
	function setMaxLength ($maxlen)
	{
		$maxlen = intval($maxlen);
		
		if ($maxlen <= 0)
		{
			$this->removeAttribute("maxlength");
		} else {
			$this->updateAttributes(array("maxlength" => $maxlen));
		}
		
	}

	/**
     * sets the initial value of the text box.
	 *
     * @param $value string Initial value
	 *
     * @return none
     */			
	function setValue ($value)
	{
		$this->updateAttributes(array("value" => $value));
	}
	
	/**
     * Renders the textbox
 	 *
     * @param none
	 * @return string Rendered HTML
     */		
	function toHtml ()
	{
		$attributes = $this->getAttributes(true);
		return $this->fillSkeleton($attributes);
	}

}


/**
 * HTML Password Box
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLPasswordbox extends cHTMLFormElement
{
	
	/**
     * Constructor. Creates an HTML password box.
	 *
	 * If no additional parameters are specified, the
	 * default width is 20 units.
     *
     * @param $name string Name of the element
     * @param $initvalue string Initial value of the box
	 * @param $width int width of the text box
	 * @param $maxlength int maximum input length of the box
	 * @param $id string ID of the element
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */		
	function cHTMLPasswordbox ($name, $initvalue = "", $width = "", $maxlength = "", $id = "", $disabled = false, $tabindex = null, $accesskey = "")
	{
		cHTMLFormElement::cHTMLFormElement($name, $id, $disabled, $tabindex, $accesskey);
		$this->_tag = "input";
		$this->setValue($initvalue);
		
		$this->setWidth($width);
		$this->setMaxLength($maxlength);
		
		$this->updateAttributes(array("type" => "password"));
	}


	/**
     * sets the width of the text box.
	 *
     * @param $width int width of the text box
	 *
     * @return none
     */			
	function setWidth ($width)
	{
		$width = intval($width);
		
		if ($width <= 0)
		{
			$width = 20;
		}
		
		$this->updateAttributes(array("size" => $width));
		
	}

	/**
     * sets the maximum input length of the text box.
	 *
     * @param $maxlen int maximum input length
	 *
     * @return none
     */		
	function setMaxLength ($maxlen)
	{
		$maxlen = intval($maxlen);
		
		if ($maxlen <= 0)
		{
			$this->removeAttribute("maxlength");
		} else {
			$this->updateAttributes(array("maxlength" => $maxlen));
		}
		
	}

	/**
     * sets the initial value of the text box.
	 *
     * @param $value string Initial value
	 *
     * @return none
     */			
	function setValue ($value)
	{
		$this->updateAttributes(array("value" => $value));
	}
	
	/**
     * Renders the textbox
 	 *
     * @param none
	 * @return string Rendered HTML
     */		
	function toHtml ()
	{
		$attributes = $this->getAttributes(true);
		return $this->fillSkeleton($attributes);
	}

}

class cHTMLTextarea extends cHTMLFormElement
{
	var $_value;
	
	/**
     * Constructor. Creates an HTML text area.
	 *
	 * If no additional parameters are specified, the
	 * default width is 60 chars, and the height is 5 chars.
     *
     * @param $name string Name of the element
     * @param $initvalue string Initial value of the textarea
	 * @param $width int width of the textarea
	 * @param $height int height of the textarea
	 * @param $id string ID of the element
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */		
	function cHTMLTextarea ($name, $initvalue = "", $width = "", $height = "", $id = "", $disabled = false, $tabindex = null, $accesskey = "")
	{
		cHTMLFormElement::cHTMLFormElement($name, $id, $disabled, $tabindex, $accesskey);
		$this->_tag = "textarea";
		$this->setValue($initvalue);
		
		$this->setWidth($width);
		$this->setHeight($height);
		
		//$this->updateAttributes(array("type" => "text"));
	}


	/**
     * sets the width of the text box.
	 *
     * @param $width int width of the text box
	 *
     * @return none
     */			
	function setWidth ($width)
	{
		$width = intval($width);
		
		if ($width <= 0)
		{
			$width = 60;
		}
		
		$this->updateAttributes(array("cols" => $width));
		
	}

	/**
     * sets the maximum input length of the text box.
	 *
     * @param $maxlen int maximum input length
	 *
     * @return none
     */		
	function setHeight ($height)
	{
		$height = intval($height);
		
		if ($height <= 0)
		{
			$height = 5;
		}
		
		$this->updateAttributes(array("rows" => $height));
		
	}

	/**
     * sets the initial value of the text box.
	 *
     * @param $value string Initial value
	 *
     * @return none
     */			
	function setValue ($value)
	{
		$this->_value = $value;
	}
	
	/**
     * Renders the textbox
 	 *
     * @param none
	 * @return string Rendered HTML
     */		
	function toHtml ()
	{
		$attributes = $this->getAttributes(true);
		return $this->fillSkeleton($attributes) . $this->_value . $this->fillCloseSkeleton();
	}

}
/**
 * HTML Label for form elements
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLLabel extends cHTML
{

    /**
     * The text to display on the label
     * @var string
     */	
	var $text;
	

	/**
     * Constructor. Creates an HTML label which can be linked
	 * to any form element (specified by their ID).
	 *
	 * A label can be used to link to elements. This is very useful
	 * since if a user clicks a label, the linked form element receives
	 * the focus (if supported by the user agent).
	 *
     * @param $text string Name of the element
     * @param $for string ID of the form element to link to.
	 *
     * @return none
     */			
	function cHTMLLabel ($text, $for)
	{
		cHTML::cHTML();
		$this->_tag = "label";
		
		$this->updateAttributes(array("for" => $for));
		$this->text = $text;
		
	}

	/**
     * Renders the label
 	 *
     * @param none
	 * @return string Rendered HTML
     */			
	function toHtml ()
	{
		$attributes = $this->getAttributes(true);
		return $this->fillSkeleton($attributes) . $this->text . $this->fillCloseSkeleton();
	}

}

/**
 * HTML Select Element
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLSelectElement extends cHTMLFormElement
{
    /**
     * All cHTMLOptionElements
     * @var array
     */		
	var $_options;


	/**
     * Constructor. Creates an HTML select field (aka "DropDown").
	 *
     * @param $name string Name of the element
     * @param $width int width of the select element
	 * @param $id string ID of the element
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */		
	function cHTMLSelectElement ($name, $width = "", $id = "", $disabled = false, $tabindex = null, $accesskey = "")
	{
		cHTMLFormElement::cHTMLFormElement($name, $id, $disabled, $tabindex, $accesskey);
		$this->_tag = "select";
		
	}


	/**
     * Adds an cHTMLOptionElement to the number of choices.
	 *
     * @param $index string Index of the element
     * @param $element object Filled cHTMLOptionElement to add
	 *
     * @return none
     */			
	function addOptionElement ($index, $element)
	{
		$this->_options[$index] = $element;
	}

	function setMultiselect ()
	{
		$this->updateAttributes(array("multiple" => "multiple"));
	}	

	function setSize ($size)
	{
		$this->updateAttributes(array("size" => $size));
	}
	
	/**
     * Sets a specific cHTMLOptionElement to the selected
	 * state. 
	 *
     * @param $lvalue string Specifies the "value" of the cHTMLOptionElement to set
	 *
     * @return none
     */			
	function setDefault ($lvalue)
	{
		if (is_array($this->_options))
		{
			foreach ($this->_options as $key => $value)
			{
				if ($value->getAttribute("value") == $lvalue)
				{
					$value->setSelected(true);
					$this->_options[$key] = $value;
				} else {
					$value->setSelected(false);
					$this->_options[$key] = $value;
				}
			}
		}	
	}

	/**
     * Renders the select box
 	 *
     * @param none
	 * @return string Rendered HTML
     */		
	function toHtml ()
	{
		
		$attributes = $this->getAttributes(true);
		
		$options = "";
		
		if (is_array($this->_options))
		{
			foreach ($this->_options as $key => $value)
			{
				$options .= $value->toHtml();
			}
		}
		
		return ($this->fillSkeleton($attributes) . $options . $this->fillCloseSkeleton());
	}

}


/**
 * HTML Select Option Element
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLOptionElement extends cHTMLFormElement
{
	/**
	 * Title to display
     * @var string 
     * @access private
	 */	
	var $_title;


	/**
     * Constructor. Creates an HTML option element.
	 *
     * @param $title string Displayed title of the element
     * @param $value string Value of the option
	 * @param $selected boolean If true, element is selected
	 * @param $disabled boolean If true, element is disabled
	 *
     * @return none
     */			
	function cHTMLOptionElement ($title, $value, $selected = false, $disabled = false)
	{
		cHTML::cHTML();
		$this->_tag = "option";
		$this->_title = $title;
		
		$this->updateAttributes(array("value" => $value));
		
		$this->setSelected($selected);
		$this->setDisabled($disabled);
		
	}

	/**
     * sets the selected flag
	 *
     * @param $selected boolean If true, adds the "selected" attribute 
	 *
     * @return none
     */			
	function setSelected ($selected)
	{
		if ($selected == true)
		{
			$this->updateAttributes(array("selected" => "selected"));
		} else {
			$this->removeAttribute("selected");
		}	
	}

	/**
     * sets the disabled flag
	 *
     * @param $disabled boolean If true, adds the "disabled" attribute 
	 *
     * @return none
     */			

	function setDisabled ($disabled)
	{
		if ($disabled == true)
		{
			$this->updateAttributes(array("disabled" => "disabled"));
		} else {
			$this->removeAttribute("disabled");
		}	
	}
	
	
	/**
     * Renders the option element. Note:
	 * the cHTMLSelectElement renders the options by itself.
 	 *
     * @param none
	 * @return string Rendered HTML
     */			
	function toHtml ()
	{
		$attributes = $this->getAttributes(true);
		return $this->fillSkeleton($attributes).$this->_title.$this->fillCloseSkeleton();
	}

}

/**
 * HTML Radio Button
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLRadiobutton extends cHTMLFormElement
{
	/**
	 * Values for the check box
     * @var string 
     * @access private
	 */		
	var $_value;

	/**
     * Constructor. Creates an HTML radio button element.
	 *
     * @param $name string Name of the element
	 * @param $value string Value of the radio button
	 * @param $id string ID of the element
	 * @param $checked boolean Is element checked?
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */		
	function cHTMLRadiobutton ($name, $value, $id = "", $checked = false, $disabled = false, $tabindex = null, $accesskey = "")
	{
		cHTMLFormElement::cHTMLFormElement($name, $id, $disabled, $tabindex, $accesskey);
		$this->_tag = "input";
		$this->_value = $value;
		
		$this->setChecked($checked);
		$this->updateAttributes(array("type" => "radio"));
		$this->updateAttributes(array("value" => $value));
	}

	/**
     * Sets the checked flag.
	 *
     * @param $checked boolean If true, the "checked" attribute will be assigned.
	 *
     * @return none
     */			
	function setChecked ($checked)
	{
		if ($checked == true)
		{
			$this->updateAttributes(array("checked" => "checked"));
		} else {
			$this->removeAttribute("checked");
		}
	}


	/**
     * Renders the option element. Note:
	 *
	 * If this element has an ID, the value (which equals the text displayed)
	 * will be rendered as seperate HTML label, if not, it will be displayed
	 * as regular text. Displaying the value can be turned off via the parameter.
 	 *
     * @param $renderlabel boolean If true, renders a label 
	 *
	 * @return string Rendered HTML
     */			
	function toHtml ($renderLabel = true)
	{
		$attributes = $this->getAttributes(true);
		
		if ($renderLabel == false)
		{
			return $this->fillSkeleton($attributes);
		}
		
		$id = $this->getAttribute("id");
		
		$renderedLabel = "";
		
		if ($id != "")
		{
			$label = new cHTMLLabel($this->_value, $this->getAttribute("id"));
			$renderedLabel = $label->toHtml();
		} else {
			$renderedLabel = $this->_value;
		}
		
		return $this->fillSkeleton($attributes). $renderedLabel;
	}

}

/**
 * HTML Checkbox
 *
 * @author      Timo A. Hummel <timo.hummel@4fb.de>
 */
class cHTMLCheckbox extends cHTMLFormElement
{
	var $_value;

	/**
     * Constructor. Creates an HTML checkbox element.
	 *
     * @param $name string Name of the element
	 * @param $value string Value of the radio button
	 * @param $id string ID of the element
	 * @param $checked boolean Is element checked?
	 * @param $disabled string Item disabled flag (non-empty to set disabled)
	 * @param $tabindex string Tab index for form elements
	 * @param $accesskey string Key to access the field
	 *
     * @return none
     */		
	function cHTMLCheckbox ($name, $value, $id = "", $checked = false, $disabled = false, $tabindex = null, $accesskey = "")
	{
		
		cHTMLFormElement::cHTMLFormElement($name, $id, $disabled, $tabindex, $accesskey);
		$this->_tag = "input";
		$this->_value = $value;
		
		$this->setChecked($checked);
		$this->updateAttributes(array("type" => "checkbox"));
		$this->updateAttributes(array("value" => $value));
	}

	/**
     * Sets the checked flag.
	 *
     * @param $checked boolean If true, the "checked" attribute will be assigned.
	 *
     * @return none
     */			
	function setChecked ($checked)
	{
		if ($checked == true)
		{
			$this->updateAttributes(array("checked" => "checked"));
		} else {
			$this->removeAttribute("checked");
		}
	}

	/**
     * Renders the checkbox element. Note:
	 *
	 * If this element has an ID, the value (which equals the text displayed)
	 * will be rendered as seperate HTML label, if not, it will be displayed
	 * as regular text. Displaying the value can be turned off via the parameter.
 	 *
     * @param $renderlabel boolean If true, renders a label 
	 *
	 * @return string Rendered HTML
     */			
	function toHtml ($renderlabel = true)
	{
		$id = $this->getAttribute("id");
		
		$renderedLabel = "";
		
		if ($renderlabel == true)
		{
    		if ($id != "")
    		{
    			$label = new cHTMLLabel($this->_value, $this->getAttribute("id"));
    			$label->setClass($this->getAttribute("class"));
    			$renderedLabel = $label->toHtml();
    		} else {
    			$renderedLabel = $this->_value;
    		}
		}
		
		$attributes = $this->getAttributes(true);
		return $this->fillSkeleton($attributes). $renderedLabel;
	}

}
?>