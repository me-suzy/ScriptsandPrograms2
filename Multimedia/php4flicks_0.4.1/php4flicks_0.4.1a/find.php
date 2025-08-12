<?  
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

require('config/config.php');?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>Find</title>
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
		<script type="text/javascript"><!--
			stop = new Image();			stop.src = 'pics/stop.gif';
			stop_a = new Image(); 		stop_a.src = 'pics/stop_a.gif';

			go = new Image();			go.src = 'pics/go.gif';
			go_a = new Image(); 		go_a.src = 'pics/go_a.gif';
			
			function swap(imgID,imgObjName) {
				//imgID: old image, imgObjName: new image!
				document.images[imgID].src = eval(imgObjName + ".src");
			}
			function doquery(){
				// generates a SELECT clause to be submitted
				// _COLS_ will be replaced by needed column values, ORDER BY and LIMIT are appended
				
				var filtertitle = 'search results: ';
				var allmedia = true;
				var allgenres = true;
				var bquery1 = document.data.filter1for.value !='';
				var bquery2 = document.data.filter2for.value !='';
			
				if(bquery2 && !bquery1){
					alert('you must specify a 1st filter criterium if you enter AND|OR <filter2>'); return;
				}
				if(bquery1 && document.data.filter1value.value ==''){
					alert('please enter a value for filter \''+document.data.filter1for[document.data.filter1for.selectedIndex].text+' '+document.data.filter1mode.value+' ...\''); return;
				}
				if(bquery2 && document.data.filter2value.value ==''){
					alert('please enter a value for filter \''+document.data.filter2for[document.data.filter2for.selectedIndex].text+' '+document.data.filter1mode.value+' ...\''); return;
				}
				
				var query = 'SELECT SQL_CALC_FOUND_ROWS _COLS_ FROM ';
				rExp = /\'/gi;	// to escape apostrophes
				
				// joins?
				switch(document.data.filter1for[document.data.filter1for.selectedIndex].text){
					case 'director':
						query += 'movies,directs,people';
						break;
					case 'writer':
						query += 'movies,writes,people';
						break;
					case 'actor':
						query += 'movies,plays_in,people';
						break;
					default:
						query += 'movies';
				}
				query += ' WHERE ';

				// media
				var mquery = '';
				for(i=0; i<document.data.medium.length; i++){
					if(document.data.medium[i].selected){
						if(mquery!='')
							mquery += ' OR ';
						mquery += document.data.medium[i].value;
					} else allmedia = false;
				}
				if(!allmedia)
					query += '(' + mquery+') ';
			
				// genres
				var gquery = '';
				for(i=0; i<document.data.genre.length; i++){
					if(document.data.genre[i].selected){
						if(gquery!='')
							gquery += ' OR ';
						gquery += document.data.genre[i].value;
					} else allgenres = false;
				}		
				if(!allgenres){
					if(!allmedia)
						query += 'AND ('+gquery+')';
					else
						query += '('+gquery+')';
				}
				
				if(allgenres && allmedia && !bquery1){
					query += '1';
					filtertitle = 'all movies ';
				}
				
				// 1st filter criterium
				if(bquery1){
					if(!allgenres || !allmedia)
						query += ' AND ';
					switch(document.data.filter1mode.value){
						case 'LIKE':
							query += '('+document.data.filter1for.value+' LIKE \'%'+document.data.filter1value.value.replace(rExp,'\\\'')+'%\'';
							break;
						
						case '=':
							query += '('+document.data.filter1for.value+' = \''+document.data.filter1value.value.replace(rExp,'\\\'')+'\'';
							break;
						
						case 'fulltext':
							query += '(MATCH(movies.name,aka) AGAINST(\''+document.data.filter1value.value.replace(rExp,'\\\'')+'\' IN BOOLEAN MODE)';
					}
				}
				
				// 2nd filter criterium
				if(bquery2){
					// use ... UNION ... instead of WHERE ... OR ... since it is a LOT faster
					if(document.data.filter2andor.value==' OR '){
						document.data.filter2andor[document.data.filter2andor.selectedIndex].value = '';
						query += ') UNION (SELECT _COLS_ FROM ';
						switch(document.data.filter2for[document.data.filter2for.selectedIndex].text){
							case 'director':
								query += 'movies,directs,people';
								break;
							case 'writer':
								query += 'movies,writes,people';
								break;
							case 'actor':
								query += 'movies,plays_in,people';
								break;
							default:
								query += 'movies';
						}
						query += ' WHERE '
					}
					switch(document.data.filter2mode.value){
						case 'LIKE':
							query += ' '+document.data.filter2andor.value+document.data.filter2for.value+' LIKE \'%'+document.data.filter2value.value.replace(rExp,'\\\'')+'%\')';
							break;
						
						case '=':
							query += ' '+document.data.filter2andor.value+document.data.filter2for.value+' = \''+document.data.filter2value.value.replace(rExp,'\\\'')+'\')';
							break;
						
						case 'fulltext':
							query += ' '+document.data.filter2andor.value+' MATCH(movies.name,aka) AGAINST(\''+document.data.filter2value.value.replace(rExp,'\\\'')+'\' IN BOOLEAN MODE))';
					}
				} else if(bquery1) query += ')';
				
				if(query != ''){
					opener.document.filterform['filter'].value = query;
					opener.document.filterform.filtertitle.value = filtertitle;
					opener.document.filterform.genres.value = '';
					opener.document.filterform.submit();
					//document.write(query); //debug
					window.close();
				}
			}
			
			function listOps(i){
				var mode = eval('document.data.filter'+i+'mode');
				if(eval('document.data.filter'+i+'for.value')=='movies.name'){
					mode.options[2] = new Option();
					mode.options[2].value = 'fulltext';
					mode.options[2].text = 'fulltext';
					mode.selectedIndex = 2;
				} else
					mode.length = 2;
			}
			
			function submitenter(e){
				var keycode;
				if (window.event) keycode = window.event.keyCode;
				else if (e) keycode = e.which;
				else return true;
				if (keycode == 13)
 					doquery();
 				else return true;
 			}
		--></script>
	</head>
	<body style="overflow: hidden">
		<div id="header">Search for Movies:</div>
		<div id="mainpar">
		<form name="data" action="get">
			<table id="restable">
				<tr>
					<td class="rowtitle">find in</td>
					<td><select name="medium" size="5" multiple="multiple" class="select">
						<?
						foreach($cfg['medium'] as $m)
							echo "<option value=\" medium='$m' \" selected=\"selected\">$m</option>";
						?>
						</select>
					</td>
					<td class="rowtitle">genres:</td>
					<td><select name="genre" size="5" multiple="multiple" class="select">
						<option value=" ISNULL(genre) " selected="selected">&lt;none&gt;</option>
						<?
						foreach($cfg['genre'] as $m)
							echo "<option value=\"FIND_IN_SET('$m',genre)\" selected=\"selected\">$m</option>";
						?>
						</select>
					</td>
				</tr>	
				<tr>
					<td class="rowtitle">where...</td>
					<td><select name="filter1for" class="select" onchange="listOps(1);">
						<option value="">select...</option>
						<option value="directs.movie_fid = movies.fid AND directs.people_id = people.id AND people.name">director</option>
						<option value="writes.movie_fid = movies.fid AND writes.people_id = people.id AND people.name">writer</option>
						<option value="plays_in.movie_fid = movies.fid AND plays_in.people_id = people.id AND people.name">actor</option>
						<option value="movies.name">title / a.k.a.</option>
						<option value="cat">category</option>
						<option value="nr">number</option>
						<option value="year">year</option>
						<option value="lang">language</option>
						<option value="format">format</option>
						<option value="sound">sound</option>
						<option value="comment">comment</option>
						</select>
					</td>
					<td><select name="filter1mode" class="selectsmall">
						<option value="LIKE">like</option>
						<option value="=">=</option>
						</select>
					</td>
					<td><input class="inputmed" name="filter1value" onkeydown="submitenter(event);"/></td>
				</tr>
				<tr>
					<td><select name="filter2andor" class="selectsmall">
						<option value=" AND ">AND</option>
						<option value=" OR ">OR</option>
						</select>
					</td>
					<td><select name="filter2for" class="select" onchange="listOps(2);">
						<option value="">select...</option>
						<option value="directs.movie_fid = movies.fid AND directs.people_id = people.id AND people.name">director</option>
						<option value="writes.movie_fid = movies.fid AND writes.people_id = people.id AND people.name">writer</option>
						<option value="plays_in.movie_fid = movies.fid AND plays_in.people_id = people.id AND people.name">actor</option>
						<option value="movies.name">title / a.k.a.</option>
						<option value="cat">category</option>
						<option value="nr">number</option>
						<option value="year">year</option>
						<option value="lang">language</option>
						<option value="format">format</option>
						<option value="sound">sound</option>
						<option value="comment">comment</option>
						</select>
					</td>
					<td><select name="filter2mode" class="selectsmall">
						<option value="LIKE">like</option>
						<option value="=">=</option>
						</select>
					</td>
					<td><input class="inputmed" name="filter2value" onkeydown="submitenter(event);"/></td>
				</tr>
			</table>
		</form>
		</div>
		<div id="footer">
			<img name="stop" alt="abort" src="pics/stop.gif" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')" onclick="window.close();"/>
			<img name="go" alt="find it!" src="pics/go.gif" onclick="doquery()" onmouseover="swap('go','go_a')" onmouseout="swap('go','go')"/>&nbsp;
		</div>
	</body>
</html>

