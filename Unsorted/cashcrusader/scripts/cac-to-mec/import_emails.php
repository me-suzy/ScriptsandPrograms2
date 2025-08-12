<? 
set_time_limit(86400);
include("conf.inc.php");
include("../conf.inc.php");
require_once("../functions.inc.php");
echo "<pre>\n";
$ads=file($cac_data_root."data/pedata.txt");
for ($i=0;$i<count($ads);$i++){
list($description,$pay,$id,$url)=split("::",trim($ads[$i]));
$users=file($cac_data_root."data/pedata/$description");
$count=count($users);
$pay=$pay*100000;
mysql_query("insert into ".$mysql_prefix."email_ads set id='$id',description='$description',clicks=$count,run_type='ongoing',value=$pay,vtype='cash',site_url='$url'");
echo "Importing: $description\n";
$last=mysql_insert_id();
for ($idx=0;$idx<$count;$idx++){
$user=trim($users[$idx]);
echo "-  $user\n";
mysql_query("insert into ".$mysql_prefix."paid_clicks set id=$last,username='$user',value=$pay,vtype='cash',type='paidmail',ip_host='import from CAC'");
}}
echo "</pre>";
