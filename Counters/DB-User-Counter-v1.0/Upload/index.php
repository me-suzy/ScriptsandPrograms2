<?php
//	+---------------------------------------------------------------
//	|	Script by: Niraj Shah
//	|	Email: demon@demonboard.co.uk
//	|	URL: http://www.demonboard.co.uk
//	|	(C) 2002-2004 Demon Board Ltd.
//	|	
//	|	Main Class File
//	+---------------------------------------------------------------

if ( file_exists( "online/config.php" ) ) {
	$file = "online/config.php";
} else {
	$file = "config.php";
}
include "$file";
$url = $_GET['url'];

class online {
	
	//	+---------------------------------------------------------------
	//	|	Constructor
	//	+---------------------------------------------------------------
	function online() {
		global $url, $file;
		$this->header();
		if ( !@filesize( "$file" ) > 0 ) {
			print $this->error( "Please run installer before using the script." );
		} else {
			$this->update( $_SERVER['REMOTE_ADDR'], time(), $_SERVER['REQUEST_URI'] );
			if ( isset( $_GET['stats'] ) ) {
				$this->showStats();
			}
		}
		$this->footer();
	}

	//	+---------------------------------------------------------------
	//	|	Connect to SQL Server and Database
	//	+---------------------------------------------------------------
	function connect() {
		global $host, $username, $password, $db;
		@mysql_connect( $host, $username, $password ) or die( $this->error( "Cannot connect to SQL Server.<br>".mysql_error() ) );
		@mysql_select_db( $db ) or die( $this->error( "Cannot connect to Database.<br>".mysql_error() ) );
	}

	//	+---------------------------------------------------------------
	//	|	Disconnect from SQL Server
	//	+---------------------------------------------------------------
		function disconnect() {
		@mysql_close() or die( $this->error( "Cannot disconnect from SQL Server.<br>".mysql_error() ) );
	}

	//	+---------------------------------------------------------------
	//	|	Display Error
	//	+---------------------------------------------------------------
		function error( $text, $title = "Error!" ) {
		$return = "	<br><table width='400' align='center' style='border-style: solid; border-width: 1px;' bordercolor='#992A2A' bgcolor='#F2DDDD'>
					  <tr>
						<td><strong>$title</strong></td>
					  </tr>
					  <tr>
						<td>$text</td>
					  </tr>
					</table><br>";
		return $return;
	}

	//	+---------------------------------------------------------------
	//	|	Add link to Database or Update existing Link
	//	+---------------------------------------------------------------
		function update( $ip, $time, $url ) {
		$this->connect();
		$query = @mysql_query( "SELECT * FROM users WHERE ip = '$ip'" ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		$exists = @mysql_num_rows( $query );
		if ( $exists > 0 ) {
			mysql_query( "UPDATE users SET url = '$url', time = $time WHERE ip = '$ip'" ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		} else {
			mysql_query( "INSERT INTO users VALUES( '$ip', '$time', '$url' );" ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		}
		$query2 = @mysql_query( "DELETE FROM users WHERE time < ".( time() - ( 60 * 15 ) ).";" ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		$this->disconnect();
	}

	//	+---------------------------------------------------------------
	//	|	Output Users online
	//	+---------------------------------------------------------------
		function output() {
		$this->connect();
		$query = @mysql_query( "SELECT * FROM users WHERE time > ".( time() - ( 60 * 15 ) ) ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		$online = @mysql_num_rows( $query );
		$this->disconnect();
		return $online;
	}

	//	+---------------------------------------------------------------
	//	|	Show Statistics
	//	+---------------------------------------------------------------
		function showStats() {
		$this->connect();
		$get = mysql_query( "SELECT * FROM users ORDER BY time DESC" ) or die( $this->error( "Cannot execute stats query.<br>".mysql_error() ) );
		while( $row = mysql_fetch_array( $get, MYSQL_ASSOC ) )
			$stats[] = $row;
		
		if ( @mysql_num_rows( $get ) > 0 ) {
			print "<table style='border-width: 1; border-color: #000000; border-style: dashed' cellpadding='5' align='center'>\n".
				  "	<caption>\n".
				  "	<b>Recent Online Users (Last 15 Minutes):</b>\n".
				  "	</caption>\n".
				  "	<tr>\n".
				  "	<td align='center'><b>IP</b></td>\n".
				  "	<td align='center'><b>Last Click</b></td>\n".
				  "	<td align='center'><b>URL</b></td>\n".
				  "	</tr>\n";
			
			for ( $i = 0; $i < mysql_num_rows( $get ); $i++ ) {
				print "	<tr>\n".
					  "	<td><a href='javascript:void(0);' onclick='alert( \"Hostname: ".gethostbyaddr( $stats[$i]['ip'] )."\" );'>".$stats[$i]['ip']."</a></td>\n".
					  "	<td align='center'>".date( "D, jS F Y, g:i:s A", $stats[$i]['time'] )."</td>\n".
					  "	<td><a href='".$stats[$i]['url']."'>".$stats[$i]['url']."</a></td>\n".
					  "	</tr>\n";
			}
			
			print "</table>\n";
		} else {
			$error = "No statistical information available. Please check back soon. To 
					record information, simply link to the following URL:<p>
					<i>http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?url=your-URL-here</i>
					</p><p>e.g:<br>
					<a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?url=http://www.demonboard.co.uk'>
					http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?url=http://www.demonboard.co.uk
					</a>
					</p>";
			print $this->error( $error );
		}
			
		print "<p class='copyright' align='center'>\n".
			  "Powered by DB User Counter v1.0<br>\n".
			  "	&copy; 2002-2005 Demon Board Ltd.<br>\n".
			  "	<a href='?url=http://www.demonboard.co.uk'>http://www.demonboard.co.uk/</a>\n".
			  "</p>\n";
		
		$this->disconnect();
	}

	//	+---------------------------------------------------------------
	//	|	HTML Header
	//	+---------------------------------------------------------------
		function header() {
		print "<!----------\n".
				"+---------------------------------------------------------------\n".
				"|	Script by: Niraj Shah\n".
				"|	Email: demon@demonboard.co.uk\n".
				"|	URL: http://www.demonboard.co.uk\n".
				"|	(C) 2002-2004 Demon Board Ltd.\n".
				"|	Powered by DB User Counter\n".
				"+---------------------------------------------------------------\n".
				"---------->\n";
		print "	<HTML>
				<HEAD>
				<TITLE>DB User Counter</TITLE>
					<style>
					body, td, th, form, input, textarea, select {
						color: #000000;
						font-family: Verdana, Arial, Helvetica, sans-serif;
						font-size: 11px;
					}
					
					.copyright {
						font-family: Verdana, Arial, Helvetica, sans-serif;
						font-size: 10px;
					}
					</style>
				<BODY>";
	}

	//	+---------------------------------------------------------------
	//	|	HTML Footer
	//	+---------------------------------------------------------------
		function footer() {
		print "	</BODY>
				</HEAD>
				</HTML>";
	}

}
//	+---------------------------------------------------------------
//	|	Run Script
//	+---------------------------------------------------------------
//	$html = new online(); 	//	Start script
//	$html->output();		//	Output information
//	+---------------------------------------------------------------
//	|	Script by: Niraj Shah
//	|	Email: demon@demonboard.co.uk
//	|	URL: http://www.demonboard.co.uk
//	|	(C) 2002-2004 Demon Board Ltd.
//	+---------------------------------------------------------------
if ( isset( $_GET['stats'] ) ) {
	$html = new online();
}
?>
