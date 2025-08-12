<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
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
function checkform()
{
	if(document.inputform.mimetype.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nomimetype)?>");
		return false;
	}
	return true;
}
function addExtension()
{
	var extension=document.inputform.new_extension.value;
	if(extension.length<1)
		return;
	if(extension.substr(0,1)!='.')
		extension="."+extension;
	var numoptions=document.inputform.new_exts.options.length;
	document.inputform.new_exts.options[numoptions]=new Option(extension,extension);
<?php
if(is_opera7())
	echo "window.setTimeout('Opera_findSelect( \'new_exts\' ).options['+numoptions+'].selected = true;',0);\n";
else	
	echo "document.inputform.new_exts.options[numoptions].selected=true;\n";
?>
}
</script>
