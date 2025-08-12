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
    <td align="left" valign="top"><table width="100%" height="2" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td></td>
        </tr>
      </table>
      <?php 
//upload file setup
	$pom_file_name = $_POST["pom_file_name"];
	$tmp_name  = $_FILES['file_up']['tmp_name'];
	$file_name = $_FILES['file_up']['name'];
	$file_size = $_FILES['file_up']['size'];
	$file_type = $_FILES['file_up']['type'];
	$path = "../../images/articles/depot/";

//fix filename
$file_name=str_replace('%','_',$file_name);
$file_name=str_replace(' ','_',$file_name);


		//upload image
		//check filesize
		$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_image_filesize'";
		$result=mysql_query($query);
		$articles_image_filesize=mysql_result($result,0,"config_value");
		
		if($file_size > ($articles_image_filesize*1024)) { 
		$filesize_error=1;
		} 
		//check filetype
		if((!preg_match("/\.(gif|jpg|jpeg|png)$/i", $file_name)) and ($file_name<>"")) { 
		$filetype_error=1;
		} 

		//do uplaod		
		if  ($filesize_error<>1 and $filetype_error<>1) {
			copy($tmp_name, $path.$file_name);
		}
		else
			{
			//clear image data
				//we are not using database here
			}
	
	//end upload	


//refresh list *******************************
if ($picture_preview<>"no") {$preview_on='onClick="Get_URL()" onChange="Get_URL()"';} else {$preview_on="";}

$new_list='<select name="txtFileName" size="20" class="formfields" style="width:200" '.$preview_on.'>';

//load all image file names
if ($handle = opendir('../../images/articles/depot')) {
    while (false !== ($file = readdir($handle))) { 
        if ($file != "." AND $file != ".." AND $file != "thumb") { 
				$filelist[] = $file;  // insert each filename as an array element
		}
    }
    closedir($handle); 
}

//give results
natcasesort($filelist);   // sort the array
	while (list ($key, $val) = each ($filelist)) // give it out sorted
		{
			$new_list=$new_list."<option value=\"".$val."\">".$val."</option>";				
		}
$new_list=$new_list."</select>";
//******************************************

if ($file_name<>"") {
		//display message		
		if ($filesize_error==1) {$error_txt_1='Image filesize is invalid. File up to '.$articles_image_filesize.' kb is accepted. Image NOT uploaded.\n'; }
		if ($filetype_error==1) {$error_txt_2='Image filetype is invalid. Files of gif | jpg | jpeg | png type are accepted. Image NOT uploaded.'; }		
		//go back
		if  ($filesize_error==1 or $filetype_error==1) {
			if ($filesize_error==1) {$full_error_txt=$full_error_txt.$error_txt_1;}
			if ($filetype_error==1) {$full_error_txt=$full_error_txt.$error_txt_2;}

		echo '
		<script language="JavaScript" type="text/JavaScript">
		this.location="image_manage.php";
		alert ("'.$full_error_txt.'");
		</script>
		';
		//giving 10 sec for error message, then reload
		}
		else
		{
		echo '
		<br>
		<br>
		<span class="maintext"><strong>Status:</strong> Image uploaded!</span> &nbsp;<img src="../../images/app/all_good.jpg" width="16" height="16" align="absbottom"><br>
		<meta http-equiv="Refresh" content="3; url=image_manage.php">' ;
		}
}
else {
		echo '
		<script language="JavaScript" type="text/JavaScript">
		this.location="image_manage.php";
		alert ("Nothing to upload. Browse for image file first. Image NOT uploaded.");
		</script>
		' ;
}
?>
<script>
//refresh list
function refresh_list() {
	 parent.window.filelist.innerHTML = '<?php echo "$new_list";?>'
}
refresh_list();
</script>
    </td>
  </tr>
</table>
</body>
</html>