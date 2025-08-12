<?	/*	php4flicks movie database (c) mr.Fox					*
 	*	released under the GNU General Public License				*
 	*	contact and additional information: http://php4flicks.ch.vu		*/

	/* importcsv.php - allows you to import a movie list in csv format into php4flicks
	** csv format: {title,nr[,medium[,imdbid]]}
	*/
	
	session_start();
	if(!isset($_SESSION['user'])){
		header('location: ../login.php');
	}

	if($_SERVER['REQUEST_METHOD']=='POST'){
		// store uploaded .csv file in session.
	
		// mark file as non-existent if not present.
		isset($_FILES['file']) or $_FILES['file']['size'] = 0;
			
		$_FILES['file']['size']>0 or $_FILES['file']['error'] = 4;
		//otherwise nonexistent files cause no error => bug?
	
		if(!$_FILES['file']['error']){
			// no error, so get csv data and go to add page
			$handle = fopen($_FILES['file']['tmp_name'], 'rb');
			unset($_SESSION['import']);
			$csvdata = fread($handle, filesize($_FILES['file']['tmp_name']));
			$csvarr = csv_get_lines($csvdata);
			$_SESSION['import'] = array_reverse($csvarr);
			fclose($handle);
			header('Location: add.php?action=import');
		} else {
			switch($_FILES['file']['error']){
				case 1:
				case 2: $err = 'the file is too large.'; break;
				case 3: $err = 'file upload was interrupted.'; break;
				case 42: $err = 'please select a .gif or .jpg file!'; break;
				default: $err = 'no file was uploaded.';
			}
?>

		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
			<html>
				<head>
					<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
					<title>CSV IMPORT</title>
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
						back = new Image();		back.src = '../pics/back.gif';
						back_a = new Image(); 	back_a.src = '../pics/back_a.gif';

						function swap(imgID,img) {
							//imgID: img name, imgObjName: new image!
							document.images[imgID].src = eval(img + ".src");
						}
					</script>
				</head>

				<body>
					<div id="header">Error</div>
					<div id="content">
						<div class="rowtitle">The following error occured:</div><?= $err ?>
					</div>
					<div id="footer">
						<img name="back" alt="go back" src="../pics/back.gif" onmouseover="swap('back','back_a')" onmouseout="swap('back','back')" onclick="history.back();"/>&nbsp;
					</div>
				</body>
			</html>		
<?		
		}
	} else {
		//no POST data, show form
?>
		
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
			<html>
				<head>
					<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
					<title>Import From file</title>
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
					go = new Image();		go.src = '../pics/go.gif';
					go_a = new Image(); 	go_a.src = '../pics/go_a.gif';

					stop = new Image();	stop.src = '../pics/stop.gif';
					stop_a = new Image(); 	stop_a.src = '../pics/stop_a.gif';

					function swap(imgID,img) {
						//imgID: img name, imgObjName: new image!
						document.images[imgID].src = eval(img + ".src");
					}
			
					function submitenter(myfield,e){
						var keycode;
						if (window.event) keycode = window.event.keyCode;
						else if (e) keycode = e.which;
						else return true;
						if (keycode == 13){
 							myfield.form.submit();
 							return false;
 						}else return true;
 					}
 					function resize(x,y){
						if(navigator.userAgent.indexOf('MSIE')>-1)
							window.resizeTo(x+20,y+40) //stupid ie thinks window size are outer measures
						else
							{window.innerWidth = x; window.innerHeight = y+10;}
							//the +10 is to correct a strange bug in moz, which otherwise changes the window size on each reload...
					}
				</script>
			</head>

			<body onload="resize(530,220); document.data.file.focus(); ">
				<div id="header">Select file:</div>
				<div id="content">
					<form enctype="multipart/form-data" name="data" action="importcsv.php" method="post">
						<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
						<table id="restable">
							<tr>
								<td colspan="2" class="rowtitle">Please select a comma separated text file to import movies from.</td>
							</tr><tr>
								<td>file:</td><td><input type="file" class="inputmed" name="file" onkeydown="submitenter(this,event)"/></td>
							</tr><tr>
								<td colspan="2" style="height: 100%">&nbsp;</td>
							</tr>
						</table>
					</form>
					The format of the imported file must be {title,nr[,medium[,imdb-id]]}.<br/>
					Text delimiter is ". Title and nr must not be empty. If an imbd-id is specified, the title is ignored.
				</div>
				<div id="footer">
					<img name="stop" alt="abort" src="../pics/stop.gif" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')" onclick="window.close();"/>
					<img name="go" alt="get new poster" src="../pics/go.gif" onclick="document.data.submit();" onmouseover="swap('go','go_a')" onmouseout="swap('go','go')"/>&nbsp;
				</div>
			</body>
		</html>

<?	}	
	
	function csv_get_lines($str)
	// remove crap and split lines
	{
		$newstr = str_replace("\r\n","\n",$str); //win-style line breaks
		$newstr = str_replace('"','',$newstr);
		$newstr = str_replace("\t",'',$newstr);
		return explode("\n",$newstr);
	}



?>

