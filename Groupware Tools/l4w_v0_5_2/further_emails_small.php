<?php

   	/*=====================================================================
	// $Id: further_emails_small.php,v 1.2 2005/01/03 08:56:30 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

    include ("inc/pre_include_standard.inc.php");
    include ("header.inc");
?>


<script language=javascript>

	function go() {

		var anz = opener.formular.weitere_emails.length;
 		opener.formular.weitere_emails.options[(anz-1)].text=document.formular.further_email.value;
 		opener.formular.weitere_emails.selectedIndex=(anz-1);
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
	<td><input type=button class=buttonstyle onClick='javascript:go()' name=submit value='<?=translate ("submit")?>'></td>
</tr>
</table>
</form>
</body>
</html>
