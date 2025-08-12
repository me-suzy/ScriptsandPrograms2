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
$full_name=$_POST["full_name"];
$username=$_POST["username"];
$password=$_POST["password"];
$email=$_POST["email"];

if (empty($full_name) or empty($username) or empty($password) or empty($email)) {header("Location: test_access_denied.php");}
?>
<html>
<head>
<title>100janCMS Articles Control Test, powered by 100janCMS Articles Control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="site_style.css" rel="stylesheet" type="text/css">
<meta http-equiv="imagetoolbar" content="no">
<script type="text/javascript" src="checkform.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="maintext" scroll="auto">

<center>
  <table width="755" height="100%" border="0" cellpadding="0" cellspacing="0" background="images/bg_full.png" class="maintext">
    <tr>
      <td align="left" valign="top"> <table width="100%" height="80" border="0" cellspacing="5" cellpadding="0" class="maintext">
          <tr> 
            <td width="79%" > <span class="titletext0">100janCMS Articles Control 
              Test</span><br>
              <span class="titletext0blue"><strong>Register</strong></span><br> 
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
//fix
$now_date=time();
$password=md5($password);

		//register visitor
		$query = "INSERT INTO ".$db_table_prefix."visitors VALUES ('','$username','$password','$full_name','$email','$now_date')";
		mysql_query($query);

?>
              Registration successfull! <img src="images/all_good.jpg" width="16" height="16" align="absmiddle"></td>
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