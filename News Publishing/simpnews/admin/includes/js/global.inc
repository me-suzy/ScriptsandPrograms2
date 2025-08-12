<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
<!--
function openWindow(loc)
{
	openWin = openWindow_full(loc,"myWindow",20,100,500,450,0,1,0,0,1,0,0,0,0);
}
function openWindow2(loc, top, left, width, height)
{
	openWin = openWindow_full(loc,"myWindow",top,left,width,height,0,1,0,0,1,0,0,0,0);
}
function openWindow3(loc, windowID, top, left, width, height)
{
	openWin = openWindow_full(loc,windowID,20,100,500,450,0,1,0,0,1,0,0,0,1);
}
function openWindow_full(loc, windowid, top, left, width, height, buttons, scrollbars, location, menubar, resizeable, status, directories, toolbar, autoclose)
{
	var options="top="+top+",left="+left+",width="+width+",height="+height;
	if(buttons==1)
		options+=",buttons=yes";
	else
		options+=",buttons=no";
	if(scrollbars==1)
		options+=",scrollbars=yes";
	else
		options+=",scrollbars=no";
	if(location==1)
		options+=",location=yes";
	else
		options+=",location=no";
	if(menubar==1)
		options+=",menubar=yes";
	else
		options+=",menubar=no";
	if(resizeable==1)
		options+=",resizeable=yes";
	else
		options+=",resizeable=no";
	if(status==1)
		options+=",status=yes";
	else
		options+=",status=no";
	if(directories==1)
		options+=",directories=yes";
	else
		options+=",directories=no";
	if(toolbar==1)
		options+=",toolbar=yes";
	else
		options+=",toolbar=no";
	openWin = window.open(loc,windowid,options);
	openWin.focus()
	if (autoclose==1)
	{
		window.onunload = function(){openWin.close()}
	}
}
// -->
</script>
<script type="text/javascript" language="javascript">
<!--
function checkAll(myform)
{
	for (i = 0; i < myform.length; i++)
	{
		if( myform.elements[i].type == 'checkbox')
			myform.elements[i].checked = true;
	}
}

function uncheckAll(myform)
{
	for (i = 0; i < myform.length; i++)
		if( myform.elements[i].type == 'checkbox' )
			myform.elements[i].checked = false;
}
function confirmDel(entrytxt, targetlocation)
{
	result = window.confirm('<?php echo undo_htmlentities($l_confirmdel)?> ('+entrytxt+')');
	if(result==true)
		window.location.href=targetlocation;
}
function showprogressbox()
{
<?php
if(is_msie())
	echo "	document.all['progressbox'].style.visibility = 'visible';\n";
else
	echo "	document.layers['progressbox'].visibility = 'visible';\n";
?>
}
function hideprogressbox()
{
<?php
if(is_msie())
	echo "	document.all['progressbox'].style.visibility = 'hidden';\n";
else
	echo "	document.layers['progressbox'].visibility = 'hidden';\n";
?>
}
// -->
</script>
