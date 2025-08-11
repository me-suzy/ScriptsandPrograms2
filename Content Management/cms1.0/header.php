<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}?>                               
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">
<?php
if(! $B3){    
$result= mysql_query ("SELECT * from header limit 1");   
$row=mysql_fetch_array($result);                     
?>
<span class="heading">INSERT HEADER FOR THE SITE
</span> <span class="help"><a href="headerhelp.htm" target="_blank">HELP</a></span>
<form method="POST" action="header.php">
  <div align="center">
    <center>
    <table border="1" bordercolor="#333333">
      <tr>
        <td class="text-design1">background image</td>
        <td class="text-design1"><input name="bimage" type="text" class="text-box1" size="40" value="<?php echo $row["bimage"]; ?>" ></td>
      </tr>
	  <tr>
        <td  class="text-design1">company logo</td>
        <td  class="text-design1"><input name="logo" type="text" class="text-box1" size="40" value="<?php echo $row["logo"]; ?>"> </td>
      </tr>
      <tr>
          <td class="text-design1">company name</td>
          <td  class="text-design1"><input name="company" type="text" class="text-box1" size="40" value="<?php echo $row["company"]; ?>"> </td>
      </tr>
	   <tr>
          <td class="text-design1">punch line</td>
          <td class="text-design1"><input name="punchline" type="text" class="text-box1" size="40" value="<?php echo $row["punchline"]; ?>"> </td>
      </tr>
    </table>
	
    </center>
  </div>
  <p align="center"><input name="B3" type="submit" class="back-button" value="Submit"></p>
  <p align="center" class="submenu"><b><font color="#FF0000">Please make sure the images are in
  the images folder and the paths given are correct</font></b></p>
  <p class="headingcenter"><a href="options.php">Back to Options</a></p>
</form>
  <?php }
  else { if(empty($bimage) || empty($logo) ||empty($punchline) ||empty($company))
  {
   display($name,$bimage,$logo,$company,$punchline);
  }
  else{
  commands($name,$bimage,$logo,$company,$punchline);
  } ?>
  
<p class="headingcenter"><a href="options.php">Back to Options</a></p>

  <?php }?>
  
 <?php function commands($name,$bimage,$logo,$company,$punchline){
 
include ("connect.php");
  $result1 = mysql_query ("UPDATE  header set  bimage='$bimage',logo='$logo',company='$company',punchline='$punchline' where 1") or die(mysql_error());
  print "<span class='heading'>succesfully entered</span>";
  }
?>

<?php function display($name,$bimage,$logo,$company,$punchline){
?>
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">
<span class="heading">CRITICAL FIELDS MISSING </span> <span class="help"><a href="headerhelp.htm" target="_blank">HELP</a></span>
<form method="POST" action="header.php">
  <div align="center">
    <center>
    <table width="100%" border="1" bordercolor="#666666">
     
      <tr>
          <td width="27%" align="center" class="text-design2">BACKGROUND IMAGE</td>
        <td width="72%" align="center" class="text-design2"><input name="bimage" type="text" class="text-box1" value="<?php echo $bimage; ?>" size="20" ></td>
      </tr>
	  <tr>
        <td width="27%" align="center" class="text-design1">COMPANY LOGO</td>
        <td width="72%" align="center" class="text-design1"><input name="logo" type="text" class="text-box1" value="<?php echo $logo;?>" size="20"> </td>
      </tr>
      <tr>
          <td width="27%" height="59" align="center" class="text-design2">COMPANY NAME</td>
          <td width="72%" align="center" class="text-design2"><input name="company" type="text" class="text-box21 value="<?php echo $company; ?>" size="20"> </td>
      </tr>
	   <tr>
          <td width="27%" height="59" align="center" class="text-design1">PUNCH LINE</td>
          <td width="72%" align="center" class="text-design1"><input name="punchline" type="text" class="text-box1" value="<?php echo $punchline;?>" size="20"> </td>
      </tr>
	  
      
    </table>
    </center>
  </div>
  <p align="center"><input name="B3" type="submit" class="back-button" value="Submit">
  </p>
  <p align="center" class="submenu"><b><font color="#FF0000">Please make sure the images are in
  the images folder and the paths given are correct</font></b></p>
</form>
<p class="headingcenter"><a href="options.php">Back to Options</a></p>
   <?php }?>

