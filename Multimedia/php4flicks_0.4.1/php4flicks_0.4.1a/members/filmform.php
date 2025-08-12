<?	
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

	/*script to display a form with all information found on a movie

	variables
	*	$actor,
	*	$writer,
	*	$director,
	*	$title,
	*	$runtime,
	*	$imdbid,
	*	$buttons (the buttons to be displayed in the filmform. each button is an array(name, source, on_mouseover_source, onclick action).)
	must be set externally!!!

	if the form is reloaded and $reload set, the script tries to get these variables, plus:
	*	$lang,
	*	$sound,
	*	$medium,
	*	$cat,
	*	$nr,
	*	$format,
	*	$ratio,
	*	$comment
	from the POST data. the buttons must be set externally in any case.
	
	*/

	
// config
require_once('../config/config.php');

if(!isset($buttons)) $buttons = array();

if(isset($reload)){
	$reload = true;
	// user has hit the '+=' button - he wants more fields!
	$title = htmlspecialchars(isset($_POST['title'])?$_POST['title']:'');
	$year = (isset($_POST['year'])?$_POST['year']:'1999');
	$director = (isset($_POST['director'])?$_POST['director']:array());
	$writer = (isset($_POST['writer'])?$_POST['writer']:array());
	$runtime = (isset($_POST['runtime'])?$_POST['runtime']:'0');
	$actor = (isset($_POST['actor'])?$_POST['actor']:array());
	($imdbid = $_POST['imdbid']) or die('no movie id.');
	$comment = (isset($_POST['comment'])?$_POST['comment']:'');
	$aka = (isset($_POST['aka'])?$_POST['aka']:'');
	$slang = $_POST['lang_array'];
	$ssound = $_POST['sound_array'];
	$sgenre = $_POST['genre_array'];
	$sformat = $_POST['format'];
	$sratio = $_POST['ratio'];
	$smedium = $_POST['medium'];
	$nr = $_POST['nr'];
	$cat = $_POST['cat'];
	$setposter = $_POST['setposter'];
	$fid = $_POST['fid'];
} else{
	if(!isset($slang)) $slang = array();
	if(!isset($ssound)) $ssound = array();
	if(!isset($sgenre)) $sgenre = array();
	if(!isset($sformat)) $sformat = '#';
	if(!isset($sratio)) $sratio = '#';
	if(!isset($smedium)) $smedium = '#';
	if(!isset($comment)) $comment = '';
	if(!isset($aka)) $aka = '';
	if(!isset($nr)) $nr = '';
	if(!isset($cat)) $cat = '';
	if(!isset($fid)) $fid = '';
}

$writersize = (isset($_GET['writersize'])?$_GET['writersize']:sizeof($writer));
$actorsize = (isset($_GET['actorsize'])?$_GET['actorsize']:sizeof($actor));
$directorsize = (isset($_GET['directorsize'])?$_GET['directorsize']:sizeof($director));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
		<title><?= $title; ?></title>
		<link rel="stylesheet" type="text/css" href="../config/flicks.css"/>
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
		<!--
			function resize(x,y){
				if(navigator.userAgent.indexOf('MSIE')>-1)
					window.resizeTo(x+10,y+20) //stupid ie thinks window size are outer measures
				else
					{window.innerWidth = x; window.innerHeight = y;}
			}
			
			function getperson(s){
				theUrl = 'getperson.php?'+s;
				mywindow = window.open(theUrl,'myname','resizable=yes,width=350,height=270,');
				if (mywindow.opener == null) mywindow.opener = self;
				mywindow.focus();
			}
			<?
