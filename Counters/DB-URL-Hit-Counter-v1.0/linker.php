<?php
//	+---------------------------------------------------------------
//	|	Script by: Niraj Shah
//	|	Email: demon@demonboard.co.uk
//	|	URL: http://www.demonboard.co.uk
//	|	(C) 2002-2004 Demon Board Ltd.
//	|	
//	|	Main Class File
//	+---------------------------------------------------------------

include "config.php";
$url = $_GET['url'];

class linker {
	
	//	+---------------------------------------------------------------
	//	|	Constructor
	//	+---------------------------------------------------------------
	function linker() {
		global $url;
		if ( !filesize( "config.php" ) > 0 ) {
			$this->header();
			print $this->error( "Please run installer before using the script." );
			$this->footer();
		} else {
			if ( !$url ) {
				$this->header();
				if ( !isset( $_GET['stats'] ) )
					print $this->error( "No URL specified. If you are looking for statistical information, please click <a href='?stats'>here</a>" );
				else $this->showStats();
				$this->footer();
			} else {
				$this->addLink( $url, time() );
				header( "Location: $url" );
			}
		}
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
	//	|	Show Statistics
	//	+---------------------------------------------------------------
		function showStats() {
		$this->connect();
		$get = mysql_query( "SELECT * FROM linker ORDER BY lastvisit DESC" ) or die( $this->error( "Cannot execute stats query.<br>".mysql_error() ) );
		while( $row = mysql_fetch_array( $get, MYSQL_ASSOC ) )
			$stats[] = $row;
		
		if ( @mysql_num_rows( $get ) > 0 ) {
			print "<table style='border-width: 1; border-color: #000000; border-style: dashed' cellpadding='5' align='center'>\n".
				  "	<caption>\n".
				  "	<b>Statistics:</b>\n".
				  "	</caption>\n".
				  "	<tr>\n".
				  "	<td align='center'><b>URL</b></td>\n".
				  "	<td align='center'><b>No. of Visits</b></td>\n".
				  "	<td align='center'><b>Last Visit</b></td>\n".
				  "	</tr>\n";
			
			for ( $i = 0; $i < mysql_num_rows( $get ); $i++ ) {
				print "	<tr>\n".
					  "	<td><a href='?url=".$stats[$i]['url']."'>".$stats[$i]['url']."</a></td>\n".
					  "	<td align='center'>".$stats[$i]['hits']."</td>\n".
					  "	<td>".date( "D, jS F Y, g:i A", $stats[$i]['lastvisit'] )."</td>\n".
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
			  "Powered by DB URL Hit Counter v1.0<br>\n".
			  "	&copy; 2002-2005 Demon Board Ltd.<br>\n".
			  "	<a href='?url=http://www.demonboard.co.uk'>http://www.demonboard.co.uk/</a>\n".
			  "</p>\n";
		
		$this->disconnect();
	}

	//	+---------------------------------------------------------------
	//	|	Add link to Database or Update existing Link
	//	+---------------------------------------------------------------
		function addLink( $url, $time ) {
		$this->connect();
		$query = @mysql_query( "SELECT * FROM linker WHERE url = '$url'" ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		$exists = @mysql_num_rows( $query );
		if ( $exists > 0 ) {
			mysql_query( "UPDATE linker SET hits = hits + 1, lastvisit = '$time' WHERE url = '$url'" ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		} else {
			mysql_query( "INSERT INTO linker VALUES( '$url', 1, $time );" ) or die( $this->error( "Error executing query.<br>".mysql_error() ) );
		}
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
				"|	Powered by DB URL Hit Counter\n".
				"+---------------------------------------------------------------\n".
				"---------->\n";
		print "	<HTML>
				<HEAD>
				<TITLE>DB URL Hit Counter</TITLE>
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
	$html = new linker();
//	+---------------------------------------------------------------
//	|	Script by: Niraj Shah
//	|	Email: demon@demonboard.co.uk
//	|	URL: http://www.demonboard.co.uk
//	|	(C) 2002-2004 Demon Board Ltd.
//	+---------------------------------------------------------------
?>
