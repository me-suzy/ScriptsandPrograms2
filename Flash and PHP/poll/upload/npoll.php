<?php
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
?><?php require_once('Connections/poll.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new poll")) {
  $insertSQL = sprintf("INSERT INTO flash_poll (question, numopt, opt1, opt2, opt3, opt4, opt5, opt6, opt7, opt8, skin) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['select'], "int"),
                       GetSQLValueString($_POST['textfield2'], "text"),
                       GetSQLValueString($_POST['textfield3'], "text"),
                       GetSQLValueString($_POST['textfield4'], "text"),
                       GetSQLValueString($_POST['textfield22'], "text"),
                       GetSQLValueString($_POST['textfield32'], "text"),
                       GetSQLValueString($_POST['textfield42'], "text"),
                       GetSQLValueString($_POST['textfield23'], "text"),
                       GetSQLValueString($_POST['textfield33'], "text"),
                       GetSQLValueString($_POST['select2'], "int"));

  mysql_select_db($database_poll, $poll);
  $Result1 = mysql_query($insertSQL, $poll) or die(mysql_error());

  $insertGoTo = "main.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Create new poll</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-size: 12px;
	font-style: italic;
}
.style2 {font-size: 12px}
-->
</style>
</head>
<body>
<p><strong>Flash Poll System</strong></p>
<p>&nbsp;</p>
<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Create a new poll </strong></p>
<p class="style1">When you create a new poll it will be inactive by default.If you want to turn it active go to Open/Close poll. </p>
<form action="<?php echo $editFormAction; ?>" method="POST" name="new poll" id="new poll">
  <p>&nbsp;</p>
  <p><span class="style2">Poll question</span>    
    <input name="title" type="text" id="title" value="" size="70">
  </p>
  <p> <span class="style2">Number of options</span>
    <select name="select" class="style2">
      <option value="1" selected>1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
    </select>
</p>
  <p><span class="style2">Skin&nbsp;</span>&nbsp;     
    <select name="select2" class="style2">
      <option value="1" selected>1</option>
      <option value="2">2</option>
      <option value="3">3</option>
    </select>
</p>
  <p> <span class="style2">Option 1</span>
    <input name="textfield2" type="text" value=" ">
</p>
  <p> <span class="style2">Option 2</span>
    <input name="textfield3" type="text" value=" ">
</p>
  <p class="style2"> Option 3
<input name="textfield4" type="text" value=" ">
</p>
  <p class="style2"> Option 4
<input name="textfield22" type="text" value="  ">
  </p>
  <p class="style2"> Option 5
<input name="textfield32" type="text" value=" ">
  </p>
  <p class="style2"> Option 6
<input name="textfield42" type="text" value=" ">
  </p>
  <p class="style2">Option 7 
    <input name="textfield23" type="text" value=" ">
  </p>
  <p class="style2"> Option 8
<input name="textfield33" type="text" value=" ">
</p>
  <p>
    <input name="hiddenField" type="hidden" value="0">
</p>
  <p>  
    <input type="submit" name="Submit" value="Submit">
  </p>
  <p></p>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="new poll">
</form>
<p><strong> </strong></p>
</body>
</html>
