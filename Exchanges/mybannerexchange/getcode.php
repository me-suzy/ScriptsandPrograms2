<?
include "./config.php";
if ($id) $jscode = "<script language=\"JavaScript\" src=\"".$bx_url."show.php?id=$id\"></script>";
if (!$id) $jscode = "Error: No ID was given.";
?>
<html>
<head>
<title>BannerExchange Code</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="<? print $tablebgcolor; ?>" text="<? print $tabletextcolor; ?>">
<div align="center"><font face="Verdana, Arial, Helvetica, sans-serif">Place this 
  code anywhere on your main site's page </font></div>
<form name="form1" method="post" action="">
  <div align="center">
    <textarea name="xxxxxx" cols="50" rows="4"><? print $jscode; ?></textarea>
  </div>
</form>
</body>
</html>
