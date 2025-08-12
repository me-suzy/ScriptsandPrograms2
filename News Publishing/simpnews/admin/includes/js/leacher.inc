<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform()
{
	if(document.inputform.useragent.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nouseragent)?>");
		return false;
	}
	return true;
}
</script>
