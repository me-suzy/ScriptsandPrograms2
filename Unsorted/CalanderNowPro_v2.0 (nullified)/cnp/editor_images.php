<html>

<head>

<title> </title>

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td bgcolor="#CCCCCC"><b><font size="5" face="Arial, Helvetica, sans-serif">Image Upload</font></b></td>
  </tr>
</table>
<h1><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?php
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
$dir=images;
$apsolut_dir="images";
if (!$func) {
$func = "one";
}
switch ($func) {
case one:
echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"editor_images.php\">";
echo "Please select an image to upload and then click on the [upload] button.<br><br><input type=\"file\" name=\"uploadedfile\" size=\"30\">";
echo "<input type=\"hidden\" name=\"max_file_size\" value=\"100000\">";
echo "<input type=\"hidden\" name=\"func\" value=\"two\">";
echo " <br><br><input type=\"submit\" value=\"Upload\">";
?>
  </font></h1>
<h1><font size="2" face="Arial, Helvetica, sans-serif">
  <p></p>
  <hr width="100%" size="1" noshade>
  <font size="2" face="Arial, Helvetica, sans-serif"><a href="javascript:window.close();">Cancel 
  / Close</a></font> 
  <?php
break;
case two:
$ime="{$HTTP_POST_VARS['name']}";
$ekst=$uploadedfile_type;
$duzina=strlen($ekst);
$pos=strpos($ekst,"/")+1;
$ekstenzija=substr($ekst,$pos,$duzina);
if($ekstenzija=="pjpeg"){
$ekstenzija="jpg";
}
if($ekstenzija=="x-png"){
$ekstenzija="png";
}
if($ekstenzija=="x-wmf"){
$ekstenzija="wmf";
}
if($ekstenzija=="octet-stream"){
$ekstenzija="psd";
}
$extra = rand (1000, 90000);
$ime = $extra;
$ime2 = $ime;
if (!$ime)
{
$ime=$uploadedfile_name;
}
else {
$ime.=".".$ekstenzija;
}
if($uploadedfile<>"none") {
  if(!copy($uploadedfile,"$dir/$ime")) {
   print("<b>Error:</b> image did not send.<br>");
   print("Image is too big or it does not exist.<br>");
   print("Try again.");
  }
  else {
  ?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><br>
  <font color="#FF0000">Please drag the image below into the text box.</font>.</font></font><font size="2" face="Arial, Helvetica, sans-serif"></p> 
  </font></h1>

<h1><font size="2" face="Arial, Helvetica, sans-serif">

<img src="images/<? print $ime; ?>">

  <p></p>

 

  <hr width="100%" size="1" noshade>
  <font size="2" face="Arial, Helvetica, sans-serif"><a href="javascript:window.close();">Click 
  here once your image has been placed.</a></font><br>

  <?php
  }
}
break;
}
?>

  </font></h1>

</body>

</html>