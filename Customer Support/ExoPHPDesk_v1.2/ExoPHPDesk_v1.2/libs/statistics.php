<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> STATISTICS . PHP File - HelpDesk Statistics
// >> Started : June 04, 2004
// >> Edited  : June 04, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

//
// Check for admin access
// This area is currently only for administrators
//
if( $L_TYPE == 'admin' )
{
	
	//
	// Get total tickets
	//
	$T_query = $db->query( "SELECT status,waiting,opened,priority FROM phpdesk_tickets ORDER by opened ASC" );
	$TOTAL_TICKETS =  $db->num( $T_query );
	
	// Array Of Hours
	$hours = array();
	
	// Unset OPEN TICKETS
	$OPEN_TICKETS = $CLOSE_TICKETS = $SWAIT_TICKETS = $MWAIT_TICKETS = $PHIGH_TICKETS = $MEDIUM_TICKETS = $PLOW_TICKETS = 0;
	while( $fetch = $db->fetch( $T_query ))
	{
		if( $fetch['status'] == 'Open' )
		{
			$OPEN_TICKETS++;
		}
		
		if( $fetch['status'] == 'Closed' )
		{
			$CLOSE_TICKETS++;
		}
		
		if( $fetch['waiting'] == 'Staff' )
		{
			$SWAIT_TICKETS++;
		}
		
		if( $fetch['waiting'] == 'Member' )
		{
			$MWAIT_TICKETS++;
		}
		
		if( $fetch['priority'] == '1' )
		{
			$PHIGH_TICKETS++;
		}
		
		if( $fetch['priority'] == '2' )
		{
			$MEDIUM_TICKETS++;
		}
		
		if( $fetch['priority'] == '3' )
		{
			$PLOW_TICKETS++;
		}
		
		// Get Hour And SET!!
		$tHour = exo_date('H', $fetch['opened']);
		$hours[$tHour] += 1;
		
		$LAST_TICKETS = $fetch['opened'];
	}
	
	//
	// PREPARE PERCENTAGE
	//
	$OPEN_PERCENT  = number_format( ( $OPEN_TICKETS / $TOTAL_TICKETS ) * 100, 1 );
	$CLOSE_PERCENT = number_format( ( $CLOSE_TICKETS / $TOTAL_TICKETS ) * 100, 1 );
	$SWAIT_PERCENT = number_format( ( $SWAIT_TICKETS / $TOTAL_TICKETS ) * 100, 1 );
	$MWAIT_PERCENT = number_format( ( $MWAIT_TICKETS / $TOTAL_TICKETS ) * 100, 1 );
	$PHIGH_PERCENT = number_format( ( $PHIGH_TICKETS / $TOTAL_TICKETS ) * 100, 1 );
	$MEDIUM_PERCENT = number_format( ( $MEDIUM_TICKETS / $TOTAL_TICKETS ) * 100, 1 );
	$PLOW_PERCENT  = number_format( ( $PLOW_TICKETS / $TOTAL_TICKETS ) * 100, 1 );
	
	//
	// Prepare Opened Tickets Hour Stuff
	//
	$x = 0;
	while( $x <= 24 )
	{
		$zero = ( $x < 10 ) ? '0' : NULL;
		$HOURS[$zero.$x] = ($hours[$zero.$x] == NULL) ? '0' : $hours[$zero.$x];
		$x++;
	}
	
	// End Ticket Statistics
	
	// Start User Statistics
	// Queries to prepare Data
	$UM_query = $db->query( "SELECT id,username FROM phpdesk_members ORDER by registered DESC" );
	$US_query = $db->query( "SELECT id,responses,username FROM phpdesk_staff ORDER by responses DESC" );
	$UA_query = $db->query( "SELECT id FROM phpdesk_admin" );
	$HS_query = $db->query( "SELECT username,rating FROM phpdesk_staff ORDER by rating DESC" );

	// Prepare Data
	$MEMBERS = $db->num( $UM_query );
	$STAFF   = $db->num( $US_query );
	$ADMINS  = $db->num( $UA_query );
	
	// Fetch Queries
	$U_fetch = $db->fetch( $UM_query );
	$S_fetch = $db->fetch( $US_query );
	$H_fetch = $db->fetch( $HS_query );
	
	// Data!!
	$LAST_USER = $U_fetch['username'];
	$mRESPONSE = $S_fetch['username'];
	$MR_Number = $S_fetch['responses'];
	$HR_Number = $H_fetch['rating'];
	$hRate     = $H_fetch['username'];
	
	// END User statistics
	
	// Start General Statistics
	// Queries to prepare data
	$KG_query = $db->query( "SELECT id FROM phpdesk_kbgroups" );
	$FQ_query = $db->query( "SELECT id FROM phpdesk_kb" );
	$DB_query = $db->query( "SHOW TABLE STATUS FROM {$db->db[db_datab]}" );
	$SR_query = $db->query( "SELECT id FROM phpdesk_saved WHERE `type` = 'Response'" );
	$TR_query = $db->query( "SELECT id FROM phpdesk_troubles WHERE isparent = '1'" );
	$AN_query = $db->query( "SELECT id FROM phpdesk_announce" );
	$SV_query = $db->query( "SELECT id FROM phpdesk_servers" );
	
	// Get Database size
	$DB_size = 0;
	while( $row = $db->fetch($DB_query) )
	{
		$DB_size += $row['Data_length'];
		$DB_size += $row['Index_length'];
	} 

	// Data!
	$FAQ = $db->num( $FQ_query );
	$KB_groups = $db->num( $KG_query );
	$DB_size   = number_format( $DB_size / 1024, 1 );
	$AT_size   = number_format( dirSize( $Attach_dir ) / 1024, 1 );
	$SResponse = $db->num( $SR_query );
	$TShooters = $db->num( $TR_query );
	$Announces = $db->num( $AN_query );
	$Servers   = $db->num( $SV_query );
	
	_parse( $tpl_dir . 'statistics.tpl' );
	$Read = getBlock( $class->read, 'ADMIN' );
	echo $Read;
}

?>