<?
################################
# MySQL Variables - Must be configured first!
$sqlhost = "";
$sqllogin = "";
$sqlpass = "";
$sqldb = "";
$table = "fh_owners"; //Table where messages are stored
$tableposts = "fh_msgs";
$tableonline = "fh_online";
################################

$fhtitle = "NukedWeb Forums";
$fhurl = "http://www.nukedweb.com/forums/"; //ALWAYS end with a trailing slash!

#These variables set the appearance of certain pages, and the design of the site overall.

$headerfile = "../head.php";

$footerfile = "../foot.php";

$tablewidth = "90%";

$bordersize = "1";

$cellspacing = "0";

$cellpadding = "2";

$tablebgcolor = "#3973ce";

$tabletextcolor = "#FFFFFF";

$tablefontname = "Verdana, Arial, Helvetica, sans-serif";

$tablefontsize = "-1";

$adminallowimages = "1";
$allowedtags = "<b><i><u><s><a>"; // These tags are allowed, all others are stripped from posts.
$maxsize = "80000";
$onlineuserexpireminutes = "5"; // # of minutes ago one user last viewed the tagboard. This calculates who's online (5 minutes recommended).

function verifylogin($email,$password){

	global $table;

	$sql = "select id from $table where email='$email' and password='$password'";

	$result = mysql_query($sql);

	$numrows = mysql_num_rows($result);

	if ($numrows!=0) {
		$resrow = mysql_fetch_row($result);
		$id = $resrow[0];
		return $id;
	}

	return "";

}

function makeindent($indt){
	for($x=0;$x<$indt;$x++){	
		$brks .= "&nbsp;&nbsp;&nbsp;";
	}
	return $brks;
}

function getreplies($pid,$owner){
	global $tableposts,$table,$ckForumAdminEmail,$ckForumAdminPassword;
	$adminrights = "";
	$sql = "select email from $table where email='$ckForumAdminEmail' and password='$ckForumAdminPassword' and id='$owner'";
	$result = mysql_query($sql) or die("Failed: $sql");
	if (mysql_num_rows($result)!=0) $adminrights = "1";
	$sql = "select id,indent,author,subject,msg,filename from $tableposts where pid='$pid' and owner='$owner'";
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	for($x=0;$x<$numrows;$x++){
		$resrow = mysql_fetch_row($result);
		$mid = $resrow[0];
		$indent = $resrow[1];
		$author = $resrow[2];
		$subject = $resrow[3];
		$msg = $resrow[4];
		$filename = $resrow[5];
		$nb = "";
		for($v=0;$v<$indent;$v++){	
			$nb .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if ($indent=="1") $nb .= "&nbsp;&nbsp;&nbsp;";
		$pichtml = "";
		if ($filename) $pichtml = "&nbsp;<img src='images/attachment.gif'>";
		if ($adminrights=="1") $delhtml = "[<a href='forum.php?id=$owner&deleteid=$mid'>X</a>] ";
		print $nb."$delhtml<a href='show.php?id=$owner&mid=$mid'>$subject</a>$pichtml By $author<br>";
		flush();
		print getreplies($mid,$owner);
		flush();
	}
	return "";
}

function convert_smilies($txt,$list){
	$cnt = count($list);
	for($x=0;$x<$cnt;$x++){
		$lyn = $list[$x];
		if (substr($lyn,0,1)!="#"){
			$lsta = explode("|", $lyn);
			$from = $lsta[0];
			$to = $lsta[1];
			$to = str_replace("\r", "", $to);
			$to = str_replace("\n", "", $to);
			$txt = str_replace($from, "<img src='$to' align='middle'>", $txt);
		}
	}
	return $txt;
}

function convert_profanity($txt,$list){
	$cnt = count($list);
	for($x=0;$x<$cnt;$x++){
		$lyn = $list[$x];
		if (substr($lyn,0,1)!="#"){
			$lsta = explode("|", $lyn);
			$from = $lsta[0];
			$to = $lsta[1];
			$to = str_replace("\r", "", $to);
			$to = str_replace("\n", "", $to);
			$txt = stri_replace($from, $to, $txt);
		}
	}
	return $txt;
}

function stri_replace($search,$replace,$text) {
	$pos=strpos(strtolower($text),strtolower($search));
	if ($pos!==false) $text=substr_replace ( $text, $replace, $pos,strlen($search));
	return $text;
}

$db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("OOps!");
mysql_select_db($sqldb, $db);
?>