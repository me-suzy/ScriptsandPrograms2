<?php
	
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	/admin/docs/ DIRECTORY OF THE INSTALLATION PACKAGE.	

	// XHTML Compliant = Yes

	/////////////////////////////////////////////////////////////*/
	
	/** 
	 * Abstraction class used to generate HTML interfaces
	 *
	 * @package iWare Professional
	 * @author David N. Simmons <http://www.dsiware.com>
	 * @version 3.0.9
	 * @access public
	 * @copyright iWare 2002,2003
	 *
	 */
	class GUI {

		/**
		 * Outputs JavaScript used to send an alert message
		 *
		 * @param string $msg Text to be displayed in the JavaScript alert box
		 * @access public
		 */
		function Message($msg)
			{
			echo "<script language=\"JavaScript\">\nwindow.alert('".$msg."');\n</script>";
			}

		/**
		 * Outputs JavaScript used to redirect a browser window
		 *
		 * @param string $url Relative or absolute URL to redirect to
		 * @access public
		 */
		function Navigate($url)
			{
			echo "<script language=\"JavaScript\">\nwindow.location='".$url."';\n</script>";
			}

		/**
		 * Outputs an opening container table for a program interface with a titlebar and set width
		 *
		 * @param string $titlebar Text to be displayed in the widget interfaces titlebar area
		 * @param integer $size Width of the widget interface in pixels, if not supplied the dfault with of 600 pixels will be used
		 * @access public
		 */
		function OpenWidget ($titlebar,$size=600)
			{
			global $ModLoader;
			if(isset($ModLoader) && $ModLoader==1){$path="../../admin/images/";}
			else{$path="images/";}
			echo "<center>\n<table border=0 class=\"guiWidget\" bgcolor=\"#e4e4e4\" cellpadding=3 cellspacing=0 width=".$size.">\n";
			echo "<tr>\n<td bgcolor=\"#e4e4e4\" background=\"".$path."widget_titlebar.jpg\"><span class=\"guiWidgetTitle\">".$titlebar."</span></td>\n</tr>\n";
			echo "<tr>\n<td align=center bgcolor=\"#ffffff\"><br />\n";
			}

		/**
		 * Outputs a closing container table for a program interface
		 *
		 * @access public
		 */
		function CloseWidget ()
			{
			global $ModLoader;
			if(isset($ModLoader) && $ModLoader==1){$path="../../admin/images/";}
			else{$path="images/";}
			echo "<br /></td>\n</tr>\n";
			echo "<tr>\n<td bgcolor=\"#c0c0c0\" background=\"".$path."widget_titlebar.jpg\">&nbsp;</td>\n</tr>\n";
			echo "</table>\n</center>\n";
			}

		/**
		 * Outputs an opening HTML form for POST data
		 *
		 * @param string $name The value used for the HTML forms name attribute
		 * @param string $action The value used for the HTML forms action attribute
		 * @param string $onsubmit The value used for the HTML forms onSubmit () JavaScript event
		 * @access public
		 */
		function OpenForm ($name,$action,$onsubmit)
			{
			echo "<form method=POST name=\"".$name."\" action=\"".$action."\" onSubmit=\"".$onsubmit."\">\n";
			}

		/**
		 * Outputs a closing HTML form element
		 *
		 * @access public
		 */
		function CloseForm ()
			{
			echo "</form>\n";
			}

		/**
		 * Returns a text label
		 *
		 * @param string $text Text to be displayed as the label
		 * @return string
		 * @access public
		 */
		function Label ($text)
			{
			return "<span class=\"guiLabel\">".$text."</span>";
			}

		/**
		 * Returns a HTML checkbox element
		 *
		 * @param string $name The value used for the HTML checkbox name attribute
		 * @param string $value The value used for the HTML checkbox value attribute
		 * @param integer $checked Sets the initial state of the checkbox, 1 = checked, 0 = unchecked. If the value is not supplied 0 will be used as the default
		 * @return string
		 * @access public
		 */
		function CheckBox ($name,$value,$checked=0)
			{
			$str="<input type=\"checkbox\" name=\"".$name."\" value=\"".$value."\" class=\"guiRadioOption\" ";
			if($checked==1){$str.= "checked";}
			$str.= " />\n";
			return $str;
			}

		/**
		 * Returns a HTML radio option element
		 *
		 * @param string $name The value used for the HTML radio option name attribute
		 * @param string $value The value used for the HTML radio option value attribute
		 * @param integer $checked Sets the initial state of the radio option, 1 = checked, 0 = unchecked. If the value is not supplied 0 will be used as the default
		 * @return string
		 * @access public
		 */
		function RadioOption ($name,$value,$checked=0)
			{
			$str="<input type=\"radio\" name=\"".$name."\" value=\"".$value."\" class=\"guiRadioOption\" ";
			if($checked==1){$str.= "checked";}
			$str.= " />\n";
			return $str;
			}

		/**
		 * Returns a HTML text box
		 *
		 * @param string $name The value used for the HTML text box name attribute
		 * @param string $value The value used for the HTML text box value attribute
		 * @param integer $size The value used for the HTML text box size attribute
		 * @return string
		 * @access public
		 */
		function TextBox ($name,$value,$size)
			{
			return "<input type=\"text\" name=\"".$name."\" value=\"".$value."\" size=\"".$size."\" class=\"guiTextBox\" />\n";
			}

		/**
		 * Returns a HTML password box
		 *
		 * @param string $name The value used for the HTML password box name attribute
		 * @param string $value The value used for the HTML password box value attribute
		 * @param integer $size The value used for the HTML text password size attribute
		 * @return string
		 * @access public
		 */
		function PwdBox ($name,$value,$size)
			{
			return "<input type=\"password\" name=\"".$name."\" value=\"".$value."\" size=\"".$size."\" class=\"guiTextBox\" />\n";
			}

		/**
		 * Returns a HTML hidden form element
		 *
		 * @param string $name The value used for the HTML password box name attribute
		 * @param string $value The value used for the HTML password box value attribute
		 * @return string
		 * @access public
		 */
		function Hidden ($name,$value)
			{
			return "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\" />\n";
			}

		/**
		 * Returns a HTML text area box
		 *
		 * @param string $name The value used for the HTML text area box name attribute
		 * @param string $value The value used for the HTML text area box value attribute
		 * @param integer $rows The value used for the HTML  text area rows attribute
		 * @param integer $cols The value used for the HTML  text area cols attribute
		 * @return string
		 * @access public
		 */
		function TextArea ($name,$value,$rows,$cols)
			{
			$str="<textarea name=\"".$name."\" rows=\"".$rows."\" cols=\"".$cols."\" class=\"guiTextArea\">".str_replace("'","",$value)."</textarea>\n";
			return $str;
			}

		/**
		 * Outputs an opening HTML select box element
		 *
		 * @param string $name The value used for the HTML select box name attribute
		 * @param integer $size The value used for the select box size attribute
		 * @param integer $multiple Sets the allow single or multiple selection state, 1 = multiple 0 = single. If this value is not given the default of 0 will be used
		 * @access public
		 */
		function OpenListBox ($name,$size,$multiple=0)
			{
			echo "<select name=\"".$name;
			if($multiple==1){echo "[]";}
			echo "\" size=\"".$size."\" class=\"guiListBox\">\n";
			}

		/**
		 * Outputs a HTML select box option element
		 *
		 * @param string $value The value used for the option name attribute
		 * @param string $label The value used for the option display text
		 * @param integer $selected Sets the option selection state, 1 = selected 0 = unselected. If this value is not given the default of 0 will be used
		 * @access public
		 */
		function ListOption ($value,$label,$selected=0)
			{
			echo "<option value=\"".$value."\" ";
			if ($selected==1){echo " selected ";}
			echo ">".$label."</option>\n";
			}

		/**
		 * Outputs an closing HTML select box element
		 *
		 * @access public
		 */
		function CloseListBox ()
			{
			echo "</select>\n";
			}

		/**
		 * Returns a HTML submit button for use in forms
		 *
		 * @param string $label Text to be displayed on the button
		 * @return string
		 * @access public
		 */
		function Button ($label)
			{
			return "<input type=\"submit\" value=\"".$label."\" class=\"guiButton\" />\n";
			}

		/**
		 * Outputs a textual hyperlink
		 *
		 * @param string $label Text to be displayed in the hyperlink
		 * @param string $url Relative or absolute URL to link to
		 * @param string $target Value to be used as the HTML target attribute, options include _self, _blank
		 * @access public
		 */
		function Hyperlink ($label,$url,$target="")
			{
			echo "<a href=\"".$url."\"";
			if(!empty($target)){echo " target=\"".$target."\"";}
			echo " class=\"guiHyperlink\">".$label."</a>\n";
			}

		/**
		 * Outputs an opening HTML body tag with defined attributes
		 *
		 * @param integer $is_mod Determines how to set the URL to the background image used in the body tag. If set to 1 the URL of the background image will be adjusted accordingly for use in a module. If this value is not given the default of 0 will be used.
		 * @access public
		 */
		function PageBody ($is_mod=0)
			{
			echo "<body bgcolor=\"#c0c0c0\" text=\"#000000\" topmargin=1 marginheight=1 ";
			if($is_mod==1){echo "background=\"../../admin/images/bg.jpg\"";}
			else{echo "background=\"images/bg.jpg\"";}
			echo ">\n";
			echo "<br /><br />\n";
			}

	// End of class
	}
?>