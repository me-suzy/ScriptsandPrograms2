<script type="text/javascript" language="javascript">
<!--
function openWindow(loc)
{
	openWin = openWindow_full(loc,"myWindow",20,100,500,450,0,1,0,0,1,0,0,0,0);
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
