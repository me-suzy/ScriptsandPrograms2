<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

// view.php - display movie details

if(!isset($_GET['fid'])) die();

require_once('config/config.php');

$result = mysql_query('SELECT name,aka,cat,nr,id,runtime,year,genre,sound,lang,ratio,format,medium,comment,inserted FROM movies WHERE fid = '.$_GET['fid']);
$row = mysql_fetch_array($result);
$id = $row['id'];
$fid = $_GET['fid'];

$directors = ''; $actors = ''; $writers = '';
getpeople($directors,'directs');
getpeople($actors,'plays_in');
getpeople($writers,'writes');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
		<title><?=  '....['.$row['cat'].$row['nr'].']............................................................................' ?> </title>
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
			
			edit=new Image();		edit.src = 'pics/edit.gif';
			edit_a =new Image();	edit_a.src = 'pics/edit_a.gif';

			function swap(imgName,img){
				document.images[imgName].src = eval(img + ".src");
			}
		</script>
	</head>

	<body>
		<div id="header"><?= strlen($row['name'])>32 ? substr($row['name'],0,30).'..' : $row['name'] ?></div>
		<div id="mainpar">
			<table id="restable">
				<tr>
					<td colspan="2" class="rowtitle"><?= '['.$row['cat'].$row['nr'].'] '.$row['name'].' ('.$row['year'].')' ?></td>
				</tr>
				<tr style="height: 100%">
					<td rowspan="6" height="150px" width="100px"><img alt="open poster" src="imgget.php?for=<?= $fid ?>" width="97" height="150" border="0" onclick="window.open('imgget.php?for=<?= $fid ?>','image','resizable=yes,width=350,height=270,');"/></td>
					<td>directed by:</td>
				</tr>
				<tr style="height: 100%">
					<td class="row0"><?= $directors ?></td>
				</tr>
				<tr style="height: 100%">
					<td>&nbsp;</td>
				</tr>
				<tr style="height: 100%">
					<td>written by:</td>
				</tr>
				<tr style="height: 100%">
					<td class="row0"><?= $writers ?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr style="height: 100%">
					<td align="center"><a href="http://www.imdb.com/title/tt<?= $id ?>/" target="_blank"><img alt="imdblogo" src="pics/imdb.gif"/></a></td>
					<td/>
				</tr>
				<tr>
					<td>runtime:</td>
					<td class="row0" ><?= $row['runtime'] ?></td>
				</tr>
				<tr>
					<td>cast:</td>
					<td class="row0" ><?= $actors ?></td>
				</tr>
<?	if($row['genre'] != null){
		$genres = explode(',',$row['genre']);
?>
				<tr>
					<td>genres:</td>
					<td class="row0">
<?				for($i=0; $i<(sizeof($genres)-1);$i++)
					echo '<a onclick="opener.browseGenre(\''.$genres[$i].'\');">'.$genres[$i].', </a>';
				echo '<a onclick="opener.browseGenre(\''.$genres[$i].'\');">'.$genres[$i].'</a>';
?>					
					</td>
				</tr>
<?	} ?>
<?	if($row['aka'] != null) { ?>
				<tr>
					<td>also known as:</td>
					<td class="row0" ><?= str_replace("\n",'<br/>',$row['aka']) ?></td>
				</tr>
<?	} ?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>medium</td>
					<td class="row0" ><?= $row['medium'] ?></td>
				</tr>
				<tr>
					<td>format</td>
					<td class="row0" ><?= $row['format'] ?></td>
				</tr>
				<tr>
					<td>ratio</td>
					<td class="row0" ><?= $row['ratio'] ?></td>
				</tr>
				<tr>
					<td>lang</td>
					<td class="row0" ><?= $row['lang'] ?></td>
				</tr>
				<tr>
					<td>sound</td>
					<td class="row0" ><?= $row['sound'] ?></td>
				</tr>
<?	if($row['comment'] != null) { ?>
				<tr style="height: 100%">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>comment</td>
					<td class="row0" ><?= str_replace("\n",'<br/>',$row['comment']) ?></td>
				</tr>
<?	} ?>
				<tr style="height: 100%">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>inserted</td>
					<td class="row0" ><?= $row['inserted'] ?></td>
				</tr>
			</table>
		</div>
		<div id="footer">
			<img name="stop" alt="ok" src="pics/stop.gif" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')" onclick="window.close();"/>
			<img name="edit" alt="edit movie" src="pics/edit.gif" onmouseover="swap('edit','edit_a')" onmouseout="swap('edit','edit')" onclick="location.href='members/edit.php?fid=<?= $_GET['fid'] ?>'"/>&nbsp;
		</div>
	</body>

</html>


<?
	// this would be unnecessary if mysql supported views:(
	function getpeople(&$out,$table){
		global $fid;
		$res = mysql_query("SELECT people.id,people.name FROM $table,people WHERE $table.movie_fid =$fid AND $table.people_id = people.id ORDER BY people.id") or die(mysql_error());
		while($row = mysql_fetch_row($res)){
			if($out != '') $out .= ', ';
			$out .= '<a target="_blank" href="http://www.imdb.com/name/nm'.$row[0].'/">'.$row[1].'</a>';
		}
		return;
	}
?>
