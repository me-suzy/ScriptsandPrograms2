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
$marker=$_POST["marker"];
$comment=$_POST["comment"];
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
	this.location="articles_marker_add.php";
}
function cancel_go2()
{
	this.location="articles_marker_edit.php?id=<?php echo $id;?>";
}
</script>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: <span class="titletext0blue"> 
      <?php 
      if ($action=="new") 
	  {echo "Add new Marker";} else {echo "View/Edit Markers";}
	  ?>
      </span></td>
  </tr>
</table>
<br>
<br>
<?php 
//htmlspecialchars
$comment=htmlspecialchars($comment ,ENT_QUOTES);

//fix input
$marker=str_replace("\"","^",$marker);
$marker=str_replace("'","^",$marker);
$marker=str_replace(" ","_",$marker);


//check if marker already exists
//===========================================================
if (!empty($id)) {$where=" WHERE idMark<>$id";}
		$query = "SELECT * FROM ".$db_table_prefix."articles_marker ".$where;
		$result=mysql_query($query);
 		$num=mysql_numrows($result); //how many rows
	
		for ($i=0;$i<$num;$i++) {
			$existing_marker=mysql_result($result,$i,"marker");
			if ($existing_marker==$marker) {$marker_exists=1;}
		}

if ($marker_exists==1) {

	//where did we came from
	if ($id=="") {$go="cancel_go()";} else {$go="cancel_go2()";}
	
echo '
<span class="red">Error:</span> &nbsp;Marker already exists! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>
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



	if ($action=="new") //add marker
	{

	

		$query = "INSERT INTO ".$db_table_prefix."articles_marker VALUES ('','$marker','$comment' )";
		mysql_query($query);
		//display message
		echo '
		<span class="maintext"><strong>Status:</strong> Marker has been added!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16"><br>		
		<meta http-equiv="Refresh" content="3; url=articles_marker_add.php">' ;


	}
	else  //change marker
	{


		//get current marker name
		$query="SELECT * FROM ".$db_table_prefix."articles_marker WHERE idMark=".$id;
		$result=mysql_query($query);
		$row = mysql_fetch_array($result); //wich row
	 	$current_marker=$row["marker"];

		//update all articles marker reference
		$query = "UPDATE ".$db_table_prefix."articles_items SET marker='".$marker."' WHERE marker='".$current_marker."'";
		mysql_query($query);
		
		//update all comments marker reference
		$query = "UPDATE ".$db_table_prefix."comments SET marker='".$marker."' WHERE marker='".$current_marker."'";
		mysql_query($query);

		//update user_privileges
		$query="SELECT * FROM ".$db_table_prefix."users";
		$result=mysql_query($query);
		$num=mysql_numrows($result); //how many rows
		
			$i=0;
			while ($i < $num) {
				$user_id=mysql_result($result,$i,"idUsers");
				$user_privileges=mysql_result($result,$i,"user_privileges");
				$new_privileges=str_replace("$current_marker",$marker,$user_privileges);
					//now update in database
					$query2 = "UPDATE ".$db_table_prefix."users SET user_privileges='".$new_privileges."' WHERE idUsers='".$user_id."'";
					mysql_query($query2);
			++$i;
			}
			
		//save new marker
		$query = "UPDATE ".$db_table_prefix."articles_marker SET marker='$marker', comment='$comment' WHERE idMark='$id'";
		mysql_query($query);



		//display message
		echo '
		<span class="maintext"><strong>Status:</strong> Marker has been saved!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16"><br>		
		<meta http-equiv="Refresh" content="3; url=articles_marker_search.php">
		';

	
	}

?>

<br>
<br>
<br>

</body>
</html>