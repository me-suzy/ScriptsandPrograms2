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

  $campaign = _fetch(_query("select * from campaigns where id='$cid'"));
  $owner = _fetch(_query("select login, fname, lname from members where id='$campaign[user_id]'"));


  if($campaign[ id ] == "")
  {
?>
	<center><b>Please, choose campaign!</b></center>
<?
  }
  else
  {
?>
<table border=0 celspacing=3 cellpadding=3>
<tr><td colspan=2><font size=3><b>Details for campaign:</td></tr>
<tr>
	<td>Campaign's owner:</td>
	<td><? echo "$owner[login] - $owner[fname] $owner[lname]" ?></td>
</tr>
<tr>
	<td>Name:</td>
	<td><? echo $campaign[title] ?></td>
</tr>
<tr>
	<td>Url:</td>
	<td>
		<?
			$url = (strstr($campaign[url],"http://")!=""?"":"http://").$campaign[url];
			echo "<a href=$url target=_blank>$url</a>";
		?>
	</td>
<tr>
	<td>Group:</td>
	<td><? echo ppath_wl($campaign[ group_id ]) ?></td>
</tr>
<tr>
	<td>Title:</td>
	<td><? echo $campaign[ title ] ?></td>
</tr>
<tr>
	<td>Keywords:</td>
	<td><? echo $campaign[ ikeys ] ?></td>
</tr>
<tr>
	<td>Registration date:</td>
	<td><? echo date("d M Y   H:i:s", $campaign[ rdate ]) ?></td>
</tr>
<tr>
	<td>Another campaign groups:</td>
	<td><?
				$gr = _query("select * from camp_groups where cid='$campaign[id]'");

				if(_empty($gr))
				{
					echo "No additional groups for this campaign";
				}
				else
				{
					while($fg = _fetch($gr))
					{
						echo ppath_wl($fg[ guid ])."<br>";
					}
				}
	?></td>
</tr>
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
