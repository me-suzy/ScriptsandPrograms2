<? 
set_time_limit(86400);
include("conf.inc.php");
include("../conf.inc.php");
require_once("../functions.inc.php");
$ads=file($cac_data_root."data/linkdata.txt");
for ($i=0;$i<count($ads);$i++){
list($description,$amount,$html,$clicks,$id)=split("::",$ads[$i]);
if (file_exists($cac_data_root."data/linkdata/$description")){
$count=file($cac_data_root."data/linkdata/$description");
$count=count($count);
$html=trim($html);
$amount=trim($amount)*100000;
mysql_query("insert into ".$mysql_prefix."ptc_ads set vtype='cash',value=$amount,id='$id',description='$description',category='Main',html='$html',clicks=$count,run_current=$count,run_quantity=$clicks,run_type='clicks'");
echo "Importing: $description<br>";}
}