if(!$cfg['old_browsers']){
			?>
			//don't try to understand what this does. i tried myself, and got nuts...
			var dirOffset = <?= $directorsize + 3 ?>;
			var writerOffset = <?= $directorsize + $writersize + 4 ?>;
			var actOffset = <?= $directorsize + $actorsize + $writersize + 6 ?>;
			var dirSize = <?= $directorsize ?>;
			var actSize = <?= $actorsize ?>;
			var writerSize = <?= $writersize ?>;
			
			function addRows(cat, nofrows){
				// displays additional fields for director, writer, actor entries
				// cat = 'actor' | 'director' | 'writer'
				switch(cat){
					case 'director':
						begin = dirOffset;
						dirOffset += nofrows; writerOffset += nofrows; actOffset += nofrows;
						var cell = document.getElementById('pic');
						cell.rowSpan+=nofrows;
						for(i=begin; i<dirOffset; i++){
							insert2(cat,i,dirSize++);
						}
						break;
					case 'writer':
						begin = writerOffset;
						writerOffset += nofrows; actOffset += nofrows;
						var cell = document.getElementById('pic');
						cell.rowSpan+=nofrows;
						for(i=begin; i<writerOffset; i++){
							insert2(cat,i,writerSize++);
						}
						break;
					case 'actor':
						var tbl = document.getElementById("formt");
						begin = actOffset;
						actOffset += nofrows;
						for(i=begin; i<actOffset; i++){
							var row = tbl.insertRow(i);
							var cell0 = row.insertCell(0);
							var cell1 = row.insertCell(1);
							var cell2 = row.insertCell(2);
							cell0.appendChild(inputh = document.createElement('input'));
							cell1.appendChild(input = document.createElement('input'));
							cell2.appendChild(link = document.createElement('a'));
							inputh.setAttribute('name','actor['+actSize+'][id]');
							input.setAttribute('name','actor['+actSize+'][name]');
							link.setAttribute('href','#');
							link.appendChild(document.createTextNode('x'));
							if ((!document.all)&&(document.getElementById)){
								inputh.setAttribute('type','hidden');
								input.setAttribute('class','inputmed');
								input.setAttribute("onChange","if(this.value!='') getperson('cat=actor&amp;idx="+actSize+"&amp;name='+this.value);");
								link.setAttribute("onClick","document.data['actor["+actSize+"][name]'].value=''");
							}
							if ((document.all)&&(document.getElementById)){
								link["onclick"]=new Function("document.data['actor["+actSize+"][name]'].value=''");
								input["onchange"] = new Function("if(this.value!='') getperson('cat=actor&amp;idx="+actSize+"&amp;name='+this.value);");
								inputh.setAttribute('id','actor['+actSize+'][id]');
								input.setAttribute('id','actor['+actSize+'][name]');
								inputh.setAttribute('name','actor['+actSize+'][id]');
								input.setAttribute('name','actor['+actSize+'][name]');
								inputh.style.display = 'none';
								input.className = 'inputmed';
							}
							actSize++;
						}
						break;
					}
				}
				
			function insert2(cat,i,size){
				// create & insert new rows	
				var tbl = document.getElementById("formt");
				var row = tbl.insertRow(i);
				var cell0 = row.insertCell(0);
				var cell1 = row.insertCell(1);
				cell0.appendChild(inputh = document.createElement('input'));
				cell0.appendChild(input = document.createElement('input'));
				cell1.appendChild(link = document.createElement('a'));
				inputh.setAttribute('name',cat+'['+size+'][id]');
				input.setAttribute('name',cat+'['+size+'][name]');
				link.setAttribute('href','#');
				link.appendChild(document.createTextNode('x'));
				// this is how it SHOULD work according to all standards...
				if ((!document.all)&&(document.getElementById)){
					inputh.setAttribute('type','hidden');
					input.setAttribute('class','inputmed');
					link.setAttribute("onClick","document.data['"+cat+"["+size+"][name]'].value=''");
					input.setAttribute("onChange","if(this.value!='') getperson('cat="+cat+"&amp;idx="+size+"&amp;name='+this.value);");
				}
				// and this is to fix ie bugs!!!
				if ((document.all)&&(document.getElementById)){
					link["onclick"]=new Function("document.data['"+cat+"["+size+"][name]'].value=''");
					input["onchange"] = new Function("if(this.value!='') getperson('cat="+cat+"&amp;idx="+size+"&amp;name='+this.value);");
					inputh.setAttribute('id',cat+'['+size+'][id]');
					input.setAttribute('id',cat+'['+size+'][name]');
					inputh.style.display = 'none';
					input.className = 'inputmed';
				}
			}
			
			<?
}

