<? 
set_time_limit(86400);
include("conf.inc.php");
include("../conf.inc.php");
require_once("../functions.inc.php");
$ads=file($cac_data_root."data/bannerdata.txt");
for ($i=0;$i<count($ads);$i++){
list($description,$html,$id,$views)=split("::",$ads[$i]);
$runtype='ongoing';
if (trim($views)){
$count=file($cac_data_root."data/bannerdata/$description");
$count=count($count)/2;
$runtype='views';
}
$html=trim($html);
mysql_query("insert into ".$mysql_prefix."rotating_ads set id='$id',description='$description',category='Main',html='$html',views='$count',run_current='$count',run_quantity='$views',run_type='$runtype'");
echo "Importing: $description<br>";
}
