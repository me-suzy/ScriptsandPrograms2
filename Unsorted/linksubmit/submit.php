<?php 
require "variables.php";
?>
<html>
<head>
<title><?php echo $title ?> </title>
</head>
<body bgcolor="#006699" text="#FFFFFF" link="#FFFFFF" vlink="#FFFF00" alink="#FF0000">
<center><font size="4">
<?php
echo $text1 ?>
<br>
<?php echo $text2 ?><br>
 <a href="<?php echo $url1 ?>" target=_blank">
 <?php 
 echo $urltext1 ?>
 </a><br>
  <font color="#000000">Submit you site for an Instant Free Listing</font></font> 
</center> <FORM METHOD="POST" ACTION="linklist.php">
  <table width="500" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr> 
      <td><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Website 
          Name:</font></strong> </div></td>
      <td> <div align="left"> 
          <INPUT TYPE="text" NAME="wsname" SIZE="30">
        </div></td>
    </tr>
    <tr> 
      <td width="105"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Website 
          URL:</font></strong> </div></td>
      <td width="195"> <div align="left">no http:// just www.&nbsp; 
          <INPUT TYPE="text" NAME="wsurl" SIZE="24">
        </div></td>
    </tr>
    <tr> 
      <td><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Description:</font></strong></div></td>
      <td><textarea name="description" cols="26"></textarea></td>
    </tr>
    <tr> 
      <td><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Email:</font></strong></div></td>
      <td><input type="text" NAME="email"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="center">By Submitting your link you agree to recieve mailings 
          from us.<br>
          <INPUT TYPE="submit" value="Submit!">
        </div></div>
      </td>
    </tr>
  </table>
</form>

<center><font size="4">
<?php
echo $text1 ?>
<br>
<?php echo $text2 ?><br>
 <a href="<?php echo $url2 ?>" target=_blank">
 <?php 
 echo $urltext2 ?>
 </a></font>
</center>
</html>

