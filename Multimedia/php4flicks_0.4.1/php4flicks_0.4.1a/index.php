<?	
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

	// index.php -- display main page with movie list
	
	require_once('config/config.php');
	
	// columns to be listed
	$cols = ' DISTINCT CONCAT(cat,nr)as nr,movies.name,year,runtime,medium,movies.id,fid ';
	
	// check if user is logged in
	$loggedin = false;
	if(!isset($_POST['login']) || $_POST['login'] != '0'){
		session_start();
		if(!isset($_SESSION['user'])){
			session_unset(); session_destroy();
		} else $loggedin = true;
	}
	// if loggedin is true, the logout-button instead of the login-b. are shown, but of course also additional info could be shown!

	// default query (overwritten below if filter posted)
	$query = "SELECT SQL_CALC_FOUND_ROWS $cols FROM movies ";
		
	// iff filter has been submitted, use it
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		// form has been posted, page, filter and sortby[] values are present!
		
		// WHERE clause
		if(strlen($_POST['filter'])>0){
			// where clause was submitted
			// check if it is a select and not malicious SQL
			if(substr($_POST['filter'],0,38) != 'SELECT SQL_CALC_FOUND_ROWS _COLS_ FROM')
				die('don\'t try that.');
			$query = str_replace('_COLS_',$cols,$_POST['filter']);
		}
		// ORDER BY clause
		$sortsize = sizeof($_POST['sortby']);
		for($i=0; $i<$sortsize; $i++){
			$sortarray[$i] = $_POST['sortby'][$i];
			if($sortarray[$i]=='') break;
		}
		// fill rest of sort array with default values
		for($j=0; $j<$sortsize-$i; $j++){
			if(!isset($cfg['defaultsort'][$j])) break;
			$sortarray[$i] = $cfg['defaultsort'][$j];
			$i++;
		}
		$sortsize = sizeof($sortarray);
		$query .= ' ORDER BY '.implode($sortarray,',');

	} else {
		// default query
		$sortarray = $cfg['defaultsort'];
		$sortsize = sizeof($cfg['defaultsort']);
		$sortby = implode($sortarray,',');
		$query .= " ORDER BY $sortby ";
		$_POST['filtertitle'] = 'all movies';
		$_POST['filter'] = '';
		$_POST['genres'] = '';
	}
	// LIMIT clause
	if(!isset($_POST['page']) || $_POST['page'] == '')
		$_POST['page'] = '0';
	$query .= ' LIMIT '.$_POST['page'].','.$cfg['nofflicks'];
	
	$result = mysql_query($query) or die(mysql_error());
	
	$rowresult = mysql_query('SELECT FOUND_ROWS()') or die(mysql_error());
	$row = mysql_fetch_row($rowresult);
	$rowcount = $row[0];
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
		<title><?= $cfg['pagetitle'] ?></title>
		<link rel="stylesheet" type="text/css" href="config/flicks.css"/>
		<link rel="shortcut icon" href="favicon.ico"/>
		<link rel="bookmark icon" href="favicon.ico"/>
		<!-- another ugly hack because microsoft thinks standards are not for them -->
		<!--[if IE]>
			<style>
			#footer{
				position:absolute;
				left:0px;
				bottom:expression(body.scrollBottom + 'px');
			}

			body{
				height: 100%;
				overflow:hidden;
			}

			#indexmain{
				height: 100%;
				overflow: auto;
			}

			#navbox{
				margin-bottom:200px;
			}
			
			/* some other ie specific stuff */
			body{
				scrollbar-3dlight-color: white; 
				scrollbar-arrow-color: red; scrollbar-darkshadow-color: black;
				scrollbar-face-color: white; scrollbar-highlight-color: silver;
				scrollbar-shadow-color: white; scrollbar-track-color: white;
			}
			</style>
		<![endif]-->

		<script type="text/javascript" language="JavaScript">
		<!--
			search = new Image();		search.src = 'pics/search.gif';
			search_a = new Image(); 	search_a.src = 'pics/search_a.gif';

			pr = new Image();			pr.src = 'pics/print.gif';
			pr_a = new Image(); 		pr_a.src = 'pics/print_a.gif';
			
			add = new Image();			add.src = 'pics/add.gif';
			add_a = new Image(); 		add_a.src = 'pics/add_a.gif';
			
			info = new Image();			info.src = 'pics/info.gif';
			info_a = new Image(); 		info_a.src = 'pics/info_a.gif';

			login = new Image();		login.src = 'pics/<?= ($loggedin?'logout.gif':'login.gif')?>';
			login_a = new Image(); 		login_a.src = 'pics/<?= ($loggedin?'logout_a.gif':'login_a.gif')?>';
			
			up = new Image();			up.src = 'pics/up.gif';
			up_g = new Image(); 		up_g.src = 'pics/up_g.gif';
			
			down = new Image();			down.src = 'pics/down.gif';
			down_g = new Image(); 		down_g.src = 'pics/down_g.gif';
			
			windowargs = 'location=no,menubar=no,status=no,titlebar=no,toolbar=no,directories=no';
			
			function swap(imgID,imgObjName) {
				if(imgID != '')
					document.images[imgID].src = eval(imgObjName + ".src");
				document.images['<?= $sortarray[0]?>'.replace(' ','_')].src = '<?= strpos($sortarray[0],'DESC')>0?'pics/down.gif':'pics/up.gif'?>';
			}
			
			function tfilter(f){
				//sets the filter to title=f,genre=... and submits filterform.
				var allgenres=true;
				var genrenull = true;
				var query='SELECT SQL_CALC_FOUND_ROWS _COLS_ FROM movies WHERE ';
				document.filterform.genres.value = '';
								
				gquery='';
				for(i=0;;i++){
					if(!(cur=document.getElementById('genres_'+i)))
						break;
					if(cur.checked){
						document.filterform.genres.value += ','+cur.value;
						genrenull = false;
						if(gquery != '')
							gquery += ' OR ';
						gquery += ' FIND_IN_SET(\''+cur.value+'\',genre) ';
					} else
						allgenres = false;
				}
				
				if(allgenres){
					document.filterform.genres.value = '';
				} else if(genrenull){
					document.filterform.genres.value = '&lt;none&gt;';
					query += '( ISNULL(genre) )';
				} else					
					query += '('+gquery+')';
				
				if(f=='' && allgenres) query += '1';
				if(f!='' && !allgenres) query += ' AND ';
				
				rExp = /\'/gi;
				f = f.replace(rExp,'\\\'');
				if(f=='#'){
					document.filterform['filter'].value = query+'name REGEXP \'^[^a-zA-Z]\'';
					document.filterform.filtertitle.value = 'movies # ';
				}
				else if(f.length == 1){
					document.filterform['filter'].value = query+'name like \''+f+'%\'';
					document.filterform.filtertitle.value = 'movies '+f.toLowerCase();
				} else if(f!=''){
					document.filterform['filter'].value = query+'MATCH(movies.name,aka) AGAINST(\''+f+'\' IN BOOLEAN MODE)';
					document.filterform.filtertitle.value = 'matches for '+f.toLowerCase();
				} else {
					document.filterform['filter'].value = query;
					document.filterform.filtertitle.value = 'all movies';
				}
				
				document.filterform.page.value = '0';
				//alert(document.filterform['filter'].value); //debug
				document.filterform.submit();
			}
			
			function showOptions(f){
				var optionBox = document.getElementById('optionBox');
				var theBox = document.getElementById('navbox');
				if(f){
					// show search options
					optionBox.style.visibility = 'visible';
					//document.getElementById('navbox').borderBottomStyle = 'hidden';
					document.getElementById('navbrowse').innerHTML='<a onclick="showOptions(false)">hide genres</a>';
					theBox.style.height = '85px';
					window.scrollBy(0,65);
				} else {
					// no genre restrictions must be made
					checkGenres(true);
					if(document.filterform.genres.value != ''){
						// genre restrictions apply, remove
						document.filterform.genres.value = '';
						tfilter('');
					}
					// hide search options
					optionBox.style.visibility = 'hidden';
					document.getElementById('navbrowse').innerHTML='<a onclick="showOptions(true)">browse genres...</a>';
					theBox.style.height = '19px';
				}
			}
			
			function checkGenres(val){
				for(i=0;;i++){
					if(!(cur=document.getElementById('genres_'+i)))
						break;
					else
						cur.checked = val;
				}
			}
			function browseGenre(name){
				for(i=0;;i++){
					if(!(cur=document.getElementById('genres_'+i)))
						break;
					if(cur.value == name)
						cur.checked = true;
					else
						cur.checked = false;
				}
				tfilter('');
			}
			
			function sortby(s){
				var theBlank = s.indexOf('_');
				// dont sort after same column twice! check if this column already present
				for(i=0; i<<?= $sortsize-1 ?>;i++)
					if(document.filterform['sortby['+i+']'].value.substring(0,theBlank) == s.substring(0,theBlank))
						break;
				for(i;i>0;i-=1)
					document.filterform['sortby['+i+']'].value = document.filterform['sortby['+(i-1)+']'].value;	
				document.filterform['sortby[0]'].value = s.replace('_',' ');; 
				document.filterform.submit();
			}
			function showall(){
				//resets filter values but not sortby, sends filterform.
				document.filterform.genres.value = '';
				document.filterform['filter'].value = '';
				document.filterform.filtertitle.value = 'all movies';
				document.filterform.page.value = '0';
				document.filterform.submit();
			}
			function setpage(p){
				document.filterform.page.value = p;
				document.filterform.submit();
			}
			
			function submitenter(e){
				var keycode;
				if (window.event) keycode = window.event.keyCode;
				else if (e) keycode = e.which;
				else return true;
				if (keycode == 13){
 					tfilter(document.getElementById('title').value);
 				}else return true;
 			}
		-->
		</script>

	</head>

	<body onload="swap('',''); if(document.filterform.genres.value!='') showOptions(true); else showOptions(false);">
		<form name="filterform" action="index.php" method="post">
			<!-- specifies filter criteria for flicks, e.g. title like a% AND year = 1999 OR director like ... -->
			<!-- this is set with javascript. i hate javascript. you can't take seriously a language that allows 4 different ways to access array elements(all of which just might work, if you're lucky). but then again it can be very useful...-->
			<input type="hidden" name="page" value="<?= isset($_POST['page'])?$_POST['page']:'0' ?>"/>
			<input type="hidden" name="filtertitle" value="<?= isset($_POST['filtertitle'])?$_POST['filtertitle']:'all movies'?>"/>
			<!-- any valid WHERE clause here. director=..., actor=..., writer=... will be replaced with the correct joins -->
			<input type="hidden" name="filter" value="<?= $_POST['filter'] ?>"/>
