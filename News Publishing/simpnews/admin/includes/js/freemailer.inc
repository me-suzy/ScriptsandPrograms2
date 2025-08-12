<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
<!--
function checkform()
{
	if(document.inputform.address.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noaddress)?>");
		return false;
	}
	return true;
}
//-->
</script>
