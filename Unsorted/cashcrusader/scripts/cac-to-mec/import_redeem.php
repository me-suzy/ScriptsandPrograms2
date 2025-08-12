<? 
set_time_limit(86400);
include("conf.inc.php");
include("../conf.inc.php");
require_once("../functions.inc.php");
$ads=file($cac_data_root."data/redemption.txt");
for ($i=0;$i<count($ads);$i++){
list($jnk,$amount,$description1,$description2)=split("::",$ads[$i]);
$amount=$amount*100000;
mysql_query("insert into ".$mysql_prefix."redemptions set description='$description1 $description2',amount=$amount,type='cash'");
echo "Importing: $description<br>";
}
