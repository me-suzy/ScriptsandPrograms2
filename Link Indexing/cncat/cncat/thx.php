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

require "config.php";
require "lang/".$LANGFILE;

$r=mysql_query("SELECT name,html FROM ".$db["prefix"]."templates;") or die(mysql_error());
while ($a=mysql_fetch_assoc($r)) $TMPL[$a["name"]]=$a["html"];

$title=$LANG["linkwasadded"]." / ".$CATNAME;
$id=intval($_GET["id"]);

include "_top.php";

$template=$TMPL["bmenu"];
$template=str_replace("%MODERATORSTEXT",$LANG["moderators"],$template);
$template=str_replace("%ADDLINKTEXT",$LANG["addlink"],$template);
$template=str_replace("%MAINTEXT",$LANG["main"],$template);
print $template;

$url="http://".$_SERVER["HTTP_HOST"].substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"/",1)+1);
?>
<br><Hr size=1>

<?=$LANG["thx"];?>

<P><?=$LANG["placelink"];?>: <a href="<?=$url;?>from.php?<?=$id;?>"><b><?=$url;?>from.php?<?=$id;?></b></a></P>

<?
include "_bottom.php";
?>
