<?php
//Read in config file
$admin=1;
$thisfile = "index";

//test cookies for admin
@setcookie("CookiesOn",1,time()+60,"/","",0);

$index=1;
$configfile = "../includes/config.php";
include($configfile);

if(!$ses["user_perm"] || ($ses["user_perm"]>2 && $ses["user_perm"]!=5)) //not root, admin or editor
{	inl_header("login.php?_target=top");
	die();
}
?>
<html>
<head>
<title><?php echo $la_pagetitle;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
</head>
<frameset rows="85,*" frameborder="NO" border="0" framespacing="0" cols="*"> 
  <frame name="top" scrolling="NO" noresize src="top.php?sid=<?php echo $sid;?>" marginwidth="0" marginheight="0" frameborder="NO" >
  <frameset cols="180,*" frameborder="NO" border="0" framespacing="0" rows="*"> 
    <frame name="left" scrolling="NO" noresize src="left.php?sid=<?php echo $sid;?>" marginwidth="0" marginheight="0" frameborder="NO">
    <frame name="body" src="navigate.php?sid=<?php echo $sid;?>" scrolling="AUTO" frameborder="NO">
  </frameset>
</frameset>
<noframes> 
<body bgcolor="FFFFFF" text="000000">
</body>
</noframes> 
</html>