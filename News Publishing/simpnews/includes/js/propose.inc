<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language=JavaScript src=<?php echo $url_simpnews?>/js/formcheckfunctions.js></script>
<script type="text/javascript" language=JavaScript>
<!--
function checkuserform()
{
	if(document.inputform.email.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noemail)?>");
		return false;
	}
	if(document.inputform.email.value.length>0)
	{
		if(!isEmail(document.inputform.email.value))
		{
			alert("<?php echo undo_htmlentities($l_novalidemail)?>");
			return false;
		}
	}
	if((document.inputform.password.value.length>0) || (document.inputform.password2.value.length>0))
	{
		if(document.inputform.password.value != document.inputform.password2.value)
		{
			alert("<?php echo undo_htmlentities($l_passwordmismatch)?>");
			return false;
		}
	}
	return true;
}
function checkform()
{
<?php
if(bittst($proposereq,BIT_1))
{
?>
	if(document.inputform.poster.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noname)?>");
		return false;
	}
<?php
}
if(bittst($proposereq,BIT_2))
{
?>
	if(document.inputform.email.value.length>0)
	{
		if(!isEmail(document.inputform.email.value))
		{
			alert("<?php echo undo_htmlentities($l_novalidemail)?>");
			return false;
		}
	}
<?php
}
if(bittst($proposereq,BIT_3))
{
?>
	if(document.inputform.input_heading.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noheading)?>");
		return false;
	}
<?php
}
?>
	if(document.inputform.entrytxt.value<1)
	{
		alert("<?php echo undo_htmlentities($l_notext)?>");
		return false;
	}
	return true;
}
function checkform_edit()
{
<?php
if(bittst($proposereq,BIT_3))
{
?>
	if(document.inputform.input_heading.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noheading)?>");
		return false;
	}
<?php
}
?>
	if(document.inputform.entrytxt.value<1)
	{
		alert("<?php echo undo_htmlentities($l_notext)?>");
		return false;
	}
	return true;
}
//  End -->
</script>
