<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>Exchange System</title>
  <link rel="stylesheet" href="../style.css">
 </head>
<body bgcolor=FFFFFF class=bodytext link=0 vlink=0 alink=0 text=0>
<?
  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("bots/errbot");
  require("bots/mcbot");
  require("bots/genbot");
  require("bots/grbot");


  if(!isset($sc)) _fatal("Access denied!","Please, authorize before access this page!");

  $db=con_srv();

  $banner = _fetch(_query("select * from banners where id='$cid'"));
  $owner = _fetch(_query("select title from campaigns where id='$banner[cid]'"));


  if($banner[ id ] == "")
  {
?>
	<center><b>Please, choose banner!</b></center>
<?
  }
  else
  {
?>
<table border=0 celspacing=3 cellpadding=3>
<tr><td colspan=2><font size=3><b>Details for banner:</td></tr>
<tr>
	<td>Banner's campaign:</td>
	<td><? echo "$owner[title]" ?></td>
</tr>
<tr>
	<td>Banner type:</td>
	<td><? echo "$banner[type]" ?></td>
</tr>
<?
	if($banner[ type ] ==Image)
	{
?>
<tr>
	<td>Banner Url:</td>
	<td>
		<?
			$url = (strstr($banner[burl],"http://")!=""?"":"http://").$banner[burl];

			echo "<a href=$url target=_blank>$url</a>";
		?>
	</td>
</tr>
<tr>
	<td>Preview:</td>
	<td>
	</td>
</tr>
<tr>
	<td colspan=2>
		<?
			echo "<img src=$url alt='Banner preview'>";
		?>
	</td>
</tr>
<?
	}
	elseif($banner[ type ] ==Flash)
	{
?>
<tr>
	<td>Banner Url:</td>
	<td>
		<?
			$url = (strstr($banner[burl],"http://")!=""?"":"http://").$banner[burl];
			echo "<a href=$url target=_blank>$url</a>";
			$sz = split("x",$banner[isize]);
			$w = $sz[0];$h = $sz[1];

		?>
	</td>
</tr>
<tr>
	<td>Preview  :</td>
	<td>
	</td>
</tr>
<tr>
	<td colspan=2>
		<?
echo " <SCRIPT>document.writeln('<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=\"$w\" height=\"$h\">');\n";
			echo "document.writeln('<param name=movie value=\"$url\">');\n";
			echo "document.writeln('<param name=quality value=high><param name=\"SCALE\" value=\"noborder\">');\n";
			echo "document.writeln('<embed src=\"$url\" quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"$w\" height=\"$h\" scale=\"noborder\">');\n";
			echo "document.writeln('</embed>');\n";
			echo "document.writeln('</object>');\n </SCRIPT>";


		?>
	</td>
</tr>
<?
	} else
	{
?>
<tr>
	<td>Banner's text:</td>
	<td>
	</td>
</tr>
<tr>
	<td colspan=2>
		<?
			echo "$banner[btext]";
		?>
	</td>
</tr>
<?
	}
?>
<tr>
	<td colspan=2><a href=# onClick="window.close();">Close window</a></td>
</tr>
</table>
<?
  }
?>

<?
  dc_srv($db);
?>
</body>
</html>
