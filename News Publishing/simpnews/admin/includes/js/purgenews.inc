<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
<!--
function catchanged()
{
	if(document.selectionform.purgecat.selectedIndex==0)
	{
		document.selectionform.catexclude.disabled=false;
		document.selectionform.excludecat.disabled=false;
	}
	else
	{
		document.selectionform.catexclude.checked=false;
		document.selectionform.catexclude.disabled=true;
		document.selectionform.excludecat.disabled=true;
	}
}
function checkselform()
{
	if(document.selectionform.purgedays.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nopurgedays)?>");
		return false;
	}
	return true;
}
//-->
</script>
