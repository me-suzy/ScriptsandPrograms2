<?php
include("../const.inc.php");
include("./function.inc.php");

$sourcefolder = "../";
$destfolder = "../";
$filename = "const.inc.php";
$linearray = array(4,5,6,7,8);
$contentarray[0] = "\$DBName = \"$installdbname\";\n";
$contentarray[1] = "\$DBUser = \"$installdbuser\";\n";
$contentarray[2] = "\$DBPassword = \"$installdbpass\";\n";
$contentarray[3] = "\$DBHost = \"$installdbhost\";\n";
$contentarray[4] = "\$adminemail = \"$installadminemail\";\n";

copyandmodifyfile($sourcefolder,$destfolder,$filename,$filename,$linearray,$contentarray);

$conn = mysql_connect($installdbhost,$installdbuser,$installdbpass);
$createquery = "CREATE DATABASE $installdbname";
mysql_query($createquery,$conn);
mysql_close($conn);

include("./DbSql.inc.php");


$dumpfile = file("./install/blank.sql");
for ($i=0;$i<=count($dumpfile);$i++){
$tempdumpquery .= $dumpfile[$i];
}


$dumpquery = explode(";",$tempdumpquery);

$db = new DBSQL($DBName);

for ($i=0;$i<=count($dumpquery);$i++){
$db->createtable($dumpquery[$i]);
}

$sql = "insert into newsadmin (username,password) values ('$installusername','$installpassword')";
$db->insert($sql);

?>
<html>
<head>
<title><?php print "$admin_install"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top">       
      <hr width="90%" size="1" noshade>
      <table width="90%" border="0" cellspacing="0" cellpadding="4" height="300">
        <tr> 
          <td align="center"> 
            <p><a href="index.php"><?php print "$admin_adminindex"; ?></a>
            </p>            
          </td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" height="40">&nbsp;</td>
  </tr>
</table>
<?php
include("bottom.php3");
?>

</body>
</html>