if(isset($cfg['cats'])){
			// function to automatically chose category,next free number when medium is selected
			?>
			function setcat(medium){
				var theCat = ''; var free = '';
				switch(medium){
				<?
				foreach($cfg['medium'] as $m){
					if(isset($cfg['cats'][$m])){
						echo 'case \''.$m.'\' : theCat = \''.$cfg['cats'][$m].'\';';
						//next free number
						if($nr==''){
							$res = mysql_query("SELECT MAX(nr)+1 as free FROM movies WHERE cat='".$cfg['cats'][$m]."' GROUP BY cat") or die(mysql_error());
							$row = mysql_fetch_row($res);
							echo "free='$row[0]'; break;";
						} else echo 'break;';
					}
				}
?>
				}
				if(free=='')
					free = '<?= ($nr==''?0:$nr) ?>';
				document.data.cat.value = theCat;
				document.data.nr.value = free;
			}
<?	}
			// the buttons
			foreach($buttons as $b){
				echo $b[0]."=new Image();".$b[0].".src = '".$b[1]."';\n";
				echo $b[0]."_a =new Image();".$b[0]."_a.src = '".$b[2]."';\n";
			}

?>

			function swap(imgName,img){
				document.images[imgName].src = eval(img + ".src");
			}
			
			function check(){
				if(document.data.title.value==''){
					alert('a title must be entered.'); document.data.title.focus(); return false;
				}
				if(document.data.nr.value==''){
					alert('a number must be entered.'); document.data.nr.focus(); return false;
				}
				
				var langChecked = false;				
				for(i=0;i<document.data['lang_array[]'].length;i++){
					if(document.data['lang_array[]'][i].checked)
						langChecked = true;
				}
				if(!langChecked){
					alert('a language must be selected.'); document.data['lang_array[]'][0].focus(); return false;
				}
				
				var soundChecked = false;				
				for(i=0;i<document.data['sound_array[]'].length;i++){
					if(document.data['sound_array[]'][i].checked)
						soundChecked = true;
				}
				if(!soundChecked){
					alert('a sound option must be selected.'); document.data['sound_array[]'][0].focus(); return false;
				}	
				
				return true;		
			}

		-->
		</script>
	</head>

	<body onload="resize(350,600); <? if($cat=='' && ($smedium!='#')) echo "setcat('$smedium')"; else if($cat=='' && isset($cfg['cats'])) echo 'setcat(\''.$cfg['medium'][0].'\');'?>">
		<div id="header"><?= $title ?></div>
		<form name="data" action="insert.php" method="post">
		<div id="mainpar">
			<input type="hidden" value="<?= $fid; ?>" name="fid"/>
			<input type="hidden" value="<?= $imdbid; ?>" name="imdbid"/>
			<input type="hidden" value="<?= $setposter; ?>" name="setposter"/>
			<table id="formt">
				<tr>
					<td colspan="2"><input type="text" class="inputmed" name="title" value="<?= $title; ?>"/>&nbsp;<input type="text" class="nr" name="year" value="<?= $year; ?>"/><a href="http://www.imdb.com/title/tt<?= $imdbid ?>" target="_blank"><img alt="imdblogo" style="position: relative; left:42px; top: 3px;" src="../pics/imdb.gif"/></a></td>
					<td></td>
				</tr>
				<tr>
					<td id="pic" rowspan="<?= $writersize+$directorsize+4 ?>"><img id="posterimg" alt="posterimg" src="../imgget.php?for=<?= ($setposter!=false && $setposter!='') ? $imdbid.'&amp;from=session' : $fid; ?>" width="95" height="150" border="0" onclick="window.open('editposter.php?id=<?= $imdbid ?>','myname','resizable=yes,width=350,height=220');"/></td>
					<td style="height:100%;"><input type="text" class="nr" name="cat" value="<?= $cat ?>"/>&nbsp;<input type="text" name="nr" class="nr" value="<?= $nr ?>"/></td>
					<td></td>
				</tr>
				<tr style="height:100%;">
					<td>directed by: <a href="#" onclick="<?= $cfg['old_browsers']?"document.data.action='".$referer."action=reload&directorsize=".(integer)($directorsize+2)."'; document.data.submit();":"addRows('director',2)" ?>">[+=]</a></td>
					<td></td>
				</tr>
