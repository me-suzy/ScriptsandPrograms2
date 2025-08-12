<? 
set_time_limit(86400);
include("conf.inc.php");
include("../conf.inc.php");
require_once("../functions.inc.php");
$ads=file($cac_data_root."data/random.txt");
for ($i=0;$i<count($ads);$i++){
$id=trim($ads[$i]);
mysql_query("update ".$mysql_prefix."users set free_refs='YES',account_type='gold' where username='$id'"); 
echo "Importing: $id<br>\n";
}
