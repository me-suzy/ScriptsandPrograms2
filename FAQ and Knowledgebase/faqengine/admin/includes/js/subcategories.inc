<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform1()
{
	if(document.inputform.subcategory.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nosubcatname)?>");
		return false;
	}
	if(document.inputform.category.selectedIndex<1)
	{
		alert("<?php echo undo_htmlentities($l_nocategory)?>");
		return false;
	}
	return true;
}
</script>
