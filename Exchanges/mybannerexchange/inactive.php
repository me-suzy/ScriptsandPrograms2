<? include "./config.php"; ?>

<html>

<head>

<title>Account Inactive</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>



<body bgcolor="<? print $tablebgcolor; ?>" text="<? print $tabletextcolor; ?>">

<div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">Your 

  account is temporarily inactive because you haven't sent a hit from your website 

  in the last <? print $hours_must_be_active; ?> hours. This is because your site has very low traffic, or because 

  you haven't placed the banner code on your page in a visible place. <a href="getcode.php?id=<? print $id; ?>"><font color="<? print $tabletextcolor; ?>">If 

  you need the banner code, click here</a></font>. :)</font></div>

</body>

</html>

