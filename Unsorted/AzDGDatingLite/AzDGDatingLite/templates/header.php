<?php
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[1] + $mtime1[0];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 TRANSITIONAL//EN">
<html>
<head>
<title>AzDGDatingLite v1.01</title>
<META name=robots content=all>
<META HTTP-EQUIV="Expires" Content="0">
<meta http-equiv=Content-Type content="text/html; charset=<?php echo $langcharset; ?>">
<meta name=Copyright content="AzDG.com">
<meta name=Author content="AzDG.com">
<META name=description content="dating script from AzDG">
<META name=keywords content="dating script from AzDG">
<style type="text/css">
<!--
a:link
{text-decoration:none;font-size:12px;color:blue}
a:visited
{text-decoration:none;font-size:12px;color:blue}
a:hover
{text-decoration:none;font-size:12px;color:red}
a.menu:link
{font-weight:bold;font-size:11px;
text-decoration:none;color:black}
a.menu:visited
{font-weight:bold;font-size:11px;
text-decoration:none;color:black}
a.menu:hover
{font-weight:bold;font-size:11px;
text-decoration:none;color:red}

td
{
font-family:Verdana,tahoma;
font-size:12px;
}
.mes
{
font-family:Verdana,tahoma;
font-size:12px;
color:black;
font-weight:bold;
test-align:justify;
}
.desc
{
font-family:Verdana,tahoma;
font-size:12px;
color:black;
font-weight:bold;
}

.dat
{
font-family:Verdana,tahoma;
font-size:12px;
color:red;
font-weight:bold;
}
.head
{
font-family:Verdana,tahoma;
font-size:14px;
color:red;
font-weight:bold;
}
.button
{
    font-family:Verdana;
	font-size:12px;
	color:navy;
	background:#FEA9DC;
	border: black;
	border-style: solid;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
    width:100px
}
.input,.textarea,.select
{
    font-family:Verdana;
	font-size:12px;
	color:navy;
	background:#FEC0ED;
	border: black;
	border-style: solid;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
    width:200px
}
.sinput
{
    font-family:Verdana;
	font-size:12px;
	color:navy;
	background:#FEC0ED;
	border: black;
	border-style: solid;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
    width:50px
}
-->
</style>

</head>
<body bgcolor=#FFCAFE leftmargin=0 topmargin=0>
<center><Table Border="0" CellSpacing="0" CellPadding="0">
	<Tr>
		<Td Width="740" Height="96"><Img Src="<?php echo $url; ?>/images/logo.jpg" Border="0" Height="96" Width="740" Alt="DSP Dating"></Td>
	</Tr>
</Table>
<?php
print <<<EOT
<center>
<Table Border="1" CellSpacing="0" CellPadding="2" bgcolor=$color3 bordercolor=black>
	<Tr>
		<Td Width="117" align="center"><a href="$url/index.php?l=$l" class=menu>$lang[25]</a></Td><Td Width="117" align="center"><a href="$url/add.php?l=$l" class=menu>$lang[1]</a></Td><Td Width="117" align="center"><a href="$url/login.php?l=$l" class=menu>$lang[2]</a></Td><Td Width="117" align="center"><a href="$url/search.php?l=$l" class=menu>$lang[3]</a></Td><Td Width="117" align="center"><a href="$url/email.php?l=$l" class=menu>$lang[23]</a></Td><Td Width="117" align="center"><a href="$url/stat.php?l=$l" class=menu>$lang[24]</a></Td></Tr></Table>
EOT;
?>
