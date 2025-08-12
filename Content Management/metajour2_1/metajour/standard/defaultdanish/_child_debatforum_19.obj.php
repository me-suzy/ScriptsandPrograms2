<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 19;
function objfile_19 () {
$obj = owNew('documentsection');
$objdata['name'] = "Debatforum";
$objdata['subname'] = "";
$objdata['extension'] = "forum";
$objdata['configset'] = "";
$objdata['params'] = "";
$objdata['script'] = "0";
$objdata['content'] = "<P>Dette er et debatforum, som er velegnet til&nbsp;længere debatter om stort og småt.&nbsp;Du kan lave lige så mange fora du vil på din side. I et debatforum bliver alle tråde (eller emner) listet her på den første side, mens du skal ind i det enkelte indlæg for at læse svarene og skrive et svar.<BR></P>";
$obj->createObject($objdata);
$obj->moveTo(2);
return $obj;
}
?>
