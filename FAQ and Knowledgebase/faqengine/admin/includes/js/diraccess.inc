<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform()
{
	if(document.inputform.dirname.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nodirname)?>");
		return false;
	}
	return true;
}
function choosedir()
{
	var url='<?php echo do_url_session("sel_dir.php?$langvar=$act_lang")?>';
	openWindow(url);
}
</script>
