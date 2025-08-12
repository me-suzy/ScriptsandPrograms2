<?php


$wo_ip = $db->data["_CLIENT"]["IP"];
$ref = $db->data["_CLIENT"]["REFERER"];

if (strlen($ref) > 4){
if (isset($_SERVER["REQUEST_URI"])){
  $ref = $_SERVER["REQUEST_URI"];
};
};

$min = date("i");
$min_p = date("i") + 5;
$min_m = date("i") - 5;

if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
  $wouser = "";  //the user is a guest
} else {

    //if is admin
    if ($user_power == "1"){
      $wouser = "<b><font color=\"$cadmin\">".$_SESSION["user"]."</font></b>"; //the user is an admin
    } else {
      $wouser = "<font color=\"$cmember\">".$_SESSION["user"]."</font>";  //the user is a member / moderator
    }; //end if
};

$on = $db->query("whos_online", "1", $wo_ip);

//if not on, add
if ($on == ""){
$db->addRow("whos_online", array("$wouser", "$wo_ip", "$ref", "$min"), false);
$db->reBuild();
};
//end

//if on, edit
if ($on != ""){
$db->editRow("whos_online", $on, array("$wouser", "$wo_ip", "$ref", "$min"), false);
$db->reBuild();
};
//end

//remove someone from the list if the minute* is + or - 5 mins from locale time.
$wo_dele_p = array();
for ($o = 2; $o < count(@$db->data["_DB"]["whos_online"]); $o++){
  if ($db->data["_DB"]["whos_online"]["$o"]["3"] >= $min_p){
  $wo_dele_p[] = $o;
  }; //end if;
}; //end $o

//now remove them
foreach ($wo_dele_p as $line){
  $db->deleteRow("whos_online", $line);
}; //end foreach.
//end

//remove someone from the list if the minute* is + or - 5 mins from locale time.
$wo_dele_m = array();
for ($o = 2; $o < count(@$db->data["_DB"]["whos_online"]); $o++){
  if ($db->data["_DB"]["whos_online"]["$o"]["3"] <= $min_m){
  $wo_dele_m[] = $o;
  }; //end if;
}; //end $o

//now remove them
foreach ($wo_dele_m as $line){
  $db->deleteRow("whos_online", $line);
}; //end foreach.
//end

$db->reBuild();

?>
