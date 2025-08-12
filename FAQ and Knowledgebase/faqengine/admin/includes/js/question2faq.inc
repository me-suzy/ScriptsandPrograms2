<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform()
{
	if((document.inputform.category.selectedIndex<1) && (document.inputform.subcategory.selectedIndex<1))
	{
		alert("<?php echo undo_htmlentities($l_nocategory)?>");
		return false;
	}
	if(document.inputform.heading.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noheading)?>");
		return false;
	}
	return true;
}
</script>
