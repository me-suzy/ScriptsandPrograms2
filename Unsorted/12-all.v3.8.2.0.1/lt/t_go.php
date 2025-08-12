<?PHP
if ($e != "subscriberemailec"){
if ($l != "open"){
$l = str_replace("|Q|", "?", $l);
$l = str_replace("|E|", "=", $l);
$l = str_replace("|A|", "&", $l);
header("Location: $l");
}
@include("../engine.inc.php");
$numresults=mysql_query ("SELECT * FROM Links
                         WHERE nl LIKE '$i'
						 AND link LIKE '$l'
						 LIMIT 1
                       ");
$numrows=mysql_num_rows($numresults);
$b = mysql_fetch_array($numresults);
$email = base64_decode ($e);
if ($numrows == 0){
mysql_query ("INSERT INTO Links (nl, link) VALUES ('$i' ,'$l')");  
		$numresults3=mysql_query ("SELECT * FROM Links
								 WHERE nl LIKE '$i'
								 AND link LIKE '$l'
								 LIMIT 1
							   ");
		$idt = mysql_fetch_array($numresults3);
		$idtt = $idt["id"];
		$nltt = $idt["nl"];
			$currenttime = date("H:i:s");
			$today = date("Ymd");
		mysql_query ("INSERT INTO 12all_LinksD (lid, nl, stime, sdate, email, times) VALUES ('$idtt' ,'$nltt' ,'$currenttime' ,'$today' ,'$email' ,'1')");  

}
else {
		$idtt = $b["id"];
		$nltt = $b["nl"];
		$subcheck=mysql_query ("SELECT * FROM 12all_LinksD
								 WHERE lid = '$idtt'
								 AND email = '$email'
							   ");
		$sctn=mysql_num_rows($subcheck);
		$scta = mysql_fetch_array($subcheck);
		$sctid = $scta["id"];
		$sctit = $scta["times"];
		if ($sctn == 0){
			$currenttime = date("H:i:s");
			$today = date("Ymd");
			mysql_query ("INSERT INTO 12all_LinksD (lid, nl, stime, sdate, email, times) VALUES ('$idtt' ,'$nltt' ,'$currenttime' ,'$today' ,'$email' ,'1')");  
		}
		else {
			$times = $sctit + 1;
			mysql_query("UPDATE 12all_LinksD SET times='$times' WHERE (id='$sctid')");
		}
}
exit; 
}
else {
if ($l != "open"){
$l = str_replace("|Q|", "?", $l);
$l = str_replace("|E|", "=", $l);
$l = str_replace("|A|", "&", $l);

header("Location: $l");
}
}
?>