<?
for($i=0; $i<sizeof($director); $i++){
?>
				<tr style="height:100%;">
					<td><input type="hidden" value="<?= $director[$i]['id'] ?>" name="director[<?=$i?>][id]"/> <input type="text" class="inputmed" name="director[<?=$i?>][name]" value="<?= $director[$i]['name'] ?>" onchange="if(this.value!='') getperson('cat=director&amp;idx=<?=$i?>&amp;name='+this.value);"/></td>
					<td><a href="#" onclick="document.data['director[<?=$i?>][name]'].value='';">x</a></td>
				</tr>
<?	}

for($i=sizeof($director); $i<$directorsize; $i++){
?>
				<tr style="height:100%;">
					<td><input type="hidden" name="director[<?=$i?>][id]"/><input type="text" class="inputmed" name="director[<?=$i?>][name]" onchange="if(this.value!='') getperson('cat=director&amp;idx=<?=$i?>&amp;name='+this.value);"/></td>
					<td><a href="#" onclick="document.data['actor[<?=$i?>][name]'].value='';">x</a></td>
				</tr>
<?	}?>
				<tr style="height:100%;">
					<td>written by: <a href="#" onclick="<?= $cfg['old_browsers']?"document.data.action='".$referer."action=reload&writersize=".(integer)($writersize+2)."'; document.data.submit();":"addRows('writer',2)" ?>">[+=]</a></td>
					<td></td>
				</tr>
<?
for($i=0; $i<sizeof($writer); $i++){
?>
				<tr style="height:100%;">
					<td><input type="hidden" value="<?= $writer[$i]['id'] ?>" name="writer[<?=$i?>][id]"/> <input type="text" class="inputmed" name="writer[<?=$i?>][name]" value="<?= $writer[$i]['name'] ?>" onchange="if(this.value!='') getperson('cat=writer&amp;idx=<?=$i?>&amp;name='+this.value);"/></td>
					<td><a href="#" onclick="document.data['writer[<?=$i?>][name]'].value='';">x</a></td>
				</tr>
<?	}

for($i=sizeof($writer); $i<$writersize; $i++){
?>
				<tr style="height:100%;">
					<td><input type="hidden" name="writer[<?=$i?>][id]"/><input type="text" class="inputmed" name="writer[<?=$i?>][name]" onchange="if(this.value!='') getperson('cat=writer&amp;idx=<?=$i?>&amp;name='+this.value);"/></td>
					<td><a href="#" onclick="document.data['writer[<?=$i?>][name]'].value='';">x</a></td>
				</tr>
<?	}?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>runtime:</td>
					<td><input type="text" class="nr" name="runtime" value="<?= $runtime; ?>"/></td>
					<td></td>
				</tr>
				<tr>
<?
if(sizeof($actor)==0) $actor=array(array('id'=>'','name'=>''));
?>
					<td>cast: <a href="#" onclick="<?= $cfg['old_browsers']?"document.data.action='".$referer."action=reload&actorsize=".(integer)($actorsize+5)."'; document.data.submit();":"addRows('actor',5)" ?>">[+=]</a><input type="hidden" value="<?= $actor[0]['id'] ?>" name="actor[0][id]"/></td>
					<td><input type="text" class="inputmed" name="actor[0][name]" value="<?= $actor[0]['name'] ?>" onchange="if(this.value!='') getperson('cat=actor&amp;idx=0&amp;name='+this.value);"/></td>
					<td><a href="#" onclick="document.data['actor[0][name]'].value='';">x</a></td>
				</tr>
