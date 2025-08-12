<?php
$la = "a";
$z = "b";
include ("config.php");

if (((table("af_manager") == FALSE) && (table("onecms_content") == FALSE) && (table("onecms_users") == FALSE))) {
header('location: install.php');
die;
} else {
header('location: a_index.php?view=home');
}// checks to see if onecms is not installed and if so, takes you to the install file, otherwise takes you to control panel
?>