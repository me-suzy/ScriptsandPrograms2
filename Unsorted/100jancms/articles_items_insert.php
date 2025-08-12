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
$action=$_POST["action"];
$id=$_POST["id"];
$article_title=$_POST["article_title"];
$marker=$_POST["marker"];
$status=$_POST["status"];
$category=$_POST["category"];
	$pom_file_name = $_POST["pom_file_name"];
	$tmp_name  = $_FILES['file_up']['tmp_name'];
	$file_name = $_FILES['file_up']['name'];
	$file_size = $_FILES['file_up']['size'];
	$file_type = $_FILES['file_up']['type'];
	$path = "images/articles/";
$position=$_POST["position"];
$alt=$_POST["alt"];
$source=$_POST["source"];
$location=$_POST["location"];
$keywords=$_POST["keywords"];
$priority=$_POST["priority"];
$flag=$_POST["flag"];
$expire=$_POST["expire"];
$d=$_POST["d"];
$m=$_POST["m"];
$y=$_POST["y"];
$h=$_POST["h"];
$mi=$_POST["mi"];
$sec=$_POST["sec"];
$articles_text=$_POST["articles_text"];
$articles_text_2=$_POST["articles_text_2"];
$comments_allow=$_POST["comments_allow"];
$comments_registered=$_POST["comments_registered"];
$comments_approve=$_POST["comments_approve"];
$visits=$_POST["visits"];
$rate=$_POST["rate"];


?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/page_bg.jpg");
background-repeat: 
repeat-y;
background-attachment: 
fixed
}
</style>

<script language="JavaScript" type="text/JavaScript">
function cancel_go()
{
	this.location="articles_items_search.php";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: <span class="titletext0blue"> 
      <?php 
      if ($action=="new") 
	  {echo "Add new Article";} else {echo "View/Edit Articles";}
	  ?>
      </span></td>
  </tr>
</table>
<br>
<br>
<?php 

//htmlspecialchars
$article_title=htmlspecialchars($article_title ,ENT_QUOTES);
$alt=htmlspecialchars($alt ,ENT_QUOTES);
$source=htmlspecialchars($source ,ENT_QUOTES);
$location=htmlspecialchars($location ,ENT_QUOTES);

//fix input
$keywords=str_replace(";",",",$keywords);
$keywords=trim($keywords);
$keywords_len=strlen($keywords);
if (substr($keywords, $keywords_len-1, 1)==",") {$keywords=substr($keywords, 0, $keywords_len-1);}

//add slashes to correctly insert html to database
$articles_text=addslashes($articles_text);
$articles_text_2=addslashes($articles_text_2);

//date
$date=mktime($h,$mi,$sec,$m,$d,$y);
//priority
if ($priority==1) {$priority=1;} else {$priority=0;}
//flag
if ($flag==1) {$flag=1;} else {$flag=0;}
//comments_allow
if ($comments_allow==1) {$comments_allow=1;} else {$comments_allow=0;}
//comments_registered
if ($comments_registered==1) {$comments_registered=1;} else {$comments_registered=0;}
//comments_approve
if ($comments_approve==1) {$comments_approve=1;} else {$comments_approve=0;}

//fix filename
$file_name=str_replace('%','_',$file_name);
$file_name=str_replace(' ','_',$file_name);

//Work on database

	if ($action=="new") //add article
	{
		
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
			$file_name='';
			$alt='';
			$position='left';
			}
	
	//end upload	
 
 		$query = "INSERT INTO ".$db_table_prefix."articles_items VALUES ('','$article_title','$marker','$status','$category','$file_name','$position','$alt','$source','$location','$keywords','$expire','$date','$articles_text','$articles_text_2','$priority','$flag','".$_SESSION["current_user_username"]."','','$comments_allow','$comments_registered','$comments_approve','0','0' )";
		mysql_query($query);


		//display message
		echo '<span class="maintext"><strong>Status:</strong> &nbsp;&nbsp;Article has been added!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16" align="absbottom"><br>';
		if ($filesize_error==1) {echo '<b>Warning:</b>&nbsp;&nbsp;Image filesize is invalid. File up to '.$articles_image_filesize.' kb is accepted. Image NOT uploaded.&nbsp;<img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>'; }
		if ($filetype_error==1) {echo '<b>Warning:</b>&nbsp;&nbsp;Image filetype is invalid. Files of gif | jpg | jpeg | png type are accepted. Image NOT uploaded.&nbsp;<img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle">'; }		
		//go back to add page
		if  ($filesize_error==1 or $filetype_error==1) {
		echo '
		<br>
		<br>
		<br>
		<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Back" onClick="cancel_go()">
		<script language="JavaScript" type="text/JavaScript">
		document.all.cancel_button.focus();
		</script>
		';
		}
		else
		{
		echo'<meta http-equiv="Refresh" content="3; url=articles_items_add.php">' ;
		}

	}
	else  //edit article
	{


	//give me current data
	$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id;
	$result=mysql_query($query);
	$row = mysql_fetch_array($result); //in wich row are we
	$image=$row["image"];

		//if image is not set, set it
		if ($image=="")
			{
			$new_image="image='$file_name',"; //replace name in database
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
			$new_image='';
			$alt='';
			$position='left';
			}
	
	//end upload	
			}

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
			$new_image='';
			$alt='';
			$position='left';
			}
	
	//end upload	

		//if image already exists, replace it
		if (($image<>"") AND ($file_up<>"") AND ($filesize_error<>1) AND ($filetype_error<>1))
			{
			unlink ("images/articles/".$image); //delete old image
			$new_image="image='$file_name',"; //replace name
				
			}
	

		$query = "UPDATE ".$db_table_prefix."articles_items SET title='$article_title',marker='$marker',status='$status',category='$category',".$new_image."position='$position',alt='$alt',source='$source',location='$location',keywords='$keywords',expire='$expire',date='$date',text='$articles_text',text2='$articles_text_2',priority='$priority',flag='$flag',edited_by='".$_SESSION["current_user_username"]."',comments_allow='$comments_allow',comments_registered='$comments_registered',comments_approve='$comments_approve',visits='$visits',rate='$rate' WHERE idArtc='$id'";
		mysql_query($query);
		
		//display message
		echo '<span class="maintext"><strong>Status:</strong> Article has been saved!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16" align="absbottom"><br>';
		if ($filesize_error==1) {echo '<b>Warning:</b>&nbsp;&nbsp;Image filesize is invalid. File up to '.$articles_image_filesize.' kb is accepted. Image NOT uploaded.&nbsp;<img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>'; }
		if ($filetype_error==1) {echo '<b>Warning:</b>&nbsp;&nbsp;Image filetype is invalid. Files of gif | jpg | jpeg | png type are accepted. Image NOT uploaded.&nbsp;<img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle">'; }		
		//go back
		if  ($filesize_error==1 or $filetype_error==1) {
		echo '
		<br>
		<br>
		<br>
		<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Back" onClick="cancel_go()">
		<script language="JavaScript" type="text/JavaScript">
		document.all.cancel_button.focus();
		</script>
		';
		}
		else
		{
		echo'<meta http-equiv="Refresh" content="3; url=articles_items_search.php">' ;
		}
	
	}

?>

<br>
<br>
<br>

</body>
</html>