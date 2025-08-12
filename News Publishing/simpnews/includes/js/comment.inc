<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language=JavaScript src="<?php echo $url_simpnews?>/js/formcheckfunctions.js"></script>
<script type="text/javascript" language=JavaScript>
<!--
function checkform()
{
	if(document.inputform.poster.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noname)?>");
		return false;
	}
	if(document.inputform.comment.value<1)
	{
		alert("<?php echo undo_htmlentities($l_nocomment)?>");
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
	return true;
}
//  End -->
</script>
