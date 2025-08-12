<?
if ($action=="") $action="step1";
if ($action=="save1") {
$str="<? $"."filename='".$filename."';  $"."imgdir='".$imgdir."';  $"."image=".$image.";?>";
fwrite(fopen("config.php","w+"), $str) or die ("Directory not CHMODed");
if (!fopen($filename,"w+")) echo "Directory not CHMODed";
echo "<Script language=\"Javascript\">
self.location = '$PHP_SELF?action=step2';
</script>";
}elseif ($action=="save2"){
if ($ch=="a") {
if (chmod($filename,0777)) echo "CHMOD successful<br>"; else echo "Couldn't CHMOD automatically, please do it manually";
}
$link="http://$HTTP_HOST".$PHP_SELF;
$code="&lt;? inclu"."de('$link') ?&gt;";
echo "Install Complete<br>Please copy and paste the following code into your pages : <br><br><code>$code</code><br>
<br><p><font color=red>Don't forget to delete this page</font>";
}elseif ($action=="step2"){
include ("config.php");
echo "<html>
<head>
<title>Install -- STEP 2</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>

<body>
<div align=\"center\">
  <p><font face=\"Verdana\" size=4><b>INSTALL COUNTER</b></font><br>
  </p>
  <p><font face=\"Verdana\">Your install is almost complete. You must only CHMOD
    $filename to 755 (or 777)</font></p>
  <p><font face=\"Verdana\">We may also CHMOD it automatically but this is not always
    possible. </font></p>
  <form action=\"$PHP_SELF\" method=\"get\" name=\"form2\" id=\"form2\">
  <input type=\"hidden\" name=\"action\" value=\"save2\">
    <div align=\"left\">
      <p align=\"center\">
        <select name=\"ch\" id=\"ch\">
          <option value=\"a\" selected>Try to CHMOD automatically</option>
          <option value=\"m\">I'll do it manually</option>
        </select>
      </p>
      <div align=\"left\">
        <p align=\"center\">
        <code>Please do the CHMOD manually (if selected) before submitting...</code><br><br>
          <input type=\"submit\" name=\"Submit\" value=\"Submit\">
          <input type=\"reset\" name=\"Submit2\" value=\"Reset\">
        </p>
      </div>
  </div>
  </form>
  <p align=\"left\">&nbsp; </p>
</div>
<center><font size=1 face=verdana> <br><br><br><br><a href=http://www.kyscorp.tk>Kyscorp.tk</a> &copy; 2000-2003 Kys Counter 1.0
</font></center></body>
</html>";
}else{
echo "<html>
<head>
<title>Install --- STEP 1</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>

<body>
<div align=\"center\">
  <p><font face=\"Verdana\" size=4><b>INSTALL COUNTER</b></font><br>
  </p>
<p><font face=\"Verdana\">You may either choose the default settings (keep every
    field as it) or if you want, you may change the settings.</font></p>
  <p>&nbsp;</p>
  <font face=\"Verdana\"></font>
  <form action=\"$PHP_SELF\" method=\"get\" name=\"form1\" id=\"form1\">
    <div align=\"left\">
      <p><font face=\"Verdana\">File name where to store data :
        <input name=\"filename\" type=\"text\" id=\"filename\" value=\"counter.txt\">
        <input name=\"action\" type=\"hidden\" id=\"action\" value=\"save1\">
        </font></p>
      <p><font face=\"Verdana\">Directory where images can be found :
        <input name=\"imgdir\" type=\"text\" id=\"imgdir\" value=\"numbers\">
        <font size=\"2\"><em>(Please remove the trailing slash &quot;/&quot;)</em></font></font>
      </p>
      <p><font face=\"Verdana\">Display :
        <select name=\"image\" id=\"image\">
          <option value=\"1\" selected>Images</option>
          <option value=\"0\">Numbers</option>
        </select>
        </font></p>
      <div align=\"center\">
        <input type=\"submit\" name=\"Submit\" value=\"Submit\">
        <input type=\"reset\" name=\"Submit2\" value=\"Reset\">
      </div>
      <p>&nbsp; </p>
    </div>
  </form>
  <p align=\"left\">&nbsp; </p>
</div>
<center><font size=1 face=verdana> <br><br><br><br><a href=http://www.kyscorp.tk>Kyscorp.tk</a> &copy; 2000-2003 Kys Counter 1.0
</font></center></body>
</html>
";
}

?>
