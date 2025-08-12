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

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" >


<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Articles: <span class="titletext0blue"> 
      Edit Article</span></td>
  </tr>
</table>
<br>
<br>
<?php 

		//what is the image filename
		$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id; 
		$result=mysql_query($query);
		$row = mysql_fetch_array($result); //wich row
	 	$image=$row["image"];


		
//check if some other articles uses same image
			$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc<>".$id; 
			$result=mysql_query($query);
			$num=mysql_numrows($result); //how many row
			
			for ($i=0;$i<$num;$i++) 
		{
		$existing_image=mysql_result($result,$i,"image");
			if ($existing_image==$image) 
			{
			$is_using_it=1;
			}
		} 
			
			
if ($is_using_it!==1)	{

		unlink ("images/articles/".$image);
}

		 // delete database filename reference
		$query="UPDATE ".$db_table_prefix."articles_items SET image='' WHERE idArtc=".$id; 
		mysql_query($query);


		echo "<span class=\"maintext\"><strong>Status:</strong> Image deleted!</span> &nbsp;<img src=\"images/app/all_good.jpg\" width=\"16\" height=\"16\"><br>";
		echo'<meta http-equiv="Refresh" content="3; url=articles_items_edit.php?id='.$id.'">';

?>

</body>
</html>
