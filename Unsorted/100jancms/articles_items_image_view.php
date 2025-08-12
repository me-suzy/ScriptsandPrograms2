<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 


//receive posted data
$id=$_GET["id"];

?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/JavaScript">

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

</script>

</head>

<body bgcolor="#FFFFFF" leftmargin="10" rightmargin="0" bottomMargin="0" topmargin="10" marginwidth="0" marginheight="0" class="maintext" scroll="auto">
<?php 
$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row

$image=$row["image"];
$title=$row["title"];
$alt=$row["alt"];

echo '
<b>Article:</b>&nbsp;'.$title.'<br>
<b>Image:</b>&nbsp;'.$image.'<br>
<a href="javascript:;" onClick="window.close()">close this window</a>
<br>
<br>
<img src="images/articles/'.$image.'" alt="'.$alt.'">
<br>
'
;?>
</body>
</html>
