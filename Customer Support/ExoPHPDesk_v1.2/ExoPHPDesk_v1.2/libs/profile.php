<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> PROFILE . PHP File - For Viewing Profile
// >> Started : February 25, 2004
// >> Edited  : June 02, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

if( !empty( $_GET['id'] ))
{
	// Get The Types
	$tType = @where( NULL, 1, $_GET['id'] );
	$uType = @preg_replace( '/phpdesk_(.*)/i', "\\1", $tType );
	
	// Get the User Stuff
	$PQ = $db->query( "SELECT * FROM $tType WHERE id = '{$_GET[id]}'" );
}
else
{
	// Get The Types
	$tType = @where( $_GET['user'] );
	$uType = @preg_replace( '/phpdesk_(.*)/i', "\\1", $tType );
	
	// Get the User Stuff
	$PQ = $db->query( "SELECT * FROM $tType WHERE username = '{$_GET[user]}'" );
}

if( empty( $_GET['id'] ) && empty( $_GET['user'] ))
{
	echo $error['id_missing'];
}
elseif( ( $L_TYPE == 'members' && $uType != 'members' ) )
{
	echo $error['no_auth_or_record'];
}
elseif( $uType == 'admin' )
{
	echo $error['no_auth_or_record'];
}
elseif( !$db->num( $PQ ))
{
	echo $error['no_auth_or_record'];
}
else
{

	$PF = $db->fetch( $PQ );
	
	$_GET['id'] = ( $_GET['id'] == NULL ) ? $PF['id'] : $_GET['id'];
	
	$PF['username'] = ( $uType == 'admin' ) ? $PF['name'] : $PF['username'];
	
	// Queries	
	$TC1 = $db->query( "SELECT COUNT(*) AS responses FROM phpdesk_responses WHERE sname = '{$PF['username']}'" );
	$TC2 = $db->query( "SELECT COUNT(*) AS tickets FROM phpdesk_tickets WHERE admin_id = '{$_GET[id]}'" );
	
	// Fetch
	$To1 = $db->fetch( $TC1 );
	$To2 = $db->fetch( $TC2 );
	
	// Variables
	$TG['responses'] = $To1['responses'];
	$TG['tickets']	 = $To2['tickets'];
	
	if( $uType == 'staff' )
	{
		$User = strtolower( $PF['username'] );
		
		/* Get The Average Response Time Of The Staff */
		$RQ = $db->query( "SELECT tid FROM phpdesk_responses WHERE sname = '{$User}'" );
		
		while( $RF = $db->fetch( $RQ ))
		{
			$TQ = $db->query( "SELECT t.id,t.opened,r.tid,r.sname,r.posted FROM phpdesk_tickets t 
									LEFT JOIN phpdesk_responses r ON(r.tid=t.id) 
								WHERE t.id = '{$RF['tid']}' 
									ORDER by t.opened DESC, r.posted DESC" );
							
			while( $TF = $db->fetch( $TQ ))
			{
		
				$RRF = $TF;
				$Z = 0;

				$Z++;
		
				if( $Z == 1 )
				{
					$LAST = $RRF['opened'];
				}
				else
				{
					$LAST = $RRF['posted'];
				}
		
				if( strtolower( $RRF['sname'] ) == $User )
				{
					$T_PERC =  $RRF['posted'] - $LAST;
					$tArray .= ( $T_PERC / 60 ) . "|||";

					$Counter++;
				}			
			}
		}

		$tArray = explode( "|||", $tArray );
		foreach( $tArray AS $Split )
		{
			$TOTAL += $Split;
		}

		$TOTAL = ( $Counter > 0 ) ? ( $TOTAL / $Counter ) : 0;
		$TOTAL = ( $TOTAL > 60 ) ? ( $TOTAL / 60 ) : $TOTAL;
		$SUFIX = ( $TOTAL > 60 ) ? " Hours" : " Minutes";

	}
	
 	$PF['rating'] = ( $PF['rating'] != NULL ) ? $PF['rating'] . $tpl['out_five'] : $tpl['none_yet'];
	
	// Parse The Template
	_parse( $tpl_dir . 'view.tpl' );
	$Read = getBlock( $class->read, 'PROFILE' );
	
	//
	// If Its a Member!
	// Then do the custom registration fields stuff..
	//
	if( $uType == 'members' )
	{
		$xtra = template( $Read, $T_ST.'xtra', '/#xtra]' );
		$EXTRAS = reverse_ticket( $PF['FIELDS'], $PF['VALUES'], $xtra );
		$Read = preg_replace( '/\[#xtra(.*)\/#xtra\]/is', $EXTRAS, $Read );
	}	
	
	echo $Read;

}

?>