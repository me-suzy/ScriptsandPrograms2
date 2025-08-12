<?
$ID = $HTTP_GET_VARS['ID'];
$integer = $HTTP_GET_VARS['integer'];
$image = $HTTP_GET_VARS['image'];

IF ($mode==on) {
header("Location:indepthadmin.php?ID=$ID&#$integer");
}
?>
<HTML><HEAD>
<TITLE>Student Work</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
 
 
<?
include("../header2.php");

$dimen = GetImageSize("$image");

echo "<CENTER><IMG SRC=$image $dimen[3] BORDER=0>";

echo "<P><A HREF=skip.php?mode=on&ID=$ID&stunum=$integer>Back to the Teacher Administration Page</A></CENTER>";

include("../footer.php");
?>