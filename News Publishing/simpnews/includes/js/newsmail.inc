<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language=JavaScript src=<?php echo $url_simpnews?>/js/formcheckfunctions.js></script>
<script type="text/javascript" language=JavaScript>
<!--

function checkform()
{
	if(document.inputform.sendermail.value.length>0)
	{
		if(!isEmail(document.inputform.sendermail.value))
		{
			alert("<?php echo undo_htmlentities($l_novalidsendermail)?>");
			return false;
		}
	}
	else
	{
		alert("<?php echo undo_htmlentities($l_nosendermail)?>");
		return false;
	}
	if(document.inputform.receivermail.value.length>0)
	{
		if(!isEmail(document.inputform.receivermail.value))
		{
			alert("<?php echo undo_htmlentities($l_novalidreceivermail)?>");
			return false;
		}
	}
	else
	{
		alert("<?php echo undo_htmlentities($l_noreceivermail)?>");
		return false;
	}
	return true;
}
//  End -->
</script>
