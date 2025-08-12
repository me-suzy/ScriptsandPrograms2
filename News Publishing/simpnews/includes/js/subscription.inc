<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="JavaScript" src="<?php echo $url_simpnews?>/js/formcheckfunctions.js"></script>
<script type="text/javascript" language="JavaScript">
<!--
function checkform()
{
	if(document.subscriptionform.email.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noemail)?>");
		return false;
	}
	if(!isEmail(document.subscriptionform.email.value))
	{
		alert("<?php echo undo_htmlentities($l_novalidemail)?>");
		return false;
	}
	return true;
}
//  End -->
</script>
