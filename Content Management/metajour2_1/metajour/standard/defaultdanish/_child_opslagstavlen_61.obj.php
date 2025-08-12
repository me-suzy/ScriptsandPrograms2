<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 61;
function objfile_61 () {
$obj = owNew('documentsection');
$objdata['name'] = "Opslagstavlen";
$objdata['subname'] = "";
$objdata['extension'] = "bulletinboard";
$objdata['configset'] = "";
$objdata['params'] = "";
$objdata['script'] = "0";
$objdata['content'] = "Dette er en opslagstavle - en slags simpelt debatforum, hvor de brugere der har adgang til siden, kan lave opslag til hinanden. Meget velegnet til intranet, til gæstebøger, kommentarer til webmaster, til simple debatter og lignende. ";
$obj->createObject($objdata);
$obj->moveTo(2);
return $obj;
}
?>
