<?

#PHP Script by Ogün MERÝÇLÝGÝL, ©2000 ogun@photoshoptools.com
#---------------------------------------------
#<Professional Links v1.2>
#<By Ogün MERÝÇLÝGÝL from TURKEY/ISTANBUL>
#<ogun@photoshoptools.com>
#<http://www.photoshoptools.com/plinks
#---------------------------------------------
$errordegiskeni = "ERROR.. All field please fill.";
if ($Add)
{
if (strlen($plinks) < 1)
{
echo "<font size=\"4\" face=\"verdana\"><b>$errordegiskeni</b></font>";
exit;
}
if (strlen($url) < 1) 
{
echo "<font size=\"4\" face=\"verdana\"><b>$errordegiskeni</b></font>";
exit;
}
}




if($Add) { // Only edit file if requested
$fp = fopen("../links.txt","a+"); // Open plinks file
fwrite($fp, "`$plinks`$url`$plinks`\n"); // Write to plinks file
fclose($fp); // Close and save plinks
echo "<font color=red size=4 face=verdana><b>New Link $url ($plinks) added...!</b></font>";
}
if($update) {
$plinks_file = file("../links.txt");
$plinks_joined = join($plinks_file,"");
if($oplinks) {
$plinks_joined = str_replace("`" . $oplinks . "`", "`" . $plinks . "`", $plinks_joined);
}
if($ourl) {
$plinks_joined = str_replace($ourl, $url, $plinks_joined);
}
$fp = fopen("../links.txt","write");
fwrite($fp, $plinks_joined);
fclose($fp);
echo "<font color=red size=4 face=verdana><b>Url $ourl ($oplinks) is changed to $url ($plinks) updated successful.</b></font>";
}


?>
<!--
---------------------------------------------
<Professional Links v1.2>
<By Ogün MERÝÇLÝGÝL from TURKEY/ISTANBUL>
<ogun@photoshoptools.com>
<http://www.photoshoptools.com/plinks
---------------------------------------------

The above PHP code needs no editing, you may edit the following HTML
if you wish, though you are the only one who should see the page.
No password capability exists yet, but you may add it or wait for v2.0
Please send all additions and patches to ogun@azenet.net, I may put it
on the FourLetterWords homepage.
 -->
<HTML>
<HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</HEAD>
<BODY>
<h2>
<Professional plinks v1.2>
</h2>
<h3>Add a New plinks</h3>
<form action="admin.php" method="post">
<input type="hidden" name="add" value="1">
<b>Plinks Name</b><br>
<input type="text" name="plinks" size=4 maxlength=15>
<p>
<b>URL</b><br>
http://<input type="text" name="url" size=40 maxlength=80>
<p>
<input type="submit" name="Add" value="Add">
<input type="reset" value="Reset">
</form>
<hr>
<h3>Update an Existing plinks</h3>
<form action="<? echo $PHP_SELF ?>" method="get">
<input type="hidden" name="update" value="1">
<b>Original plinks Name</b><br>
<input type="text" name="oplinks" size=4 maxlength=15>
<p>
<b>Original URL</b><br>
http://<input type="text" name="ourl" size=40 maxlength=80>
<p>
<b>New plinks Name</b><bR>
<input type="text" name="plinks" size=4 maxlength=15>
<p>
<b>New URL</b><br>
http://<input type="text" name="url" size=40 maxlength=80>
<p>
<input type="submit" value="Update">
<input type="reset" value="Reset">
</form>
</BODY>
</HTML>