<?
	// Show submitted data:
	if (isset($_POST['demo'])) {
		if (get_magic_quotes_gpc())
			$demo = stripslashes($_POST['demo']);
		else
			$demo = $_POST['demo'];
		print $demo;
		exit;
	}
?>

<HTML>
<HEAD>
<TITLE>Phoundry Editor Test</TITLE>
<?php
	/**
	 * Enter the absolute url to the directory where you unpacked the
	 * Phoundry Editor package. This is required!
	 */
	$editorUrl = '.';

	/**
	 * Enter the language you wish to use in the Editor interface.
	 * Currently available options are: english, nederlands, deutsch
	 * and turkish.
	 * If omitted, the editor will be presented in English.
	 */
	$editorLang = 'english';


	/**
	 * Enter the complete path to the directory on disk
	 * where your images are located.
	 * If you allow for image uploads (see setting upload
	 * below), then make sure this directory is writable (w)
	 * and executable (x) for the user the webserver is 
	 * running as (usually 'nobody').
	 * If omitted, the ability to use images will be disabled.
	 */
	$uploadDir = '/var/www/my_site/htdocs/upload';

	/**
	 * Enter the absolute URL corresponding to the directory set 
	 * in the $uploadDir variable
	 * (as you would type it in a browser).
	 */
	$uploadUrl = '/upload';

	/**
	 * You need to include the editor.php file to load all the 
	 * PHP-code needed for the Phoundry Editor to work. You can
	 * use an absolute path, or one relative to this document.
	 */
	include 'editor.php';
?>
</HEAD>
<BODY>
<FORM METHOD="post" ACTION="<?= $_SERVER['PHP_SELF'] ?>">
<?php
	/**
	 * Create a new Phoundry Editor object.
	 * The Phoundry Editor Constructor expects one argument: a name.
	 * This is the same name as you'd use in a <TEXTAREA>
	 */
	$demo = new PhoundryEditor('demo');

	/**
	 * Optionally set the height of the editor in pixels (defaults to 300).
	 */
	$demo->set('height', 530);

	/**
	 * Whether or not to enable the authoring of TABLEs (defaults to true).
	 */
	$demo->set('tables',true);

	/**
	 * Whether or not to enable the authoring of FORMs (defaults to false).
	 */
	$demo->set('forms',true);

	/**
	 * If you want to allow for insertion of images in the editor,
	 * you need to set the 'imgDir' property. Set it to the path to the
	 * directory the "Image Properties" dialog should start browsing in.
	 * This directory is relative to the '$uploadDir' defined in the HEAD
	 * of the document.
	 *    (in this case, we'd be browsing the 
	 *     /var/www/my_site/htdocs/upload/ directory).
	 */
	$demo->set('imgDir','/');

	/**
	 * Per default, image uploads are also allowed in the image
	 * dialog window. If you wish to disable uploads, set the
	 * variable 'upload' to false.
	 */
	$demo->set('upload',true);

	/**
	 * Does this editor work with part of a page (default), or with a 
	 * complete page including <HTML> and </HTML> tags?
	 */
	$demo->set('page', true);

	/**
	 * Optionally set the initial value (content) of the editor.
	 */
	$value =<<<EOM
<HTML>
<HEAD>
<TITLE>Phoundry Editor Demo</TITLE>
</HEAD>
<BODY>
<TABLE BORDER=1>
<TR>
	<TD VALIGN="top">
	<IMG SRC="http://editor.phoundry.com/editor/pics/editor.gif" ALT="Phoundry Editor">
	<BR><SMALL>Right-click the image to edit its properties.</SMALL>
	</TD>
	<TD VALIGN="top" BGCOLOR="#efc200">
	<H3><A NAME="demo">Phoundry Editor Demo</A></H3>
	When you right-click inside a TABLE-cell, and then click &quot;Table properties&quot;, you can edit the properties of the cell you're in,
	as well as the properties of the table row or the table itself!
	</TD>
</TR>
</TABLE>
<P>
This paragraph contains some <B>bold text</B>, some <I>italicized text</I> and some <U>underlined text</U>.
<BR>
<A HREF="http://editor.phoundry.com" TARGET="_blank">Hyperlinks</A> are easy to author as well (just right-click on the 
hyperlink to edit its properties).
</P>
<P>FORMs are fully supported as well. You can right-click any FORM-element in order to edit its properties.</P>
<FORM NAME="testForm" ACTION="action.php" METHOD="post">
<TABLE BGCOLOR="#dbd5db">
<TR>
	<TD ALIGN="right">Your name:</TD>
	<TD><INPUT TYPE="text" NAME="name" SIZE=20></TD>
</TR>
<TR>
	<TD ALIGN="right">Check if you like the Phoundry Editor:</TD>
	<TD><INPUT TYPE="checkbox" NAME="i_like_it" VALUE="true"></TD>
</TR>
<TR>
	<TD ALIGN="right">Your favorite Phoundry Editor feature?</TD>
	<TD>
	<INPUT TYPE="radio" NAME="feature" VALUE="forms">Forms
	<INPUT TYPE="radio" NAME="feature" VALUE="tables">Tables
	<INPUT TYPE="radio" NAME="feature" VALUE="images">Images
	</TD>
</TR>
<TR>
	<TD ALIGN="right">Your job:</TD>
	<TD><SELECT NAME="job">
	<OPTION VALUE="designer">designer</OPTION>
	<OPTION VALUE="programmer">programmer</OPTION>
	<OPTION VALUE="other">other</OPTION>
	</SELECT></TD>
</TR>
<TR>
	<TD></TD>
	<TD>
	<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
	<INPUT TYPE="reset" NAME="reset" VALUE="Reset">
	</TD>
</TR>
</TABLE>
</FORM>
</BODY>
</HTML>
EOM;
	$demo->set('value',$value);

	
	/**
	 * Show the editor.
	 */
	print $demo->html();
?>

<BR>
<INPUT TYPE="submit" VALUE="Submit">
</FORM>
</P>

</BODY>
</HTML>
