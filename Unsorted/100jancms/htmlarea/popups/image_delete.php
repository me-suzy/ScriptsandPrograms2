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

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../cms_style.css" rel="stylesheet" type="text/css">
<script>
function _CloseOnEsc() {
  if (event.keyCode == 27) {

    parent.window.close();
    return;
  }
}

function Init() {
  document.body.onkeypress = _CloseOnEsc;
}

</script>


</head>

<body leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" scroll="no" onLoad="Init()" class="maintext">
<table width="200" height="130" border="0" cellpadding="0" cellspacing="3" class="maintext">
  <tr>
      
    <td align="left" valign="top"> <table width="100%" height="2" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td></td>
        </tr>
      </table>
      <?php 

	//delete picture
	unlink ("../../images/articles/depot/".$image);

//refresh list
if ($picture_preview<>"no") {$preview_on='onClick="Get_URL()" onChange="Get_URL()"';} else {$preview_on="";}

$new_list='<select name="txtFileName" size="20" class="formfields" style="width:200" '.$preview_on.'>';

//load all image file names
if ($handle = opendir('../../images/articles/depot')) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) { 
        if ($file != "." AND $file != ".." AND $file != "thumb") { 
				$filelist[] = $file;  // insert each filename as an array element
		}
    }
    closedir($handle); 
}

//give results
natcasesort($filelist);   // sort the array
	while (list ($key, $val) = each ($filelist)) // give it out sorted.
		{
			$new_list=$new_list."<option value=\"".$val."\">".$val."</option>";				
		}
$new_list=$new_list."</select>";

//echo message
		echo "<br><span class=\"maintext\"><strong>Status:</strong> Image deleted!</span> &nbsp;<img src=\"../../images/app/all_good.jpg\" width=\"16\" height=\"16\"><br>";
		echo'<meta http-equiv="Refresh" content="3; url=image_manage.php">';	
?>
      <script>
function refresh_list() {
	 parent.window.filelist.innerHTML = '<?php echo "$new_list";?>'
}
refresh_list()
</script>
	  </td>
  </tr>
</table>
</body>
</html>