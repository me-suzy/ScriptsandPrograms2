<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language=JavaScript>
<!--
function enable_datesels(enable)
{
	document.searchform.startday.disabled=!enable;
	document.searchform.startmonth.disabled=!enable;
	document.searchform.startyear.disabled=!enable;
	document.searchform.endday.disabled=!enable;
	document.searchform.endmonth.disabled=!enable;
	document.searchform.endyear.disabled=!enable;
}
function highlightentry(entrycount, withheadingrow)
{
	if(withheadingrow==1)
		document.all["headingrow"+entrycount].style.backgroundColor='<?php echo $activcellcolor?>';
	document.all["entryrow"+entrycount].style.backgroundColor='<?php echo $activcellcolor?>';
}
function unhighlightentry(entrycount, withheadingrow)
{
	if(withheadingrow==1)
		document.all["headingrow"+entrycount].style.backgroundColor='<?php echo $newsheadingbgcolor?>';
	document.all["entryrow"+entrycount].style.backgroundColor='<?php echo $contentbgcolor?>';
}
//  End -->
</script>
