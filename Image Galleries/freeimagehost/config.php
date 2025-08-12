<?
################################

# MySQL Variables - Must be configured first!

$sqlhost = "";

$sqllogin = "";

$sqlpass = "";

$sqldb = "";

$tableusers = "ih_users";

$tablepics = "ih_images";

################################


$ihtitle = "FreeImageHost";
$ihurl = "http://www.nukedweb.com/imagehost/";
$numuploadfields = "5";
$uploadfieldcharwidth = "50";
$upfilesfolder = "./upfiles/";

#These variables set the appearance of certain pages, and the design of the site overall.

$headerfile = "../head.php";

$footerfile = "../foot.php";

$tablewidth = "90%";

$bordersize = "1";

$cellspacing = "0";

$cellpadding = "2";

$tablebgcolor = "#507ca0";

$tabletextcolor = "#FFFFFF";

$tablefontname = "Verdana, Arial, Helvetica, sans-serif";

$tablefontsize = "-1";



#Image Limitations
$maximgsize = 70000;
$remove_days = "7";
$remove_views = "100";
$maximagesperaccount = "20";


#Overlay Text Config
$overlay_text = "FreeImageHost";
$overlay_colors = "255 255 255";
$overlay_font = "5"; // (valid values are 1 through 5)
$overlay_x = "";
$overlay_y = "1";


$db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("OOps!");

mysql_select_db($sqldb, $db);

function verifylogin($email,$password){

	global $tableusers;

	$sql = "select id from $tableusers where email='$email' and password='$password'";

	$result = mysql_query($sql);

	$numrows = mysql_num_rows($result);

	if ($numrows!=0) {
		$resrow = mysql_fetch_row($result);
		$id = $resrow[0];
		return $id;
	}

	return "";

}

function numimagesacct($id){
	global $tablepics;

	$sql = "select count(*) from $tablepics where owner='$id'";

	$result = mysql_query($sql);

	$numrows = mysql_num_rows($result);

	$resrow = mysql_fetch_row($result);
	$cnt = $resrow[0];
	return $cnt;
}

function cleanup(){
	global $tableusers,$tablepics,$remove_days,$remove_views,$upfilesfolder;
	if ($remove_views){
		$sql = "select id,owner,filename from $tablepics where views > '$remove_views'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$numrows = mysql_num_rows($result);
		for($x=0;$x<$numrows;$x++){
			$resrow = mysql_fetch_row($result);
			$id = $resrow[0];
			$owner = $resrow[1];
			$filename = $resrow[2];
			$sq2 = "select exempt_views from $tableusers where id='$owner'";
			$reslt2 = mysql_query($sq2) or die("Failed: $sql");
			$resrw2 = mysql_fetch_row($reslt2);
			$exempt = "";
			$exempt = $resrw[0];
			if (!$exempt){
				$sq3 = "delete from $tablepics where id='$id'";
				$reslt3 = mysql_query($sq3) or die("Failed: $sq3");
				$z = unlink($upfilesfolder.$owner."-".$filename);
			}
		}
	}
	if ($remove_days){
		$purgeseconds = $remove_days * 86400;
		$olddate = strftime("%Y-%m-%d %H:%M:%S", time() - $purgeseconds);
		$sql = "select id,owner,filename from $tablepics where uploaded < '$olddate'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$numrows = mysql_num_rows($result);
		for($x=0;$x<$numrows;$x++){
			$resrow = mysql_fetch_row($result);
			$id = $resrow[0];
			$owner = $resrow[1];
			$filename = $resrow[2];
			$sq2 = "select exempt_date from $tableusers where id='$owner'";
			$reslt2 = mysql_query($sq2) or die("Failed: $sql");
			$resrw2 = mysql_fetch_row($reslt2);
			$exempt = "";
			$exempt = $resrw[0];
			if (!$exempt){
				$sq3 = "delete from $tablepics where id='$id'";
				$reslt3 = mysql_query($sq3) or die("Failed: $sq3");
				$z = unlink($upfilesfolder.$owner."-".$filename);
			}
		}



	}

}


?>