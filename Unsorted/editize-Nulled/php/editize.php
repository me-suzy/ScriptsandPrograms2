<?php

/**
 * A PHP API that allows developers to easily insert the
 * Editize form control into their HTML forms. See the
 * product documentation for details on the use of this
 * API, as comments in this file focus on implementation
 * of that API only.
 */
class Editize
{
	// The name of the form element for the Editize control.
	// Normally the user will assign a name, but we assign a default
	// here just in case.
	var $name = "editize";
	
	// The codebase path. If set, this is the relative or absolute URL
	// of the directory that contains the Editize Applet JAR files
	// and license file(s).
	var $codebase = ".";
	
	// The width of the Editize control
	var $width = 600;
	
	// The height of the Editize control.
	var $height = 600;
	
	// Show submit button as part of applet if true.
	var $showsubmitbutton = false;
	var $submitbuttonlabel;
	
	// Base font properties
	var $basefontface;
	var $basefontsize;
	var $basefontcolor;
	
	// Heading properties
	var $headfontface;
	var $headfontsize;
	var $headfontcolor;
	
	// Subheading properties
	var $subheadfontface;
	var $subheadfontsize;
	var $subheadfontcolor;
	
	// Block Quote properties
	var $insetfontface;
	var $insetfontsize;
	var $insetfontcolor;
	
	// Code block properties
	var $monospacedbackgroundcolor;
	
	// Highlighting properties
	var $highlightcolor;
	
	// Link properties
	var $linkcolor;
	
	// Image properties
	var $images;
	var $baseurl;
	var $imglisturl;
	
	// Enable/disable formatting features
	var $editbuttons;
	var $paragraphstyles;
	var $headingstyle;
	var $subheadingstyle;
	var $insetstyle;
	var $monospacedstyle;
	var $paragraphalignments;
	var $bulletlists;
	var $numberedlists;
	var $boldtext;
	var $italictext;
	var $underlinetext;
	var $highlighttext;
	var $inlinecode;
	var $hyperlinks;
	var $linkurls = array();
	var $tables;
	
	/**
	 * Displays the Editize control using code suitable for whatever
	 * browser is detected. Should be called inside a form.
	 */
	function display($content = '')
	{
		echo $this->getCode($content);
	}
	
