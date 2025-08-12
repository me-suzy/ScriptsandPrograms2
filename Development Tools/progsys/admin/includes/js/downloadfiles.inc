<script type="text/javascript" language="javascript">
<!--
function checkAll(myform)
{
	for (i = 0; i < myform.length; i++)
		if( myform.elements[i].type == 'checkbox')
			myform.elements[i].checked = true;
}

function uncheckAll(myform)
{
	for (i = 0; i < myform.length; i++)
		if( myform.elements[i].type == 'checkbox' )
			myform.elements[i].checked = false;
}

function dosubmit(modename)
{
	var entrieschecked=0;
	for (i = 0; i < document.downloadfiles.length; i++)
		if((document.downloadfiles.elements[i].type == 'checkbox') && document.downloadfiles.elements[i].checked)
			entrieschecked++;
	if(entrieschecked<1)
	{
		alert("<?php echo undo_htmlentities($l_noentriesselected)?>");
		return false;
	}
	document.downloadfiles.mode.value=modename;
	return document.downloadfiles.submit();
}
// -->
</script>
