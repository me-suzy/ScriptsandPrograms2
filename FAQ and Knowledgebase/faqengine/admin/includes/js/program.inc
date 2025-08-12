<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform()
{
	if(document.inputform.programmname.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noprogname)?>");
		return false;
	}
	if(document.inputform.progid.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noid)?>");
		return false;
	}
	return true;
}
</script>
