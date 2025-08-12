<?
/*	php4flicks movie database (c) mr.Fox					*
 *	released under the GNU General Public License				*
 *	contact and additional information: http://php4flicks.ch.vu		*/

 	//login.php
	// this is used for both login and logout, in contrast to what the name suggests...

session_start();

if(!isset($_GET['action'])) $_GET['action'] = '';

switch($_GET['action']){
	case 'logout':
		// destroy session
		session_unset();
		session_destroy();
?>	
		<script language="javascript">
			opener.document.filterform.login.value = '0';
			opener.document.filterform.submit();
			window.close();
		</script>
<?
		break;
		
	case 'login':
		//don't try to log in twice
		if(isset($_SESSION['user'])){
?>	
			<script language="javascript">
				opener.location.href=opener.location.href.replace('#',''); window.close();
			</script>
<?
			die();
		}
		//username, password were submitted
		require_once('config/config.php');
		// check if username, pw are in user-array
		foreach($cfg['users'] as $u){
			if ($u['user'] == $_POST['user'] && strtolower($u['md5pass']) == md5($_POST['pass'])){
				//username, pw ok!
				$_SESSION['user'] = $_POST['user'];
				break;
			}
		}
		if(!isset($_SESSION['user'])){
			if(isset($_GET['location']))
				header('location: login.php?location='.$_GET['location']);
			else
				header('location: login.php');
		} else {	//pw,username ok, so return to location specified by caller and close login window.
		?>
			<script language="javascript">
				opener.document.filterform.login.value = '1';
				opener.document.filterform.submit();
				<?= (isset($_GET['location'])?'location.href=\''.$_GET['location'].'\'':'window.close();')?>
			</script>
		<? }
		break;
	default:
		// neighter login nor logout, so just display login form
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
		<title>Enter Username, Password</title>
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

		<script type="text/JavaScript">
			go = new Image();		go.src = 'pics/go.gif';
			go_a = new Image(); 	go_a.src = 'pics/go_a.gif';

			stop = new Image();	stop.src = 'pics/stop.gif';
			stop_a = new Image(); 	stop_a.src = 'pics/stop_a.gif';

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
		</script>
	</head>

	<body onload="document.data.user.focus(); ">
		<div id="header">Login:</div>
		<div id="content">
			<form name="data" action="login.php?action=login<? if(isset($_GET['location'])) echo('&location='.$_GET['location']); ?>" method="post">
				<input type="text" class="inputmed" name="user" value="username" onfocus="this.value='';"/><br/>
				<input type="password" class="inputmed" name="pass" value="password" onfocus="this.value='';" onkeydown="submitenter(this,event)"/>
			</form>
		</div>
		<div id="footer">
			<img name="stop" alt="abort" src="pics/stop.gif" onmouseover="swap('stop','stop_a')" onmouseout="swap('stop','stop')" onclick="window.close();"/>
			<img name="go" alt="log me in!" src="pics/go.gif" onclick="document.data.submit();" onmouseover="swap('go','go_a')" onmouseout="swap('go','go')"/>&nbsp;
		</div>
	</body>

</html>
<?

} // end switch
