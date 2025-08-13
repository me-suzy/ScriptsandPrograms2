<html>
<head>
<title>infTrade Admin</title>
<style>
body {font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
td {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : xx-small; color : #000000; font-weight : normal; text-decoration : none;}
a:link { text-decoration : none;}
a:visited { text-decoration : none;}
a:hover { text-decoration : underline;}
h2 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : large; color : #000000; font-weight : bold; text-decoration : none;}
.but {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; color : #000000; font-weight: normal; background-color: #E8E8E8; border: 1px solid #000000; height: 21; cursor: hand; }
.radio1 { color : #FFFFFF; background-color: #000040; cursor : hand; height:14}
.hh {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : small; color : #000000; font-weight : bold; text-decoration : none;}
.men1 {font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
.tradelink {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : xx-small; color : #FFFFFF; font-weight : normal; text-decoration : none;}
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<div align="center">
<table cellspacing="0" cellpadding="0"><tr><td>
<table width="100%">
<tr>
<td colspan="2"><hr width="100%" size="1" color="#000000" noshade></td>
</tr>
<tr><td>
<p class="men1">| <a href="index.php<?php if($_GET["s"]) { print "?s={$_GET['s']}"; } ?>"><strong>Refresh</strong></a> | 
<a href="#" onClick="window.open('admin.php?addsite=1', '_blank', 'width=470,height=280,resizable=0,scrollbars=0,status=0');"><strong>Add Site</strong></a> | 
<a href="#" onClick="window.open('admin.php?settings=1', '_blank', 'width=600,height=410,resizable=0,scrollbars=0,status=0');"><strong>Settings</strong></a> | 
<a href="#" onClick="window.open('admin.php?blacklist=1', '_blank', 'width=350,height=450,resizable=0,scrollbars=1,status=0');"><strong>BlackList</strong></a> | 
<a href="#" onClick="window.open('admin.php?updatetop=1', '_blank', 'width=450,height=190,resizable=0,scrollbars=1,status=0');"><strong>Update Toplists</strong></a> |
<a href="#" onClick="window.open('admin.php?history=1', '_blank', 'width=560,height=500,resizable=0,scrollbars=1,status=0');"><strong>History</strong></a> | 
<a href="#" onClick="window.open('admin.php?daystats=1', '_blank', 'width=820,height=400,resizable=0,scrollbars=1,status=0');"><strong>Last 24H Stats</strong></a> |
</p>
</td>
<td align="right"><p class="hh">infTrade v1.00</p></td>
</tr>
<tr>
<td colspan="2"><hr width="100%" size="1" color="#000000" noshade></td>
</tr>
</table>

<table cellspacing="1" cellpadding="3" bgcolor="#000040">
<tr>
<td rowspan="2" valign="bottom" bgcolor="#C0C0C0"><font color="#000000">S.</font></td>
<td rowspan="2" valign="bottom" bgcolor="#C0C0C0" width="140"><font color="#000000">Site</font></td>
<td colspan="5" align="center" bgcolor="#C0C0C0"><font color="#000000">This Hour</font></td>
<td colspan="6" align="center" bgcolor="#C0C0C0"><font color="#000000">Last 24 Hours</font></td>
<td width="35" rowspan="2" bgcolor="#C0C0C0">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#C0C0C0" align="right" width="45"><font color="#000000">In</font></td>
<td bgcolor="#C0C0C0" align="right" width="45"><font color="#000000">Out</font></td>
<td bgcolor="#C0C0C0" align="right" width="45"><font color="#000000">Click</font></td>
<td bgcolor="#C0C0C0" align="center" width="45"><font color="#000000">Prod</font></td>
<td bgcolor="#C0C0C0" align="right" width="40"><font color="#000000">Force</font></td>
<td bgcolor="#C0C0C0" align="right" width="45"><font color="#000000">In</font></td>
<td bgcolor="#C0C0C0" align="right" width="45"><font color="#000000">Out</font></td>
<td bgcolor="#C0C0C0" align="right" width="45"><font color="#000000">Click</font></td>
<td bgcolor="#C0C0C0" align="center" width="45"><font color="#000000">Prod</font></td>
<td bgcolor="#C0C0C0" align="center" width="40"><font color="#000000">R Set</font></td>
<td bgcolor="#C0C0C0" align="center" width="40"><font color="#000000">R Out</font></td>
</tr>



<?php
require("../it/dbsettings.php");
require("../it/update.php");

$tidc = time();
$tid = localtime(time());
$thour = $tid[2];

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());


$result = mysql_query("SELECT lastupdate FROM updateinfo") or die(mysql_error());
$line = mysql_fetch_array($result, MYSQL_NUM);
$lutid = localtime($line[0]);
if( $lutid[2] != $thour ) {
	updatehour($thour,$lutid[2],$lutid[3],$lutid[4],$lutid[5]+1900);
	$result = mysql_query("UPDATE updateinfo SET lastupdate='$tidc'");
	}

$query = "SELECT siteid, sitedomain, siteurl, sitename, sitedesc, status, ratio, in$thour AS hourin, out$thour AS hourout, clk$thour AS hourclk, force$thour AS hourforce, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23 AS totalin, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS totalout, clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23 AS totalclk FROM sites WHERE siteid=1"; 
$result = mysql_query($query) or die(mysql_error());
$line = mysql_fetch_array($result, MYSQL_ASSOC);


$site_totalin = $line["totalin"];
$site_hourin = $line["hourin"];
$site_totalout = $line["totalout"];
$site_hourout = $line["hourout"];
$site_totalclk = $line["totalclk"];
$site_hourclk = $line["hourclk"];
$site_hourforce = $line["hourforce"];
if ( $site_hourin > 0 ) { $site_hourprod = (int)(($site_hourclk * 100) / $site_hourin) ."%"; } else { $site_hourprod = "-"; }
if ( $site_totalin > 0 ) { $site_totalprod = (int)(($site_totalclk * 100) / $site_totalin) ."%"; } else { $site_totalprod = "-"; }
if ( $site_totalin > 0 ) { $site_rout = (int)(($site_totalout * 100) / $site_totalin) ."%"; } else { $site_rout = "-"; }

$all_hourin = $site_hourin;
$all_hourout = $site_hourout;
$all_hourclk = $site_hourclk;
$all_totalin = $site_totalin;
$all_totalout = $site_totalout;
$all_totalclk = $site_totalclk;

print <<<END
<tr>
<td align="center" bgcolor="#000080">&nbsp;</td>
<td bgcolor="#000080"><font color="#FFFFFF">NoRef/Def.Url</font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF">$site_hourin</font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF">$site_hourout</font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF">$site_hourclk</font></td>
<td align="center" bgcolor="#000080"><font color="#FFFFFF">$site_hourprod</font></td>
<td align="right" bgcolor="#000080">&nbsp;</td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF">$site_totalin</font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF">$site_totalout</font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF">$site_totalclk</font></td>
<td align="center" bgcolor="#000080"><font color="#FFFFFF">$site_totalprod</font></td>
<td align="center" bgcolor="#000080">&nbsp;</td>
<td align="center" bgcolor="#000080">&nbsp;</td>
<td align="center" bgcolor="#000080">&nbsp;</td>
</tr>
END;

$sortarr = array('sitedomain','totalin DESC','totalout DESC','totalclk DESC','prod DESC','hourin DESC','hourout DESC','hourclk DESC');
$sortby = "sitedomain";

if( $_GET["s"] ) {
	$sortby = $sortarr[$_GET['s']];
	}

$query = "SELECT siteid, sitedomain, siteurl, sitename, sitedesc, status, ratio, in$thour AS hourin, out$thour AS hourout, clk$thour AS hourclk, force$thour AS hourforce, in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23 AS totalin, out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23 AS totalout, clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23 AS totalclk, IFNULL((clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23)/(in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23),0) AS prod FROM sites WHERE siteid>1 ORDER BY status, $sortby, sitedomain"; 
$result = mysql_query($query) or die(mysql_error());

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $bgcolor = "#008000";
	if ( $line["status"] == 1 ) { $bgcolor = "#00FF00"; }
	elseif ( $line["status"] == 5 ) { $bgcolor = "#800000"; }
	elseif ( $line["status"] == 6 ) { $bgcolor = "#0080C0"; }

	$site_id = $line["siteid"];
	$site_url = $line["siteurl"];
	$site_domain = $line["sitedomain"];
	$site_ratio = $line["ratio"];
	$site_id = $line["siteid"];
	$site_totalin = $line["totalin"];
	$site_hourin = $line["hourin"];
	$site_totalout = $line["totalout"];
	$site_hourout = $line["hourout"];
	$site_totalclk = $line["totalclk"];
	$site_hourclk = $line["hourclk"];
	$site_hourforce = $line["hourforce"];
	if ( $site_hourin > 0 ) { $site_hourprod = (int)(($site_hourclk * 100) / $site_hourin) ."%"; } else { $site_hourprod = "-"; }
	if ( $site_totalin > 0 ) { $site_totalprod = (int)(($site_totalclk * 100) / $site_totalin) ."%"; } else { $site_totalprod = "-"; }
	if ( $site_totalin > 0 ) { $site_rout = (int)(($site_totalout * 100) / $site_totalin) ."%"; } else { $site_rout = "-"; }

	$all_hourin += $site_hourin;
	$all_hourout += $site_hourout;
	$all_hourclk += $site_hourclk;
	$all_totalin += $site_totalin;
	$all_totalout += $site_totalout;
	$all_totalclk += $site_totalclk;

print <<<END
<tr>
<td bgcolor="$bgcolor">&nbsp;</td>
<td bgcolor="#E8E8E8"><a href="$site_url" target="_blank"><font color="#000000">$site_domain</font></a></td>
<td align="right" bgcolor="#E8E8E8">$site_hourin</td>
<td align="right" bgcolor="#E8E8E8">$site_hourout</td>
<td align="right" bgcolor="#E8E8E8">$site_hourclk</td>
<td align="center" bgcolor="#E8E8E8">$site_hourprod</td>
<td align="right" bgcolor="#E8E8E8">$site_hourforce</td>
<td align="right" bgcolor="#E8E8E8">$site_totalin</td>
<td align="right" bgcolor="#E8E8E8">$site_totalout</td>
<td align="right" bgcolor="#E8E8E8">$site_totalclk</td>
<td align="center" bgcolor="#E8E8E8">$site_totalprod</td>
<td align="center" bgcolor="#E8E8E8">$site_ratio%</td>
<td align="center" bgcolor="#E8E8E8">$site_rout</td>
<td align="center" bgcolor="#E8E8E8"><a href="#" onClick="window.open('admin.php?editsite=1&siteid=$site_id', '_blank', 'width=550,height=510,status=0,scrollbars=0,resizable=0');"><img src="iconedit.gif" alt="Edit" border="0" width="14" height="14"></a>
<a href="#" onClick="window.open('admin.php?statsite=$site_id', '_blank', 'width=750,height=520,status=0,scrollbars=0,resizable=0');"><img src="iconstats.gif" alt="Stats" border="0" width="14" height="14"></a>
<a href="#" onClick="window.open('admin.php?delsite=$site_id', '_blank', 'width=450,height=190,status=0,scrollbars=0,resizable=0');"><img src="icondel.gif" alt="Delete" border="0" width="14" height="14"></a></td>
</tr>
END;
	}

if ( $all_hourin > 0 ) { $all_hourprod = (int)(($all_hourclk * 100) / $all_hourin) ."%"; } else { $all_hourprod = "-"; }
if ( $all_totalin > 0 ) { $all_totalprod = (int)(($all_totalclk * 100) / $all_totalin) ."%"; } else { $all_totalprod = "-"; }
if ( $all_totalin > 0 ) { $all_rout = (int)(($all_totalout * 100) / $all_totalin) ."%"; } else { $all_rout = "-"; }

print <<<END
<tr>
<td align="center" bgcolor="#000080">&nbsp;</td>
<td bgcolor="#000080"><font color="#FFFFFF"><strong>TOTAL</strong></font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_hourin</strong></font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_hourout</strong></font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_hourclk</strong></font></td>
<td align="center" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_hourprod</strong></font></td>
<td align="right" bgcolor="#000080">&nbsp;</td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_totalin</strong></font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_totalout</strong></font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_totalclk</strong></font></td>
<td align="right" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_totalprod</strong></font></td>
<td align="center" bgcolor="#000080">&nbsp;</td>
<td align="center" bgcolor="#000080"><font color="#FFFFFF"><strong>$all_rout</strong></font></td>
<td align="center" bgcolor="#000080">&nbsp;</td>
</tr>
END;

mysql_free_result($result);
mysql_close($link);
?>



</table>
<br><br>
<table width="100%"><tr><td valign="top">
<table cellspacing="1" cellpadding="4">
<tr><td width="10" bgcolor="#008000">&nbsp;</td><td><font color="#000000">Normal</font></td></tr>
<tr><td width="10" bgcolor="#00FF00">&nbsp;</td><td><font color="#000000">High Priority</font></td></tr>
<tr><td width="10" bgcolor="#800000">&nbsp;</td><td><font color="#000000">Paused</font></td></tr>
<tr><td width="10" bgcolor="#0080C0">&nbsp;</td><td><font color="#000000">Unreviewed</font></td></tr>
</td></tr></table>
</td><td valign="top" align="center">
<table cellpadding="0" border="0" cellspacing="0"><tr><td>
Current Server Time:
<?php echo date ("D M d - h:i:s A"); ?>
<br><br>For support visit: <a href="http://www.inftrade.com/forum/" target="_blank">http://www.inftrade.com/forum/</a>
<br><br>Latest updates can be found at: <a href="http://www.inftrade.com/" target="_blank">http://www.inftrade.com/</a>
<br><br>Copyright &copy; 2003-2004 by infTrade.com. All Rights Reserved.
</td></tr></table>
</td>
<td align="right" valign="top">
<form action="index.php" method="get">
<select name="s">
<option value="0">Domain</option>
<option value="1">Total In</option>
<option value="2">Total Out</option>
<option value="3">Total Clicks</option>
<option value="4">Total Prod</option>
<option value="5">Hour In</option>
<option value="6">Hour Out</option>
<option value="7">Hour Clicks</option>
</select>
<input type="submit" value="Sort" class="but">
</form>
</td>

</tr></table>
<p></p>

</div>   
</body>
</html>

