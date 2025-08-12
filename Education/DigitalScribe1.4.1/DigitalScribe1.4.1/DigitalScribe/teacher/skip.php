<?
$ID = $HTTP_GET_VARS['ID'];
$stunum = $HTTP_GET_VARS['stunum'];
header("Location:indepthadmin.php?ID=$ID&#$stunum");
?>