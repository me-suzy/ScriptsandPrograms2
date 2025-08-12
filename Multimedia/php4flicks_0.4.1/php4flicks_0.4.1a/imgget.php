<?	/*	php4flicks movie database (c) mr.Fox					*
 	*	released under the GNU General Public License				*
 	*	contact and additional information: http://php4flicks.ch.vu		*/

	// imgget.php - gets an image, given a movie id
	// if from=session is past in GET, $_SESSION is checked, else its fetched from db (default)
	
	if(!isset($_GET['from'])) 
		$_GET['from'] = '';
	
	switch($_GET['from']){
		case 'session':
			session_start();
			if(!isset($_SESSION['image'][$_GET['for']]) || $_SESSION['image'][$_GET['for']]==''){
				header('Location: pics/nopic.gif'); 
				exit;
			}
			header('Cache-Control: no-cache');
			if(substr($_SESSION['image'][$_GET['for']],0,3)=='GIF')
				header('Content-Type: image/gif');
			else
				header('Content-Type: image/jpeg');
			header('Content-Disposition: inline');	
			echo $_SESSION['image'][$_GET['for']];
			break;

		case 'db':
		default:
			//get it from db!
			require('config/config.php');
			$result = mysql_query('SELECT poster FROM movies WHERE movies.fid = \''.$_GET['for'].'\'') or die(mysql_error());
			$pic = mysql_fetch_row($result);
			if($pic[0]){
				if(substr($pic[0],0,3)=='GIF')
					header('Content-Type: image/gif');
				else
					header('Content-Type: image/jpeg');
				header('Content-Disposition: inline');	
				echo $pic[0];
			} else 
				header('Location: pics/nopic.gif');
	} // end switch
?>
