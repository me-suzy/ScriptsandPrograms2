<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
<!--
<?php
if(is_opera7())
{
?>
//get a reference to the select box using its name
function Opera_findSelect( oName, oDoc ) {
	if( !oDoc )
	{
		oDoc = window.document;
	}
	for (var x = 0;x < oDoc.forms.length;x++)
	{
		if(oDoc.forms[x][oName])
		{
			return oDoc.forms[x][oName];
		}
	}
 	//scan layers ...
 	for(var x = 0;document.layers && x < oDoc.layers.length; x++)
	{
		var theOb = MWJ_findObj( oName, oDoc.layers[x].document );
		if( theOb )
		{
			return theOb;
		}
	}
	return null;
}
<?php
}
?>
function addFile(selfilename,selfilenr)
{
	var numoptions=document.inputform.new_files.options.length;
	document.inputform.new_files.options[numoptions]=new Option(selfilename,selfilenr);
<?php
if(is_opera7())
	echo "window.setTimeout('Opera_findSelect( \'new_files\' ).options['+numoptions+'].selected = true;',0);\n";
else	
	echo "document.inputform.new_files.options[numoptions].selected=true;\n";
?>
}
function sendnow()
{
	if(document.inputform.immediatlysendemail.checked == true)
	{
		document.inputform.dontsendemail.checked=false;
		document.inputform.dontsendemail.disabled=true;
	}
	else
		document.inputform.dontsendemail.disabled=false;
	return;
}
function dontemail()
{
	if(document.inputform.dontsendemail.checked == true)
	{
		document.inputform.immediatlysendemail.checked=false;
		document.inputform.immediatlysendemail.disabled=true;
	}
	else
		document.inputform.immediatlysendemail.disabled=false;
	return;
}
function reccheck()
{
	var selbutton;
	for (i=0;i<document.inputform.recurtype.length;i++)
	{
		if (document.inputform.recurtype[i].checked==true)
			selbutton=i+1;
	}
	if(selbutton == 2)
		document.inputform.recur_weekday.disabled=false;
	else
		document.inputform.recur_weekday.disabled=true;
	if(selbutton == 5)
	{
		document.inputform.recur_numweek.disabled=false;
		document.inputform.recur_wday2.disabled=false;
	}
	else
	{
		document.inputform.recur_numweek.disabled=true;
		document.inputform.recur_wday2.disabled=true;
	}
	return;
}
function use_time(enable)
{
	if(enable)
	{
		document.inputform.sel_hour.disabled=false;
		document.inputform.sel_min.disabled=false;
	}
	else
	{
		document.inputform.sel_hour.disabled=true;
		document.inputform.sel_min.disabled=true;
	}
}
function massdel()
{
	document.eventlist.mode.value="massdel";
	document.eventlist.submit();
}
function massmove()
{
	document.eventlist.mode.value="massmove";
	document.eventlist.submit();
}
function dopreview()
{
	if(!checkinputform())
		return;
	document.inputform.target="preview";
	document.inputform.action="evprev.php";
	document.inputform.submit();
	document.inputform.target="_self";
	document.inputform.action="events.php";
}
function checkinputform()
{
	if(document.inputform.eventtext.value.length<1)
	{
		alert('<?php echo undo_htmlentities($l_noeventtext)?>');
		return false;
	}
	return true;
}
// -->
</script>
