<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script src="<?php echo $url_simpnews?>/admin/js/tabbed/utils.js" type="text/javascript"></script>
<script src="<?php echo $url_simpnews?>/admin/js/tabbed/viewport.js" type="text/javascript"></script>
<script src="<?php echo $url_simpnews?>/admin/js/tabbed/global.js" type="text/javascript"></script>
<script src="<?php echo $url_simpnews?>/admin/js/tabbed/cookie.js" type="text/javascript"></script>
<script src="<?php echo $url_simpnews?>/admin/js/tabbed/tabs.js" type="text/javascript"></script>
<script type="text/javascript">
/** TabParams       - Change these to customize behavior.
 *
 * 
 * useClone        - if true, uses a clone of the tabs beneath the
 *                    contents.
 *
 * alwaysShowClone - if true, the clone will be visible at all times.
 *                     otherwise, the clone will be visible only 
 *                     when the active content div's girth 
 *                     extends beyond the viewport height.
 * 
 * eventType       - The event that triggers a tab. mouseover, mousedown, 
 *                    mouseup.
 * tabTagName      - speeds performance. Use "*" for multiple types.
 */

TabParams = {
	useClone         : false,
	alwaysShowClone  : false,
	eventType        : "click",
	tabTagName       : "span"
};
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
function checkform()
{
	if(document.layoutform.rss_auto_title.value>100)
	{
<?php
	$errortext=undo_htmlentities($l_rss_newsfeed);
	$errortext.=" -> \\\"".undo_htmlentities($l_auto_title)."\\\":\\n";
	$errortext.=undo_htmlentities($l_value_too_large);
	$errortext.=" ".sprintf($l_maxchars2_asc,"100");
?>
		alert('<?php echo $errortext?>');
		return false;
	}
	if(document.layoutform.rss_auto_short.value>500)
	{
<?php
	$errortext=undo_htmlentities($l_rss_newsfeed);
	$errortext.=" -> \\\"".undo_htmlentities($l_auto_short)."\\\":\\n";
	$errortext.=undo_htmlentities($l_value_too_large);
	$errortext.=" ".sprintf($l_maxchars2_asc,"500");
?>
		alert('<?php echo $errortext?>');
		return false;
	}
	if(document.layoutform.wap_auto_title.value>100)
	{
<?php
	$errortext=undo_htmlentities($l_wap_newsfeed);
	$errortext.=" -> \\\"".undo_htmlentities($l_auto_title)."\\\":\\n";
	$errortext.=undo_htmlentities($l_value_too_large);
	$errortext.=" ".sprintf($l_maxchars2_asc,"100");
?>
		alert('<?php echo $errortext?>');
		return false;
	}
	if(document.layoutform.wap_auto_short.value>500)
	{
<?php
	$errortext=undo_htmlentities($l_wap_newsfeed);
	$errortext.=" -> \\\"".undo_htmlentities($l_auto_short)."\\\":\\n";
	$errortext.=undo_htmlentities($l_value_too_large);
	$errortext.=" ".sprintf($l_maxchars2_asc,"500");
?>
		alert('<?php echo $errortext?>');
		return false;
	}
	if(document.layoutform.wap_evs_maxldays.value<2)
	{
<?php
	$errortext=undo_htmlentities($l_wap_newsfeed);
	$errortext.=" -> \\\"".undo_htmlentities($l_wap_evs_maxldays)."\\\":\\n";
	$errortext.=undo_htmlentities($l_value_too_small);
	$errortext.=" ".sprintf($l_hastobe,">=2");
?>
		alert('<?php echo $errortext?>');
		return false;
	}
	if(document.layoutform.wap_evs_dayrange.value<1)
	{
<?php
	$errortext=undo_htmlentities($l_wap_newsfeed);
	$errortext.=" -> \\\"".undo_htmlentities($l_wap_evs_dayrange)."\\\":\\n";
	$errortext.=undo_htmlentities($l_value_too_small);
	$errortext.=" ".sprintf($l_hastobe,">=1");
?>
		alert('<?php echo $errortext?>');
		return false;
	}
	if(document.layoutform.wap_evlist2_epp.value<1)
	{
<?php
	$errortext=undo_htmlentities($l_wap_newsfeed);
	$errortext.=" -> \\\"".undo_htmlentities("wap_short_events2 - $l_entriesperpage")."\\\":\\n";
	$errortext.=undo_htmlentities($l_value_too_small);
	$errortext.=" ".sprintf($l_hastobe,">=1");
?>
		alert('<?php echo $errortext?>');
		return false;
	}
<?php
	if(strlen($layoutid)<1)
	{
?>
	if(document.layoutform.layoutid.value.length<1)
	{
		alert('<?php echo undo_htmlentities($l_nolayoutid)?>');
		return false;
	}
	var reg = new RegExp("^[-_a-zA-Z0-9]*$");
	if(!reg.test(document.layoutform.layoutid.value))
	{
		alert('<?php echo undo_htmlentities($l_layoutid_error_js)?>');
		return false;
	}
<?php
	}
?>
	return true;
}
</script>
<link type="text/css" rel="StyleSheet" href="<?php echo $url_simpnews?>/admin/css/tabbed.css" />
