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
ini_set("session.use_trans_sid",0);

if (version_compare(phpversion(), "4.2.0", ">=")) $ob=TRUE; else $ob=FALSE;

if ($ob) {ob_start();ob_implicit_flush(0);}
include "config.php";
if ($ob) {ob_clean();ob_implicit_flush(1);}


$cid=intval($_SERVER["QUERY_STRING"]);
@mysql_query("UPDATE ".$db["prefix"]."main SET gout=gout+1 WHERE lid='$cid';");
$r=mysql_query("SELECT url FROM ".$db["prefix"]."main WHERE lid='$cid';") or die(mysql_error());
if (mysql_num_rows($r)!=1) die("URL not found.");
$url=mysql_result($r,0,0);
header("Location: ".$url);
?>