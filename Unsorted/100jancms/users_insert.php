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
$id=$_POST["id"];
$full_name=$_POST["full_name"];
$username=$_POST["username"];
$password=$_POST["password"];
$email=$_POST["email"];
$admin=$_POST["admin"];
$articles=$_POST["articles"];
$comments=$_POST["comments"];



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
	this.location="users_add.php";
}
function cancel_go2()
{
	this.location="users_edit.php?id=<?php echo $id;?>";
}
</script>


</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Users: <span class="titletext0blue">
		<?php 
	if ($action=="new") 
	{echo "Add new User";} else {echo "View/Edit Users";}
	?>
	</span></td>
  </tr>
</table>
<br>
<br>
<?php 
//fix input
$full_name=htmlspecialchars($full_name ,ENT_QUOTES);
$username=htmlspecialchars($username ,ENT_QUOTES);
$username=str_replace("'","^",$username);
$username=str_replace("\"","^",$username);
$comment=htmlspecialchars($comment ,ENT_QUOTES);


	
	//process pass
	if (!$password=="")
	{
		$encpass = md5("$password");
		$do_password = "password='$encpass',";
	}

//process user_privileges

//admin
$user_privileges="";
if ($admin=="1") {$user_privileges="ADMIN, ";}



//process ARTICLES user_privileges
$i=0;
if (count($articles) > 0) {
$user_privileges=$user_privileges."ARTICLES_MASTER, "; //outer clearance

   foreach($articles as $name_element) 
    { 
	$user_privileges=$user_privileges."ARTICLES[".$articles[$i]."], ";
	$i++;
	}

}

//process COMMENTS user_privileges
$i=0;
if (count($comments) > 0) {
$user_privileges=$user_privileges."COMMENTS_MASTER, "; //outer clearance

   foreach($comments as $name_element) 
    { 
	$user_privileges=$user_privileges."COMMENTS[".$comments[$i]."], ";
	$i++;
	}

}



//check if username already exists
//===========================================================
if (!empty($id)) {$where=" WHERE idUsers<>$id";}
		$query = "SELECT * FROM ".$db_table_prefix."users ".$where;
		$result=mysql_query($query);
 		$num=mysql_numrows($result); //how many rows
	
		for ($i=0;$i<$num;$i++) {
			$existing_username=mysql_result($result,$i,"username");
			if ($existing_username==$username) {$user_exists=1;}
		}


if ($user_exists==1) {

	//where did we came from
	if ($id=="") {$go="cancel_go()";} else {$go="cancel_go2()";}
	
echo '
<span class="red">Error:</span> &nbsp;Username already exists! <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"><br>
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



//MAIN
	if ($action=="new") //new
	{
			
			$current_time=time();
			$query = "INSERT INTO ".$db_table_prefix."users VALUES ('','$full_name','$username','$encpass','$email','$comment','$user_privileges','$current_time','articles=collapse, comments=collapse, visitors=collapse, users=collapse, help=collapse, admin=collapse,','0')";

			mysql_query($query);
		
			//display message
			echo '<span class="maintext"><strong>Status:</strong> New user has been added!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16"><br>
				  <meta http-equiv="Refresh" content="3; url=users_add.php">' ;

	}

	else //edit
	{		

		$query = "UPDATE ".$db_table_prefix."users SET full_name='$full_name',username='$username',".$do_password." email='$email',comment='$comment',user_privileges='$user_privileges' WHERE idUsers='$id'";
		mysql_query($query);

		//display message
		echo '<span class="maintext"><strong>Status:</strong> User data saved!</span> &nbsp;<img src="images/app/all_good.jpg" width="16" height="16"><br>
			  <meta http-equiv="Refresh" content="3; url=users_search.php">';

	}



?>

<br>
<br>
<br>
	
</body>
</html>
