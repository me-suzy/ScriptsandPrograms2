<?php
/*******************************************************************************
/
/       	Check Cookie -> [store vote] -> [send cookie]
/
*******************************************************************************/

// database-file class
require_once 'includes/configmagik.php';

// necessary for script path
include_once 'config.php';

if (!isset($txtPath)) {
	// build database-file path based on script path
	$txtPath = $scriptPath."txt/txtdb.ini.php";
}





// if someone voted by the poll form...
if ( (isset($_POST['voto'])) && ($_POST['voto']!="") ) {

	// Create and configure new ConfigMagik-Object
	$dbTXT = new ConfigMagik( $txtPath, true, true);
	$dbTXT->SYNCHRONIZE = false;

        // get pollid
	$pollid = $dbTXT->get('pollid', 'MAIN');

	// check if user already voted this poll (pollid)
        if ( !isset($_COOKIE['poll4all']) || $_COOKIE['poll4all']!=$pollid ) {

		// store vote
                $anKey = "an".$_POST['voto']."poll";
                $dbTXT->increment($anKey, 'ANSWERS');
                
                // If store succeed send cookie to client
                if ($dbTXT->save()) setcookie( "poll4all", $pollid, time()+60*60*24*360, "/" );
                else print "fatal error: writing file failed.<br />";

        }
        

}




?>
