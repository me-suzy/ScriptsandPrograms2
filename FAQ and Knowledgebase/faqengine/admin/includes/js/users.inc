<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform1()
{
	if(document.inputform.username.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nousername)?>");
		return false;
	}
	if((document.inputform.password.value.length<1) || (document.inputform.password2.value.length<1))
	{
		alert("<?php echo undo_htmlentities($l_nopassword)?>");
		return false;
	}
	if(document.inputform.password.value != document.inputform.password2.value)
	{
		alert("<?php echo undo_htmlentities($l_passwordmismatch)?>");
		return false;
	}
	if(document.inputform.email.value.length>0)
	{
		if(!emailCheck(document.inputform.email.value))
		{
			alert("<?php echo undo_htmlentities($l_invalidemail)?>");
			return false;
		}
	}
	return true;
}
function checkform2()
{
	if(document.inputform.username.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nousername)?>");
		return false;
	}
	if(document.inputform.email.value.length>0)
	{
		if(!emailCheck(document.inputform.email.value))
		{
			alert("<?php echo undo_htmlentities($l_invalidemail)?>");
			return false;
		}
	}
	if(document.inputform.password.value.length>0)
	{
		if(document.inputform.password2.value.length<1)
		{
			alert("<?php echo undo_htmlentities($l_nopassword)?>");
			return false;
		}
		if(document.inputform.password.value != document.inputform.password2.value)
		{
			alert("<?php echo undo_htmlentities($l_passwordmismatch)?>");
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript" language="JavaScript" src="../js/emailcheck.js"></script>
