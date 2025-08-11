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
//added
$result= mysql_query ("SELECT * from footer limit 1");   
$row=mysql_fetch_array($result);                     
//added
?>
</head>
<body>
<p class="headingcenter"><a href="options.php">Back to Options</a></p>
<p class="headingcenter">FOOTER FOR THE SITE</p>
<form method="POST" action="insfooter.php">
  <div align="center">
    <table border="1" bordercolor="#333333">
      <tr>
          <td class="text-design1">
		  <textarea name="text" cols="72" rows="3" class="text-box1"><?php echo $row["text"]; ?></textarea> 
          </td>
      </tr>
    </table>
  </div>
  <p align="center"><input name="B3" type="submit" class="back-button" value="Submit"></p>
  
</form>
  <?php }
  else { if(empty($text))
  {
   display($text);
  }
  else{
  commands($text);
  } ?>
  
<p class="headingcenter"><a href="options.php">Back to Options</a></p>

  <?php }?>
  
 <?php function commands($text){
 
include ("connect.php");
  $result = mysql_query ("UPDATE footer set text='$text' where 1") or die(mysql_error());
  print "<span class='heading'>succesfully entered</span>";
  }
?>

<?php function display($text){
?>
<span class="heading">CRITICAL FIELDS MISSING IN HEADER ENTRY
</span>
<form method="POST" action="insfooter.php">
  <div align="center">
    <center>
    <table width="86%" border="1" bordercolor="#333333">
      <tr>
        <td width="23%" align="center" class="text-design1">TEXT</td>
          <td width="77%" align="center" class="text-design1"><textarea name="text" cols="72" class="text-box1"><?php echo $text ?></textarea> 
          </td>
      </tr>
      
      
    </table>
    </center>
  </div>
  <p align="center"><input name="B3" type="submit" class="back-button" value="Submit">
  </p>
</form>
   <?php }?>

