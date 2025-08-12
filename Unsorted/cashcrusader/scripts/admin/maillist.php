<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
set_time_limit(0);
$cur_time=date("Y-m-d H:i");
header("Content-disposition: filename=$mysql_database.emails.txt");
                                        header("Content-type: application/octetstream");
                                        header("Pragma: no-cache");
                                        header("Expires: 0");
$result=@mysql_query("select email from ".$mysql_prefix."users");
while($row=@mysql_fetch_row($result)){
echo $row[0]."\r\n";
}
