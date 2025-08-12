<?
require_once("frontend.php");


if(!(isset($id))) {
$id = 1;
$type = "home";
}

?>

<html>
<head>
<title>Web Content Mangement</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta Name="Coverage" Content="Worldwide">
<meta Name="ROBOTS" Content="ALL">
<meta name="keywords" content="web content management,enterprise web content management,web site content management,web content management software,web content management tool,web site content management tool, php web content management system,web based content management">
<meta name="description" content="web content management for your website written in php">

<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
<center>
<p>
  <? s_sponsors1($type,$id); ?>
</p>


<table width="80%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#cccccc" valign = "middle" align = "right" height = "5"><h1>FREE WEB CONTENT MANAGEMENT</H1></td>
  </tr>
</table>


<br>
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="15%" valign="top" bgcolor="#D7E8FD" ><p>
        <? dmenu(); ?>
        <? dmenu_p(); ?>
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="67%" valign="top" bgcolor="#F7F7F7">
      <? templatetype($type,$id)?>
    </td>
    <td width="18%" valign="top" bgcolor="#CCCCCC"><br>
      <? s_sponsors3($type,$id); ?><br>
        <? dmenu_s(); ?>
      </td>
  </tr>
</table>
</center></body>
</html>
