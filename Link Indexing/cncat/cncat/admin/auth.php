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
if (version_compare(phpversion(), "4.2.0", ">=")) $ob=TRUE; else $ob=FALSE;

if ($ob) {ob_start();ob_implicit_flush(0);}
require "../config.php";
require "../lang/".$LANGFILE;
if ($ob) {ob_clean();ob_implicit_flush(1);}

$errpassword=$errlogin=0;

session_register("cncatsid");
if ($_SESSION["cncatsid"]!="thisissomestring") {
    /* Authorisation */
	if ($_POST["action"]=="enter") {
		if ($_POST["login"]==$db["alogin"] && md5($_POST["password"])==$db["apassword"]) {
			$_SESSION["cncatsid"]="thisissomestring";
			Header("Location: ./");
			exit;
			}
		else {
			if ($_POST["login"]!=$db["alogin"]) $errlogin=1;
			if (md5($_POST["password"])!=$db["apassword"]) $errpassword=1;
			}
		}
	/* Displaying authorisation form */
?>
<HTML>
<HEAD>
<TITLE>CNCat ::: <?=$LANG["moderators"];?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?=$LANG["charset"];?>">
<STYLE>
<!--
body {font-family:verdana;font-size:11px;}
th {color:white;text-align:left;font-family:verdana;font-size:11px;}
td {font-family:verdana;font-size:11px;}
input,select {font-family:verdana;font-size:11px;}
.t1 {background-color:#EFE5F0;}
.t2 {background-color:#D9C2DC;}
.white {color:white;}
//-->
</STYLE>
</HEAD>
<BODY>
<table cellspacing=0 cellpadding=6 border=0 width=100%><tr><th background='<?=$ADLINK;?>../cat/tablebg.gif'>CNCat ::: <?=$LANG["moderators"];?></th></tr></table><br>

<table border=0 height=80% width=100%><tr><td>
<table border=0 cellspacing=0 cellpadding=0 align=center width=250>
<tr><th background='<?=$ADLINK;?>../cat/tablebg.gif' colspan=2><a href=http://www.cn-software.com/><img src='<?=$ADLINK;?>../cat/cnlogo.gif' width=31 height=25 border=0></a></th><th background='<?=$ADLINK;?>../cat/tablebg.gif' width=100%><a href=http://www.cn-software.com/><font color=white>CNCat 2.0</font></a></th></tr>
</table>
<table border=0 cellspacing=1 cellpadding=6 align=center width=250>
<form action='<?=$ADLINK;?>index.php' method=post>
<tr class=t1><td>Login:</td><td><input type=text name=login value=''>
<? if ($errlogin==1) print "<br><small style='color:red'>".$LANG["wrong_login"]."</small>";?>
</td></tr>
<tr class=t1><td>Password:</td><td><input type=password name=password value=''>
<? if ($errpassword==1) print "<br><small style='color:red'>".$LANG["wrong_password"]."</small>";?>
</td></tr>
</tr>
<tr><td class=t2 align=right colspan=2><input type=submit value='Enter &gt;&gt;'></th></tr>
<input type=hidden name=action value='enter'>
</form>
</table>
</td></tr></table>
<?
	include "_bottom.php";
	exit;
	}

function GetParentName($cid) {
	GLOBAL $db;

	do {
		$r=mysql_query("SELECT name,parent FROM ".$db["prefix"]."cat WHERE cid='$cid';");
		if (mysql_num_rows($r)==1) {
			$name=" ::: ".mysql_result($r,0,0).$name;
			$cid=mysql_result($r,0,1);
			}
		} while (mysql_num_rows($r)==1);
	return(substr($name,5,strlen($name)));
	}

function sync_names() {
	GLOBAL $db;
	
	$r=mysql_query("SELECT cid,name FROM ".$db["prefix"]."cat") or die(mysql_error());
	while ($a=mysql_fetch_array($r)) {
		mysql_query("DELETE FROM ".$db["prefix"]."cat_linear WHERE cid='".$a["cid"]."';") or die(mysql_error());
		mysql_query("INSERT INTO ".$db["prefix"]."cat_linear SET name='".GetParentName($a["cid"])."', cid='".$a["cid"]."';") or die(mysql_error());
		}
	}

function sync() {
	GLOBAL $db;
	$R=Array();

	mysql_query("UPDATE ".$db["prefix"]."cat SET count='0';") or die(mysql_error());
	$r=mysql_query("select cat1,count(*) from ".$db["prefix"]."main where type=1 and cat1!=0 group by cat1;") or die(mysql_error());
	for ($i=0;$i<mysql_num_rows($r);$i++) {
		$cid=mysql_result($r,$i,0);
		$count=mysql_result($r,$i,1);
		if (!isset($R[$cid])) $R[$cid]=0;
		$R[$cid]+=$count;
		}

	while (list($k, $v)=each($R)) {
		mysql_query("UPDATE ".$db["prefix"]."cat SET count=count+'$v' WHERE cid='$k';") or die(mysql_error());
		$id=$k;
		do {
			$r=mysql_query("SELECT parent FROM ".$db["prefix"]."cat WHERE cid='$id';") or die(mysql_error());
			$id=@mysql_result($r,0,0);
			if ($id!=0) {
				mysql_query("UPDATE ".$db["prefix"]."cat SET count=count+'$v' WHERE cid='$id';") or die(mysql_error());
				}
			} while ($id!=0);
		}
	}

function qp_enc( $input = "", $line_max = 76, $space_conv = true ) {
   $hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
   $lines = preg_split("/(?:\r\n|\r|\n)/", $input);
   $eol = "?=\r\n\t";
   $escape = "=";
   $output = "";
   $charset = "=?Windows-1251?Q?";

       while( list(, $line) = each($lines) ) {
               $linlen = strlen($line);
               $newline = "";
               for($i = 0; $i < $linlen; $i++) {
                       $c = substr( $line, $i, 1 );
                       $dec = ord( $c );
                       if ( ( $i == 0 ) && ( $dec == 46 ) ) {
                               $c = "=2E";
                       }
                       if ( $dec == 32 ) {
                               if ( $i == ( $linlen - 1 ) ) {
                                       $c = "_";
                               } else if ( $space_conv ) {
                                       $c = "_";
                               }
                       } elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) {
                               $h2 = floor($dec/16);
                               $h1 = floor($dec%16);
                               $c = $escape.$hex["$h2"].$hex["$h1"];
                       }
                       if ( (strlen($newline) + strlen($c)) >= $line_max ) {
                               $output .= $charset.$newline.$eol;
                               $newline = "";
                               if ( $dec == 46 ) {
                                       $c = "=2E";
                               }
                       }
                       $newline .= $c;
               } // end of for
               $output .= $charset.$newline.$eol;
       } // end of while
       return trim(str_replace("\r\n","\n",$output));
	} 

?>