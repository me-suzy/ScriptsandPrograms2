<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 

function converttime($timetoconvert, $notime, $timezone) {

include "settings.php";
if($notime == 3) {
$yr = substr($timetoconvert, 0, 4);
$mn = substr($timetoconvert, 5, 2);
$dy = substr($timetoconvert, 8, 2);
$mn = date("M", strtotime("2003-$mn-01"));
$dateform = str_replace("d", $dy, $dateform);
$dateform = str_replace("M", $mn, $dateform);
$dateform = str_replace("Y", $yr, $dateform);
echo $dateform;
}
elseif($notime == 2) {
global $timetodisp;
$timetodisp = date($timeform, ($timetoconvert + (3600 * $timezone)));
}

elseif ($notime == 1) {
$thedate = strtotime($timetoconvert);
$dateform = date($dateform, $thedate);
echo $dateform;
}
else {
$timeform = date($timeform, ($timetoconvert + (3600 * $timezone)));
echo $timeform;
}

return;
}
?>