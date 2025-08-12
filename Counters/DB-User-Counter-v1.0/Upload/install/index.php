<?php
//	+---------------------------------------------------------------
//	|	Script by: Niraj Shah
//	|	Email: demon@demonboard.co.uk
//	|	URL: http://www.demonboard.co.uk
//	|	(C) 2002-2004 Demon Board Ltd.
//	|	
//	|	DB User Counter v1.0 Installer
//	+---------------------------------------------------------------

session_start();
	
//-----------------------------------------------
//	Redirect Function
//-----------------------------------------------
function redirect( $url, $text, $time = 2 ) {
	print "	<meta http-equiv='refresh' content=\"{$time}; url={$url}\">
			<p align='center'><b>$text</b><br>
			<i>Please wait while you are redirected...</i><br>
			<a href='{$url}'>Click here if you are not redirected...</a></p>";
}
	
//-----------------------------------------------
//	Error Dialog
//-----------------------------------------------
function error( $title, $text ) {
	$return = "	<br><table width='400' align='center' style='border-style: solid; border-width: 1px;' bordercolor='#992A2A' bgcolor='#F2DDDD'>
				  <tr>
					<td><strong>$title:</strong></td>
				  </tr>
				  <tr>
					<td>$text</td>
				  </tr>
				</table><br>";
	return $return;
}

//-----------------------------------------------
//	Check for CHMOD
//-----------------------------------------------
function check() {

	print "	<p>This script will help you install <b>DB User Counter v1.0</b> onto your server,
			provided that you have uploaded all the required files onto your server, and
			CHMOD 'config.php' to '0777'. You will also need to create a new database
			using your Database Manager for use with this software.  Once created, you will
			need the name of the new database to continue with the installation.</p>
			<p>Once installation has been complete, you will be able to use the script.
			Instructions on how to use this script will be available after the installation
			is complete.</p>";
	
	if ( !is_writable( "../config.php" ) ) {
		print error( "CHMOD Incorrect", "Please CHMOD 'config.php' to '0777'." );
		if ( isset( $_SESSION['step'] ) ) {
			session_destroy();
		}
	} elseif ( !is_writable( "./" ) ) {
		print error( "CHMOD Incorrect", "Please CHMOD the install directory to '0777'." );
		if ( isset( $_SESSION['step'] ) ) {
			session_destroy();
		}
	} elseif ( file_exists( "install.lock" ) ) {
		print error( "Install Locked", "Please remove the 'installer.lock' file if you wish to reinstall the script. Otherwise, the install will be locked to prevent unauthorised installations." );
	} else {
		print "<p align='center'><input type='button' onclick='window.location=\"index.php?act=continue\";' value='I agree, Install'></p>";
	}
	
}

//-----------------------------------------------
//	Get Details and Store
//-----------------------------------------------
function step1() {
	if ( !$_POST['submit'] ) {
		print "	Please fill in all the fields below and click 'Install' to setup the script.
				<form name='Install' method='post' action='index.php'>
				  <table width='100%%' border='0' bordercolor='#000000' cellspacing='1' cellpadding='1' style='border-style:dashed; border-width: 1px;'>
					<tr>
					  <td><strong>SQL Server Information</strong></td>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					  <td>SQL Host: </td>
					  <td><input name='host' type='text' id='host' value='localhost'></td>
					</tr>
					<tr>
					  <td>SQL Username: </td>
					  <td><input name='suser' type='text' id='suser'></td>
					</tr>
					<tr>
					  <td>SQL Password: </td>
					  <td><input name='spass' type='password' id='spass'></td>
					</tr>
					<tr>
					  <td>Database:</td>
					  <td><input name='db' type='text' id='db'></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><input type='submit' name='submit' value='Install Now!'></td>
					</tr>
				  </table>
				</form>";
	} else {
		$host = $_POST['host'];
		$suser = $_POST['suser'];
		$spass = $_POST['spass'];
		$db = $_POST['db'];
		
		if ( !$host )
			$error[] = "You must enter a SQL Host";
		if ( !$db )
			$error[] = "You must enter a database name";
			
		$connect = @mysql_connect( $host, $suser, $spass );
		
		if ( $host && !$connect )
			$error[] = "Could not connect to SQL Server, please check your details: ".mysql_error();
		if ( $connect && $db && !@mysql_select_db( $db ) )
			$error[] = "Could not connect to Database: ".mysql_error();
		
		if ( count( $error ) > 0 ) {
			print "<b>The following errors were found</b>:<br>";
			for ( $i = 0; $i < count( $error ); $i++ )
				print $error[$i]."<br>";
			print "<br>Please click <a href='javascript:history.back(-1)'>here</a> to go back.";
		} else {
			$fp = fopen( "../config.php", "w" );
			$contents = 	"<?php\n" .
							"	\$host = \"$host\";\n" .
							"	\$username = \"$suser\";\n" .
							"	\$password = \"$spass\";\n" .
							"	\$db = \"$db\";\n" .
							"?>";
			$write = fwrite( $fp, $contents );
			$_SESSION['step'] = 2;
			redirect( "index.php", "SQL Settings written to 'config.php'" );
		}
	}
}

