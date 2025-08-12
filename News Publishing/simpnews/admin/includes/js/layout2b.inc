<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function evcalshorts(callingfield)
{
	if(callingfield.checked)
	{
		document.layoutform.eventcalmarkers.disabled=true;
		document.layoutform.eventcalmarkers.checked=false;
	}
	else
		document.layoutform.eventcalmarkers.disabled=false;
}
function changePage(destPage)
{
	document.layoutform.layoutpage.value=destPage;
	document.layoutform.srcpage.value=<?php echo $layoutpage?>;
	document.layoutform.mode.value="changepage";
	document.layoutform.submit();
}
</script>
<link type="text/css" rel="StyleSheet" href="<?php echo $url_simpnews?>/admin/css/tabs.css" />
