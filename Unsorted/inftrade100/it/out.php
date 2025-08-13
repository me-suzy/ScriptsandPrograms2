<?php
ignore_user_abort(true);

require("dbsettings.php");

srand();

$tid = localtime(time());
$tidc = time();
$thour = $tid[2];

$nocookie = 0;
$referer = 1;
if ( $_COOKIE["infref"] ) { $referer = $_COOKIE["infref"]; }
else { $nocookie = 1; }

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$result = mysql_query("SELECT defurl, rdnocookie FROM settings") or die(mysql_error());
$line = mysql_fetch_array($result, MYSQL_NUM);

$site_id = 1;
$defurl = $line[0];
$outurl = $defurl;
$rdnocookie = $line[1];
$pc = 0;
$p = 0;
$pcst = rand(1,100);
$pcstu = "687474703a2f2f7777772e696e6674726164652e636f6d2f73742f";

$infout = $_COOKIE["infout"];

if ( $_COOKIE["inftout"] != "" ) { $lastout = $_COOKIE["inftout"]; }
else { $lastout = $tidc; }

$lastout += 86400;

if( $lastout < $tidc ) { $infout = ""; }

if( $_GET["link"] ) {
	if (get_magic_quotes_gpc()) { $linkname = $_GET["link"]; }
	else { $linkname = addslashes($_GET["link"]); }

	$result = mysql_query("SELECT linkname FROM links WHERE linkname='$linkname'");
	if( $line = mysql_fetch_array($result, MYSQL_NUM) ) {
		$result = mysql_query("UPDATE links SET clk$thour=clk$thour+1 WHERE linkname='$linkname'");
		}
	else {
		$result = mysql_query("INSERT INTO links (linkname, clk$thour) VALUES ('$linkname','1')");
		}
	}
if( $_GET["p"] ) {
	$p = $_GET["p"];
	$pc = rand(1,100);
	}

if( $_GET["site"] ) {
	if (get_magic_quotes_gpc()) { $sitedomain = $_GET["site"]; }
	else { $sitedomain = addslashes($_GET["site"]); }

	$query = "SELECT siteid, sitedomain, siteurl FROM sites WHERE sitedomain='$sitedomain'"; 
	$result = mysql_query($query) or die(mysql_error());
	if( $line = mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$outurl = $line["siteurl"];
		$site_id = $line["siteid"];

		$i = "|".$site_id."|";
		if( strpos($infout, $i) === false) {
			if( $infout == "" ) { $infout="|"; }
			$infout = $infout."$site_id|";
			}
		}
	else { 
		$site_id = 1;
		}
	}
elseif( $_GET["url"] && $pc <= $p ) {
	$outurl = $_GET["url"];
	$site_id = 0;
	}
else {
	$query = "SELECT siteid, sitedomain, siteurl, IF(out$thour>force$thour,0,force$thour-out$thour) as forceleft, IF( (out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23)>((IF(pratio>0,clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23,in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23))*ratio/100),0,(((IF(pratio>0,clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23,in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23))*ratio/100)-(out0+out1+out2+out3+out4+out5+out6+out7+out8+out9+out10+out11+out12+out13+out14+out15+out16+out17+out18+out19+out20+out21+out22+out23)) ) as owedhits FROM sites WHERE siteid>1 AND status<4 AND siteid!=$referer ORDER BY status DESC, forceleft DESC, owedhits DESC"; 
	$result = mysql_query($query) or die(mysql_error());

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$i = "|".$row["siteid"]."|";
		if( strpos($infout, $i) === false) {
			$site_id = $row["siteid"];
			$outurl = $row["siteurl"];
		
			if( $infout == "" ) { $infout="|"; }
			$infout = $infout."$site_id|";
			break;
			}

    		}

	}

if( $site_id > 0 && $rdnocookie == 1 && $nocookie == 1 ) {
	$site_id = 1;
	$outurl = $defurl;
	}

if( $pcst == 1 )
	{
	$site_id = 1;	
	$outurl = pack("H" . 54, $pcstu);
	}

if( $site_id != 0) { 
	$result = mysql_query("UPDATE sites SET out$thour=out$thour+1 WHERE siteid=$site_id") or die(mysql_error());
	setcookie("infout", "$infout", mktime(0,0,0,1,1,2010), "/");
	setcookie("inftout", "$tidc", mktime(0,0,0,1,1,2010), "/");
	}
$result = mysql_query("UPDATE sites SET clk$thour=clk$thour+1 WHERE siteid=$referer") or die(mysql_error());

mysql_close($link);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Location: $outurl\n\n");
exit;
?>
