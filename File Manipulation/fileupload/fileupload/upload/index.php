<?
//set these variables-----------------------------------------------------------------
$path = "~off/images/";   //path to your targetfolder after your domain
$max_size = 500000;          //maximum filesize

//optionally
$domain = $_SERVER["HTTP_HOST"];      //your domainname - change if necessary like "www.wza.be"

//------------------------------------------------------------------------------------
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>file upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFCC" text="#990000" link="#990000" vlink="#990000" alink="#990000" leftmargin="20" topmargin="20" marginwidth="20" marginheight="20">

<FORM ENCTYPE="multipart/form-data" ACTION="index.php" METHOD="POST">
        <strong><font color="#990000" face="Geneva, Arial, Helvetica, sans-serif">IMAGE (jpg/gif) </font></strong><font color="#990000">:</font>
        <INPUT TYPE="file" NAME="userfile">
        <INPUT TYPE="submit" VALUE="Upload">
</FORM>

<br>
<?

if (!isset($HTTP_POST_FILES['userfile'])) exit;

if (is_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'])) {

if ($HTTP_POST_FILES['userfile']['size']>$max_size) {
        echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">File is too big !</font><br>\n"; exit; }
if (($HTTP_POST_FILES['userfile']['type']=="image/gif") || ($HTTP_POST_FILES['userfile']['type']=="image/pjpeg") || ($HTTP_POST_FILES['userfile']['type']=="image/jpeg") || ($HTTP_POST_FILES['userfile']['type']=="image/png")) {

        if (file_exists("../".$path . $HTTP_POST_FILES['userfile']['name'])) {
                echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">There already exists a file with this name, please rename your file and try again</font><br>\n"; exit; }

        $res = copy($HTTP_POST_FILES['userfile']['tmp_name'], "../".$path .$HTTP_POST_FILES['userfile']['name']);

        if (!$res) { echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">Didn't work, please try again</font><br>\n"; exit; } else {
        ?>
<br>
<p>
  <font color="#333333" face="Geneva, Arial, Helvetica, sans-serif">Find your file here: <strong><font color="#990000"><a href="http://<? echo $domain; ?>/<? echo "../".$path; ?><? echo $HTTP_POST_FILES['userfile']['name']; ?>" target="_blank"><br>
  http://<? echo $domain; ?>/<? echo $path; ?><? echo $HTTP_POST_FILES['userfile']['name']; ?><br>
  </a></font></strong><br>
  HTML:<br>
  <font color="#990000"><strong>&lt;img src=&quot;http://<? echo $domain; ?>/<? echo $path; ?><? echo $HTTP_POST_FILES['userfile']['name']; ?>&quot;&gt;</strong></font><br>
  <br>
  BBCode: <font color="#990000"><strong><br>
  [img]http://<? echo $domain; ?>/<? echo $path; ?><? echo $HTTP_POST_FILES['userfile']['name']; ?>[/img]</strong></font></font></p>
<?
 }
echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\"><hr>";
echo "Name: ".$HTTP_POST_FILES['userfile']['name']."<br>\n";
echo "Size: ".$HTTP_POST_FILES['userfile']['size']." bytes<br>\n";
echo "Type: ".$HTTP_POST_FILES['userfile']['type']."<br>\n";
echo "</font>";
echo "<br><br><img src=\"http://".$domain."/".$path.$HTTP_POST_FILES['userfile']['name']."\">";
} else { echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">Verkeerd bestandstype, enkel gif, jpg of png !!!</font><br>\n"; exit; }

}

?>


</body>
</html>
