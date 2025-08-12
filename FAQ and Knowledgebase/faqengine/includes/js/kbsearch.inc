<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
<!--
var isNetscape = false;
var isIE = false;
var isWhoKnows = false;
<?php
if(!is_opera())
{
?>
function submitByEnter()
{

	//This determines which browser the user is using
	if (parseInt(navigator.appVersion) >= 4) {
		if(navigator.appName == "Netscape") {
		  isNetscape = true;
		}else if (navigator.appName == "Microsoft Internet Explorer"){
		  isIE = true;
		}else {
			isWhoKnows = true;
		}
	}

	//This stuff captures the events of the user
	if(isNetscape) {
		document.captureEvents(Event.KEYUP);
	}
	document.onkeyup = checkValue
}
<?php
}
?>
function checkValue(evt){
		var theButtonPressed;
		if (isNetscape) {
			if(evt.target.type == "text"){
				theButtonPressed = evt.which;
			}
        	}else if(isIE) {
			if (window.event.srcElement.type == "text") {
				theButtonPressed = window.event.keyCode;
			}
		}else if(isWhoKnows) {
			alert("<?php echo $l_usesubmit?>");
		}

		if (theButtonPressed == 13) {
			document.searchform.specmode.value="search";
			document.searchform.dosearch.click();
		}
}
//-->
</script>
<script type="text/javascript" language=JavaScript>
<!--
function openSearchHelp(loc)
{
	openWindow_full(loc,"searchhelp",<?php echo $helpwindowtop?>,<?php echo $helpwindowleft?>,<?php echo $helpwindowwidth?>,<?php echo $helpwindowheight?>,0,1,0,0,1,0,0,0,1);
}
function srchTool(inputfield)
{
<?php
	$destUrl="srchtool.php?$langvar=$act_lang";
	if(isset($layout))
		$destUrl.="&layout=$layout";
	$destUrl.="&inputfield=";
?>
	openWindow_full('<?php echo $destUrl?>' + inputfield, 'searchtool', 10, 10, 400, 200, 0,1,0,0,1,0,0,0,1);
}
//  End -->
</script>
