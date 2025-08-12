<?
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
/************************************************************************/
/*                    Administration Tools                              */
/*                                                                      */
/*         (c) 2003 - 2004 by   WebExpert  (lcotfas@rdslink.ro)         */
/*                         www.webexpertbiz.com                         */
/************************************************************************/
/*                                                                      */
/************************************************************************/
include('Connections/poll.php');
?>
<html>
<h1>Settings</h1>
<form action="settings.php" method="post">
<table style="border-width: 0px" cellspacing="0" cellpadding="0">
<tr><td width="150" bgcolor="#CCCCCC" style="border-style: none; border-width: medium">&nbsp;</td>
<td width="200" bgcolor="#CCCCCC" style="border-style: none; border-width: medium"></td>
</tr>
<?
$passn1 = $_POST['passn1'];
$passn2 = $_POST['passn2'];
  if ($passn1 != $passn2)
  {
   echo ("<tr><td>Complete password for verification</td><td width=\"200\"></td></tr>");
  }
  else if($passn1 != "")
  {
   $sql = "UPDATE flash_access SET Password= '".$passn1."'";
   mysql_query($sql);
   print '<tr><td><p>Password modified.</p></td><td width=\"200\"></td></tr>';
   }
?>
    <tr><td style="border-style: none; border-width: medium">New password:</td>
	  <td width="200" style="border-style: none; border-width: medium"><input name="passn1" type="password"></td></tr>
    <tr><td style="border-style: none; border-width: medium">Confirm password:</td>
	  <td width="200" style="border-style: none; border-width: medium"><input name="passn2" type="password"></td></tr>
  </p>
  <p> 
    
 </p>
</table>
<table cellpadding="0" cellspacing="0" style="border-width: 0px">

    <tr>
      <td width="150" bgcolor="#CCCCCC" style="border-style: none; border-width: medium">&nbsp;</td>
      <td width="200" bgcolor="#CCCCCC" style="border-style: none; border-width: medium"></td>
    </tr>
   <tr>
     <td style="border-style: none; border-width: medium">Web Site address:*</td>
	  <?
	  if ($_POST['address']!=""){$sql = "UPDATE flash_access SET address= '".$_POST['address']."'";
	                           $resursa = mysql_query($sql);}
	  $sql2 = "select * FROM flash_access";
	  $resursa2 = mysql_query($sql2);
	  $address = mysql_result($resursa2,0,"address");
	  ?>
     <td style="border-style: none; border-width: medium"><input type="text" name="address" value="<?=$address; ?>"></td>
   </tr>
    <p></p>
    <p>
    <tr>
      <td style="border-style: none; border-width: medium"><input type="submit" value="Modify settings" name="zile"></td>
      <td width="200" style="border-style: none; border-width: medium">&nbsp;</td>
    </tr>

</table>
</form>

Copy-paste this code on any web page to display the poll:
<textarea name="textarea" cols="80" rows="8"><EMBED src=<?=$address; ?>poll/poll.swf?homepath=<?=$address; ?> 
width=160 height=350 type=application/x-shockwave-flash bgcolor="#FFFFFF" quality="high"></EMBED></textarea>



<hr width="300" align="left">
<p>*web site address must be: http://www.yourserver.com/pooladdress/<br>
</p>
