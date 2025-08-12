<?php

//WARRING Do not change anything here it could cause the script to stop working.

if (file_exists("admin/inc/design$cn.php")){
$ld=file("admin/inc/design$cn.php");
for ($i=0;$i<count($ld);$i++) { $ld[$i] = trim($ld[$i]); }
$bc = "$ld[0]"; 
$Hbc = "$ld[1]"; 
$Ht =" $ld[2]";
$bgc ="$ld[3]";
$bgi ="$ld[5] ";
$ctbl="$ld[4]";
$cpb ="$ld[6]";
$cpw ="$ld[7]";
$cpbc ="$ld[8]"; 
}

elseif (file_exists("admin/inc/design.php")){
$ld=file("admin/inc/design.php");
for ($i=0;$i<count($ld);$i++) { $ld[$i] = trim($ld[$i]); }
$bc = "$ld[0]"; 
$Hbc = "$ld[1]"; 
$Ht =" $ld[2]";
$bgc ="$ld[3]";
$bgi ="$ld[5] ";
$ctbl="$ld[4]";
$cpb ="$ld[6]";
$cpw ="$ld[7]";
$cpbc ="$ld[8]";
}
else
{
$bc = "#3300FF"; 
$Hbc = "#0099FF"; 
$Ht =" #FFFFFF";
$bgc ="#FFFFFF";
$bgi =" "; 
$ctbl="#F5F5F5";
$cpb ="dashed";
$cpw ="4";
$cpbc ="#000000"; 
};
//end.

//border outline color 
// header color
// Head line font color.
//Background color.
//Url to background image. (optional)
//Table color.
?>


