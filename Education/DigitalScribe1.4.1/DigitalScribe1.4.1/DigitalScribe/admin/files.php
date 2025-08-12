<?
require("checkpass2.php");
require("../teacher/checkpass.php");
?>
<HTML<HEAD><TITLE>Administration</TITLE>
<LINK REL ="stylesheet" HREF="../style.css" TYPE="text/css">
<?
include("../header1.php");
echo '<span class=title>Edit the Templates</span><P>';
echo "<P><A HREF=admin.php>Go Back to Admin Home<A>";

$file = "../style.css";
$file1 = "../header1.php";
$file2 = "../header2.php";
$file3 = "../footer.php";

IF (isset($HTTP_POST_VARS['style'])) {
    $fp = fopen("$file","w");
    fwrite($fp,stripslashes($HTTP_POST_VARS['content']));
    fclose($fp);
}

IF (isset($HTTP_POST_VARS['header1'])) {
    $fp = fopen("$file1","w");
    fwrite($fp,stripslashes($HTTP_POST_VARS['content1']));
    fclose($fp);
}

IF (isset($HTTP_POST_VARS['header2'])) {
    $fp = fopen("$file2","w");
    fwrite($fp,stripslashes($HTTP_POST_VARS['content2']));
    fclose($fp);
}

IF (isset($HTTP_POST_VARS['footer'])) {
    $fp = fopen("$file3","w");
    fwrite($fp,stripslashes($HTTP_POST_VARS['content3']));
    fclose($fp);
}

$fp = fopen("$file","r");
echo "<P>File: $file";
?>
<FORM METHOD=POST ACTION=files.php>
<TEXTAREA NAME=content COLS=60 ROWS=8>
<?
$rfile = fread($fp,filesize($file));
echo "$rfile";

echo "</TEXTAREA>";

fclose($fp); ?>
<BR><INPUT TYPE=Submit VALUE=Update NAME=style>
</FORM>

<?
$fp = fopen("$file1","r");
echo "<P>File: $file1";
?>
<FORM METHOD=POST ACTION=files.php>
<TEXTAREA NAME=content1 COLS=60 ROWS=8>
<?
$rfile = fread($fp,filesize($file1));
echo "$rfile";

echo "</TEXTAREA>";

fclose($fp); ?>
<BR><INPUT TYPE=Submit VALUE=Update NAME=header1>
</FORM>

<?
$fp = fopen("$file2","r");
echo "<P>File: $file2";
?>
<FORM METHOD=POST ACTION=files.php>
<TEXTAREA NAME=content2 COLS=60 ROWS=8>
<?
$rfile = fread($fp,filesize($file2));
echo "$rfile";

echo "</TEXTAREA>";

fclose($fp); ?>
<BR><INPUT TYPE=Submit VALUE=Update NAME=header2>
</FORM>

<?
$fp = fopen("$file3","r");
echo "<P>File: $file3";
?>
<FORM METHOD=POST ACTION=files.php>
<TEXTAREA NAME=content3 COLS=60 ROWS=8>
<?
$rfile = fread($fp,filesize($file3));
echo "$rfile";

echo "</TEXTAREA>";

fclose($fp); ?>
<BR><INPUT TYPE=Submit VALUE=Update NAME=footer>
</FORM>
<?
include("../footer.php");
?>