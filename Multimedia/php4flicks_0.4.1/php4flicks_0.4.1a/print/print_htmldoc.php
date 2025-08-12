<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

	// list.php - generates a movie list. takes an argument of the form WHERE ... ORDER BY ...
	// (htmldoc allows to pass only one var via GET)

	if(!isset($_GET['what']) || $_GET['what'] == '')
		$_GET['what'] = ' ORDER BY nr ASC';

	require('../config/config.php');
	$result = mysql_query('SELECT DISTINCT CONCAT(cat,nr)as nr, id, fid, name, year, runtime, medium FROM movies '.$_GET['what']) or die(mysql_error());
	
	function directorsearch($moviefid){
		$res = mysql_query("SELECT people.name FROM directs,people WHERE directs.movie_fid = $moviefid AND directs.people_id = people.id;") or die(mysql_error());
		$out = '';
		while($row = mysql_fetch_row($res))
			$out .= ', '.$row[0];
		($out != '')or($out = '  &nbsp;');
		return substr($out,2);
	}
	
?>

<!-- NO css here, since HTMLdoc does NOT understand it! -->
<!-- FOOTER LEFT "$DATE" -->
<!-- FOOTER RIGHT "$PAGE of $PAGES" -->
<!-- MEDIA LEFT 45mm -->
<!-- MEDIA TOP 30mm -->
<!-- MEDIA SIZE <?= $cfg['pagesize'] ?> -->

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
		<meta name="generator" content="Adobe GoLive 5">
	</head>

	<body bgcolor="#ffffff">
		<font size="-1" color="#000088" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular" >
		<table border="0" cellpadding="0" cellspacing="0" width="670px">
		<tr>
			<td colspan="5" align="center" height="18px"><img src="../pics/movielist.gif"/></td>
		</tr>
		<tr height="20px">
			<td width="50px"/><td width="330px"><font size="-1" face="Arial, Helvetica, sans-serif" color="#000088"><b>title</b></font></td><td width="170px"><font size="-1" face="Arial, Helvetica, sans-serif" color="#000088"><b>directed by</b></font></td><td width="60px"><font size="-1" face="Arial, Helvetica, sans-serif" color="#000088"><b>runtime</b></font></td><td width="60px"><font size="-1" face="Arial, Helvetica, sans-serif" color="#000088"><b>medium</b></font></td>
		</tr>
<?	$brow = true;	
	while($row = mysql_fetch_array($result)) { 
?>
			<tr height="20px" valign="top" <? if($brow) echo 'bgcolor="#f5f5f5"'; ?>>
				<td><?= $row['nr'] ?></td>
				<td><?= $row['name'].' ('.$row['year'].')' ?></td>
				<td><?= directorsearch($row['fid']) ?></td>
				<td><?= $row['runtime'] ?></td>
				<td><?= $row['medium'] ?></td>
			</tr>
<?	
	$brow = !$brow;
	} 
?>
		</table>
		</font>
		<p></p>
	</body>

</html>
