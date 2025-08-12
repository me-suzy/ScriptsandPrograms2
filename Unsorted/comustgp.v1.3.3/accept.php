<?
##########################################
###         ComusTGP version 1.3.3     ###
###         nibbi@nibbi.net            ###
###         Copyright 2002             ###
##########################################
?>
<html>
<head>
<title>Comus TGP Accept Post Page</title></head>
<body bgcolor=#FFFFFF>
<?
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");

If (isset($accept)){
   $Query = "UPDATE tblTgp SET newpost= '$accept', date= '$dnow' WHERE sessionid = '$seid'";                   
      $result = mysql_query($Query);
   
   echo "<br><br><center><table width=600 border=0 cellspacing=3 cellpadding=3>
    <tr>
      <td>
        <h2 align=center>Thank you!<br>
          The completion of your post has been successful.</h2>
        <br>
        <h4 align=center>Your gallery will be reviewed within 2 days (most likely 
          within a few hours).</h4>
     </td>
    </tr>
  </table><br>
</center>";
}
?>
</body>
</html>
