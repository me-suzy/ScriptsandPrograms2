<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 16;
function objfile_16 () {
$obj = owNew('documentsection');
$objdata['name'] = "Bliv registreret bruger";
$objdata['subname'] = "";
$objdata['extension'] = "register";
$objdata['configset'] = "";
$objdata['params'] = "";
$objdata['script'] = "0";
$objdata['content'] = "<P>På visse websites kan det være relevant at give brugerne mulighed for at registrere sig. Dette kan blandt andet være for at give adgang til særlige informationer for brugere man kender. Administrator kan naturligvis til enhver tid oprette brugere i administrationssystemet. Efter fuldendt registrering er det nødvendigt at brugerne logger ind på websitet, for at de opnår de særlige rettigheder registreringen giver. Samtidigt, uanset om brugeren er logget ind eller ej, giver website-statistikken et samlet billede af denne brugers færden - også i tiden før han registrerede sig.</P>
<P>Registreringen af færden på websitet er afhængigt af cookies. Skulle brugeren fra tid til anden slette sine cookies, vil de blive genetableret når brugeren atter logger ind.</P>";
$obj->createObject($objdata);
$obj->moveTo(2);
return $obj;
}
?>
