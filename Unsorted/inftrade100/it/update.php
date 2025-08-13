<?php
function updatehour($thour,$lhour,$lday,$lmon,$lyear) {

$rhour = $lhour;
if( $thour > 23 ) { $thour=0; }

while( $thour != $rhour ) {
	$rhour++;
	if( $rhour > 23 ) {
		$rhour = 0; 
		updateday($lday,$lmon,$lyear);
		}
	$result = mysql_query("UPDATE sites SET clk$rhour='0', in$rhour='0', out$rhour='0'");
	$result = mysql_query("UPDATE links SET clk$rhour='0'");
	}
createtoplists();
}

function updateday($lday, $lmon, $lyear) {

$mons = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

if( $lday < 10 ) { $lday = "0".$lday; }

$query = "SELECT (sum(in0)+sum(in1)+sum(in2)+sum(in3)+sum(in4)+sum(in5)+sum(in6)+sum(in7)+sum(in8)+sum(in9)+sum(in10)+sum(in11)+sum(in12)+sum(in13)+sum(in14)+sum(in15)+sum(in16)+sum(in17)+sum(in18)+sum(in19)+sum(in20)+sum(in21)+sum(in22)+sum(in23)) AS hitsin, (sum(out0)+sum(out1)+sum(out2)+sum(out3)+sum(out4)+sum(out5)+sum(out6)+sum(out7)+sum(out8)+sum(out9)+sum(out10)+sum(out11)+sum(out12)+sum(out13)+sum(out14)+sum(out15)+sum(out16)+sum(out17)+sum(out18)+sum(out19)+sum(out20)+sum(out21)+sum(out22)+sum(out23)) AS hitsout, (sum(clk0)+sum(clk1)+sum(clk2)+sum(clk3)+sum(clk4)+sum(clk5)+sum(clk6)+sum(clk7)+sum(clk8)+sum(clk9)+sum(clk10)+sum(clk11)+sum(clk12)+sum(clk13)+sum(clk14)+sum(clk15)+sum(clk16)+sum(clk17)+sum(clk18)+sum(clk19)+sum(clk20)+sum(clk21)+sum(clk22)+sum(clk23)) AS clicks FROM sites"; 
$result = mysql_query($query) or die(mysql_error());
$stats = mysql_fetch_array($result, MYSQL_ASSOC);

$hitsin = $stats["hitsin"];
$hitsout = $stats["hitsout"];
$clicks = $stats["clicks"];

$datum = "$lday-{$mons[$lmon]}-$lyear";

$result = mysql_query("INSERT INTO history (datum, hitsin, hitsout, clicks) VALUES ('$datum','$hitsin','$hitsout','$clicks')") or die(mysql_error());
$result = mysql_query("DELETE FROM links WHERE (clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23)=0") or die(mysql_error());
$result = mysql_query("DELETE FROM visitlog WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(tid) > 86400") or die(mysql_error());

$result = mysql_query("SELECT minin,mininact,minprod,minprodact FROM settings") or die(mysql_error());
$minsett = mysql_fetch_array($result, MYSQL_ASSOC);

if( $minsett['mininact'] == 1 ) {
	$result = mysql_query("DELETE FROM sites WHERE (in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23) < {$minsett['minin']} AND siteid>1 AND status<3") or die(mysql_error());
	}
else {
	$result = mysql_query("UPDATE sites SET status=5 WHERE (in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23) < {$minsett['minin']} AND siteid>1 AND status<3") or die(mysql_error());
	}

if( $minsett['minprodact'] == 1 ) {
	$result = mysql_query("DELETE FROM sites WHERE ((clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23)/(in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23)*100)<{$minsett['minprod']} AND (in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23)>0 AND siteid>1 AND status<3") or die(mysql_error());
	}
else {
	$result = mysql_query("UPDATE sites SET status=5 WHERE ((clk0+clk1+clk2+clk3+clk4+clk5+clk6+clk7+clk8+clk9+clk10+clk11+clk12+clk13+clk14+clk15+clk16+clk17+clk18+clk19+clk20+clk21+clk22+clk23)/(in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23)*100)<{$minsett['minprod']} AND (in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23)>0 AND siteid>1 AND status<3") or die(mysql_error());
	}

}

function createtoplists() {

$toplistdir = "../ittoplist/";
if( is_dir($toplistdir)) {
	if( !$dh = opendir($toplistdir) ) { return; }
	while (($file = readdir($dh)) !== false) {
		if( strpos($file,".top") !== false ) {
			$toplistfiles[] = $file;
			}
		}
	$query = "SELECT sitedomain,sitename,sitedesc,status,(in0+in1+in2+in3+in4+in5+in6+in7+in8+in9+in10+in11+in12+in13+in14+in15+in16+in17+in18+in19+in20+in21+in22+in23) AS hitsin FROM sites WHERE siteid>1 AND status<3 ORDER BY hitsin DESC"; 
	$result = mysql_query($query) or die(mysql_error());
	while( $line = mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$topsites[] = $line;
		}

	for( $i=0; $i<count($toplistfiles); $i++ ) {
		$ctoplistfile = $toplistdir.$toplistfiles[$i];
		$ctoplistfilehtml = str_replace(".top",".html",$ctoplistfile);

		$toptempl = file_get_contents($ctoplistfile);
		$toptempl = preg_replace("/[\$x]site(\d+)/e", "\$topsites['\\1'-1]['sitename']", $toptempl);
		$toptempl = preg_replace("/[\$x]domain(\d+)/e", "\$topsites['\\1'-1]['sitedomain']", $toptempl);
		$toptempl = preg_replace("/[\$x]desc(\d+)/e", "\$topsites['\\1'-1]['sitedesc']", $toptempl);
		$toptempl = preg_replace("/[\$x]in(\d+)/e", "\$topsites['\\1'-1]['hitsin']", $toptempl);
		$fh = fopen ($ctoplistfilehtml, "w");
		fwrite($fh, $toptempl);
		fclose($fh);
		}
	}
}
?>