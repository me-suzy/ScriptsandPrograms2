<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

session_start();
if(!isset($_SESSION['user'])){
	header('location: ../login.php?location=members/add.php');
}

/*
add.php - display search form, get information from imdb and display it. uses fetch_movie class.
pml fetch class is used to fetch data from imdb. available vars of doFetch():

	"Title",			string
	"Year",				int
	"Poster",			url
	"Director",			array of array(string'id',string'name')
	"Credits",			array of array(string'id',string'name')
	"Genre",			array of string
	"Rating",			real
	"Starring",			array of array(string'id',string'name')
	"Plot",				string
	"Release",			date (1999-03-07)
	"Runtime",			int
	"imdbid",			string
	"aka"				string
*/

require_once('../config/config.php');
require_once('imdb/fetch_movie.php');

$FetchClass = new fetch_movie($cfg['searchLimit'],$cfg['actorLimit']);

$import = false;
if(!isset($_GET['action'])){
	if(isset($_SESSION['import']))
		$_GET['action'] = 'import';
	else
		$_GET['action']='';
}

switch($_GET['action']) {
    case '':
    	// display form to enter movie title
        include('movietitleform.html');
        break;

	/* import feature for csv files - added 05.11.04 by mrfox 
	** csv format: {title,nr[,medium[,imdbid]]}
	*/
	case 'import':
		$import = true;
		if(!isset($_SESSION['import'])){
			header('Location: importcsv.php');
			break;
		} else {
			// read from import array as long as possible
			if($line = array_pop($_SESSION['import'])){
				$values = explode(',',$line);
				if(isset($values[3]) && $values[3] != ''){
					//imdb id set, go fetch it
					if($values[2]!='')
						header('Location: add.php?action=fetch&FetchID='.$values[3].'&nr='.$values[1].'&medium='.$values[2]);
					else
						header('Location: add.php?action=fetch&FetchID='.$values[3].'&nr='.$values[1]);
				}
				
				if(isset($values[2]) && $values[2] != '')
					$ret = $FetchClass->DoSearch($out,$values[0],'add.php?action=fetch&amp;nr='.$values[1].'&amp;medium='.$values[2]);
				else
					$ret = $FetchClass->DoSearch($out,$values[0],'add.php?action=fetch&amp;nr='.$values[1]);
			} else {
				// whole list imported, clear array and exit
				header('Location: add.php?action=stopimport');
			}
		}
		// fall-through!
            		
            	
    case 'search':
    	// search IMDB for movie title entered in form above!
    	if(!$import)
        	$ret = $FetchClass->DoSearch($out, $_GET['title'], "add.php?action=fetch"); 
        //function doSearch(&$out, $SearchString, $EntryUrl) {
        //Where $FormUrl is for the search-again-form
        //and $EntryUrl the link for the found entries
        if($ret==PML_FETCH_SEARCHERROR)
            die('search-error');

        if($ret==PML_FETCH_SEARCHDONE){ //search sucessfully done, the found items are displayed as links - now the user has to select one
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
		<html>
			<head>
				<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
				<title>Search Results</title>
				<link rel="stylesheet" type="text/css" href="../config/flicks.css"/>
				<script type="text/JavaScript">
					back = new Image();	back.src = '../pics/back.gif';
					back_a = new Image(); 	back_a.src = '../pics/back_a.gif';
					abort = new Image();	abort.src = '../pics/abort.gif';
					abort_a = new Image(); 	abort_a.src = '../pics/abort_a.gif';

					function resize(x,y){
						if(navigator.userAgent.indexOf('MSIE')>-1)
							window.resizeTo(x+10,y+20) //stupid ie thinks window size are outer measures
						else
							{window.innerWidth = x; window.innerHeight = y;}
					}
	
					function swap(imgID,img) {
						//imgID: img name, imgObjName: new image!
						document.images[imgID].src = eval(img + ".src");
					}
				</script>
			</head>

			<body onload="resize(350,600)">
        		<div id="header">Search Results:</div>
        		<div id="mainpar">
        			<?= $out;?>
        		</div>
        		<div id="footer">
					<img name="back" alt="back" src="../pics/back.gif" onmouseover="swap('back','back_a')" onmouseout="swap('back','back')" onclick="history.back();"/>&nbsp;<?= $import?'<img name="abort" alt="abort import" src="../pics/abort.gif" onmouseover="swap(\'abort\',\'abort_a\')" onmouseout="swap(\'abort\',\'abort\')" onclick="location.href=\'add.php?action=stopimport\';"/>&nbsp;':'' ?>
				</div>
        	</body>
        </html>
<?
        break;
		}
        //or else $ret must be PML_FETCH_EXACTMATCH - a exact match was found, the user doesn't have to do anything more //continue fetching:
    
    case 'fetch':
    	// get all the imdb data and display it
    	if($FetchClass->FetchID=='') $FetchClass->FetchID=$_GET['FetchID'];
    	if($FetchClass->FetchID=='') die('Searching error..');
 
    	// IMDB ID
		$FetchClass->DoFetch($imdbid,'imdbid');
		//title
		$FetchClass->DoFetch($title,'Title');
		//year
		$FetchClass->DoFetch($year,'Year');
		//poster
		if($FetchClass->DoFetch($poster,'Poster') == PML_FETCH_OK){
			//fetch poster: (=>$_SESSION['image'][_movieid_])
			include('fetchimg.php');
			fetchimg($imdbid,$poster);
			$setposter = true;
		} else
			$setposter = false;
		//director
		$FetchClass->DoFetch($director,'Director');
		//credits
		$FetchClass->DoFetch($writer,'Credits');
		//runtime
		$FetchClass->DoFetch($runtime,'Runtime');
		//actors
		$FetchClass->DoFetch($actor,'Starring');
		// alternative titles
		$FetchClass->DoFetch($aka,'aka');
		// genres
		$FetchClass->DoFetch($sgenre,'Genre');
		// now show it!
	
		if(isset($_GET['nr'])){
    		$nr = $_GET['nr'];
    		if(isset($_GET['medium']))
    			$smedium=$_GET['medium'];
    		
			$referer= 'add.php?import=1&amp;';
			$buttons = array(	//buttons to be displayed in filmform
				array('stop','../pics/abort.gif','../pics/abort_a.gif', 'location.href=\'add.php?action=stopimport\';'),
				array('insert','../pics/go.gif','../pics/go_a.gif', "document.data.action='update.php'; if(check()){document.data.submit(); this.onclick='return false';}"),
				array('skip','../pics/skip.gif','../pics/skip_a.gif', 'location.href=\'add.php\';')
			);
		} else {
			$referer= 'add.php?';
			$buttons = array(	//buttons to be displayed in filmform
    			array('back','../pics/back.gif','../pics/back_a.gif', 'history.back();'),
				array('stop','../pics/stop.gif','../pics/stop_a.gif', 'window.close();'),
				array('insert','../pics/go.gif','../pics/go_a.gif', "document.data.action='update.php'; if(check()){document.data.submit(); this.onclick='return false';}")
			);
		}
		
		include('filmform.php');
		break;
		
	case 'reload':
		$reload = true;		//if this is set, filmform gets its data from POST array
		if(isset($_GET['import'])){
			$referer= 'add.php?import=1&amp;';
			$buttons = array(	//buttons to be displayed in filmform
				array('stop','../pics/abort.gif','../pics/abort_a.gif', 'location.href=\'add.php?action=stopimport\';'),
				array('insert','../pics/go.gif','../pics/go_a.gif', "document.data.action='update.php'; if(check()){document.data.submit(); this.onclick='return false';}"),
				array('skip','../pics/skip.gif','../pics/skip_a.gif', 'location.href=\'add.php\';')
			);
		} else {
			$referer= 'add.php?';
			$buttons = array(	//buttons to be displayed in filmform
    			array('back','../pics/back.gif','../pics/back_a.gif', 'history.back();'),
				array('stop','../pics/stop.gif','../pics/stop_a.gif', 'window.close();'),
				array('insert','../pics/go.gif','../pics/go_a.gif', "document.data.action='update.php'; if(check()){document.data.submit(); this.onclick='return false';}")
			);
		}
		include('filmform.php');
		break;


	case 'stopimport':
		// clear import array and exit
		unset($_SESSION['import']);
?>
		<html>
			<head><title>import aborted</title></head>
			<body onload="window.close()"/>
		</html>
<?
}	// end switch	
?>
