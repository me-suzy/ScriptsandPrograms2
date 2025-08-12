<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
function checkform()
{
	if(document.inputform.ipadr.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noipadr)?>");
		return false;
	}
	var ipparts=document.inputform.ipadr.value.split(".");
	if(ipparts.length != 4)
	{
		alert("<?php echo undo_htmlentities($l_novalidipadr)?>");
		return false;
	}
	if(document.inputform.subnetmask.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nosubnetmask)?>");
		return false;
	}
	var netmaskparts=document.inputform.subnetmask.value.split(".");
	if(netmaskparts.length != 4)
	{
		alert("<?php echo undo_htmlentities($l_novalidnetmask)?>");
		return false;
	}
	return true;
}
</script>
