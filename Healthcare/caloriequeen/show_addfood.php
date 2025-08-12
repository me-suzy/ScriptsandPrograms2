<?
//start a session
session_start();

//validate user to see if they are allowed to be here
if ($_SESSION[valid] != "yes") {
   header("Location: http://website.com/food_menu.php");
   exit;

}
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
$menu_block = "<h3>Calorie Queen Food Management System</h3>
<p>Administration
<ul>
<li><a href=\"show_addfood.php\">Add a Food</a>
<li><a href=\"pick_delete.php\">Delete a Food</a>
</ul>
<p>View Current Foods
<ul>
<li><a href=\"show_foodsbyname.php\">Show Foods Ordered By Name</a></ul>";

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
      <div align="left">
<h3 align="center">Calorie Queen Admin</h3>
<form method="post" action="do_addfood.php">
<table cellspacing=2 cellpadding=1 width="578">
<tr>
<th><font size="2" face="Arial">General Information</font></th>
</tr>
<tr>
<td valign=top align="center">
<p><font face="Arial"><strong><font size="2">Food Name:</font></strong><br>
<input type="text" name="food_name" size=35 maxlength=35></font></p>
<p><font face="Arial"><strong><font size="2">Food Category:</font></strong><br>
<input type="text" name="food_category" size=35 maxlength=35></font></p>
<p><font face="Arial"><strong><font size="2">Serving Size:</font></strong><br>
<input type="text" name="serving_size" size=35 maxlength=35></font></p>
<p><font face="Arial"><strong><font size="2">GmWt1:</font></strong><br>
<input type="text" name="gmwt1" size=35 maxlength=35></p>
<p><strong><font face="Arial" size="2">GmWt_Desc1:</font></strong><br>
<input type="text" name="gmwt_desc1" size=35 maxlength=35></p>
<p><strong><font face="Arial" size="2">GmWt2:</font></strong><br>
<input type="text" name="gmwt2" size=35 maxlength=35></font></p>
<p><strong><font face="Arial" size="2">GmWt_Desc2:</font></strong><br>
<input type="text" name="gmwt_desc2" size=35 maxlength=35></p>

<h3><font size="2" face="Arial">Nutrient Info<br>
</font>
</h3>
<p><font face="Arial"><strong><font size="2">Calories:</font></strong><br>
<input type="text" name="calories" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Total Fat:</font></strong><br>
<input type="text" name="total_fat" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Saturated Fat:</font></strong><br>
<input type="text" name="saturated_fat" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Trans-Fat:</font></strong><br>
<input type="text" name="trans_fat" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Carbohydrates:</font></strong><br>
<input type="text" name="carbohydrates" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Dietary Fiber:</font></strong><br>
<input type="text" name="dietary_fiber" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Sugar:</font></strong><br>
<input type="text" name="sugar" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Protein:</font></strong><br>
<input type="text" name="protein" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Cholesterol:</font></strong><br>
<input type="text" name="cholesterol" size=35 maxlength=35></p>
<p><font face="Arial"><strong><font size="2">Sodium:</font></strong><br>
<input type="text" name="sodium" size=35 maxlength=35></font></p>
</td>
</tr>
<tr>
<td align=center><br><br>
<input type="submit" name="submit" value="Add Food">
<p><a href="food_menu.php">Return to Main Menu</a></p>
</td>
</tr>
</table>
</form>

		<p>

        
        <br>
      </div>
    </tr>
</table>
</body>
</html>