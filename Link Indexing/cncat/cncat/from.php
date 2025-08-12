<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/
ini_set("session.use_trans_sid",0);
if (version_compare(phpversion(), "4.2.0", ">=")) $ob=TRUE; else $ob=FALSE;

if ($ob) {ob_start();ob_implicit_flush(0);}
include "config.php";
error_reporting(E_ALL & ~E_NOTICE);
if ($ob) {ob_clean();ob_implicit_flush(1);}

$u=intval($_SERVER["QUERY_STRING"]);
@mysql_query("UPDATE ".$db["prefix"]."main SET gin=gin+1 WHERE lid='$u';");
header("Location: ./");
?>