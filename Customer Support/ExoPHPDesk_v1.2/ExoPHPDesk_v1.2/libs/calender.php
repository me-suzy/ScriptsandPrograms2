<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> CALENDER . PHP File - HelpDesk Calender!
// >> Started : June 29, 2004
// >> Edited  : July 06, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

// 
// Get the highest day of the month ...
//
function max_day( $month, $year )
{
	while( $x < 33 )
	{
		$x++;
		$t = explode( ' ', exo_date('d m y', mktime(0, 0, 0, $month, $x, $year)) );

		// if its for the current month 
		if( $t[1] == $month )
		{
			$h_day = $t[0];
		}
	}
	
	return $h_day;
}

//
// Get the start position for the first day!
//
function st_day( $day )
{
	// prepare an array of week days!
	$days = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
	foreach( $days as $sday )
	{
		$x++;
		if( $day == $sday )
		{
			return $x;
		}
	}
}

//
// If No ACT Defined!
// We are listing all the days of the month, so lets move...
//
if( ACT == '' )
{
	if( (!$_GET['m'] AND !$_GET['y']) && ($_POST['m'] AND $_POST['y']) )
	{
		$_GET['m'] = $_POST['m'];
		$_GET['y'] = $_POST['y'];
	}
	
	// check in the URL if there is a month or year specified!
	$t = ( $_GET['m'] == NULL OR $_GET['y'] == NULL ) ? explode( ' ', exo_date('m Y', time()) ) : 
			array( $_GET['m'], $_GET['y'] );
				
	// Get the highest day of the current month!
	$max_day = max_day( $t[0], $t[1] );
	
	// start day !
	$st_day  = st_day( exo_date('l', mktime( 0,0,0,$t[0],1,$t[1] )) );
	
	// parse the file ..
	_parse( $tpl_dir . 'list.tpl' );
	$Top  = template( $class->read, NULL, $T_ST );
	$Mid  = template( $class->read, $T_ST.'calender', '/#calender]' );
	$End  = template( $Mid, $T_ST.'end', '/#end]' );
	$List = template( $Mid, $T_ST, $T_ED );
	$Mid  = template( $Mid, NULL, $T_ST );
	
	// print the top and the mid part..
	echo $Top . $Mid;
	
	// set the last tr tag loc to 1
	$last_tr = 1;
	
	// define an empty array..
	$records = array();
	
	// get the content from the table for the current month..
	if( $L_TYPE == 'staff' OR $L_TYPE == 'members' )
	{
		$db->query( "SELECT title, day, month, year, id FROM phpdesk_events WHERE month = '$t[0]' AND year = '$t[1]' 
							AND ( owner = '". USER ."' OR `type` = 'public' ) " );
	}
	else
	{
		$db->query( "SELECT title, day, month, year, id FROM phpdesk_events WHERE month = '$t[0]' AND year = '$t[1]'" );
	}
	
	while( $f = $db->fetch() )
	{
		if(! is_array( $records[ $f['day'] ] ))
		{
			$records[ $f['day'] ] = array();
		}
		array_push( $records[ $f['day'] ], $f['title'].'|||'.$f['day'].'|||'.$f['month'].'|||'.$f['year'].'|||'.$f['id'] );
	}
	
	// set the new max_day
	$max_day = $max_day + $st_day;
	
	// max_count variable! Was a bit tough to do :D
	$max_count = $max_day - 1;
	$max = (35 / $max_count) >= 1 ? (35 - $max_count) : ( (35+7) - $max_count );
	$max_count += $max;
	
	// run a loop to list all the days..
	while( $x < $max_count )
	{
		// Unset Variables ..
		$vtitle = $vid = $vday = $vmonth = $day_ext = "";
		
		// Counter!
		$x++;
		
		// Dy and BG Variable!
		$dy = ( $x < $st_day OR $x >= $max_day ) ? 0 : ($dy+1);
		$bg = ( $x < $st_day  OR $x >= $max_day ) ? 'ticketbg2' : 'ticketbg';
		
		// Get the right day!
		$day = ( $x < $st_day  OR $x >= $max_day ) ? NULL : $dy;
		if( ($last_tr+7) == $x )
		{
			$last_tr = $x;
			echo '<tr>';
		}
		
		// Check if there are an events for this day!
		if( is_array($records[$day]) )
		{
			// run a loop to do all this stuffy
			foreach( $records[$day] as $record )
			{
				// create a list of vars!
				list( $vtitle, $vday, $vmonth, $vyear, $vid ) = explode( "|||", $record );

				// if all vars available!
				if( $vtitle AND $vid AND $vday AND $vmonth )
				{
					// Trim some of the text!
					$vtitle = strlen($vtitle) > 35 ? substr( $vtitle, 0, 35 ) . '...': $vtitle;
					
					// then add the event for the day..
					$day_ext .= "- <a href='$_SERVER[PHP_SELF]action=view&id=$vid&d=$vday&m=$vmonth&y=$vyear'>
								{$tpl[caleventfont]}$vtitle</font></a><br>\n";
				}
			} 
			// end loop!
		}
		// end check!
		
		$border = ( exo_date('d', time()) == $day ) ? $tpl['cal_border'] : NULL;
		$day = "<a href='$_SERVER[PHP_SELF]action=addevent&d=$day&m=$t[0]&y=$t[1]'>$day</a>";
		$day = ( $x < $st_day ) ? $day : "<div class='cal_day'>$day</div>" ;
		
		// increment the day with the day's events!
		$day = $day . $day_ext;
	
		$OUT = rpl( array( 'tdbg' => $bg, 'day' => $day, 'border' => $border ), '^', $List ) . 
			   ( !is_float( $x / 7 ) ? "</tr>" : '' );
		echo $OUT;
	}
	
	// table end..
	echo '</table>' . $End;
}

//
// Create an Event!
//
if( ACT == 'addevent' )
{

	if( !$_REQUEST['d'] OR !$_REQUEST['m'] OR !$_REQUEST['y'] )
	{
		echo $error['fields'];
	}
	// If the day specified in the URL is wrong!
	elseif( exo_date( 'm', mktime(0, 0, 0, $_REQUEST['m'], $_REQUEST['d'], $_REQUEST['y']) ) != $_REQUEST['m'] )
	{
		echo $error['cal_month'];
	}
	else
	{
		// If form isn't submitted..
		if( SUBM == NULL )	
		{
			// If editing!
			if( TYPE == 'edit' )
			{
				$db->query( "SELECT title, message, type, LOWER(owner) as owner FROM phpdesk_events WHERE id = '$_GET[id]'" );
				$f = $db->fetch();
				
				if( $L_TYPE == 'members' AND $f['owner'] != strtolower( USER ))
				{
					header("Location: $_SERVER[PHP_SELF]action=calender" );
				}
				
				$TITLE = $f['title'];
				$MESSAGE = str_replace( "<br>", "\n", $f['message'] );
				$EXT_VIEW = ( $f['type'] == 'private' ) ? 'Private Event' : 'Public Event';
				$EXT_VIEW = "<option value='$f[type]' selected>$EXT_VIEW</option>\n";
			}
			
			_parse( $tpl_dir . 'add.tpl' );
			$top = template( $class->read, NULL, $T_ST );
			$mid = template( $class->read, $T_ST.'calender', '/#calender]' );
			$VIEW = "<option value='private'>Private Event</option>\n"
				  .( $L_TYPE == 'members' ? NULL : "<option value='public' selected>Public Event</option>\n" )
				  . $EXT_VIEW;
				  
			// print out the form!
			echo $top . rpl( '^view^', $VIEW, $mid );
		}
		else
		{
			// check for required fields...
			if( !$_POST['m'] OR !$_POST['d'] OR !$_POST['y'] OR !$_POST['title'] OR !$_POST['message'] )
			{
				echo $error['fields'];
			}
			else
			{
				$_POST['message'] = str_replace( "\n", '<br>', $_POST['message'] );
				// Prepare the query!
				$SQL = "INSERT INTO phpdesk_events SET
							`title` = '$_POST[title]',
							`message` = '$_POST[message]',
							`day` = '$_POST[d]',
							`month` = '$_POST[m]',
							`year` = '$_POST[y]',
							`owner` = '". USER ."',
							`type` = '$_POST[view]'
						";
				if( TYPE == 'edit' )
				{
					$SQL = "UPDATE phpdesk_events SET
							`title` = '$_POST[title]',
							`message` = '$_POST[message]',
							`day` = '$_POST[d]',
							`month` = '$_POST[m]',
							`year` = '$_POST[y]',
							`owner` = '". USER ."',
							`type` = '$_POST[view]'
						   WHERE id = '$_GET[id]'
						  ";
				}
				
				// Execute the query1
				if( $db->query( $SQL ))
				{
					$what = ( TYPE == 'edit' ) ? 'Edited' : 'Added';
					echo rpl( '^what^', $what, $success['cal_event'] );
				}
			} // end else..

		} // end else submit
	}
} // END Event add!

//
// View An Event!
// This is for viewing an event..
//
if( ACT == 'view' )
{
	// check for the required variables..
	if( !$_GET['id'] OR !$_GET['d'] OR !$_GET['m'] OR !$_GET['y'] )
	{
		echo $error['fields'];
	}
	else
	{
		$db->query( "SELECT title, message, LOWER(owner) as owner, type, day, month, year FROM phpdesk_events WHERE id = '$_GET[id]'" );
		if(! $db->num() )
		{
			echo $error['no_auth_or_record'];
		}
		else
		{
			$f = $db->fetch();
			if( $f['type'] == 'private' AND $f['owner'] != strtolower( USER ) )
			{
				echo $error['no_auth_or_record'];
			}
			else
			{
				// replace <br> with \n then strip all html and finaly convert \n back to <br>!
				$f['message'] = rpl( "\n", '<br>', strip( rpl( '<br>', "\n", $f['message'] )) );
				
				// parse the tpl file and print it!
				_parse( $tpl_dir . 'view.tpl' );
				echo getBlock( $class->read, 'CALENDER' );
			}
		}
		// end record existant check!
	}
	// end variable check!
}
// End View!

//
// Delete an Event!
//
if( ACT == 'delete' )
{

	// prepare the sql query..
	$sql = "SELECT id FROM phpdesk_events WHERE id = '$_GET[id]' AND 
				(( `type`='private' AND LOWER(owner) = '".strtolower(USER)."' ) OR ( `type`='public' ))
		   ";
	
	// If no id..
	if( !$_GET['id'] )
	{
		echo $error['id_missing'];
	}
	elseif( !$db->num( $db->query($sql)) )
	{
		echo $error['no_auth_or_record'];
	}
	elseif( $_GET['confirm'] != 'YES' )
	{
		// confirmation!
		echo 'Do you really want to delete the record : <b>'.$_GET['id'].'</b><br />';
		echo "[ <a href='$_SERVER[PHP_SELF]action=delete&confirm=YES&id=$_GET[id]'>Yes</a> ] ";
		echo '[ <a href="'. $_SERVER['PHP_SELF'] .'">No</a> ] <br />';
	}
	else
	{
		// Delete the record..
		$db->query( "DELETE FROM phpdesk_events WHERE id = '$_GET[id]'" );
		echo rpl( '^what^', 'Deleted', $success['cal_event'] );
	}
}
// End Delete!

?>