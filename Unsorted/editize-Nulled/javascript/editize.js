/**
 * A JavaScript API that allows developers to easily insert
 * the Editize form control into their HTML forms. See the
 * product documentation for details on the use of this
 * API, as comments in this file focus on implementation
 * of that API only.
 */
function Editize()
{
	// The name of the form element for the Editize control.
	// Normally the user will assign a name, but we assign a default
	// here just in case.
	this.name = "editize";
	
	// The codebase path. If set, this is the relative or absolute URL
	// of the directory that contains the Editize Applet JAR files
	// and license file(s).
	this.codebase = ".";
	
	// The width of the Editize control
	this.width = 600;
	
	// The height of the Editize control.
	this.height = 600;
	
	// Show submit button as part of applet if true.
	this.showsubmitbutton = "";
	this.submitbuttonlabel = "";
	
	// Base font properties
	this.basefontface = "";
	this.basefontsize = "";
	this.basefontcolor = "";
        
	// Heading properties
	this.headfontface = "";
	this.headfontsize = "";
	this.headfontcolor = "";
	
	// Subheading properties
	this.subheadfontface = "";
	this.subheadfontsize = "";
	this.subheadfontcolor = "";
	
	// Block Quote properties
	this.insetfontface = "";
	this.insetfontsize = "";
	this.insetfontcolor = "";
	
	// Code block properties
	this.monospacedbackgroundcolor = "";
	
	// Highlighting properties
	this.highlightcolor = "";
	
	// Link properties
	this.linkcolor = "";
	
	// Enable/disable formatting features
	this.editbuttons = "";
	this.paragraphstyles = "";
	this.headingstyle = "";
	this.subheadingstyle = "";
	this.insetstyle = "";
	this.monospacedstyle = "";
	this.paragraphalignments = "";
	this.bulletlists = "";
	this.numberedlists = "";
	this.boldtext = "";
	this.italictext = "";
	this.underlinetext = "";
	this.highlighttext = "";
	this.inlinecode = "";
	this.hyperlinks = "";
	this.linkurls = new Array();
	this.tables = "";

	// Image properties
	this.images = "";
	this.baseurl = "";
	this.imglisturl = "";
	
	// Register object methods
	this.display = Display;
	this.displaySubmit = DisplaySubmit;
	this.trueOrFalse = TrueOrFalse;
	this.browserDetect = BrowserDetect;
	this.inAgent = InAgent;
	
	return;
        
	/**
	 * Displays the Editize control using code suitable for whatever
	 * browser is detected. Should be called inside a form.
	 */
	function Display()
	{
		var browser = this.browserDetect();
		
		var content = "";
		if (arguments.length > 0) content = arguments[0];
		var contentForHTML = htmlspecialchars(content);
		var contentForJava = javaspecialchars(content);
		
		// Build associative array of attributes
		var attribs = new Object();
		attribs["fieldid"]							= this.name;
		if (this.codebase !== "")		attribs["codebase"]		= this.codebase;
		if (this.showsubmitbutton !== "")	attribs["showsubmitbutton"]	= this.showsubmitbutton;
		if (this.submitbuttonlabel !== "")	attribs["submitbuttonlabel"]	= this.submitbuttonlabel;
		if (this.basefontface !== "")		attribs["basefontface"]		= this.basefontface;
		if (this.basefontsize !== "")		attribs["basefontsize"]		= this.basefontsize;
		if (this.basefontcolor !== "")		attribs["basefontcolor"]	= this.basefontcolor;
		if (this.headfontface !== "")		attribs["headingfontface"]	= this.headfontface;
		if (this.headfontsize !== "")		attribs["headingfontsize"]	= this.headfontsize;
		if (this.headfontcolor !== "")		attribs["headingfontcolor"]	= this.headfontcolor;
		if (this.subheadfontface !== "")	attribs["subheadingfontface"]	= this.subheadfontface;
		if (this.subheadfontsize !== "")	attribs["subheadingfontsize"]	= this.subheadfontsize;
		if (this.subheadfontcolor !== "")	attribs["subheadingfontcolor"]	= this.subheadfontcolor;
		if (this.insetfontface !== "")		attribs["blockquotefontface"]	= this.insetfontface;
		if (this.insetfontsize !== "")		attribs["blockquotefontsize"]	= this.insetfontsize;
		if (this.insetfontcolor !== "")		attribs["blockquotefontcolor"]	= this.insetfontcolor;
		if (this.monospacedbackgroundcolor!=="")	attribs["codebackgroundcolor"]	= this.monospacedbackgroundcolor;
		if (this.highlightcolor !== "")		attribs["highlightcolor"]	= this.highlightcolor;
		if (this.linkcolor !== "")		attribs["linkcolor"]		= this.linkcolor;
		if (this.baseurl !== "")		attribs["docbaseurl"]		= this.baseurl;
		if (this.imglisturl !== "")		attribs["imglisturl"]		= this.imglisturl;
		if (this.paragraphstyles !== "")	attribs["paragraphstyles"]	= this.trueOrFalse(this.paragraphstyles);
		if (this.headingstyle !== "")		attribs["headingstyle"]		= this.trueOrFalse(this.headingstyle);
		if (this.subheadingstyle !== "")	attribs["subheadingstyle"]	= this.trueOrFalse(this.subheadingstyle);
		if (this.insetstyle !== "")		attribs["blockquotestyle"]	= this.trueOrFalse(this.insetstyle);
		if (this.monospacedstyle !== "")	attribs["codeblockstyle"]	= this.trueOrFalse(this.monospacedstyle);
		if (this.paragraphalignments !== "")	attribs["paragraphalignments"]	= this.trueOrFalse(this.paragraphalignments);
		if (this.bulletlists !== "")		attribs["bulletlists"]		= this.trueOrFalse(this.bulletlists);
		if (this.numberedlists !== "")		attribs["numberedlists"]	= this.trueOrFalse(this.numberedlists);
		if (this.boldtext !== "")		attribs["boldtext"]		= this.trueOrFalse(this.boldtext);
		if (this.italictext !== "")		attribs["italictext"]		= this.trueOrFalse(this.italictext);
		if (this.underlinetext !== "")		attribs["underlinetext"]	= this.trueOrFalse(this.underlinetext);
		if (this.highlighttext !== "")		attribs["highlighttext"]	= this.trueOrFalse(this.highlighttext);
		if (this.inlinecode !== "")		attribs["inlinecode"]		= this.trueOrFalse(this.inlinecode);
		if (this.hyperlinks !== "")		attribs["hyperlinks"]		= this.trueOrFalse(this.hyperlinks);
		if (this.images !== "")			attribs["images"]		= this.trueOrFalse(this.images);
		if (this.editbuttons !== "")	attribs["editbuttons"]		= this.trueOrFalse(this.editbuttons);
		if (this.tables !== "")			attribs["tables"]		= this.trueOrFalse(this.tables);
		if (this.linkurls.length > 0)
		{
			attribs["linkurls"] = this.linkurls.length;
			for (var i=1; i<=this.linkurls.length; i++)
			{
				attribs["linkurls."+i] = this.linkurls[i-1];
			}
		}
		
		// In MSIE we use the <object> tag to load the Sun Java plugin
		if (browser == 'iewin')
		{
			document.writeln('<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" id="'+this.name+'_applet" width="'+this.width+'" height="'+this.height+'"');
			document.writeln(' codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_3_1_02-win.cab#Version=1,3,1,2">');
			document.writeln('  <param name="code" value="com.editize.EditizeApplet" />');
			document.writeln('  <param name="archive" value="editize.jar" />');
			document.writeln('  <param name="type" value="application/x-java-applet;jpi-version=1.3.1_02" />');
			document.writeln('  <param name="scriptable" value="true" />');
			document.writeln('  <param name="mayscript" value="true" />');
			for (attrib in attribs)
				document.writeln('  <param name="'+attrib+'" value="'+htmlspecialchars(attribs[attrib])+'" />');
			document.writeln('</object>');
		}
		// In NS4, we use an <embed> tag.
		else if (browser == 'nswin' || browser == 'nsunix' || browser == 'nsmac')
		{
			document.writeln('<embed type="application/x-java-applet;version=1.3"');
			document.writeln(' name="'+this.name+'_applet"');
			document.writeln(' id="'+this.name+'_applet"');
			document.writeln(' code="com.editize.EditizeApplet"');
			document.writeln(' archive="editize.jar"');
			document.writeln(' width="'+this.width+'"');
			document.writeln(' height="'+this.height+'"');
			document.writeln(' scriptable="true"');
			document.writeln(' mayscript="true"');
			for (attrib in attribs)
				document.writeln('  '+attrib+'="'+htmlspecialchars(attribs[attrib])+'"');
			document.writeln(' pluginspage="http://java.sun.com/j2se/1.3/"');
			document.writeln('></embed>');

		}
		// In all other browsers, we assume Java2 <applet> tag support
		else
		{
			// Lets the applet notify Opera users that Java 1.4+ is required.
			if (browser == 'opera') attribs["opera"] = "true";
                        // Instructs the applet to use an alternate submission method in OS X.
			if (browser == 'ie5mac' || browser == 'ns6mac')
			{
				attribs['osx'] = 'true';
				attribs['articleText'] = contentForJava;
				document.writeln('<iframe name="'+this.name+'_submitframe" width="0" height="0" style="display:none;"></iframe>');
			}
			document.writeln('<applet code="com.editize.EditizeApplet"');
			document.writeln(' codebase="'+this.codebase+'"');
			document.writeln(' id="'+this.name+'_applet"');
			document.writeln(' archive="editize.jar"');
			document.writeln(' width="'+this.width+'" height="'+this.height+'"');
			document.writeln(' mayscript="true" scriptable="true">');
			for (attrib in attribs)
				document.writeln('  <param name="'+attrib+'" value="'+htmlspecialchars(attribs[attrib])+'" />');
			document.writeln('</applet>');
		}

		document.writeln("<input type=\"hidden\" id=\""+this.name+"\" name=\""+this.name+"\" value=\""+contentForHTML+"\" />");
                
		if (!(browser == 'nswin' || browser == 'nsunix' || browser == 'nsmac'))
		{
			var editizeApplet = __getObj(this.name+'_applet');
			var editizeForm = __getObj(this.name).form;
			// Detect if the form has an onsubmit event handler, and if so grab it so
			// we can run it ourselves before running our own submit method.
			var submitHandler = null;
			if (editizeForm.onsubmit != null && editizeForm.onsubmit != __submitEditize)
				submitHandler = editizeForm.onsubmit;
			__editizeArray[__editizeArray.length] = new Array(editizeApplet, submitHandler);
			editizeForm.onsubmit = __submitEditize;
		}
	}

	function DisplaySubmit()
	{
		var text = "";
		var width = 100;
		var height = 30;
		
		if (arguments.length > 0) text = arguments[0];
		if (arguments.length > 1) width = arguments[1];
		if (arguments.length > 2) height = arguments[2];
		
		var browser = this.browserDetect();

		if (browser == 'iewin')
		{
			document.writeln('<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width="'+width+'" height="'+height+'"');
			document.writeln('  codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_3_1_02-win.cab#Version=1,3,1,2">');
			document.writeln('    <param name="codebase" value="'+this.codebase+'" />');
			document.writeln('    <param name="archive" value="editize.jar" />');
			document.writeln('    <param name="code" value="com.editize.EditizeSubmitter" />');
			document.writeln('    <param name="type" value="application/x-java-applet;jpi-version=1.3.1_02" />');
			document.writeln('    <param name="mayscript" value="true" />');
			document.writeln('    <param name="submitbuttonlabel" value="'+htmlspecialchars(text)+'" />');
			document.writeln('    <param name="fieldid" value="'+this.name+'" />');
			document.writeln('</object>');
		}
		else if (browser == 'nswin' || browser == 'nsunix' || browser == 'nsmac')
		{
			document.writeln('<embed type="application/x-java-applet;version=1.3"');
			document.writeln('  codebase="'+this.codebase+'"');
			document.writeln('  archive="editize.jar"');
			document.writeln('  code="com.editize.EditizeSubmitter"');
			document.writeln('  width="'+width+'"');
			document.writeln('  height="'+height+'"');
			document.writeln('  mayscript="true"');
			document.writeln('  pluginspage="http://java.sun.com/j2se/1.3/jre"');
			document.writeln('  submitbuttonlabel="'+htmlspecialchars(text)+'"');
			document.writeln('  fieldid="'+this.name+'"></embed>');
		}
		else
		{
			document.writeln('<applet code="com.editize.EditizeSubmitter"');
			document.writeln('  codebase="'+this.codebase+'"');
			document.writeln('  archive="editize.jar"');
			document.writeln('  width="'+width+'" height="'+height+'"');
			document.writeln('  mayscript="true">');
			document.writeln('    <param name="submitbuttonlabel" value="'+htmlspecialchars(text)+'" />');
			document.writeln('    <param name="fieldid" value="'+this.name+'" />');
			document.writeln('    <param name="osx" value="'+this.trueOrFalse(browser == 'ie5mac')+'" />');
			document.writeln('</applet>');
		}
	}

	/**
	 * Converts whitespace characters no longer supported by
	 * JRE 1.3.1_01a or later to character codes that Editize
	 * will understand.
	 */
	function javaspecialchars(text)
	{
		var newText, c, l;
		l = text.length;
		newText = "";
		for (var i=0; i<l; i++)
		{
			switch (c = text.charAt(i))
			{
				case '\n':
					newText += '\\n';
					break;
				case '\r':
					newText += '\\r';
					break;
				case '\t':
					newText += '\\t';
					break;
				case '\\':
					newText += '\\\\';
					break;
				default:
					newText += c;
			}
		}
		return newText;
	}

	function htmlspecialchars(text)
	{
		var newText, c, l;
		l = text.length;
		newText = "";
		for (var i=0; i<l; i++)
		{
			switch (c = text.charAt(i))
			{
				case '<':
					newText += '&lt;';
					break;
				case '>':
					newText += '&gt;';
					break;
				case '&':
					newText += '&amp;';
					break;
				case '"':
					newText += '&quot;';
					break;
				default:
					newText += c;
			}
		}
		return newText;
	}
	
	/**
	 * Takes a boolean and returns a 'true' or 'false' string.
	 */
	function TrueOrFalse(param)
	{
		return param ? 'true' : 'false';
	}

	/**
	 * Browser detection code
	 */
	function BrowserDetect()
	{
		var browser = "unknown";
		if ( this.inAgent('Opera') )
		{
			browser = 'opera';
		}
		else if ( this.inAgent('MSIE') )
		{
			if ( this.inAgent('Mac') )
				browser = this.inAgent('MSIE 5') ? 'ie5mac' : 'ie4mac';
			else if ( this.inAgent('Win') )
				browser = 'iewin';
		}
		else
		{
			if ( this.inAgent('Mozilla/5') || this.inAgent('Mozilla/6') )
			{
				if ( this.inAgent('Mac OS X') ) browser = 'ns6mac';
				else browser = 'ns6';
			}
			else if ( this.inAgent('Mozilla/4') )
			{
				if ( this.inAgent('Mac') ) browser = 'nsmac';
				else if ( this.inAgent('Win') ) browser = 'nswin';
				else browser = 'nsunix';
			}
		}
		return browser;
	}

	/**
	 * Utility function used by browserDetect().
	 */
	function InAgent(agent) {
	    return navigator.userAgent.indexOf(agent) >= 0;
	}

}

/**
 * Editize support functions
 */
var __editizeArray = new Array();
function __submitEditize()
{
	for (var i=0;i<__editizeArray.length;i++)
	{
		__editizeArray[i][0].writeToField();
		// Run any custom obsubmit event handler that was found for the form
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
