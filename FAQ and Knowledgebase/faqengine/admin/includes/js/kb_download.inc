<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform()
{
	var elements_checked=0;
	for (i = 0; i < document.listform.length; i++)
		if( document.listform.elements[i].type == 'checkbox')
			if(document.listform.elements[i].checked)
				elements_checked++;
	if(elements_checked<1)
	{
		alert("<?php echo undo_htmlentities($l_noneselected)?>");
		return false;
	}
	return true;
}
</script>
