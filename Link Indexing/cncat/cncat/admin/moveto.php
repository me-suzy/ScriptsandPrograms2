<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/
error_reporting(E_ALL & ~E_NOTICE);
$ADLINK="";

include "auth.php";


function ChgCount($cid,$count) {
	GLOBAL $db;

	do {
		$q=mysql_query("UPDATE ".$db["prefix"]."cat SET count=count+$count WHERE cid=$cid;") or die(mysql_error());
		$r=mysql_query("SELECT parent FROM ".$db["prefix"]."cat WHERE cid='$cid';") or die(mysql_error());
		if (mysql_num_rows($r)==1) $cid=mysql_result($r,0,0);
		} while ($cid!=0);
	}

function One($lid,$to) {
	GLOBAL $db,$CATNAME;

	$r=mysql_query("SELECT type,cat1 FROM ".$db["prefix"]."main WHERE lid='$lid';") or die(mysql_error());
	$from=mysql_result($r,0,0);
	$c=mysql_result($r,0,1);

	if ($to!=$from) {
		mysql_query("UPDATE ".$db["prefix"]."main SET type='$to' WHERE lid='$lid';") or die(mysql_query());
		if ($to==1 && ($from==0 || $from==2)) if ($c!=0) ChgCount($c,1);
		if ($from==1 && ($to==0 || $to==2)) if ($c!=0) ChgCount($c,-1);


		/* sending email */
		if ($to==2 || $to==1) {
			if ($to==1) $r=mysql_query("SELECT * FROM ".$db["prefix"]."mail WHERE mid=2 AND active=1;") or die(mysql_error());
			else $r=mysql_query("SELECT * FROM ".$db["prefix"]."mail WHERE mid=3 AND active=1;") or die(mysql_error());

			if ($a=mysql_fetch_assoc($r)) {
				$r1=mysql_query("SELECT title, url, email FROM ".$db["prefix"]."main WHERE lid='".intval($lid)."' AND mail_sended=0;") or die(mysql_error());
				if ($b=mysql_fetch_assoc($r1)) {
					mysql_query("UPDATE ".$db["prefix"]."main SET mail_sended=1 WHERE lid='".intval($_GET["lid"])."';") or die(mysql_error());
					$body=$a["body"];
					$body=str_replace("%SITENAME%",$b["title"],$body);
					$body=str_replace("%SITEURL%",$b["url"],$body);
					$body=str_replace("%CATNAME%",$CATNAME,$body);
					$body=str_replace("\r\n","\n",$body);
					if ($b["email"]!="-") {
						$err=@mail($b["email"],qp_enc($a["subject"]),$body,"From: \"".AddSlashes($CATNAME)."\" <".$a["from"].">\r\n".(!empty($a["replyto"])?"Reply-To:".$a["replyto"]."\r\n":"").$a["headers"],"-f".$a["from"]);
						if (!$err) {
							mail($b["email"],qp_enc($a["subject"]),$body,"From: \"".AddSlashes($CATNAME)."\" <".$a["from"].">\r\n".(!empty($a["replyto"])?"Reply-To:".$a["replyto"]."\r\n":"").$a["headers"]);
							}
						}
					}
				}
			}
		}
	}

$group=0;
switch ($_GET["op"]) {
	case $LANG["delete"]: $to=2; $group=1; break;
	case $LANG["tonew"]: $to=0; $group=1; break;
	case $LANG["asubmit"]: $to=1; $group=1; break;
	}

if ($group==0) One(intval($_GET["lid"]),intval($_GET["to"]));
else {
	while (list ($key, $val) = each ($_GET)) {
		if (substr($key,0,3)=="id_") One(intval(substr($key,3)),$to);
		}
	}
sync();

$ref=strpos($_SERVER["HTTP_REFERER"],"?")==0?$_SERVER["HTTP_REFERER"]:substr($_SERVER["HTTP_REFERER"],0,strpos($_SERVER["HTTP_REFERER"],"?"));
if (!empty($_SERVER["HTTP_REFERER"]) && strpos($ref,"index.php")==0) {
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=".$_SERVER["HTTP_REFERER"]."'>\n");
	print ("</HEAD></HTML>\n");
	}
else {
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=index.php?type=".intval($_GET["type"])."&start=".intval($_GET["start"])."'>\n");
	print ("</HEAD></HTML>\n");
	}
?>


