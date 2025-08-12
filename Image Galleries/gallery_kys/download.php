<HTML>
<head>
<?
$file="des/image$i.txt";
$fp=fopen($file, "r");
parse_str(fread($fp, filesize($file)));
fclose($fp);
echo "<title>Download</title></head><body bgcolor=#000000 text=#ffcc00 link=#ffcc00>";
echo "<font size=4 face=\"Verdana\" color=#ffcc00><center>$name</center>";
$file="down/image$i.zip";
echo "<br>If download does not start <a href=\"$file\">please click here</a>";
$counter++;
$w=$w ="name=".$name."&price=".$price."&code=".$code."&des=".$des."&author=".$author."&mail=".$mail."&date=".$date."&web=".$web."&counter=".$counter;
$fp=fopen("des/image$i.txt", "w+");
fwrite($fp, $w);
fclose($fp);
echo "<script language=\"javascript\">
self.location='$file';
</script>";
?></font></p>
</center>
<style>
BODY { scrollbar-face-color: "#000000"; scrollbar-arrow-color: "#000000"; scrollbar-track-color: "#000000"; scrollbar-3dlight-color:"#333333"; scrollbar-darkshadow-color: "#333333"; }
</style></body>
</HTML>
