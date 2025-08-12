<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");
}else{




$bgimage=$_FILES['new_docbgim']['tmp_name'];
$bgimage_name=$_FILES['new_docbgim']['name'];
	if ($bgimage_name!="")
	{
	$extension=explode(".",$bgimage_name);
	$num_els=count($extension);
	$ext_bg=$extension[$num_els - 1];
	$newbgfile="../images/temp_bgimage";
	/* if (file_exists($newbgfile)) unlink($newbgfile); */
	move_uploaded_file ($bgimage,$newbgfile);
	}








/* Set the background values */

if ($newbgfile!="" && !isset($replace_bg))
{if (file_exists("../images/docbgimage.$ext_bg")) unlink ("../images/docbgimage.$ext_bg");
rename("../images/temp_bgimage","../images/docbgimage.$ext_bg");
$docbgim="./images/docbgimage.$ext_bg";
} elseif ($docbgim!="" && !isset($replace_bg)) {
if (file_exists("../images/temp_bgimage")) unlink("../images/temp_bgimage");
} else {
if (file_exists("../images/temp_bgimage")) unlink("../images/temp_bgimage");
$docbgim="";
}


/* Update the DB */

$result=mysql_query("SELECT StyleId FROM style WHERE active='1' limit 0,1");
$row=mysql_fetch_row($result);
$styleid=$row[0];


mysql_query("UPDATE style SET docbgcol='$docbgcol', docbgim='$docbgim', docbgrep='$docbgrep', docbgpos='$docbgpos', docborstyle='$docborstyle', docborw='$docborw', docborcol='$docborcol' WHERE StyleId='$styleid'") or die (mysql_error());


/* Create a new css file! */

$result=mysql_query("SELECT * FROM style WHERE active='1' limit 0,1");
$row=mysql_fetch_row($result);

$bgcol=$row[2];
$bgim=$row[3];
$bgrep=$row[4];
$bgpos=$row[5];
$font=$row[6];
$fsize=$row[7];
$fcol=$row[8];
$hsize=$row[9];
$hcol=$row[10];
$hbgcol=$row[11];
$hbgim=$row[12];
$hbgrep=$row[13];
$hbgpos=$row[14];
$docbgcol=$row[15];
$docbgim=$row[16];
$docbgrep=$row[17];
$docbgpos=$row[18];
$docborstyle=$row[19];
$docborw=$row[20];
$docborcol=$row[21];
$mmbgcol=$row[22];
$mmborstyle=$row[23];
$mmborw=$row[24];
$mmborcol=$row[25];
$mmfcol=$row[26];
$smbgcol=$row[27];
$smborstyle=$row[28];
$smborw=$row[29];
$smborcol=$row[30];
$smfcol=$row[31];
$ah=$row[32];
$al=$row[33];
$av=$row[34];

/* Define the style sheet elements */

$body="body {margin:5px; background-color:$bgcol; ";
if ($bgim!="") {$body.="background-image:url('$bgim'); background-repeat:$bgrep; background-position:$bgpos;";}
$body.="vertical-align:top}";

$table="table {border-collapse:collapse}";

$td="td {font-family: $font; font-size:$fsize%; color:$fcol; border-width:0px}";
$head="#head td {font-size:$hsize%; color:$hcol; background-color: $hbgcol;";
if ($hbgim!="") $head.="background-image:url('$hbgim'); background-repeat:$hbgrep; background-position: $hbgpos;";
$head.="}";

$head.="\n #head a {color:$hcol;}";
$head.="\n #head a:hover {color:$hcol; text-decoration:none}";
$head.="\n #head a:link {color:$hcol; text-decoration:none}";
$head.="\n #head a:visited {color:$hcol; text-decoration:none}";



$doctable=".indocument {border: $docborstyle $docborw $docborcol; background-color:$docbgcol;";
if ($docbgim!="") $doctable.="background-image:url('$docbgim'); background-repeat:$docbgrep; background-position: $docbgpos;";
$doctable.="}";

$doctd=".document {border: $docborstyle $docborw $docborcol}";

$mmenutd="#mainmenu td {background-color: $mmbgcol; border: $mmborstyle $mmborw $mmborcol}";

$mmenua="#mainmenu a {color: $mmfcol}";

$mmenuact="#mainmenu .active {font-weight:bold}";

$smenutd="#sidemenu td {background-color: $smbgcol; border: $smborstyle $smborw $smborcol}";

$smenua="#sidemenu a {color: $smfcol}";
$smenuact="#sidemenu .active {font-weight:bold}";

$a="a {font-family:$font; font-size:100%; text-decoration:none}";
$ah="a:hover {color:$ah; text-decoration:underline}";
$al="a:link {color:$al}";
$av="a:visited {color:$av}";


$thesheet="$body \n $table \n $td \n $head \n $doctable \n $doctd \n $mmenutd \n $mmenua \n $mmenuact \n $smenutd \n $smenua \n $smenuact \n $a \n $ah \n $al \n $av";

$handle=fopen("../style.css","w");
fwrite($handle,$thesheet);
fclose($handle);

/* styleedit */

$body="body {margin:5px; background-color:$docbgcol; ";
if ($docbgim!="") {$docbgim=str_replace("./","../",$docbgim); 
	$body.="background-image:url('$docbgim'); background-repeat:$docbgrep; background-position:$docbgpos;";}
$body.="vertical-align:top; font-family: $font; font-size: $fsize%; color:$fcol}";

$table="table {border-collapse: collapse}";

$a="a {font-family:$font; font-size:100%; text-decoration:none; color:$al}";

$theeditsheet="$body \n $table \n $a \n $ahe \n $ale \n $ave";



$handle=fopen("./styleedit.css","w");
fwrite($handle,$theeditsheet);
fclose($handle);


Header("Location:admin.php");



}
?>
