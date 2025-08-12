<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
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
function expire_date(enable)
{
	if(enable)
	{
		document.inputform.expire_day.disabled=false;
		document.inputform.expire_month.disabled=false;
		document.inputform.expire_year.disabled=false;
		document.inputform.expire_hour.disabled=false;
		document.inputform.expire_minute.disabled=false;
	}
	else
	{
		document.inputform.expire_day.disabled=true;
		document.inputform.expire_month.disabled=true;
		document.inputform.expire_year.disabled=true;
		document.inputform.expire_hour.disabled=true;
		document.inputform.expire_minute.disabled=true;
	}
}
function first_date(enable)
{
	if(enable)
	{
		document.inputform.firstdate_day.disabled=false;
		document.inputform.firstdate_month.disabled=false;
		document.inputform.firstdate_year.disabled=false;
		document.inputform.firstdate_hour.disabled=false;
		document.inputform.firstdate_minute.disabled=false;
	}
	else
	{
		document.inputform.firstdate_day.disabled=true;
		document.inputform.firstdate_month.disabled=true;
		document.inputform.firstdate_year.disabled=true;
		document.inputform.firstdate_hour.disabled=true;
		document.inputform.firstdate_minute.disabled=true;
	}
}
function special_date(enable)
{
	if(enable)
	{
		document.inputform.input_day.disabled=false;
		document.inputform.input_month.disabled=false;
		document.inputform.input_year.disabled=false;
		document.inputform.input_hour.disabled=false;
		document.inputform.input_min.disabled=false;
	}
	else
	{
		document.inputform.input_day.disabled=true;
		document.inputform.input_month.disabled=true;
		document.inputform.input_year.disabled=true;
		document.inputform.input_hour.disabled=true;
		document.inputform.input_min.disabled=true;
	}
}
function reset_date(enable)
{
	if(enable)
	{
		document.inputform.specialdate.checked=false;
		document.inputform.specialdate.disabled=true;
		special_date(false);
	}
	else
		document.inputform.specialdate.disabled=false;
}
function dopreview()
{
	if(!checkinputform())
		return;
	document.inputform.target="preview";
	document.inputform.action="anprev.php";
	document.inputform.submit();
	document.inputform.target="_self";
	document.inputform.action="announce.php";
}
function checkinputform()
{
	if(document.inputform.announcetext.value.length<1)
	{
		alert('<?php echo undo_htmlentities($l_noannouncetext)?>');
		return false;
	}
	return true;
}
// -->
</script>
