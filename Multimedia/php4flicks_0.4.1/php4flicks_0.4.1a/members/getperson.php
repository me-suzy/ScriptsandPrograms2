
<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/


// this script is called when the value of an actor, director, or writer field in the filmform-form is changed.
// it automatically searches for matching names in the imdb and enters the selected name in the form.
// fetch_person.php is used to do the searching.

require_once('imdb/fetch_person.php');
require_once('../config/config.php');

$FetchPerson = new fetch_person($cfg['actSearchLimit'],$cfg['actCats']);

	switch($_GET['cat']){
		case 'actor':
			$FetchPerson->cats = array('actors','actresses'); break;
		case 'director':
			$FetchPerson->cats = array('directors'); break;
		case 'writer':
			$FetchPerson->cats = array('writers'); break;
		default: 
			// default value is set in fetch_person.php and is ('Actor','Actress','Director','Writer')
			break;
	}

	$ret = $FetchPerson->DoSearch($out, $_GET['name']);
 	//function doSearch(&$out, $SearchString, $EntryUrl) {
 	//Where $FormUrl is for the search-again-form
	//and $EntryUrl the link for the found entries
	if($ret==PML_FETCH_SEARCHERROR) die('search-error');
	if($ret==PML_FETCH_SEARCHDONE){ 
	// search sucessfully done, the found items are displayed as links - now the user has to select one
	// display all persons: fetch_person generates a table with all found results, if >1 match found
?>
	<html>
		<head>
			<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
			<title>Search Results</title>
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
			<script language="JavaScript">
				function resize(x,y){
					if(navigator.userAgent.indexOf('MSIE')>-1)
						window.resizeTo(x+20,y+40) //stupid ie thinks window size are outer measures
					else
						{window.innerWidth = x; window.innerHeight = y;}
				}
				function setperson(id,name){
					opener.document.data['<?= $_GET['cat']?>[<?=$_GET['idx']?>][id]'].value = id;
					opener.document.data['<?= $_GET['cat']?>[<?=$_GET['idx']?>][name]'].value = name;
					window.close();
				}
			</script>
		</head>
		<body onload="resize(350,600)">
      		<div id="header">click a person to select:</div>
       		<div class="content">
       			<div id="mainpar">
       			<?= $out;?>
       			</div>
       		</div>
       		<div id="footer"/>
       	</body>
 	</html>
<?	
	exit;
	}
	//or else $ret must be PML_FETCH_EXACTMATCH - a exact match was found, the user doesn't have to do anything more
	//so just enter the value in the form.
?>
	<html>
		<head>
		<script language="JavaScript">
			function setperson(){
				opener.document.data['<?= $_GET['cat']?>[<?=$_GET['idx']?>][id]'].value = '<?=$FetchPerson->FetchID?>';
				opener.document.data['<?= $_GET['cat']?>[<?=$_GET['idx']?>][name]'].value = '<?=$FetchPerson->_actorName?>';
				window.close();
			}
		</script>
		</head>
		<body onload="setperson();"/>
	</html>
