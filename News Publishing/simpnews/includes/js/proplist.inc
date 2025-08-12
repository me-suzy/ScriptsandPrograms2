<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language=JavaScript src=<?php echo $url_simpnews?>/js/formcheckfunctions.js></script>
<script type="text/javascript" language=JavaScript>
<!--
function confirmDel(entrytxt, targetlocation)
{
	result = window.confirm(entrytxt);
	if(result==true)
		window.location.href=targetlocation;
}
function checkformpw()
{
	if(document.inputform.password.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nopassword)?>");
		return false;
	}
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
	return true;
}
//  End -->
</script>
