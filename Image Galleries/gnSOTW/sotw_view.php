<script language="JavaScript" src='pop.js' type="text/javascript"></script><center>
<?
include('config.php');
$sql = "SELECT * FROM sotw_week ORDER BY wid DESC LIMIT 1";
$q = mysql_query($sql);
while($row = mysql_fetch_array($q)){
$wid = $row['wid'];
}
$i = 1;
if ($handle = opendir($wid.'/')) {
   while (false !== ($file = readdir($handle))) { 
       if ($file != "." && $file != "..") { 
		echo "<font color='white'>".$i++.".</font> <a href='".$wid."/$file' target='new'><img src='".$wid."/".$file."' border=0 /> </a><br>"; 
       } 
   }
   closedir($handle); 
}
echo $copyright;
?></center>