<?
for($i=1; $i<sizeof($actor); $i++){
?>
				<tr>
					<td><input type="hidden" value="<?= $actor[$i]['id'] ?>" name="actor[<?= $i?>][id]"/></td>
					<td><input type="text" class="inputmed" name="actor[<?= $i?>][name]" value="<?= $actor[$i]['name'] ?>" onchange="if(this.value!='') getperson('cat=actor&amp;idx=<?=$i?>&amp;name='+this.value);"/></td>
					<td><a href="#" onclick="document.data['actor[<?=$i?>][name]'].value='';">x</a></td>
				</tr>
<?	}

for($i=sizeof($actor); $i<$actorsize; $i++){
?>
				<tr>
					<td><input type="hidden" name="actor[<?= $i?>][id]"/></td>
					<td><input type="text" class="inputmed" name="actor[<?= $i?>][name]" onchange="if(this.value!='') getperson('cat=actor&amp;idx=<?=$i?>&amp;name='+this.value);"/></td>
					<td><a href="#" onclick="document.data['actor[<?=$i?>][name]'].value='';">x</a></td>
				</tr>
<?	}?>
				<tr>
					<td>genres:</td>
					<td>
						<table>
						<?
						$odd=false;
						foreach($cfg['genre'] as $x){
							echo(($odd?'':'<tr>').'<td><input type="checkbox" name="genre_array[]" value="'.$x.'"'.(in_array($x,$sgenre)?' checked="checked"':'').'/>'.$x.'</td>'.($odd?'</tr>':''));
							$odd = !$odd;
						}
						echo($odd?'</tr>':'<td/></tr>');
						?>
						</table>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>a.k.a title</td>
					<td><textarea class="tarea" name="aka" rows="3" cols="50"><?= $aka ?></textarea></td>
					<td><a href="#" onclick="document.aka.comment.value='';">x</a></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>medium</td>
					<td><select name="medium" class="select"<? if(isset($cfg['cats'])) echo "onchange=\"setcat(this.value);\"";?>>
						<?
						foreach($cfg['medium'] as $x)
							echo("<option value=\"$x\"".($smedium==$x?' selected="selected"':'').">$x</option>");
						?>
						</select></td>
					<td></td>
				</tr>
				<tr>
					<td>format</td>
					<td><select name="format" class="select">
						<?
						foreach($cfg['format'] as $x)
							echo("<option value=\"$x\"".($sformat==$x?' selected="selected"':'').">$x</option>");
						?>
						</select></td>
					<td></td>
				</tr>
				<tr>
					<td>ratio</td>
					<td><select name="ratio" class="select">
						<?
						foreach($cfg['ratio'] as $x)
							echo("<option value=\"$x\"".($sratio==$x?' selected="selected"':'').">$x</option>");
						?>
						</select></td>
					<td></td>
				</tr>
				<tr>
					<td>lang</td>
					<td>
						<?
						foreach($cfg['lang'] as $x){
							echo('<input type="checkbox" name="lang_array[]" value="'.$x.'"'.(in_array($x,$slang)?' checked="checked"':'').'/>'.$x.'&nbsp;');
						}
						?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>sound</td>
					<td>
						<?
						foreach($cfg['sound'] as $x)
							echo('<input type="checkbox" name="sound_array[]" value="'.$x.'"'.(in_array($x,$ssound)?' checked="checked"':'').'/>'.$x.'&nbsp;');
						?>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>comment</td>
					<td><textarea class="tarea" name="comment" rows="3" cols="50"><?= $comment ?></textarea></td>
					<td><a href="#" onclick="document.data.comment.value='';">x</a></td>
				</tr>
			</table>
		</div>
		</form>
		<div id="footer">
		<?	
			foreach($buttons as $b)
				echo '<img name="'.$b[0].'" alt="'.$b[0].'" src="'.$b[1].'" onmouseover="swap(\''.$b[0].'\',\''.$b[0].'_a\')" onmouseout="swap(\''.$b[0].'\',\''.$b[0].'\')" onclick="'.$b[3]."\"/>&nbsp;";

		?>
		</div>
	</body>
</html>
