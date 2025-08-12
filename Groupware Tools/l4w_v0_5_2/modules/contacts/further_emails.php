<?php

  /**
    * $Id: further_emails.php,v 1.7 2005/07/31 08:45:16 carsten Exp $
    *
    * add and manage additional email addresses
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package contacts
    */

	// === Base Configuration ==========================================
    include_once ("../../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    $logger->set_logging_level ($LOGGING_DEFAULT_LEVEL);
    $logger->set_ident ("contacts");
    
    // === Application Includes ========================================
    $add_module_path_offset = "../../";
    //include ("../../config/config.inc.php");
    include ("../../inc/functions.inc.php");
    
    // === Load Translations ===========================================
    loadLanguageFile ("../../");

    // --- Header ---------------------------------------------------
	include ("../common/header.inc.php");

?>


<script language=javascript>

	function go() {
		var anz = opener.document.forms[0].further_emails.length;
 		opener.document.forms[0].further_emails.options[(anz-1)].text=document.formular.further_email.value;
 		opener.document.forms[0].further_emails.selectedIndex=(anz-1);
		window.close();
	}

</script>

<form name='formular'>
<table>
<tr>
    <td><?=translate ("additional email")?>:</td>
</tr>
<tr>
	<td><input type=text name=further_email value='' size=20></td>
</tr>
<tr>
	<td><input type=button class=buttonstyle onClick='javascript:go()' name=submit value='<?=translate ("submit", null, true)?>'></td>
</tr>
</table>
</form>
</body>
</html>
