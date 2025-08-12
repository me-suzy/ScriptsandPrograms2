<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
<!--
function sendselected()
{
	var entrieschecked=0;
	for (i = 0; i < document.sendlist.length; i++)
		if((document.sendlist.elements[i].type == 'checkbox') && document.sendlist.elements[i].checked)
			entrieschecked++;
	if(entrieschecked<1)
	{
		alert("<?php echo undo_htmlentities($l_noentryselected)?>");
		return false;
	}
	document.sendlist.onlyselected.value=1;
	document.sendlist.submit();
}
function sendall()
{
	document.sendlist.onlyselected.value=0;
	document.sendlist.submit();
}
// -->
</script>
