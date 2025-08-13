<? include ("../atho.inc.php");?>
<?
include ("../config.inc.php");

?>


<html>
<head>
<title>Upload and Insert Local File</title>
<script>

/****************************    CONFIGURATION HINT    ********************************/
/**************************************************************************************
UPLOADSCRIPT= URL of upload.pl or upload.php - Relative to "upload.html" or absolute url
POOLDIR= Where is the directory for saving files. "always relative" to file upload.php or upload.pl
POOLURL= always absolute URL of uploaded directory.
FILESIZE= "50000"; //size limit in bytes; $filesize="": no limit
***************************************************************************************/


// Samples FOR USING PERLscript. You can use these for testing
/*
UPLOADSCRIPT= "http://ngocanh.virtualave.net/cgi-bin/uploads/upload.pl"; //absolute
POOLDIR= "../../jscript/uploads"; //always relative to upload.pl
POOLURL= "http://ngocanh.virtualave.net/jscript/uploads"; //always absolute
FILESIZE= "50000";
*/

// Samples for testing Perlscript at Localhost
/*
UPLOADSCRIPT= "http://127.0.0.1/cgi-bin/uploads/upload.pl"; //absolute
POOLDIR= "../../jscript/uploads"; //always relative to upload.pl
POOLURL= "http://127.0.0.1/jscript/uploads"; //always absolute
FILESIZE= "50000";
*/

// Samples FOR USING PHPscript
/*
UPLOADSCRIPT= "upload.php"; //relative to upload.html
POOLDIR= "./uploads"; //always relative to upload.php
POOLURL= "http://127.0.0.1/jscript/uploads"; //always absolute
FILESIZE= "50000";
*/
UPLOADSCRIPT= "<?echo "$uploadscript"?>"; //absolute
POOLDIR= "<?echo "$uploadsrel"?>"; //always relative to upload.php
POOLURL= "<?echo "$poolurl"?>"; //always absolute
FILESIZE= "<?echo "$filesize"?>";



// Samples FOR USING ASP-Script
/*
UPLOADSCRIPT= "upload.asp"; //relative to upload.html
POOLDIR= "./uploads"; //always relative to upload.asp
POOLURL= "show.asp"; //always same dir as UPLOADSCRIPT
FILESIZE= "50000"; 
*/

function goSubmit()
{
  with(document.forms[0])
  {
   action= UPLOADSCRIPT
   pooldir.value= POOLDIR
   poolurl.value= POOLURL
   filesize.value= FILESIZE
   submit()
  }
}

</script>

<style>
td {color:white; font-family:Arial; font-size:14px}
input {color:blue; background:#eeffee; width:120px}
</style>

</head>

<body bgcolor=menu scroll=yes>

<center>

<form method=post enctype="multipart/form-data">

<TABLE bgColor=#999999 border=1 width=100% cellpading=0 cellspacing=0>

<!-- 1st row -->
<TR>
<TD align=middle bgColor=#aa4444 colSpan=2>File Upload</TD>
</TR>

<!-- 2sd row -->
<TR>
<TD align=right>Select File:</TD>
<TD><input type=file name="file" style="width:400px" accept="application/x-www-form-urlencoded"></TD>
</TR>



<!-- Last row -->
<TR>
<TD colspan=2 align=center>
<INPUT onclick=goSubmit() title=Send type=button value="Send" style="width:70px; height:22px; background:#aa4444; color:white">
<INPUT onclick=self.close() title=Close type=button value="Close" style="width:70px; height:22px; background:#aa4444; color:white">
</TD></TR>


</TABLE>
<INPUT type=hidden name=pooldir value=''>
<INPUT type=hidden name=poolurl value=''>
<INPUT type=hidden name=filesize value=''>
</FORM>
</center>
<br><div align="center"><b>Just Click on the link next to the picture and you image will be inserted into the editor you can close the window when your done.</b></div><br>
<?php 
echo "<table border=\"3\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">\n"; 

$handle=opendir("$uploadsrel2");  
            while (false!==($file = readdir($handle))) {  
                if ($file != "." && $file != ".." 
&& $file != "index.php" && $file != "index.html" && $file != "index1.html" && $file != "index2.html" && $file != "WS_FTP.LOG" && $file != "_editori.php" && $file != "_editoru.php" && $file != "i3" && $file != "help") { 
                    $filename = str_replace(".tpl"," ",$file);   
                    echo "<tr><td><img src=\"$uploadsrel2$filename\" border=\"0\" alt=\"\"></td><td align=\"center\"><b><a href=\"javascript:window.opener.doFormatF('InsertImage,$uploadsurl$filename')\">($filename) Insert Into The Document</a></b></td></tr>\n"; 

            } 
            } 
            closedir($handle);

echo "</table>\n";			 
?>
</body>

