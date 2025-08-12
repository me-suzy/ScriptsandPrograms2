<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

	//insert.php - insert values from form into db, delete from db, or update db
	//possible actions are: insert, delete, update, default: insert
	
session_start();
if(!isset($_SESSION['user'])){
	header('location: ../login.php');
}

require_once('../config/config.php');
	
if(!isset($_GET['action']))
	$_GET['action'] = 'insert';
	
switch($_GET['action']){

	case 'insert':
		$insertid = moviequery('INSERT INTO movies');
		addpeople($insertid);
		showOK($_POST['title'],' inserted!','add.php');
		
		break;
			
	case 'update':
		moviequery('UPDATE movies');
		// this might not be very elegant or efficient but sure is simple :)
		killpeople($_POST['fid']);
		addpeople($_POST['fid']);
		showOK($_POST['title'],'successfully updated!','');
		
		break;
		
	case 'delete':
		// allow id to be passed via GET or POST method
		if(isset($_GET['fid'])) $movieid = $_GET['fid']; 
		elseif(isset($_POST['fid'])) $movieid = $_POST['fid'];
		else die('error: no movie id');
		// delete movie:
		mysql_query("DELETE FROM movies WHERE fid=$movieid") or die(mysql_error());
		killpeople($movieid);
		showOK('','movie deleted :(','');
		break;
		
	default: 
		break;
}


	function addpeople($fid){
		// adds people in $_POST[x], where x={actor,director,writer}.
		// (IGNOREs duplicates).
		if(!isset($_POST['director'])) $_POST['director'] = array();
		if(!isset($_POST['actor'])) $_POST['actor'] = array();
		if(!isset($_POST['writer'])) $_POST['writer'] = array();		
		
		$peoplevalues = '';
		
		// directors:
		$values = '';
		for($i=0; $i<sizeof($_POST['director']); $i++){
			if($_POST['director'][$i]['name']!='' && $_POST['director'][$i]['id']!= ''){
				$values .= ",('$fid','".$_POST['director'][$i]['id']."')";
				$peoplevalues .= ",('".$_POST['director'][$i]['id']."','".addslashes($_POST['director'][$i]['name'])."')";
			}
		}
		
		if($values!=''){
			$values = substr($values,1); // 1 ',' too much
			mysql_query("INSERT IGNORE INTO directs(movie_fid,people_id) VALUES $values") or die(mysql_error());
		}
		
		// actors:
		$values = '';
		for($i=0; $i<sizeof($_POST['actor']); $i++){
			if($_POST['actor'][$i]['name']!='' && $_POST['actor'][$i]['id']!= ''){
				$values .= ",('$fid','".$_POST['actor'][$i]['id']."')";
				$peoplevalues .= ",('".$_POST['actor'][$i]['id']."','".addslashes($_POST['actor'][$i]['name'])."')";
			}
		}
		if($values!=''){
			$values = substr($values,1); // 1 ',' too much
			mysql_query("INSERT IGNORE INTO plays_in(movie_fid,people_id) VALUES $values") or die(mysql_error());
		}

		// and writers...
		$values = '';
		for($i=0; $i<sizeof($_POST['writer']); $i++){
			if($_POST['writer'][$i]['name']!='' && $_POST['writer'][$i]['id']!= ''){
				$values .= ",('$fid','".$_POST['writer'][$i]['id']."')";
				$peoplevalues .= ",('".$_POST['writer'][$i]['id']."','".addslashes($_POST['writer'][$i]['name'])."')";
			}
		}
		if($values!=''){
			$values = substr($values,1); // 1 ',' too much
			mysql_query("INSERT IGNORE INTO writes(movie_fid,people_id) VALUES $values") or die(mysql_error());
		}
		
		// finally, insert into people table:
		if($peoplevalues != ''){
			$values = substr($peoplevalues,1);
			mysql_query("INSERT IGNORE INTO people VALUES $values");
		}
	}
	
	function killpeople($movieid){
    	$pers = array();
    	
    	$result = mysql_query("SELECT people_id FROM directs WHERE movie_fid = $movieid UNION SELECT people_id FROM writes WHERE movie_fid = $movieid UNION SELECT people_id FROM plays_in WHERE movie_fid = $movieid") or die(mysql_error());	//UNION is supported as of version 4
       	while($row = mysql_fetch_row($result))
			$pers[] = $row[0];

    	// delete all of it...
		mysql_query("DELETE FROM directs WHERE movie_fid = $movieid") or die(mysql_error());
		mysql_query("DELETE FROM plays_in WHERE movie_fid = $movieid") or die(mysql_error());
		mysql_query("DELETE FROM writes WHERE movie_fid = $movieid") or die(mysql_error());
		
		// ...and delete in people table, if necessary:
		foreach($pers as $p){
			// check if other references to this person exist
			$result = mysql_query("SELECT people_id FROM directs WHERE people_id = $p UNION SELECT people_id FROM writes WHERE people_id = $p UNION SELECT people_id FROM plays_in WHERE people_id = $p") or die(mysql_error());
			if(mysql_num_rows($result)==0) mysql_query("DELETE FROM people WHERE id = $p") or die(mysql_error());
		}
		
		// alternative: (1) don't delete in people table, run batch script to clean people table when necessary
		// (2) use InnoDB tables with referencial integrity that allow ON DELETE CASCADE
		// (3) (for UPDATE) first get old people from db, create array with them and use array_diff
	}
	
	function moviequery($what){
		// inserts into or updates table. $what must be 'INSERT INTO ...' or 'UPDATE ...'
		// return value: newly generated fid for inserts (auto increment), 0 otherwise
		
		global $cfg;
		
		if($_POST['title']=='' || $_POST['nr']=='' || !isset($_POST['imdbid']) || $_POST['imdbid']=='')
			die('some important information is missing! abort');
			
		if(!in_array($_POST['medium'],$cfg['medium']))
			$_POST['medium'] = $cfg['medium'][0];	//default value
			
		if(!isset($_POST['lang_array']))
			$_POST['lang_array'] = array($cfg['lang'][0]);	//default value
			
		if(!isset($_POST['sound_array']))
			$_POST['sound_array'] = array($cfg['sound'][0]);	//default value
		
		$query = $what.' SET ';
		$query .= 'name=\''.addslashes($_POST['title']);
		$query .= '\',aka='.($_POST['aka']!=''?('\''.addslashes($_POST['aka']).'\''):'NULL');
		$query .= ',cat='.($_POST['cat']!=''?('\''.addslashes($_POST['cat']).'\''):'NULL');
		$query .= ',nr=\''.$_POST['nr'];
		$query .= '\',id=\''.$_POST['imdbid'];
		$query .= '\',runtime=\''.($_POST['runtime']==''?'0':$_POST['runtime']);
		$query .= '\',year='.($_POST['year']!=''?('\''.addslashes($_POST['year']).'\''):'NULL');
		$query .= ',sound=\''.addslashes(implode(',',$_POST['sound_array']));
		$query .= '\',lang=\''.addslashes(implode(',',$_POST['lang_array']));
		$query .= '\',ratio=\''.addslashes($_POST['ratio']);
		$query .= '\',format=\''.addslashes($_POST['format']);
		$query .= '\',medium=\''.addslashes($_POST['medium']);
		$query .= '\',comment='.($_POST['comment']!=''?('\''.addslashes($_POST['comment']).'\''):'NULL');
		$query .= isset($_POST['genre_array'])?',genre=\''.addslashes(implode(',',$_POST['genre_array'])).'\'':'';
		
		if($what == 'INSERT INTO movies')
			$query .= ',inserted=CURDATE()';
			
		if($_POST['setposter'] != 'false' && $_POST['setposter'] != ''){
			// pic must be updated, was stored in session data
			$query .= ',poster=\''.addslashes($_SESSION['image'][$_POST['imdbid']]).'\'';
			unset($_SESSION['image']);
		} else if ($_POST['setposter'] == 'false'){
			$query .= ',poster=NULL';
		} // else poster remains unaffected or in case of insert is set to NULL
		
		if($what == 'UPDATE movies') 
			$query .= ' WHERE fid=\''.$_POST['fid'].'\'';
		
		mysql_query($query) or die(mysql_error());
		return mysql_insert_id();
	}
	
	function showOK($moviename,$okstring,$goto){
	?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

	<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
		<title>done</title>
		<link rel="stylesheet" type="text/css" href="../config/flicks.css"/>
		<script type="text/javascript">
		<!--
			stop = new Image();	stop.src = '../pics/stop.gif';
			stop_a = new Image(); 	stop_a.src = '../pics/stop_a.gif';
			go = new Image();	go.src = '../pics/go.gif';
			go_a = new Image(); 	go_a.src = '../pics/go_a.gif';
			function swap(imgID,img) {
				//imgID: img name, imgObjName: new image!
				document.images[imgID].src = eval(img + ".src");
			}

			function resize(x,y){
				if(navigator.userAgent.indexOf('MSIE')>-1)
					window.resizeTo(x+20,y+40) //stupid ie thinks window size are outer measures
				else
					{window.innerWidth = x; window.innerHeight = y+20;}
			}
		-->
		</script>
	</head>

		<body onload="resize(250,140)">
			<div id="header">done:</div>
			<div id="content">
				<table style="width: 100%">
					<tr class="row0"><td><?= $moviename ?></td></tr>
					<tr><td><?= $okstring ?></td></tr>
				</table>
			</div>
			<div id="footer">
				<img name="stop" alt="done" src="../pics/stop.gif" onclick="opener.document.filterform.submit(); /*reload index page*/ window.close();" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')"/>&nbsp;<? if($goto != '') { ?><img name="go" alt="more!" src="../pics/go.gif" onclick="location.href='<?= $goto ?>'"; onmouseover="swap('go','go_a')" onmouseout="swap('go','go')"/>&nbsp;<? } ?>
			</div>
		</body>	
	</html>
	<?
	}
	
