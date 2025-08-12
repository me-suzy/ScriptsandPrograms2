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
function checkform1()
{
	if(document.inputform.programm.selectedIndex<1) 
	{
		alert("<?php echo undo_htmlentities($l_noprogramm)?>");
		return false;
	}
<?php
if($upload_avail)
{
?>
	if(document.inputform.kbfile.value.length>0)
		return true;
<?php
}
?>
	if(document.inputform.keywords.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nokeywords)?>");
		return false;
	}
	if(document.inputform.heading.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noheading)?>");
		return false;
	}
	if(document.inputform.article.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noarticletext)?>");
		return false;
	}
	return true;
}
function checkform2()
{
	if(document.inputform.article.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noarticletext)?>");
		return false;
	}
	return true;
}
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
</script>
