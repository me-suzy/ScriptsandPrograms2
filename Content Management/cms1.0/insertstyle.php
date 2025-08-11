<?php
session_start();
if(!$_SESSION["passcode"])
{
include("relogin.html");
exit();
}?>
                                    
<link href="stylesheets/admin-panel.css" rel="stylesheet" type="text/css">
<?php
include("top-header.php");
if(! $B1){                            
?>

<span class="heading">INSERT NEW STYLESHEET
</span>
<span class="help"><a href="stylehelp.htm" target="_blank">HELP</a></span>
<form method="POST" action="insertstyle.php">
  <div align="center">
    <center>
    <table width="60%" border="1" bordercolor="#666666">
      <tr>
        <td width="38%" align="center" class="text-design1">Stylesheet Name(should be the same as stored)</td>
          <td width="62%" align="center" class="text-design2"> <input name="name" type="text" class="text-box1" size="20">&nbsp; </td>
      </tr>
     
    </table>
    
    </center>
  </div>
  <p align="center"><input name="B1" type="submit" class="back-button" value="Submit"></p>
</form>
  <?php }
  else { 
   include ("connect.php");
  $result = mysql_query ("INSERT into stylesheet (sname) VALUES ('$name')") or die(mysql_error());
  
   print "succesfully entered";
   
  
  ?>
  <form method="POST" action="options.php">
  <p align="center"><input name="B2" type="submit" class="back-button" value="BACK TO OPTIONS"></p>
  </form>
  <?php }
  include("footer.php");
  ?>



