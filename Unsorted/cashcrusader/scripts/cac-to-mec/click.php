<? 
include("../scripts/conf.inc.php");
include("../scripts/functions.inc.php");
list($A)=mysql_fetch_row(mysql_query("select emailid from email_ads where description='$A'"));
header("Location: /scripts/runner.php?EA=$A");
