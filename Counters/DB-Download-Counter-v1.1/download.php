<?php
#	DB Download Counter
#	Author: Demon
#	Email: demon@demonboard.co.uk
#	URL: http://www.demonboard.co.uk/
#	
#	Modification of this script is unauthorised unless you have written permission from author.
#	please contact the author via the website above.
#
#	(C) 2002-2005 Demon Board Ltd. All Rights Reserved.
#

//	MODIFY INFORMATION BELOW TO SUIT NEEDS
//	File Name Extention
$extention = ".dat";

//	DO NOT MODIFY CODE BELOW THIS LINE
//	---------------------------------------------------------------------------------------------//

class download {
	
	//	+-----------------------------------------------------------
	//	|	Download function
	//	+-----------------------------------------------------------
	function download( $url, $name, $comment = "", $database = "database/" ) {
		global $file, $extention;
		$file = $database.$name.$extention;
		if ( file_exists( $url ) ) {
			
			//	Check if data file exists
			if ( !file_exists( $file ) ) {
				// No? Then create data file and write '0' to file
				touch( $file );
				chmod( $file, 0777 );
				$fp = fopen( $file, "w" );
				fwrite( $fp, "0" );
				fclose( $fp );
			}
			//	Create Download Button
			print "<!-----DB Download Counter - (C) 2004 Demon Board Ltd.----->";
			print "<table width='250px' style='border-style: dashed; border-width: 1; border-color: #000000'><tr><td align='center'>";
			print "	<form method=\"POST\" action=\"download.php\">
					<input type=\"hidden\" value=\"$url\" name=\"u\">
					<input type=\"hidden\" value=\"$file\" name=\"f\">
					<img src='$database/download.gif' align='top' alt='Powered by DB Download Counter'>
					<input type=\"submit\" value=\"Download Now\">";
			if ( $comment != "" ) 
				print "<br><font color='#999999' size='-2'><i>$comment</i></font>";
			print "<br><font size='-2'><b>Total Downloads</b>: ";
			include "$file";
			print "</font>";
			print "</td></tr></table></form>";
			print "<!-----DB Download Counter - (C) 2004 Demon Board Ltd.----->";
				
		} else {
			//	Can't find database Directory
			print "<font color='red'>Error: Cannot find file.</a>";
		}
	}

	//	+-----------------------------------------------------------
	//	|	Download function
	//	+-----------------------------------------------------------
	function setDir( $newDir ) {
		global $file, $extention, $database;
		$database = $newDir;
	}
	
}

//	Get HTTP Variables
$u = $_POST['u'];
$f = $_POST['f'];

//	Check Address
if ( $u != "" && $f != "" ) {
	//	Check previous count and update. Write to file.
	$fp = fopen( $f, "r" );
	$count = fread( $fp, 4096);	//	Read Previous count
	fclose( $fp );
	$count++;					//	Update Count
	$fp = fopen( $f, "w" );
	fwrite( $fp, $count );		//	Write New count
	fclose( $fp );
	//	Redirect user to download file
	header( "Location: $u" );
}

#	DB Download Counter
#	Author: Demon
#	Email: demon@demonboard.co.uk
#	URL: http://www.demonboard.co.uk/
#	
#	Modification of this script is unauthorised unless you have written permission from author.
#	please contact the author via the website above.
#
#	(C) 2002-2005 Demon Board Ltd. All Rights Reserved.
#
?>