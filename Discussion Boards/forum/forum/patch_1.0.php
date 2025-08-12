<?php

$where = "<a href=\"index.php\">Home</a> > Patch To 1.1";

$lingo_file = "language/english.txt";
$f_version = "1.0";

include("header.php");

if ($f_version < "1.1"){

for ($i = 1; $i < count($db->data["_DB"]["forums"]); $i++){
if ($i == "1"){
 $db->data["_DB"]["forums"][$i][8] = "Forum_View";
 $db->data["_DB"]["forums"][$i][9] = "Post Group";
 $db->data["_DB"]["forums"][$i][10] = "Reply Group";
} else {
 $db->data["_DB"]["forums"][$i][8] = "guests";
 $db->data["_DB"]["forums"][$i][9] = "members";
 $db->data["_DB"]["forums"][$i][10] = "members";

}; //end if
}; //end for

for ($i = 1; $i < count($db->data["_DB"]["topics"]); $i++){
if ($i == "1"){
 $db->data["_DB"]["topics"][$i][8] = "Sticky";
} else {
 $db->data["_DB"]["topics"][$i][8] = "false";
}; //end if
}; //end for

for ($i = 1; $i < count($db->data["_DB"]["users"]); $i++){
if ($i == "1"){
 $db->data["_DB"]["users"][$i][9] = "Steam";
 $db->data["_DB"]["users"][$i][10] = "Aim";
 $db->data["_DB"]["users"][$i][11] = "ICQ";
 $db->data["_DB"]["users"][$i][12] = "MSN";
 $db->data["_DB"]["users"][$i][13] = "Yahoo!";
 $db->data["_DB"]["users"][$i][14] = "XFire";
} else {
 $db->data["_DB"]["users"][$i][9] = "";
 $db->data["_DB"]["users"][$i][10] = "";
 $db->data["_DB"]["users"][$i][11] = "";
 $db->data["_DB"]["users"][$i][12] = "";
 $db->data["_DB"]["users"][$i][13] = "";
 $db->data["_DB"]["users"][$i][14] = "";
}; //end if
}; //end for


$db->reBuild();

} else {

  echo ("$f_version");

  };

include("footer.php");

?>