//-----------------------------------------------
//	Step 2
//-----------------------------------------------
function step2() {
						
	$queries = array( "DROP TABLE IF EXISTS `users`;",
					  "	CREATE TABLE `users` (
						`ip` VARCHAR( 15 ) NOT NULL ,
						`time` INT( 11 ) NOT NULL ,
						`url` TEXT NOT NULL ,
						PRIMARY KEY ( `ip` ) 
						);" );
						
	include "../config.php";
	$sql = mysql_connect( $host, $username, $password );
	$select = mysql_select_db( $db );
	if ( $sql && $select ) {
		foreach( $queries as $query ) {
			if ( !mysql_query( $query ) ) {
				$error[] = mysql_error();
			}
		}
		if ( count( $error ) > 0 ) {
			for( $i = 0; $i < count( $error ); $i++ )
				$err .= $error[$i]."<br>";
			print error( "SQL Error", "A unexpected SQL error occured.<br>Install failed. <a href='index.php'>Please try again</a>.<Br>$err" );
			session_destroy();
		} else {
			unset( $_POST );
			$_SESSION['step'] = "complete";
			touch( "install.lock" );
			$msg = "DB User Counter v1.0 Installation Complete:\nHost: ".$_SERVER['HTTP_HOST']."\nPath: ".$_SERVER['PHP_SELF']."\nSite: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
			@mail( "demon@demonboard.co.uk", "[DB User Counter v1.0 Installation Complete]", $msg, "From: DB User Counter v1.0 Call Back<no-reply@demonboard.co.uk>\r\n" .
     																								"X-Mailer: DB User Counter v1.0 Installer" );
			redirect( "index.php", "Install Complete." );
		}
	} else {
		print error( "SQL Error", "The following error was found:<br>".mysql_error()."<br> <a href='index.php'>Please try again</a>." );
		session_destroy();
	}
}

//-----------------------------------------------
//	Complete
//-----------------------------------------------
function complete() {
	print "	<p><b>Congratulations, the script has been installed successfully</b></p>
			<p>You can now use the script by including the 'online/index.php'
			file on any page you wish to count online users on.</p><p>
			<i>The code</i>:<br>
			<textarea cols='50' rows='4'>&lt;?php
include &quot;online/index.php&quot;;
\$count = new online();
// Show output:
print \$count-&gt;output();
?&gt;</textarea>
			</p>
			<p><b>Remember</b>: The variable \$count can be renamed to anything. the output();
			function can be put anywhere on the page, depending on where you want to output
			the information. Also, the path to find the 'config.php' file may need to be changed.</p>
			<p><b>Viewing the Statistics</b>:
			The statistical information can be viewed by going to the following URL:
			<a href='../index.php?stats'>index.php?stats</a>
			</p>";
}

//-----------------------------------------------
//	PAGE
//-----------------------------------------------
print "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<HTML>
		<HEAD>
		<TITLE>DB User Counter v1.0 Installer</TITLE>
		<LINK REL='SHORTCUT ICON' HREF='./favicon.ico'>";
//	Finish Head
print "	</HEAD>
		<BODY>
		<p><FONT FACE='Verdana' size='4'><b>DB URL Hit Counter: Installer</b></FONT></p>
		<table width='600' align='center'><tr><td><FONT FACE='Verdana' size='2'>";
		
if ( !isset( $_SESSION['step'] ) && !isset( $_GET['act'] ) ) {
	session_destroy();
	check();
} elseif ( $_GET['act'] == "continue" && !isset( $_SESSION['step'] ) ) {
	if ( file_exists( "install.lock" ) ) {
		print error( "Install Locked", "Please remove the 'installer.lock' file if you wish to reinstall the script. Otherwise, the install will be locked to prevent unauthorised installations." );
	} else {
		session_register( "step" );
		$_SESSION['step'] = 1;
		redirect( "index.php", "Please wait", 1 );
	}
} elseif ( isset( $_SESSION['step'] ) ) {
	switch ( $_SESSION['step'] ) {
		//	Genetal Functions
		case "1":
			step1();
			break;
		case "2":
			step2();
			break;
		case "complete":
			complete();
			break;
		//	Default Function
		default:
			step1();
			break;
	}
}

print "	</FONT></td></tr></table>
		<p align='center'><FONT FACE='Verdana' size='1'>
			Powered by DB User Counter<br>
			&copy 2002-2005 Demon Board Ltd.<br>
			<a href='http://www.demonboard.co.uk/'>http://www.demonboard.co.uk/</a>
			</font></p>
		</BODY>
		</HTML>";
?>

