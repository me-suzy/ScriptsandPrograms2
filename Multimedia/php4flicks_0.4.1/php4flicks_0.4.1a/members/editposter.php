<?	/*	php4flicks movie database (c) mr.Fox					*
 	*	released under the GNU General Public License				*
 	*	contact and additional information: http://php4flicks.ch.vu		*/

	// editposter.php - allows to edit an image file for a movie
	
	session_start();
	if(!isset($_SESSION['user'])){
		header('location: ../login.php');
	}

	if($_SERVER['REQUEST_METHOD']=='POST'){
		include_once('fetchimg.php');
		if(isset($_POST['noposter'])){
?>
			<html>
				<head>
				<script language="JavaScript">
					function setposter(){
						opener.document.images['posterimg'].src = '../pics/nopic.gif';
						// appending the time to the poster url is just a stupid trick to force a reload
						opener.document.data.setposter.value = false;
						window.close();
					}
				</script>
				</head>
				<body onload="setposter();"/>
			</html>
<?		
		}
		if($_POST['url'] == '')
			$res = fetchimg($_GET['id']);
		else
			$res = fetchimg($_GET['id'],$_POST['url']);
		if($res ==''){
?>
			<html>
				<head>
				<script type="text/javascript">
					function setposter(){
						opener.document.images['posterimg'].src = '../imgget.php?for=<?= $_GET['id'] ?>&from=session&foo='+(new Date()).getTime();
						// appending the time to the poster url is just a stupid trick to force a reload
						opener.document.data.setposter.value = true;
						window.close();
					}
				</script>
				</head>
				<body onload="setposter();"/>
			</html>
<?		
		} else { //error occured
?>

		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
			<html>
				<head>
					<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
					<title>Edit Poster</title>
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
						<div class="rowtitle">The following error occured:</div><?= $res ?>
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
					<title>Edit Poster</title>
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
 					
 					function disable(){
 						// Test if the checkbox were checked
 						if(document.data.noposter.checked){
 							document.data.url.disabled = true;
 							document.data.file.disabled = true;
 						} else {
 							document.data.url.disabled = false;
 							document.data.file.disabled = false;
 						}
 					}
				</script>
			</head>

			<body onload="document.data.url.focus(); ">
				<div id="header">Poster:</div>
				<div id="content">
					<form enctype="multipart/form-data" name="data" action="" method="post">
						<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
						<table id="restable">
							<tr>
								<td colspan="2" class="rowtitle">Please specify an URL or a file here:</td>
							</tr><tr>
								<td>url:</td><td><input type="text" class="inputmed" name="url" value="http://" onkeydown="submitenter(this,event)"/></td>
							</tr><tr>
								<td>file:</td><td><input type="file" class="inputmed" name="file" onkeydown="submitenter(this,event)"/></td>
							</tr><tr>
								<td colspan="2" style="height: 100%">&nbsp;</td>
							</tr><tr>
								<td><input type="checkbox" name="noposter" onclick="disable()"/></td><td>do not store an image for this movie</td>
							</tr>
						</table>
						
					</form>
				</div>
				<div id="footer">
					<img name="stop" alt="abort" src="../pics/stop.gif" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')" onclick="window.close();"/>
					<img name="go" alt="get new poster" src="../pics/go.gif" onclick="document.data.submit();" onmouseover="swap('go','go_a')" onmouseout="swap('go','go')"/>&nbsp;
				</div>
			</body>
		</html>

<?	}	?>

