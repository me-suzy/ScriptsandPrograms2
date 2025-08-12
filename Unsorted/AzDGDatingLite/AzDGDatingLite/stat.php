<?php
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";
$sql = "SELECT count(*) as total FROM ".$mysql_table;
$result = mysql_db_query($mysql_base, $sql, $mysql_link) or die(mysql_error());
$trows = mysql_fetch_array($result);
$num = $trows[total];
if ($num == 0)
{
echo $err_mes_top.$lang[33].$err_mes_bottom;
include "templates/footer.php";
die;
}

echo "<center>";
    		$handle=opendir("stat");
            $filenumber = 0;
			while (false!==($file = readdir($handle))) { 
			    if ($file != "." && $file != "..") {
			    $statfile[$filenumber] = $file;
                $filenumber++;
                } 
			}
			closedir($handle); 
if ($filenumber == 0)
{
echo $err_mes_top.$lang[53].$err_mes_bottom;
include "templates/footer.php";
die;
}
elseif ($filenumber > 1)
{
$i = 0;
for ($i = 0; $i < $filenumber; $i++)
{
include "stat/$statfile[$i]";
}
}

include "templates/footer.php";
?>
</body>
</html>