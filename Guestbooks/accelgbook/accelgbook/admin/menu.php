<?php require_once("adminOnly.php");?>
<?php
// *** Logout the current user.
$FF_Logout = $HTTP_SERVER_VARS['PHP_SELF'] . "?FF_Logoutnow=1";
if (isset($HTTP_GET_VARS['FF_Logoutnow']) && $HTTP_GET_VARS['FF_Logoutnow']=="1") {
  session_start();
  session_unregister("MM_Username");
  session_unregister("MM_UserAuthorization");
  $FF_logoutRedirectPage = "../login.php";
  // redirect with URL parameters (remove the "FF_Logoutnow" query param).
  if ($FF_logoutRedirectPage == "") $FF_logoutRedirectPage = $HTTP_SERVER_VARS['PHP_SELF'];
  if (!strpos($FF_logoutRedirectPage, "?") && $HTTP_SERVER_VARS['QUERY_STRING'] != "") {
    $FF_newQS = "?";
    reset ($HTTP_GET_VARS);
    while (list ($key, $val) = each ($HTTP_GET_VARS)) {
      if($key != "FF_Logoutnow"){
        if (strlen($FF_newQS) > 1) $FF_newQS .= "&";
        $FF_newQS .= $key . "=" . urlencode($val);
      }
    }
    if (strlen($FF_newQS) > 1) $FF_logoutRedirectPage .= $FF_newQS;
  }
  header("Location: $FF_logoutRedirectPage");
  exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>menu</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../forText.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%"  border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td class="forTableBgRight"><a href="main.php" target="mainFrame">Main Page </a></td>
  </tr>
  <tr>
    <td class="forTableBgRight"><a href="delete_record.php" target="mainFrame">Delete Record </a></td>
  </tr>
  <tr>
    <td class="forTableBgRight"><a href="modify_record.php" target="mainFrame">Modify Record </a></td>
  </tr>
  <tr>
    <td class="forTableBgRight"><a href="adminLogOut.php" target="_parent">Log Out</a></td>
  </tr>
</table>
</body>
</html>
