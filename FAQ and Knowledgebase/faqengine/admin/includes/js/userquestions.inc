<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<script type=\"text/javascript\" language=\"javascript\">\n";
?>
var qes_olddontstore;
function qes_delqes(){
	if(document.answerform.delquestion.checked == true)
	{
		qes_olddontstore=document.answerform.dontstore.checked;
		document.answerform.dontstore.checked=true;
		document.answerform.dontstore.disabled=true;
	}
	else
	{
		document.answerform.dontstore.checked=qes_olddontstore;
		document.answerform.dontstore.disabled=false;
	}
	return;
}
<?php
echo "</script>\n";
?>