<? for($i=0; $i<$sortsize; $i++) {?>
			<input type="hidden" name="sortby[<?= $i ?>]" value="<?= $sortarray[$i] ?>"/>
<? } ?>
			<input type="hidden" name="genres" value="<?= $_POST['genres'] ?>"/>
			<input type="hidden" name="login" value="<?= $loggedin?'1':'0' ?>"/>
			<!--indicates whether a login-check is done on page load. this is for performance reasons solely, since creating/destroying a session means writing/deleting a file each time! -->
		</form>
		<div id="mainheader">
			<div class="buttonsl">
				<img name="search" src="pics/search.gif" alt="search movie" onmouseover="swap('search','search_a')" onmouseout="swap('search','search')" onclick="window.open('find.php','','width=500,height=220,resizable=no,'+windowargs);"/>
				<img name="pr" src="pics/print.gif" alt="print movie list" onmouseover="swap('pr','pr_a')" onmouseout="swap('pr','pr')" onclick="window.open('print/index.php','','width=500,height=220,resizable=no,'+windowargs);"/>
				<img name="add" src="pics/add.gif" alt="add movie" onmouseover="swap('add','add_a')" onmouseout="swap('add','add')" onclick="window.open('members/add.php','','width=250,height=140,resizable=yes'+windowargs)"/>
			</div>
			<div class="buttonsr">
				<img id="login" src="pics/<?=($loggedin?'logout.gif':'login.gif')?>" alt="<?=($loggedin?'log out':'log in')?>" onmouseover="swap('login','login_a')" onmouseout="swap('login','login')" onclick="window.open('<?=($loggedin?'login.php?action=logout':'login.php')?>','','width=250,height=140,resizable=no'+windowargs)"/>
				<img name="info" src="pics/info.gif" alt="info/about php4flicks" onmouseover="swap('info','info_a')" onmouseout="swap('info','info')" onclick="window.open('info.php','','height=600,width=350,resizable=no,'+windowargs);"/>
			</div>
			<span class="welcome"><?= $loggedin?'welcome back, '.$_SESSION['user'].'!':'' ?></span>
		</div>
		<div id="indexmain">
			<table id="maintable">
				<thead>
				<tr>
					<th colspan="7"><?= stripslashes($_POST['filtertitle']);?> [<? if($rowcount == 0) echo 'no matches'; else{ echo $_POST['page']+1; echo '..'; echo $_POST['page']+min($cfg['nofflicks'],$rowcount-$_POST['page']); echo ' of '; echo $rowcount;} echo ']'?></th>
				</tr></thead>
				<tbody>
					<tr class="rowtitle">
						<td style="width: 40px">&nbsp;<br/><img name="nr_ASC" alt="^" src="pics/up_g.gif" onclick="sortby('nr_ASC')" onmouseover="swap('nr_ASC','up')" onmouseout="swap('nr_ASC','up_g')"/><img name="nr_DESC" alt="v" src="pics/down_g.gif" onclick="sortby('nr_DESC')" onmouseover="swap('nr_DESC','down')" onmouseout="swap('nr_DESC','down_g')"/></td>
						<td>title<br/><img name="name_ASC" alt="^" src="pics/up_g.gif" onclick="sortby('name_ASC')" onmouseover="swap('name_ASC','up')" onmouseout="swap('name_ASC','up_g')"/><img name="name_DESC" alt="v" src="pics/down_g.gif" onclick="sortby('name_DESC')" onmouseover="swap('name_DESC','down')" onmouseout="swap('name_DESC','down_g')"/></td>
						<td style="width: 160px">director<br/></td>
						<td style="width: 60px">year<br/><img name="year_ASC" alt="^" src="pics/up_g.gif" onclick="sortby('year_ASC')" onmouseover="swap('year_ASC','up');" onmouseout="swap('year_ASC','up_g')"/><img name="year_DESC" alt="v" src="pics/down_g.gif" onclick="sortby('year_DESC')" onmouseover="swap('year_DESC','down')" onmouseout="swap('year_DESC','down_g')"/></td>
						<td style="width: 60px">runtime<br/><img name="runtime_ASC" alt="^" src="pics/up_g.gif" onclick="sortby('runtime_ASC')" onmouseover="swap('runtime_ASC','up');" onmouseout="swap('runtime_ASC','up_g');"/><img name="runtime_DESC" alt="v" src="pics/down_g.gif" onclick="sortby('runtime_DESC')" onmouseover="swap('runtime_DESC','down')" onmouseout="swap('runtime_DESC','down_g')"/></td>
						<td style="width: 60px">medium<br/><img name="medium_ASC" alt="^" src="pics/up_g.gif" onclick="sortby('medium_ASC')" onmouseover="swap('medium_ASC','up');" onmouseout="swap('medium_ASC','up_g');"/><img name="medium_DESC" alt="v" src="pics/down_g.gif" onclick="sortby('medium_DESC')" onmouseover="swap('medium_DESC','down')" onmouseout="swap('medium_DESC','down_g')"/></td>
						<td style="width: 28px"/>
					</tr>
<?	$brow = true;
	while($row = mysql_fetch_array($result)){
		$directors = ''; directorsearch($directors,$row['fid']);
?>
					<tr class="row<?= $brow?'0':'1'?>">
						<td><?= $row['nr']?></td>
						<td><a style="cursor: pointer;" onclick="window.open('view.php?fid=<?= $row['fid']?>','','height=600,width=350,resizable=no,'+windowargs);"><?= $row['name'] ?></a></td> 
						<td><?= $directors?></td>
						<td><?= $row['year']?></td>
						<td><?= $row['runtime']?></td>
						<td><?= $row['medium']?></td>
						<td><a href="http://www.imdb.com/title/tt<?= $row['id']?>/" target="_blank"><img alt="imdblogo" src="pics/imdb.gif"/></a></td>
					</tr>		
<?		$brow = !$brow;	
	}
?>
				</tbody>
			</table>
			<div id="navbox">
				<span class="navbuttonsl"><a <? if($_POST['page'] != '0') echo 'onclick="setpage(0)"' ?>><img alt="&lt;" src="pics/left_.gif"/></a><a <? if($_POST['page']!='0') {echo 'onclick="setpage('; echo((integer)$_POST['page'])-$cfg['nofflicks']; echo ')"'; }?>><img alt="&lt;" src="pics/left.gif"/></a></span><a href="#" onclick="showall();">all</a><? for($i=97; $i<123; $i++) echo '<a href="#" onclick="tfilter(\''.chr($i).'\');">'.chr($i).'</a>'; ?><a href="#" onclick="tfilter('#')">&#35;</a><span class="navbuttonsr"><a <? $tmp = ((integer)$_POST['page'])+$cfg['nofflicks']; if($rowcount > $tmp) echo "onclick=\"setpage($tmp)\""?>><img alt="&gt;" src="pics/right.gif"/></a><a <? if($rowcount > $tmp) {$end = --$rowcount - (($rowcount) % $cfg['nofflicks']); echo "onclick=\"setpage($end)\"";} ?>><img alt="&gt;" src="pics/right_.gif"/></a></span><span id="navsearch">search:<input title="search in titles. usage:   [+|-]word {[+|-]word}   e.g.: american +beauty -pie" style="cursor:help;" type="text" class="navinput" id="title" onkeydown="submitenter(event);"/><a href="#" onclick="tfilter(document.getElementById('title').value);">go!</a></span><span id="navbrowse"><a onclick="showOptions(true);">browse genres...</a></span>
				<div id="optionBox">
					Select Genres for search: (<a onclick="checkGenres(true);">check all</a>&nbsp;/&nbsp;<a onclick="checkGenres(false);">uncheck all</a>)
					<table>
<?
				$sgenre = explode(',',$_POST['genres']);
				
				$i=0;
				foreach($cfg['genre'] as $m){
					if($i%11 == 0)
						echo '<tr>';
					echo '<td><input type="checkbox" id="genres_'.$i.'" value="'.$m.'" '.(in_array($m,$sgenre)?'checked="checked"':'').'/>'.$m.'</td>';
					$i++;
					if($i%11 == 0)
						echo '</tr>';
				}
				for($j=0; $j<11-$i%11; $j++)
					echo '<td/>';
?>			
					</tr></table>
				</div>
			</div>
		</div>
		<div id="footer">
			<span class="copy">php4flicks <?= $cfg['version'] ?></span>
		</div>
	</body>

</html>
<? 
	// this would be unnecessary if mysql supported views:(
	function directorsearch(&$out, $movieid){
		$res = mysql_query("SELECT people.name FROM directs,people WHERE directs.movie_fid = $movieid AND directs.people_id = people.id;") or die(mysql_error());
		while($row = mysql_fetch_row($res))
			($out ==''?$out .= $row[0] : $out .= ', '.$row[0]);
		return;
	}
?>
