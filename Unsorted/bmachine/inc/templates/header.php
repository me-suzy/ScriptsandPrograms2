<html>

<head>
<title><? echo $title; ?></title>
<meta name="keywords" content="<? echo $keys; ?>">
<meta name="description" content="<? echo $title; ?>">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="calendar.css">
<link rel="stylesheet" type="text/css" href="bbcode.css">
<style>
<!--
.hid { font-size:1pt; color:#E2E2E2; }
a { font-family:Verdana; font-size:8pt; color:rgb(51,51,51); text-decoration:none; }
li { font-size:8pt; color:rgb(204,51,0); text-decoration:none; }
a:hover { color:rgb(153,153,153); }
body { font-family:Verdana; font-size:8pt; }
font { font-family:Verdana; font-size:8pt; }
p { font-family:Verdana; font-size:8pt; }

.mnu_link { font-weight:bold; color:rgb(238,238,238); }
-->
</style>
</head>

<body bgcolor="whitesmoke" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="729">
<tr>
<td width="729">
<table border="0" cellpadding="1" cellspacing="0" width="729" bgcolor="black">
<tr>
<td width="727">
<table border="0" cellpadding="0" cellspacing="0" width="729" bgcolor="white">
<tr>
<td width="729" height="85">
&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="752">
<tr>
<td width="372" height="60">
&nbsp;<a class="mnu_link" href="<? global $c_urls; echo $c_urls; ?>">
<img src="images/logo.gif" border="0"></a>
</td>
<td width="380">
<!-- EVENT DISPLAY INCLUSION //-->
<center>
<span class="date">
<?
include "events.php";
?>
</span>
</center>
<!-- TITLE //-->
<p align="center"><font size="4"><b><? echo $title; ?></b></font></p>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="100%">
      <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%" bgcolor="#565693">
        <TBODY>
        <TR>
          <TD height="20" width="100%">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>

<!-- Links inclusion //-->
<td width="100%"><p>
&nbsp;<a class="mnu_link" href="<? global $c_urls; echo $c_urls; ?>">Home</a>
<img src="images/trans.gif" width="30" height="1" border="0">
<?
global $m_search;
if($m_search==1) {
?>
<a class="mnu_link" href="search.php">Search</a><a class="mnu_link">
<img src="images/trans.gif" width="30" height="1" border="0"></a>

<?
}
?>
</td>
</tr>
</table>
</TD>
</TR></TBODY></TABLE></td>
</tr>
<tr>
<td width="729" height="2" bgcolor="#B3B3B3">
</td>
</tr>
<tr>
<td width="729" valign="top">
<table border="0" cellpadding="0" cellspacing="0" width="721">
<tr>
<td width="132" height="109" bgcolor="#F9F9F9" valign="top">
<!-- CALANDER SCRIPT INCLUSION //-->
<?

// Include the calander script
include "calendar.php";
calendar(); // This function shows the calander

// Load the Links table
// The main template can be split into any number of
// sub templates for ease of use. An example is the separate
// template file for the links itself.

include "links.template.php";
?>

<table border="0" cellpadding="2" cellspacing="0" width="126">
<tr>
<td width="18" height="19">
</td>
<td width="108" height="19"><p><b>Last 5 posts</b></p>
</td>
</tr>
<tr>
<td width="18">
</td>
<td width="108">

<!-- LAST 5 POSTS INCLUSION //-->
<?
echo getPosts(5,"<img src=images/box5.gif border=0>&nbsp;","<br><img src=# width=1 height=13>");
?>

<br>

<!-- URL TO RSS script //-->
<a href="<? global $c_urls; echo $c_urls; ?>/rss.php">
<img src="images/valid-rss.gif" border="0" alt="Get RSS Feeds for this blog"></a>

<br><br>
</td>
</tr>
</table>
</td>
<td width="1" height="109" bgcolor="#AAAAAA">
</td>
<td width="588" height="109" valign="top">
<table border="0" cellpadding="10" cellspacing="0" width="566">
<tr>
<td width="552">

<!-- Header ends here //-->