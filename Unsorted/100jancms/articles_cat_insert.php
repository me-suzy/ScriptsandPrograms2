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
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php';


//receive posted data
$action=$_POST["action"];
$category=$_POST["category"];
$id=$_POST["id"];

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
	this.location="articles_cat_add.php";
}
function cancel_go2()
{
	this.location="articles_cat_edit.php?id=<?php echo $id;?>";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: <span class="titletext0blue"> 
      <?php 
      if ($action=="new") 
	  {echo "Add new Category";} else {echo "View/Edit Categories";}
	  ?>
      </span></td>
  </tr>
</table>
<br>
<br>
<?php 

//fix input
$category=str_replace("\"","^",$category);
$category=str_replace("'","^",$category);


//check if marker already exists
//===========================================================
if (!empty($id)) {$where=" WHERE idCat<>$id";}
		$query = "SELECT * FROM ".$db_table_prefix."articles_category ".$where;
		$result=mysql_query($query);
 		$num=mysql_numrows($result); //how many rows
	
		for ($i=0;$i<$num;$i++) {
			$existing_category=mysql_result($result,$i,"category");
			if ($existing_category==$category) {$category_exists=1;}
		}


if ($category_exists==1) {

	//where did we came from
	if ($id=="") {$go="cancel_go()";} else {$go="cancel_go2()";}
	
echo '
<span class="red">Error:</span> &nbsp;Category already exists! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>
<br>
<br>
<br>
<input name="cancel_button" type="button" class="formfields2" id="cancel_button" style="width: 75px; height: 26px;" value="Back" onClick="'.$go.'">
<script language="JavaScript" type="text/JavaScript">
document.all.cancel_button.focus();
</script>
<br>
<br>
<br>
</body>
</html>
';
die;
}
//===========================================================


	if ($action=="new") //add category
	{



		$query = "INSERT INTO ".$db_table_prefix."articles_category VALUES ('','$category')";
		mysql_query($query);
		//display message
		echo "<span class=\"maintext\"><strong>Status:</strong> Category has been added!</span> &nbsp;<img src=\"images/app/all_good.jpg\" width=\"16\" height=\"16\"><br>";
		//go back
		echo'<meta http-equiv="Refresh" content="3; url=articles_cat_add.php">' ;

		
	}
	else  //change category
	{


		$query = "UPDATE ".$db_table_prefix."articles_category SET category='$category' WHERE idCat='$id'";
		mysql_query($query);
		//display message
		echo "<span class=\"maintext\"><strong>Status:</strong> Category has been changed!</span> &nbsp;<img src=\"images/app/all_good.jpg\" width=\"16\" height=\"16\"><br>";		
		//go back
		echo'<meta http-equiv="Refresh" content="3; url=articles_cat_search.php">' ;
	
	}

?>
</body>
</html>