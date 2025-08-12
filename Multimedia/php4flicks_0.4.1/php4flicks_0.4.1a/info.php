<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/


require('config/config.php');

$res = mysql_query('SELECT COUNT(*) FROM movies') or die(mysql_error());
$moviecount = mysql_fetch_row($res);
$moviecount = $moviecount[0];

$res = mysql_query('SELECT COUNT(*) FROM people') or die(mysql_error());
$peoplecount = mysql_fetch_row($res);
$peoplecount = $peoplecount[0];

$res = mysql_query('SELECT DISTINCT people_id FROM directs') or die(mysql_error());
$dircount = mysql_num_rows($res);

$res = mysql_query('SELECT DISTINCT people_id FROM plays_in') or die(mysql_error());
$actcount = mysql_num_rows($res);

$res = mysql_query('SELECT DISTINCT people_id FROM writes') or die(mysql_error());
$wrcount = mysql_num_rows($res);

// calculate db size
$res = mysql_query('SHOW TABLE STATUS') or die(mysql_error());
$dbsize = 0;
while($row = mysql_fetch_array($res))
	$dbsize += $row['Data_length'] + $row['Index_length'];
$dbsize /= 1024;


$res = mysql_query('SELECT name,fid FROM movies ORDER BY inserted DESC LIMIT 0,10') or die(mysql_error());

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
		<title>info</title>
		<link rel="stylesheet" type="text/css" href="config/flicks.css"/>
		<!-- another ugly hack because microsoft thinks standards are not for them -->
		<!--[if IE]>
			<style>
			#footer{
				position:absolute;
				left:0px;
				bottom:0px;
			}		
			</style>
		<![endif]-->
		<script type="text/javascript">
			stop=new Image();		stop.src = 'pics/stop.gif';
			stop_a =new Image();	stop_a.src = 'pics/stop_a.gif';
			function swap(imgName,img){
				document.images[imgName].src = eval(img + ".src");
			}
		</script>
	</head>

	<body>
		<div id="header">info</div>
		<div id="mainpar">
			<table id="restable">
				<tr>
					<td class="rowtitle" style="width:100px">movies in db:</td><td class="row0" style="width: 40px"><?= $moviecount ?></td><td style="width:170px">&nbsp;</td>
				</tr>
				<tr>
					<td class="rowtitle">people in db:</td><td class="row0"><?= $peoplecount ?></td><td/>
				</tr>
				<tr>
					<td>&nbsp;actors:</td><td class="row0"><?= $actcount ?></td><td/>
				</tr>
				<tr>
					<td>&nbsp;directors:</td><td class="row0"><?= $dircount ?></td><td/>
				</tr>
				<tr>
					<td>&nbsp;writers:</td><td class="row0"><?= $wrcount ?></td><td/>
				</tr>
				<tr>
					<td class="rowtitle">overall db size:</td><td class="row0"><?=($dbsize/1024 >= 1)?round(($dbsize/1024),2).' MB':round($dbsize,0).' kB'; ?></td><td/>
				</tr>
				<tr style="height: 100%">
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td class="rowtitle" colspan="3">newly added:</td>
				</tr>
<?	$brow = true;	
	while($row = mysql_fetch_array($res)){ ?>
				<tr>
					<td colspan="3" class="row<?= $brow?'0':'1' ?>"><a href="#" onclick="window.open('view.php?fid=<?= $row['fid']?>','','height=600,width=350,resizable=no,location=no,menubar=no,status=no,titlebar=no,toolbar=no,directories=no');"><?= $row['name'] ?></a></td> 
				</tr>
<?	$brow = !$brow;	
	}	?>
				<tr style="height: 100px;">
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">design, programming and everything else &copy;2003,2004 Mr.Fox;)<br/><a href="docs/CHANGES" target="_blank">[changelog]</a>&nbsp;<a href="http://php4flicks.ch.vu" target="_blank">[more info]</a></td>
				</tr>
			</table>
		</div>
		<div id="footer">
			<span class="copy">php4flicks <?= $cfg['version'] ?></span>
			<img name="stop" alt="ok" src="pics/stop.gif" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')" onclick="window.close();"/>&nbsp;
		</div>
	</body>

</html>

