<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

	//edit.php - get data from db and display form to edit it

session_start();
if(!isset($_SESSION['user'])){
	header('location: ../login.php?location=members/edit.php?fid='.$_GET['fid']);
}

if(!isset($_GET['action'])) $_GET['action'] = '';

$referer= 'edit.php?fid='.$_GET['fid'].'&';

$buttons = array(	//buttons to be displayed in filmform
	array('back','../pics/back.gif','../pics/back_a.gif', 'history.back();'),
	array('erase','../pics/del.gif','../pics/del_a.gif', "if(confirm('delete?!?')) location.href='update.php?action=delete&amp;fid=".$_GET['fid']."'"),
	array('abort','../pics/stop.gif','../pics/stop_a.gif', 'window.close();'),
	array('update','../pics/go.gif','../pics/go_a.gif', "document.data.action='update.php?action=update'; if(check()) {document.data.submit(); this.onclick='return false';}")
);

switch($_GET['action']){
	case '':
		require_once('../config/config.php');
	
		// get data
		$result = mysql_query('SELECT name,aka,cat,nr,id,runtime,year,genre,sound,lang,ratio,format,medium,comment FROM movies WHERE fid=\''.$_GET['fid'].'\'') or die(mysql_error());
		$row = mysql_fetch_array($result);
		$title = htmlspecialchars($row['name']);
		$aka = htmlspecialchars($row['aka']);
		$year = $row['year'];
		$imdbid = $row['id'];
		$fid = $_GET['fid'];
		$runtime = $row['runtime'];
		$cat = $row['cat'];
		$nr = $row['nr'];
		$smedium = $row['medium'];
		$slang = explode(',',$row['lang']);
		$ssound = explode(',',$row['sound']);
		$sgenre = explode(',',$row['genre']);
		$sformat = $row['format'];
		$sratio = $row['ratio'];
		$comment = $row['comment'];
		$setposter = false;
		//directors,actors,writers:
		$director = getpeople('directs');
		$writer = getpeople('writes');
		$actor = getpeople('plays_in');

		// display it!
		include('filmform.php');
		break;
	
	case 'reload':
		$reload = true;		//if this is set, filmform gets its data from POST array
		include('filmform.php');
	
	default: break;
}



	// this would be unnecessary if mysql supported views:(
	function getpeople($table){
		global $fid;
		$out = array();
		$res = mysql_query("SELECT people.id,people.name FROM $table,people WHERE $table.movie_fid =$fid AND $table.people_id = people.id ORDER BY people.id") or die(mysql_error());
		while($row = mysql_fetch_row($res)){
			$out[] = array('id'=>$row[0], 'name'=>$row[1]); 
		}
		return $out;
	}
?>
