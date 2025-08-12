<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 31;
function objfile_31 () {
$obj = owNew('documentsection');
$objdata['name'] = "Glemt kodeord";
$objdata['subname'] = "";
$objdata['extension'] = "forgottenpassword";
$objdata['configset'] = "";
$objdata['params'] = "";
$objdata['script'] = "0";
$objdata['content'] = "";
$obj->createObject($objdata);
$obj->moveTo(2);
return $obj;
}
?>
