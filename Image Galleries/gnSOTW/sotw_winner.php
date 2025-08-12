<?
include('config.php');
?>
<script src="pop.js" type="text/javascript"></script>
<?
$sql = "SELECT * FROM sotw_week ORDER BY wid DESC LIMIT 1";
$q = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($q)){
$lastweek = $row['wid'] - 1;
}
$sql = "SELECT * FROM sotw_submits WHERE wid='$lastweek' AND winner='Y'";
$q = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($q)){
echo "<a href='".$lastweek."/".$row['sig']."' target=new><img src='".$lastweek."/".$row['sig']."' width='65%' border=1 target='new' /></a>";
}
echo '
<br>
    <a href="javascript:pop(\'sotw_submit.php\', 400, 100);">Submit Sig</a><br>
	<a href="sotw_view.php?sotw=view">View This Weeks Entries</a>';
	echo $copyright;
?>