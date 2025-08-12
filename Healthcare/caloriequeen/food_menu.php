<?
//start a session
session_start();

//check if user is coming from a form
if ($_POST[op] == "ds") {

//**BELOW YOU WANT TO CHANGE YOUR USERNAME ("admin") AND PASSWORD ("password")-YOU ARE DONE AFTER THIS 
	//check username and password
   if (($_POST[username] != "admin") || ($_POST[password] != "password")) {
   //handle bad login
   $msg = "<p><font color=\"ff0000\">Bad Login-Try Again</p>";
   $show_form = "yes";
   } else {
   
   	//handle good login
   	$_SESSION[valid] = "yes";
   	$show_menu = "yes";
   	
   }
} else {
  //determine what to show
  if ($valid =="yes") {
  	$show_menu = "yes";
  } else {
  	$show_form = "yes";
  }
  
}
//build form block
$form_block = "<h3>Login</h3>
<form method=POST action=\"$_SERVER[PHP_SELF]\">
$msg
<p>username:<br>
<input type=\"text\" name=\"username\" size=15 maxlength=25></p>
<p>password:<br>
<input type=\"password\" name=\"password\" size=15 maxlength=25></p>
<input type=\"hidden\" name=\"op\" value=\"ds\">
<input type=\"submit\" name=\"submit\" value=\"login\" ></p>
</form>";
//build menu block
$menu_block = "<h3>Calorie Queen Admin</h3>
<p>Administration<br>
<a href=\"show_addfood.php\">Add a Food</a><br>
<a href=\"pick_delete.php\">Delete a Food</a>
</p>
<p>View Current Foods<br>
<a href=\"show_foodsbyname.php\">Show Foods Ordered By Name</a><br>
<a href=\"show_foodsbycategory.php\">Show Foods Ordered By Category</a></p>";

//assign the block to show to the $display_block variable
if ($show_form =="yes") {
	$display_block = $form_block;
} else if ($show_menu == "yes") {
	$display_block = $menu_block;
}
?>
<html>
<head>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="images/thinbarbkg.gif">
<table cellspacing="0" cellpadding="0" border="0" height="66" width="100%" background="images/topbarbkg.gif">
  <tr valign="top"> 
    <td width="796" height="58"><img src="images/topbar.gif" width="758" height="133"></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" border="0" height="208" width="745">
  <tr> 
    <td width="149" height="2" valign="top"> 
      <div align="left"> 
        <table width="115" border="0" bgcolor="#009999" bordercolor="#009999" align="center" cellpadding="1" cellspacing="1">
          <tr>
            <td height="19"><b><font face="Arial" size="2" color="#FFFFFF">Search Foods</font></b></td>
          </tr>
			<tr>
            <td height="2" bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2">
			<a href="searchbyfood.php">
			<font color="#FFFFFF">By Name</font></a></font></font></b></td>
          </tr>
			<tr>
            <td height="2" bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2">
			<a href="searchbycategory.php"><font color="#FFFFFF">By Category</font></a></font></font></b></td>
          </tr>
			<tr>
            <td bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2"><a href="food_menu.php">
			<font color="#FFFFFF">Admin</font></a></font></b></td>
          </tr>
          <tr bgcolor="#009999" valign="top"> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </div>
    </td>
    <td width="596" height="2" valign="top"> 
      <div align="center">
<? echo "$display_block"; ?>


		<p>

        
        <br>
      </div>
    </tr>
</table>
</body>
</html>