	/**
	 * Gets the code that must be output to display the Editize control
	 * in the browser detected. Call this if you need to store the code
	 * and output it at will (e.g. in a template system).
	 */
	function getCode($content = '')
	{
		global $editizeFirstDisplayed;
		
		$output = '';
		
		$browser = $this->browserDetect();

		if (!$editizeFirstDisplayed)
		{
			// Output supporting JavaScript code to be shared betweeen multiple instances
			$output .= '
<!-- Editize support functions -->
<script language="JavaScript" type="text/javascript">
var __editizeArray = new Array();
function __submitEditize()
{
	for (var i=0;i<__editizeArray.length;i++)
	{
		// Run any custom onsubmit handler that was found for the form
		__editizeArray[i][0].writeToField();
		if (__editizeArray[i][1] != null)
		{
			var retval = __editizeArray[i][1]();
			if (!retval && retval != undefined) return false;
		}
	}
	return true;
}
function __ns4submit(id)
{
	__getObj(id).form.submit();
}
function __getObj(id)
{
	if (document.getElementById) { // DOM-compliant browsers (MSIE5+, NSN6+, O5+)
		return document.getElementById(id);
	} else if (document.all) { // MSIE4
		return document.all[id];
	} else { // NSN4
		for (var i=0;i<document.forms.length;i++)
		{
			if (document.forms[i].elements[id])
				return document.forms[i].elements[id];
		}
		return eval("document."+id); // If all else fails...
	}
}
</script>
';
			$editizeFirstDisplayed = TRUE;
		}

		$contentForHTML = htmlspecialchars($content);
		$contentForJava = $this->javaspecialchars($content);
		
		// Build an array of parameters
		$attribs = array();
		$attribs['fieldid'] = $this->name;
		if (isset($this->codebase)) $attribs['codebase'] = $this->codebase;
		if (isset($this->showsubmitbutton)) $attribs['showsubmitbutton'] = $this->trueOrFalse($this->showsubmitbutton);
		if (isset($this->submitbuttonlabel)) $attribs['submitbuttonlabel'] = $this->submitbuttonlabel;
		if (isset($this->basefontface)) $attribs['basefontface'] = $this->basefontface;
		if (isset($this->basefontsize)) $attribs['basefontsize'] = $this->basefontsize;
		if (isset($this->basefontcolor)) $attribs['basefontcolor'] = $this->basefontcolor;
		if (isset($this->headfontface)) $attribs['headingfontface'] = $this->headfontface;
		if (isset($this->headfontsize)) $attribs['headingfontsize'] = $this->headfontsize;
		if (isset($this->headfontcolor)) $attribs['headingfontcolor'] = $this->headfontcolor;
		if (isset($this->subheadfontface)) $attribs['subheadingfontface'] = $this->subheadfontface;
		if (isset($this->subheadfontsize)) $attribs['subheadingfontsize'] = $this->subheadfontsize;
		if (isset($this->subheadfontcolor)) $attribs['subheadingfontcolor'] = $this->subheadfontcolor;
		if (isset($this->insetfontface)) $attribs['blockquotefontface'] = $this->insetfontface;
		if (isset($this->insetfontsize)) $attribs['blockquotefontsize'] = $this->insetfontsize;
		if (isset($this->insetfontcolor)) $attribs['blockquotefontcolor'] = $this->insetfontcolor;
		if (isset($this->monospacedbackgroundcolor)) $attribs['codebackgroundcolor'] = $this->monospacedbackgroundcolor;
		if (isset($this->highlightcolor)) $attribs['highlightcolor'] = $this->highlightcolor;
		if (isset($this->linkcolor)) $attribs['linkcolor'] = $this->linkcolor;
		if (isset($this->paragraphstyles)) $attribs['paragraphstyles'] = $this->trueOrFalse($this->paragraphstyles);
		if (isset($this->headingstyle)) $attribs['headingstyle'] = $this->trueOrFalse($this->headingstyle);
		if (isset($this->subheadingstyle)) $attribs['subheadingstyle'] = $this->trueOrFalse($this->subheadingstyle);
		if (isset($this->insetstyle)) $attribs['blockquotestyle'] = $this->trueOrFalse($this->insetstyle);
		if (isset($this->monospacedstyle)) $attribs['codeblockstyle'] = $this->trueOrFalse($this->monospacedstyle);
		if (isset($this->paragraphalignments)) $attribs['paragraphalignments'] = $this->trueOrFalse($this->paragraphalignments);
		if (isset($this->bulletlists)) $attribs['bulletlists'] = $this->trueOrFalse($this->bulletlists);
		if (isset($this->numberedlists)) $attribs['numberedlists'] = $this->trueOrFalse($this->numberedlists);
		if (isset($this->boldtext)) $attribs['boldtext'] = $this->trueOrFalse($this->boldtext);
		if (isset($this->italictext)) $attribs['italictext'] = $this->trueOrFalse($this->italictext);
		if (isset($this->underlinetext)) $attribs['underlinetext'] = $this->trueOrFalse($this->underlinetext);
		if (isset($this->highlighttext)) $attribs['highlighttext'] = $this->trueOrFalse($this->highlighttext);
		if (isset($this->inlinecode)) $attribs['inlinecode'] = $this->trueOrFalse($this->inlinecode);
		if (isset($this->hyperlinks)) $attribs['hyperlinks'] = $this->trueOrFalse($this->hyperlinks);
		if (isset($this->images)) $attribs['images'] = $this->trueOrFalse($this->images);
		if (isset($this->editbuttons)) $attribs['editbuttons'] = $this->trueOrFalse($this->editbuttons);
		if (isset($this->tables)) $attribs['tables'] = $this->trueOrFalse($this->tables);
		if (isset($this->baseurl)) $attribs['docbaseurl'] = $this->baseurl;
		if (isset($this->imglisturl)) $attribs['imglisturl'] = $this->imglisturl;
		if (isset($this->linkurls))
		{
			$attribs['linkurls'] = count($this->linkurls);
			for ($i=1; $i<=count($this->linkurls); $i++)
			{
				$attribs['linkurls.'.$i] = $this->linkurls[$i-1];
			}
		}

		// In MSIE we use the <object> tag to load the Sun Java plugin
		if ($browser == 'iewin'):

			$output .= '
<!-- Editize -->
<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" id="'.$this->name.'_applet" width="'.$this->width.'" height="'.$this->height.'"
	codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_3_1_02-win.cab#Version=1,3,1,2">
	<param name="code" value="com.editize.EditizeApplet" />
	<param name="archive" value="editize.jar" />
	<param name="type" value="application/x-java-applet;jpi-version=1.3.1_02" />
	<param name="scriptable" value="true" />
	<param name="mayscript" value="true" />
';
			foreach ($attribs as $key => $value)
			{
				$output .= "\t<param name=\"$key\" value=\"".htmlspecialchars($value)."\" />\n";
			}
			$output .= "</object>\n";

		// In NS4, we use an <embed> tag.
		elseif ($browser == 'nswin' or $browser == 'nsunix' or $browser == 'nsmac'):
			$output .= '
<!-- Editize -->
<embed type="application/x-java-applet;version=1.3"
	name="'.$this->name.'_applet"
	id="'.$this->name.'_applet"
	code="com.editize.EditizeApplet"
	archive="editize.jar"
	width="'.$this->width.'"
	height="'.$this->height.'"
	scriptable="true"
	mayscript="true"
';

			foreach ($attribs as $key => $value)
			{
				$output .= "\t$key=\"".htmlspecialchars($value)."\"\n";
			}

			$output .= '	pluginspage="http://java.sun.com/j2se/1.3/">
</embed>
';

		// In all other browsers, we assume Java2 <applet> tag support
		else:
			// Lets the applet notify Opera users that Java 1.4+ is required.
			if ($browser == 'opera') $attribs['opera'] = 'true';
			// Instructs the applet to use an alternate submission method in OS X.
			if ($browser == 'ie5mac' || $browser == 'ns6mac')
			{
				$attribs['osx'] = 'true';
				$attribs['articleText'] = $contentForJava;
				$output .= "\t<iframe name=\"".$this->name."_submitframe\" width=\"0\" height=\"0\" style=\"display:none;\"></iframe>\n";
			}
			$output .= '
<!-- Editize -->
<applet code="com.editize.EditizeApplet"
	codebase="'.$this->codebase.'"
        id="'.$this->name.'_applet"
	archive="editize.jar"
	width="'.$this->width.'" height="'.$this->height.'"
	mayscript="true" scriptable="true">
';
			foreach ($attribs as $key => $value)
			{
				$output .= "\t<param name=\"$key\" value=\"".htmlspecialchars($value)."\" />\n";
			}
			$output .= "</applet>\n";
		endif;
		
		$output .= "<input type=\"hidden\" id=\"{$this->name}\" name=\"{$this->name}\" value=\"{$contentForHTML}\" />";
		
		if (!($browser == 'nswin' or $browser == 'nsunix' or $browser == 'nsmac')) $output .= '
<script language="JavaScript" type="text/javascript">
var editizeApplet = __getObj(\''.$this->name.'_applet\');
var editizeForm = __getObj(\''.$this->name.'\').form;
var submitHandler = null;
if (editizeForm.onsubmit != null && editizeForm.onsubmit != __submitEditize)
	submitHandler = editizeForm.onsubmit;
__editizeArray[__editizeArray.length] = new Array(editizeApplet, submitHandler);
editizeForm.onsubmit = __submitEditize;
</script>
<!-- End Editize -->
';

		return $output;
	}
        
	function displaySubmit($text = '',$width = '',$height = '')
	{
		echo $this->getSubmitCode($text,$width,$height);
	}
	
	function getSubmitCode($text = '',$width = '',$height = '')
	{
		if ($text == '') $text = 'Submit';
		if ($width == '') $width = 100;
		if ($height == '') $height = 30;
	
		$browser = $this->browserDetect();
		
		$output = '';
		
		if ($browser == 'iewin')
		{
			$output .= '
<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width="'.$width.'" height="'.$height.'"
	codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_3_1_02-win.cab#Version=1,3,1,2">
	<param name="codebase" value="'.$this->codebase.'" />
	<param name="archive" value="editize.jar" />
	<param name="code" value="com.editize.EditizeSubmitter" />
	<param name="type" value="application/x-java-applet;jpi-version=1.3.1_02" />
	<param name="mayscript" value="true" />
	<param name="submitbuttonlabel" value="'.htmlspecialchars($text).'" />
	<param name="fieldid" value="'.$this->name.'" />
</object>';
		}
		elseif ($browser == 'nswin' or $browser == 'nsunix' or $browser == 'nsmac')
		{
			$output .= '
<embed type="application/x-java-applet;version=1.3"
	codebase="'.$this->codebase.'"
	archive="editize.jar"
	code="com.editize.EditizeSubmitter"
	width="'.$width.'"
	height="'.$height.'"
	mayscript="true"
	pluginspage="http://java.sun.com/j2se/1.3/jre"
	submitbuttonlabel="'.htmlspecialchars($text).'"
	fieldid="'.$this->name.'">
</embed>';
		}
		else
		{
			$output .= '
<applet code="com.editize.EditizeSubmitter"
	codebase="'.$this->codebase.'"
	archive="editize.jar"
	width="'.$width.'" height="'.$height.'"
	mayscript="true">
	<param name="submitbuttonlabel" value="'.htmlspecialchars($text).'" />
	<param name="fieldid" value="'.$this->name.'" />
	<param name="osx" value="'.$this->trueOrFalse($browser == 'ie5mac').'" />
</applet>';
		}
		
		return $output;
	}
	
	/**
	 * Converts whitespace characters no longer supported by
	 * JRE 1.3.1_01a or later to character codes that Editize
	 * will understand.
	 */
	function javaspecialchars($text)
	{
		return addcslashes($text, "\n\r\t\\");
	}
        
	/**
	 * Takes a boolean and returns a 'true' or 'false' string.
	 */
	function trueOrFalse($param)
	{
		return $param ? 'true' : 'false';
	}
        
	/**
	 * Browser detection code
	 */
	function browserDetect()
	{
		$browser = "unknown";
		if ( $this->inAgent('Opera') )
		{
			$browser = 'opera';
		}
		else if ( $this->inAgent('MSIE') )
		{
			if ( $this->inAgent('Mac') )
				$browser = $this->inAgent('MSIE 5') ? 'ie5mac' : 'ie4mac';
			elseif ( $this->inAgent('Win') )
				$browser = 'iewin';
		}
		else
		{
			if ( $this->inAgent('Mozilla/5') or $this->inAgent('Mozilla/6') )
			{
				if ( $this->inAgent('Mac OS X') ) $browser = 'ns6mac';
				else $browser = 'ns6';
			}
			elseif ( $this->inAgent('Mozilla/4') )
			{
				if ( $this->inAgent('Mac') ) $browser = 'nsmac';
				elseif ( $this->inAgent('Win') ) $browser = 'nswin';
				else $browser = 'nsunix';
			}
		}
		return $browser;
	}
	
	/**
	 * Utility function used by browserDetect().
	 */
	function inAgent($agent)
	{
		global $HTTP_SERVER_VARS;
		$notAgent = strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],$agent) === false;
		return !$notAgent;
	}        
}

/**
 * This is the equivalent of static initialization code for
 * the class above. It sets up the JavaScript upon which one or
 * more Editize instances shall rely.
 */
$editizeFirstDisplayed = FALSE;
?>