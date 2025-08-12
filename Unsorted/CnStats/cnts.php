<?
include "config.php";

mysql_connect($STATS_CONF["sqlhost"],$STATS_CONF["sqluser"],$STATS_CONF["sqlpassword"],TRUE);
mysql_select_db($STATS_CONF["dbname"]);
$r=mysql_query("SELECT t_hits,hits,hosts FROM cns_counter") or die(mysql_error());
$STATS_CONF=mysql_fetch_array($r,MYSQL_ASSOC);

Header("Content-type: image/png");
$im=ImageCreateFromPng("button.png");
$black=ImageColorAllocate($im,0,0,0);
$color=ImageColorAllocate($im,$COUNTER["inkR"],$COUNTER["inkG"],$COUNTER["inkB"]);
ImageString($im,2,86-6*strlen($STATS_CONF["t_hits"]),1,$STATS_CONF["t_hits"],$color);
ImageString($im,1,85-5*strlen($STATS_CONF["hits"]),13,$STATS_CONF["hits"],$color);
ImageString($im,1,85-5*strlen($STATS_CONF["hosts"]),20,$STATS_CONF["hosts"],$color);
ImagePng($im);
ImageDestroy($im);
?>
