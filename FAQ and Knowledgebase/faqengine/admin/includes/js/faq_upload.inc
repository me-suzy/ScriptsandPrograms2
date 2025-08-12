<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform()
{
	if(document.inputform.faqlist.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nofile)?>");
		return false;
	}
	return true;
}
</script>
