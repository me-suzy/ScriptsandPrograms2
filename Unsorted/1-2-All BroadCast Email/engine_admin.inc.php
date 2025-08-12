<?PHP
require("lang_select.php");
$usernow=base64_decode ($useracp);
$sql_admin = "SELECT * FROM Admin WHERE user='$usernow' and pass='$passacp'";
$result_admin = mysql_query($sql_admin);
$numc = mysql_numrows($result_admin);
if ($numc == 0){
header("Location: index.php?val=invalid");
exit; 
}
$row_admin = mysql_fetch_array($result_admin);
$row_admin_menu=explode(",",$row_admin["menu"]);
?>