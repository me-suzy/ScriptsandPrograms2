<HTML><HEAD>
<TITLE>Student Work</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<?
include("header2.php");

$dimen = GetImageSize("$HTTP_GET_VARS[image]");

echo "<CENTER><IMG SRC=$HTTP_GET_VARS[image] $dimen[3] BORDER=0></CENTER>";




if (isset($HTTP_GET_VARS['new'])) {
echo "<A HREF='stuworkindiv.php?teacher=$HTTP_GET_VARS[teacher]&ID=$HTTP_GET_VARS[ID]&id=$HTTP_GET_VARS[id]&new=$HTTP_GET_VARS[new]'>Back To The Work Of The Student</A>";
}

else {
echo "<A HREF='stuworkdisplay.php?teacher=$HTTP_GET_VARS[teacher]&ID=$HTTP_GET_VARS[ID]'>Back To The Work Of The Student</A>";
}

include("footer.php");
?>