<?php

  /**
    * $Id: header_checksave.inc.php,v 1.11 2005/07/27 13:00:53 carsten Exp $
    *
    * header with included check if there were any keystrokes
    * 
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

	// Skin handling:
	$css_path = "../../".get_skin_css_path ();
	$img_path = "../../".get_skin_img_path ();

?>
<html>
<head>
	<title><?php if (isset($pagetitle)) echo $pagetitle; else echo $version_name?></title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf">
	<link rel='stylesheet'          type='text/css' href='<?=$css_path?>main.css'>
	<LINK REL="SHORTCUT ICON"       HREF="http://www.evandor.com/icon.ico">
	<meta http-equiv="expires"      content="0">
	
    <script type="text/javascript"  src="../../javascripts/extern/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	
	<script language="JavaScript1.2" type="text/javascript">

	var something_changed = "false";

	function TasteGedrueckt(Ereignis) {
		Netscape = false;
		if(navigator.appName == "Netscape")  Netscape = true;
		if (Netscape) {
		   if (Ereignis.which != 18) {	 // nicht die ALT-Taste
			 something_changed="true";
		   }
		}
		else {
			 if (window.event.keyCode != 18) {	 // nicht die ALT-Taste
				something_changed="true";
			 }
		}

		return true;
	}

	function confirm_save(object_type) {
	    // in each case, unset assosiated SESSION['current_views'] entry
		if (something_changed == "true") {
			ok = confirm ("<?=translate ("save changed entry",null,true)?>");
			if (ok == true) {
				document.formular.submit();
			    return false;
			} 	     	
            return false;
		}
        var entry_id = document.formular.<?=$js_entry_id?>.value;
        if (typeof (parent.executeframe) != 'undefined')
            parent.executeframe.location.href='unset_current_view.php?entry_id='+entry_id;
        else {
            //alert (opener.parent);
            //document.location.href='unset_current_view.php?entry_id='+entry_id;
            //window.close();
            //alert ('unset_current_view.php?entry_id='+entry_id);
            //alert ("could not unlock object "+object_type);    
        }    
	}

	function save_and_return(where_to) {
		document.show_companies_form.return_to_main.value=where_to;
		something_changed="false";
	}

	document.onkeydown = TasteGedrueckt;
	
	</script>

</head>

<body onUnload="confirm_save('<?=$js_entry_id?>');">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
