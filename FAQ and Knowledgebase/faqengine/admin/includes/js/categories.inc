<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform1()
{
	if(document.inputform.category.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nocatname)?>");
		return false;
	}
	if(document.inputform.programm.selectedIndex<1)
	{
		alert("<?php echo undo_htmlentities($l_noprogramm)?>");
		return false;
	}
	return true;
}
</script>
