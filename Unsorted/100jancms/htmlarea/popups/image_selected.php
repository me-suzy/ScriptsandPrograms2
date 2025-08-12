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
include '../../restrict_access.php';

//configuration file
include '../../config_inc.php'; 


//receive posted data
if (!empty($_GET["image"])) {$image=$_GET["image"];}


?>
<html>
<head>
<?php echo "$text_encoding"; ?>
<link href="../../cms_style.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/JavaScript">
function _CloseOnEsc() {
  if (event.keyCode == 27) {

    parent.window.close();
    return;
  }
}

function Init() {
  document.body.onkeypress = _CloseOnEsc;
}

<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>

</head>

<body leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" scroll="auto" onLoad="Init()" class="maintext">
<?php 
//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_image_preview'";
$result=mysql_query($query);
$articles_editor_image_preview=mysql_result($result,0,"config_value");


if ($articles_editor_image_preview=="full") { //full image preview
	if (empty($image)) {
		echo '
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr>
		    <td align="center" valign="middle" class="maintext">select image</td>
		  </tr>
		</table>
		';
	}
	else {
		echo '<img name="image_fa" src="../../images/articles/depot/'.$image.'">';
	}
}

if ($articles_editor_image_preview=="thumbnail") { //gd thumbnail image preview
//coming soon
}

if ($articles_editor_image_preview=="no") { //no image preview
	echo '
	<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr>
	    <td align="center" valign="middle" class="maintext">image preview is off</td>
	  </tr>
	</table>
	';
}

	

//get filesize
if ($image<>"") {
$fs_value=round((filesize("../../images/articles/depot/".$image)/1024),2);
//echo 'filesize: '.$fs_value.'kb<br>';
echo '
<script>
function fs() {
	 parent.window.filesize.innerHTML=\''.$fs_value.' kb\'
}
fs()
</script>
';
}
?>

</body>
</html>
