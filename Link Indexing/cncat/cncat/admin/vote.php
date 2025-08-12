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

$vote=intval($_GET["vote"]);
$lid=intval($_GET["lid"]);

mysql_query("UPDATE ".$db["prefix"]."main SET moder_vote='$vote' WHERE lid='$lid';") or die(mysql_query());

if (isset($_SERVER["HTTP_REFERER"])) $ref=$_SERVER["HTTP_REFERER"];
else $ref="../";
print ("<HTML><HEAD>\n");
print ("<META HTTP-EQUIV=refresh CONTENT='0;url=$ref'>\n");
print ("</HEAD></HTML>\n");
?>
