<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform_edit()
{
<?php
if($upload_avail)
{
?>
	if(document.inputform.description.value.length>1)
		return true;
	if(document.inputform.uploadfile.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nofile)?>");
		return false;
	}
<?php
}
?>
	return true;
}
function checkform_new()
{
<?php
if($upload_avail)
{
?>
	if(document.inputform.uploadfile.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nofile)?>");
		return false;
	}
<?php
}
?>
	return true;
}
</script>
