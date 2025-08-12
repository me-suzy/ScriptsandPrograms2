<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> SERVER . PHP File - To track server downtimes
// >> Started : February 27, 2004
// >> Edited  : February 27, 2004
// << -------------------------------------------------------------------- >>

include( 'conf.php' );

$SQ = $db->query( "SELECT * FROM `phpdesk_servers`" );
while( $SF = $db->fetch( $SQ ))
{
	// SET THE MAX EXECUTION TIME
	ini_set( "max_execution_time", "15" );
					
	// FETCH FROM TABLE
	$F = $SF;
					
	// ARRAY OF PORTS
	$PORTS = array (
					$tpl['web_service']   => $F['web_port'],
					$tpl['mysql_service'] => $F['mysql_port'],
					$tpl['ftp_service']   => $F['ftp_port'],
					$tpl['pop3_service']  => $F['pop3_port'],
					$tpl['smtp_service']  => $F['smtp_port'],
					$tpl['imap_service']  => $F['imap_port'],
					$tpl['telnet_service']=> $F['telnet_port'],
					$tpl['ssh_service']   => $F['ssh_port'],
					);
	
	$PORT_EXISTS = 0;
	
	// Loop To Check All Ports Stats And Put Them Back
	while ( list ( $TEXT, $PORT ) = each ( $PORTS ) )
	{
		if ( $PORT != 0 )
		{
			// Extend The Counter
			$X++;
							
			// Try To Open A Connection To The Server / Port
			$STATUS = @fsockopen ( $F['ip'], $PORT, $ERROR, $ERROR_STR, 4 );
						
			// If Connection Failed
			if ( !$STATUS )
			{
				$DOWN = time() . '|||' . $PORT . "\n" . $F['down'];
				$db->query( "UPDATE phpdesk_servers SET `down` = '$DOWN' WHERE id = '{$SF[id]}'" );
			}
		}
	}
	// End Loop To Check Ports
				
}

?>