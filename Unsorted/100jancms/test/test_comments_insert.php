<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
include "../100jancms/config_connection.php";

//receive posted data
$id=$_POST["id"];
$marker=$_POST["marker"];
$section=$_POST["section"];
$comment=$_POST["comment"];
$member=$_COOKIE["website_member"];

if (empty($id) or empty($marker) or empty($section) or empty($comment)) {header("Location: test_access_denied.php");}
?>
<html>
<head>
<title>100janCMS Articles Control Test, powered by 100janCMS Articles Control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="site_style.css" rel="stylesheet" type="text/css">
<meta http-equiv="imagetoolbar" content="no">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="maintext" scroll="auto">

<center>
  <table width="755" height="100%" border="0" cellpadding="0" cellspacing="0" background="images/bg_full.png" class="maintext">
    <tr>
      <td align="left" valign="top"> <table width="100%" height="80" border="0" cellspacing="5" cellpadding="0" class="maintext">
          <tr> 
            <td width="79%" > <span class="titletext0">100janCMS Articles Control 
              Test</span><br>
              <span class="titletext0blue"><strong>Add comment</strong></span><br> 
              <span class="maintext">powered by 100janCMS 
              Articles Control</span></td>
            <td width="21%" align="right" valign="top"><img src="images/logo_login.jpg" width="128" height="44" hspace="5" vspace="5" border="0"></td>
          </tr>
        </table>
        <hr width="755" size="1" color="#DCDCDC"> 
        <div align="left"><strong>&nbsp;&nbsp;<a class="fmenu" href="test_index.php">&nbsp;HOME&nbsp;</a> 
          <a class="fmenu" href="test_register.php">&nbsp;REGISTER&nbsp;</a> <span class="maintext"><a class="fmenu" href="test_login.php">&nbsp;LOGIN&nbsp;</a> 
          <a class="fmenu" href="test_logout.php">&nbsp;LOGOUT&nbsp;</a> </span></strong><span class="maintext"> 
          <?php 
if (isset($_COOKIE["website_member"])) {echo "You are logged in as <strong>".$_COOKIE["website_member"]."</strong>.";} else {echo "You are NOT logged in.";}

?>
          </span></div>
        <hr width="755" size="1" color="#DCDCDC"> <br>
	<!-- articles start -->
        <table width="100%" border="0" cellpadding="5" cellspacing="0" class="maintext">
          <tr>
            <td align="left" valign="top"> 
              <?php 

		//article data
		$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc='".$id."'";
		$result=mysql_query($query);
		$row = mysql_fetch_array($result); //wich row	
		$comments_approve=$row["comments_approve"];
		$title=$row["title"];		


//fix
$now_date=time();
if ($comments_approve==1) {$c_approve=0;} else {$c_approve=1;}
if (empty($member)) {$member="anonymous";}


		//insert comment into database
		$query = "INSERT INTO ".$db_table_prefix."comments VALUES ('','$comment','$now_date','$member','$section','$marker','$id','$c_approve')";
		mysql_query($query);

echo '<span class="maintext2blue">'.$title.'</span><br><br>';
echo 'Comment has been added!';
if ($comments_approve==1) {echo '<br>It will be displayed once administrator approve it.';}
echo '&nbsp;<img src="images/all_good.jpg" width="16" height="16" align="absmiddle">';
?>
            </td>
          </tr>
        </table>
<!-- articles end -->
        <br>
          <br>
          <br>
        <br>
          <br>
          <br>
          <br>
      </td>
    </tr>
  </table>
</center>
</body>
</html>