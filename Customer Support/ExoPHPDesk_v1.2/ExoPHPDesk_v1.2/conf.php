<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Configuration File
// >>
// >> CONF . PHP File - Configuration of EXOHelpDesk
// >> Started : November 10, 2003
// >> Edited  : July 10, 2004
// << -------------------------------------------------------------------- >>

//
// Timer functions
// START TIME COUNTER
//
function start_time()
{
	$stime = microtime();
	$stime = explode (' ', $stime);
	$stime = $stime[1] + $stime[0];
	$start_time = $stime;
	return $start_time;
}

$StartTime = start_time();

// END TIME COUNTER
function end_time()
{
	$stime = microtime();
	$stime = explode (' ', $stime);
	$stime = $stime[1] + $stime[0];
	$end_time = $stime;

	return $end_time;
}

// Include Class File
include_once( 'db_conf.php' );
include_once( 'class/class.php' );
include_once( 'class/imap.php' );
include_once( 'class/kb.php' );

// WAKE UP THE KNOWLEDGE BASE
$KB = new KB;

// CONNECT TO MySQL SERVER
$db = new mySQL;

$db->db['db_user']  =  $user;
$db->db['db_host']  =  $host;
$db->db['db_pass']  =  $pass;
$db->db['db_datab'] =  $database;
$db->db['db_ext']   =  $DB_EXT;

$db->connect();

// is it the installation script?
if( INSTALL == 1 ) {
	return;
}

// CONFIGURATION QUERIES
$CONQ   =  $db->query ( "SELECT * FROM phpdesk_configs" );
$CONF   =  $db->fetch ( $CONQ );

// ----- DONT TOUCH BELOW UNLESS YOU KNOW WHAT YOU ARE DOING ----- //

// time zone!
$CONF['time_zone'] = '+2.00';

// Banned Ips should go here
$BannedArray = array( '198.1.1.1', '1.1.1.1' );

// LANGUAGE FILE 
$lang_file   =  $CONF['langfile'];

// URL TO HELPDESK
$help_url    =  $CONF['helpurl'];

// HELPDESK TITLE (NAME)
$site_name   =  $CONF['sitename'];

// REPLY EMAIL ADDRESS
$email       =  $CONF['remail'];

// TEMPLATE DIRECTORY
$tpl_dir     =  $CONF['tpldir'];

// CHAT LOGS DIRECTORY
$chat_dir    =  $CONF['chatdir'];

// MEMBERS CAN SEE SERVER STATUS ?
$MEM_SERV    =  $CONF['mem_serv'];

// MAIL Server Configurations
$mailtype    =  $CONF['mailtype'];
$mailhost    =  $CONF['mailhost'];
$mailuser    =  $CONF['mailuser'];
$mailpass    =  $CONF['mailpass'];

// TroubleShooter List Word Wrap
$T_W_WRAP    =  40;

$MEM_TROUBLESHOOTER  =  1;

// Attachments Configurations
$Max_Upload   =  $CONF['at_size'];
$Allowed_Ext  =  $CONF['at_ext'];
$Attach_dir   =  $CONF['at_dir'];
$Attach_pre   =  $CONF['at_prefix'];
$Attach_Allow =  $CONF['at_allow'];

// Whether the helpdesk is offline?? Fetch the reason as well..
$desk_offline = $CONF['desk_offline'];
$off_reason   = $CONF['off_reason'];

// Session Timeout In Seconds.. Default Is 3 Minutes
$STimeOut = 60 * 3;

// Cookie Session TimeOut.. Default is 3 Minutes
$CSTimeOut = 60 * 3;

// Allow Staff to Add Announcements
// 0 = No / 1 = Yes
$StaffAnnounce = $CONF['st_announce'];

// This enables Gzip Compression. If you get weird characters you should set it to 0!
$doGzip = 1;

// This enables some headers like HTTP 1.1 header and cache.
// Set to 0 if its causing problems when using Windows NT...
$doHeaders = 1;

// VERSION NUMBER
$VERSION     =  '<i>1.2 Final</i>';

// INCLUDE TEMPLATE INFO FILE
if ( file_exists( $tpl_dir.'tpl_info.php' ) )
{
	include_once( $tpl_dir.'tpl_info.php' );
}

// << -------------------------------------------------------------------- >>
// >> Most abundantly used queries
// << -------------------------------------------------------------------- >>

// SQL SELECT QUERIES
$ExSQL['sel_mem']        =  "SELECT * FROM phpdesk_members";
$ExSQL['sel_staff']      =  "SELECT * FROM phpdesk_staff";
$ExSQL['sel_admin']      =  "SELECT * FROM phpdesk_admin";
$ExSQL['sel_ticket']     =  "SELECT * FROM phpdesk_tickets";
$ExSQL['sel_response']   =  "SELECT * FROM phpdesk_responses";
$ExSQL['sel_pm']         =  "SELECT * from phpdesk_pm";
$ExSQL['sel_group']      =  "SELECT * FROM phpdesk_groups";
$ExSQL['sel_lchat']      =  "SELECT * FROM phpdesk_livechat";
$ExSQL['sel_lonline']    =  "SELECT * FROM phpdesk_liveonline";
$ExSQL['sel_note']       =  "SELECT * FROM `phpdesk_notes`";

// INSERT SQL QUERIES
$ExSQL['ins_ticket']     =  "INSERT INTO phpdesk_tickets (`admin_id`,`admin_user`,`update`,`title`,`group`,`text`,
							`priority`,`opened`,`status`,`waiting`,`fields`,`values`,`attach`) VALUES(^sql^)";
$ExSQL['ins_ticket2']    =  "INSERT INTO phpdesk_tickets (`admin_id`,`admin_user`,`update`,`title`,`group`,`text`,
							`priority`,`opened`,`status`,`waiting`) VALUES(^sql^)";
$ExSQL['ins_lchat']      =  "INSERT INTO phpdesk_livechat (chatid,timeout,starter,`status`,at) 
				  	         VALUES(";
$ExSQL['ins_lonline']    =  "INSERT INTO phpdesk_liveonline (ip,user,negotiated,timeout,utype)
						     VALUES(";

// UPDATE SQL QUERIES
$ExSQL['up_mem']         =  "UPDATE phpdesk_members SET";
$ExSQL['up_staff']       =  "UPDATE phpdesk_staff SET";
$ExSQL['up_admin']       =  "UPDATE phpdesk_admin SET";
$ExSQL['up_ticket']      =  "UPDATE phpdesk_tickets SET";
$ExSQL['up_lchat']       =  "UPDATE phpdesk_livechat SET";
$ExSQL['up_lonline']     =  "UPDATE phpdesk_liveonline SET";

// Extract SQLs
extract( $ExSQL );

// << -------------------------------------------------------------------- >>
// >> Some Useful Functions For Easy Editing
// << -------------------------------------------------------------------- >>

// AWAKE TEMPLATE PARSER
$class = new template;

// PARSE FUNCTION
function _parse( $file, $lang = '' )
{	

	global $class;
	
	// PARSE HERE
	$class->parse ( $class->path = $file, $class->lang = $lang );

}

// Append A Box To Each Record Of An Array
// Ex. Error Vars, Success Vars Arrays
function AppendBox( $Needle, $Type )
{
	$NewArray = NULL;
	
	while( list( $key, $val ) = each( $Needle ))
	{
		if( $key == 'no_staff' )
		{
			$NewArray[$key] = '<br /><table border="1"><tr><td height="22" align="center" background="tpl/Blue/images/bg_td.jpg">'
				.'<font color="white" face="verdana" size="2"><b>'. $Type .'</b></font></td></tr><td align="center">'
				.'<font face="verdana" size="2">'. $val .'<a href="javascript:window.close()">'
				.'Close Window</a></font></td></tr></table>';
		}
		elseif( $key == 'logout_user' )
		{
			$NewArray[$key] = '<br /><table><tr><td height="22" class="tdup" background="tpl/Blue/images/bg_td.jpg">'
				. $Type .'</td></tr><td align="center">'. $val .'</td></tr></table>';		
		}
		else
		{
			$NewArray[$key] = '<br /><table><tr><td height="22" class="tdup" background="tpl/Blue/images/bg_td.jpg">'
				. $Type .'</td></tr><td align="center">'. $val .'<a href="javascript:history.go(-1)">Return To Form</a>'
				.'<br><a href="javascript:history.go(-2)">Return Where You Previously Were</a></td></tr></table>';
		}
	}
	
	return $NewArray;
}

//
// exo_date()
// A replacement for php's date() function, recreated for 
// timezone compatiablities!
//
function exo_date($format, $time = "")
{
	global $CONF;

	$time   = !$time ? time() : (int)$time;
	$zone   = $CONF['time_zone'];

	if (strstr($zone, '-') OR strstr($zone, '+'))
	{
		$zone = preg_replace('/([0-9\.]+)/', '\\1', $zone );
		if (is_float((float)$zone))
		{
			$split  = explode('.', $zone);
			$offset = ((int) $split[1] / 60) * 100;
			$zone   = (float) $split[0] .'.'. $offset;
		}
		$zone = $zone * 3600;

		return gmdate($format, ($time + $zone));
	}
}

// TRIM DATA AND ADDSLASHES
function ex_strip( $NEEDLE )
{
	if ( is_array( $NEEDLE ) )
	{
		$OUT = array();
		
		while ( list( $KEY, $VAL ) = each ( $NEEDLE ) )
		{
			// FIX! for recursive arrays..
			if (is_array($VAL)) {
				$OUT[$KEY] = ex_strip($VAL);
				continue;
			}
			
			if( !get_magic_quotes_gpc() )
			{
				$TMP = addslashes ( trim ( $VAL ) );
			}
			else
			{
				$TMP = trim( $VAL );
			}
			$OUT[$KEY] = $TMP;
		}
	}
	else
	{
		if( !get_magic_quotes_gpc() )
		{
			$OUT = addslashes ( trim ( $NEEDLE ) );
		}
		else
		{
			$OUT = trim ( $NEEDLE );
		}	
	}
	
	return $OUT;
}

// PARSE ALL POST/GET DATA USING EX_STRIP FUNCTION
$_POST = ex_strip ( $_POST );

// LIST ONLINE USERS WITH LINK TO PROFILE
function listOnline()
{
	global $db, $tpl, $SID;
	
	$onLine = NULL;
	$OLQ = $db->query( "SELECT name,type FROM phpdesk_sessions WHERE timeout >= UNIX_TIMESTAMP() ORDER by name" );

	while( $OL = $db->fetch( $OLQ ))
	{
		if( $OL['type'] == 'admin' )
		{
			$onLine .= $tpl['font_rd'] . $OL['name'] . '*</font>, ';
		}
		elseif( $OL['type'] == 'staff' )
		{
			$onLine .= '<a href="index.php?fn=profile&s='.$SID.'&user='.$OL['name'].'">'. $tpl['font_gr'] 
						. $OL['name'] .'</a>*</font>, ';		
		}
		else
		{
			$onLine .= '<a href="index.php?fn=profile&s='.$SID.'&user='.$OL['name'].'">'. $OL['name']
					.'</a>, ';
		}
	}
	
	$onLine = ( substr( $onLine, -2 ) == ', ' ) ? substr( $onLine, 0, -2 ) : $onLine;
	$onLine = ( empty( $onLine )) ? $tpl['none'] : $onLine;
	
	return $onLine;
}

// List Upcoming Events Of 7 Days!
function upComingEvents()
{
	global $tpl, $db, $SID;
	
	// day / month / year
	$t = explode( ' ', exo_date( 'd m Y', time() ));
	
	// End day!
	$end = $t[0] + 7;
	
	// get the content from the table for the current day and month..
	$db->query( "SELECT title, day, month, year, id FROM phpdesk_events WHERE month = '$t[1]' AND year = '$t[2]' 
						AND day >= '$t[0]' AND ( owner = '". USER ."' OR `type` = 'public' ) ORDER by day" );
	
	if(! $db->num() )
	{
		return $tpl['no_up_event'];
	}
	else
	{
		while( $f = $db->fetch() )
		{
			$f['title'] = $f['day'] == $t[0] ? $tpl['font_rd']. $f['title'] .'</font>' : $f['title'];
			$upEvents .= "<a href='index.php?fn=calender&action=view&id=$f[id]&d=$f[day]&m=$f[month]&y=$f[year]&s=$SID'>
								{$tpl[caleventfont]}$f[title]</font></a>, ";
		}
	}

	$upEvents = '- '. ( substr( $upEvents, -2 ) == ', ' ? substr( $upEvents, 0, -2 ) : $upEvents );
	
	return $upEvents;
}

//
// Another template function!
// Users apply an array of data and the replacements take
// place right here..
//
function rpl ( $TO, $WI = '^', $WH )
{
	// check to see if an array has been posted
	if( is_array( $TO ))
	{
		while( list( $key, $val ) = each( $TO ))
		{
			$WH = str_replace( $WI . $key . $WI, $val, $WH );
		}
		
		return $WH;
	}
	else
	{
		return str_replace ( $TO, $WI, $WH );
	}
}

// 
// do slashes, replacement of addslashes
// 
function doslashes( $data, $type = 1 )
{
	if( $type == 1 )
	{
		$data = str_replace('\\', '\\\\', $data);
        $data = str_replace('\'', '\\\'', $data);
        $data = str_replace("\r", '\r'  , $data);
        $data = str_replace("\n", '\n'  , $data);
        
        return $data;
	}
}

// TEMPLATE PARSER
function template ( $TEXT, $START = '', $END = '' )
{
	
	/* TEMPLATE PARSING ACCORDING TO START AND END */
	if ( !empty ( $START ) )
	{
		$TEXT = substr ( $TEXT, strpos ( $TEXT, $START ) +  strlen ( $START ) );
	}
	
	/* VALIDATE END */
	if ( !empty ( $END ) )
	{
		$TEXT = substr ( $TEXT, 0, strpos ( $TEXT, $END ) );
	}
	
	/* RETURN */
	return $TEXT;	
	
}

// Template Blocks Parser
function getBlock ( $TEXT, $START, $END = '' )
{
	global $B_ST, $B_ED, $B_SS;
	
	// Find Out End
	$END  = (empty( $END )) ? $START : $END;
	
	// Parse Template
	$TEXT = template( $TEXT, $B_ST . $START . $B_ED, $B_SS . $END . $B_ED );

	// Return
	return $TEXT;
}

// Get Directory Size
function dirSize( $name )
{
	if($dp = opendir($name))
	{
		while($read = readdir($dp))
		{
			if($read!='.' && $read!='..')
			{				
				if(is_dir($name.$read))
				{
					$size = $size + dirSize( $name.$read.'/' );
				}
				else 
				{
					$size +=  filesize($name.$read);
				}
			}
		}
		clearstatcache();
		closedir($dp);
	}
	
	return $size;
}		

// Function TO SHOW FIELDS AND VALUES
function reverse_ticket( $FIELDS, $VALUES, $LIST )
{
	if(!empty($FIELDS))
	{
		$FIELD = explode("|||", $FIELDS);
		$VALUE = explode("|||", $VALUES);
		$x = 0;
		foreach ( $FIELD as $SPLIT )
		{
			if(!empty($SPLIT))
			{
				$BG = ( is_float( ($x+1) / 2 ) ) ? 'ticketbg' : 'ticketbg2';
				$TMP = rpl( '^opt_name^', rpl( '^SPC^', " ", ucfirst($SPLIT)), rpl('^opt_value^', $VALUE[$x] , $LIST) );
				$TMP = rpl( '^tdbg^', $BG, $TMP );
				
				$RETURN .= $TMP;
			}
			$x++;
		}
		
		return $RETURN;
	}
}

// GET VALUES ACCORDING TO FIELDS
function val_fields( $_POST, $type )
{
	global $db;

	//
	// Find out whether Profile or Ticket fields were requested ...
	//
	if( $type == 'profile' )
	{
		$query = $db->query( "SELECT field FROM phpdesk_fields WHERE type = 'Profile'" );		
	}
	else
	{
		$query = $db->query( "SELECT field FROM phpdesk_fields WHERE type = 'Ticket'" );
	}
	
	$_F = $db->fetch( $query );

	$FIELDF = $_F['field'];
						
	if( $FIELDF != NULL )
	{
		$FIELD = explode("|||", $FIELDF);
	}
	else
	{
		$FIELD = "";
	}
						
	if( is_array( $FIELD ) )
	{
		foreach( $FIELD as $SPLIT )
		{
			if( !empty( $SPLIT ) )
			{
				$FIELDS .= $_POST[$SPLIT]."|||";
			}
		}
	}
	else
	{
		$FIELDS = "";
	}

	return $FIELDS;
}

// GET ALL THE FIELDS AVAILABLE
function get_fields( $LIST, $type, $sub='', $VAL='', $FLD='', $MAND='' )
{
	global $db;
	
	if( $type == 'ticket' OR $type == 'profile' )
	{
		//
		// Find out whether Profile or Ticket fields were requested ...
		//
		if( $type == 'profile' )
		{
			$query = $db->query( "SELECT * FROM phpdesk_fields WHERE type = 'Profile'" );		
		}
		else
		{
			$query = $db->query( "SELECT * FROM phpdesk_fields WHERE type = 'Ticket'" );
		}

		$_F = $db->fetch( $query );
		$FIELDF = $_F['field'];
						
		if( $FIELDF != NULL )
		{
			$FIELD = explode("|||", $FIELDF);
		}
		else
		{
			$FIELD = "";
		}
		
		$MAND = ( empty( $MAND ) ) ? $_F['mandatory'] : $MAND;

		$MAND = explode("|||", $MAND);
		$x = 0;
		if( is_array( $FIELD ) )
		{
			$Z = 3;
			foreach( $FIELD as $SPLIT )
			{
				if( !empty( $SPLIT ) )
				{
					if( $sub == 'SQL' )
					{
						$FIELDS .= $SPLIT."|||";
					}
					else
					{
						$Z++;
						$SPLIT_N = ( $MAND[$x] == 0 && $sub != 'edit' && $sub != 'fill' ) ? "<i>". ucfirst( $SPLIT ) ."</i>" : ucfirst( $SPLIT );
						
						$OUT = str_replace( '^opt_name1^', rpl( '^SPC^', " ",$SPLIT_N), $LIST );
						$OUT = str_replace( '^x1^', $Z+1, str_replace( '^x^', $Z, $OUT ) );
						if( $sub == 'edit' )
						{
							$DO = $field = $FLDs = $VALs = $VALUE = '';
							$y = 0;
							if( !empty( $FLD ) )
							{

								$FLDs = explode("|||", $FLD);
								$VALs = explode("|||", $VAL);
								
								foreach ( $FLDs as $field )
								{
									if( strtolower ( $field ) == strtolower ( $SPLIT ) )
									{
										$DO = 'YES';
										$VALUE = $VALs[$y];
										BREAK;
									}
									$y++;
								}
							}
						}
						$REPLACE = "";
						
						if( $sub == 'fill' )
						{
							$OUT = str_replace( '^value^', rpl( '^SPC^', " ",$SPLIT ), $OUT );
							$name = ( $MAND[$x] == '1' ) ? "Yes" : "No";
							$REPLACE = '<option value="'.$MAND[$x].'">'.$name.'</option>';
						}
						
						$OUT = str_replace( '^mand^', $REPLACE , $OUT );
						$OUT = str_replace( '^opt_value^', $VALUE, $OUT );
						$FIELDS .= str_replace( '^opt_name2^', $SPLIT, $OUT );
					}
				}
				$x++;
			}
		}
		else
		{
			$FIELDS = "";
		}
		return $FIELDS;
	}
}

// Logout the user
function Dologout( $SID )
{
	global $db;
	
	// Setcookies to null and go back to login page
	setcookie('help__user', '', time()-3600);
	setcookie('help__pass', '', time()-3600);
	
	$db->query( "DELETE FROM phpdesk_sessions WHERE sid = '$_COOKIE[help__sid]'" );
	setcookie('help__sid', '', time()-3600);
				
	// Delete Session
	if( !empty( $SID ))
	{
		$db->query( "DELETE FROM phpdesk_sessions WHERE sid = '{$SID}'" );
	}
}

// WHEN WAS TICKET OPENED?
function opened( $OPEN )
{
	$opened = number_format ( ( ( time() - $OPEN ) ), 0, '.', '' )." Seconds Ago";
	if( $opened > 60 )
	{
		$opened = number_format ( ( ( time() - $OPEN ) / 60 ), 0, '.', '' )." Minutes Ago";
		if( $opened > 60 )
		{
			$opened = number_format ( ( ( time() - $OPEN ) / 3600 ), 0, '.', '' )." Hours Ago";
	
			if( $opened >= 24 )
			{
				$opened = number_format ( ( ( time() - $OPEN ) / 86400), 0, '.', '' )." Days Ago";
			}
		}
	}
	
	RETURN $opened;
}

// VALIDATION FUNCTION
function validate($type,$_POST,$sub='')
{
	global $error,$db;
	
	$REGE = '/^[a-z0-9\_\.\-]+[\@]+[a-z0-9\-]+[\.]+[a-z0-9\.]{0,10}$/i';
	$REGU = '/^[a-z0-9\_]+$/i';
	
	if($type == 'user')
	{
		if($sub == 'edit')
		{
			if(!$_POST['username'] || !$_POST['name'] || !$_POST['name']){
						return $error['fields'];
			}
			elseif($_POST['password']!="" AND strlen($_POST['password']) < 4){
						return $error['min_pass'];
			}
			elseif($_POST['password'] != $_POST['confirm']){
						return $error['pass_match'];
			}
			elseif($_POST['tppage'] <= 0){
						return $error['tppage_zero'];
			}
			elseif(!preg_match($REGE, $_POST['email'])){
						return $error['illegal_email'];
			}			
		}
		else
		{
			if(!$_POST['username'] || !$_POST['name'] || !$_POST['password'] || !$_POST['name']){
						return $error['fields'];		
			}
			elseif(!preg_match($REGU, $_POST['username'])){
						return $error['illegal_user'];		
			}
			elseif(strlen($_POST['password']) < 4){
						return $error['min_pass'];		
			}
			elseif($_POST['password'] != $_POST['confirm']){
						return $error['pass_match'];
			}
			elseif($_POST['tppage'] <= 0){
						return $error['tppage_zero'];
			}
			elseif(!preg_match($REGE, $_POST['email'])){
						return $error['illegal_email'];		
			}
			
		}
	}
	elseif($type == 'staff')
	{
		if(!$_POST['username']){ 
				return $error['fields'];
		}
		if(!preg_match($REGU, $_POST['username'])){
				return $error['illegal_user'];		
		}		
		if(!preg_match($REGE, $_POST['email'])){
				return $error['illegal_email'];		
		}
		if($sub == '' && strlen($_POST['password']) < 4){
				return $error['min_pass'];
		}
		if(($sub == 'edit' OR $sub == 'profile') && !empty($_POST['password']) && strlen($_POST['password']) < 4){
				return $error['min_pass'];
		}
		if($sub!='profile' && empty($_POST['g'])){
				return $error['no_group'];
		}
		if($sub == 'profile')
		{
			if($_POST['tppage'] <= 0){
					return $error['tppage_zero'];
			}
			if($_POST['password'] != $_POST['confirm']){
					return $error['pass_match'];
			}
		}
	}
	elseif($type == 'admin')
	{
	
		if(!$_POST['username']){
				return $error['fields'];		
		}
		if(!preg_match($REGU, $_POST['username'])){
				return $error['illegal_user'];		
		}
		if($sub == '' && strlen($_POST['password']) < 4){
					return $error['min_pass'];
		}
		if($sub == 'edit' && !empty($_POST['password']) && strlen($_POST['password']) < 4){
					return $error['min_pass'];
		}
		if($sub == 'edit' && $_POST['password'] != $_POST['confirm']){
				return $error['pass_match'];
		}
		if($_POST['tppage'] <= 0){
				return $error['tppage_zero'];
		}
		if(!preg_match($REGE, $_POST['email'])){
				return $error['illegal_email'];		
		}	
		
	}
	elseif($type == 'groups')
	{
		if(!$_POST['group_name']){
			return $error['fields'];
		}
		if(!preg_match($REGU, $_POST['group_name'])){
			return $error['illega_group'];
		}
	}
	elseif($type == 'kb')
	{
		if(empty($_POST['title']) || empty($_POST['message']) || empty($_POST['group']))
		{
			return $error['fields'];
		}
		$Q = $db->query("SELECT * FROM phpdesk_kb WHERE title='".$_POST['title']."'");
		if($sub!='edit' && $db->num($Q))
		{
			return $error['kb_exists'];
		}
	}
	elseif($type == 'fields')
	{
		$sub = ( empty( $sub ) ) ? 'Ticket' : $sub;
	
		$_Q = $db->query("SELECT * FROM phpdesk_fields WHERE type = '$sub'" );
		$_F = $db->fetch($_Q);
					
		$FIELDF = $_F['field'];
		if($FIELDF != NULL)
		{
			$FIELD = explode("|||", $FIELDF);
		}
		else
		{
			$FIELD = "";
		}
					
		$MANDAT = $_F['mandatory'];
		$MANDAT = explode("|||", $MANDAT);
					
		if(is_array($FIELD))
		{
			$x = 0;
						
			foreach( $FIELD as $SPLIT )
			{
				$SPLIT = trim ( $SPLIT );
				if(!empty( $SPLIT ))
				{
					if($MANDAT[$x] == '1')
					{
						if(empty($_POST[$SPLIT]))
						{
							$ERROR = 1;
							return $ERROR;
						}
					}
				}
				$x++;
			}
		} // END IF ARRAY
	
	}

}

// STRIP HTML/jSCRIPT
function strip($text)
{
	
	$text = strip_tags ( $text, '<br><i><u><strong><p>' );
	// $text = htmlspecialchars($text);
	// Convert URLs
	$text = preg_replace( "/(^|\s)((http|https|ftp):\/\/[^\s]+)/i", '\\1<a href="\\2">\\2</a>', $text );
	
	return $text;
}

// Number formatting function
function exo_Format( $data, $dec = 0 )
{
	//
	// Below I havn't used the exact numbers to stop confusion
	// Everyone knows that 1024 bytes = 1 kb, 1024 kb = 1 mb 
	// and vice versa.
	// 
	
	$val = 'Bytes';
	
	if( $data >= 1024*1024*1024 )
	{
		$ret = 1024*1024*1024;
		$val = 'GB';
	}
	
	if( $data >= 1024*1024 )
	{
		$ret = 1024*1024;
		$val = 'MB';
	}
	
	if( $data >= 1024 )
	{
		$ret = 1024;
		$val = 'KB';
	}
	
	// return an array of number and unit
	
	if( $val != 'Bytes' )
	{
		return array( number_format( $data / $ret, $dec, '.', ',' ), $val );
	}
	else
	{
		return array( number_format( $data, $dec, '.', ',' ), $val );
	}
	
}

// FIND THE APPROPRIATE TABLE
function where( $user, $idBased = 0, $id = NULL )
{
	global $db,$sel_mem,$sel_staff,$sel_admin;
	// GLOBAL STUFF END --------------------- //
	 
	if( $idBased == 1 )
	{
		$MEM = $db->query( $sel_mem . " WHERE id = '$id'" );
		$STF = $db->query( $sel_staff . " WHERE id = '$id'" );
		$WHERE = ( $db->num( $MEM ) ) ? "phpdesk_members" : ( ( $db->num($STF) ) ? "phpdesk_staff" : "phpdesk_admin" );
	}
	else
	{
		$CMEMS = $db->query ( $sel_mem . " WHERE username = '" . $user . "'" );
		$STAFF = $db->query ( $sel_staff . " WHERE username = '" . $user . "'" );
		$WHERE = ( $db->num( $CMEMS ) ) ? "phpdesk_members" : ( ( $db->num($STAFF) ) ? "phpdesk_staff" : "phpdesk_admin" );
	}
	
	/* RETURN TABLE NAME */
	RETURN $WHERE;
}

// Code taken From EXO Password Generator
function random ( $pass_length )
{
	// Make an array with upercase alphabets
	$a = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	// Set variable to 0
	$x = 0;
	// Make an array with lowercase alphabets	
	while ( list( $k,$c ) = each( $a ) )
	{
		if(!is_array($b))
		{
			$b = array_fill($x,($x+1), strtolower($c));
		}
		else
		{
			array_push($b, strtolower($c));
		}
	}
	// Below is a number generator, it will generate numbers from 0-9
	// If you don't want it to start from 0 you can change below
	$x = 0;
	// Change 9 to whatever ending number you desire
	while( $x <= 9 )
	{
		if(!is_array($f))
		{
			$f = array_fill($x,($x+1), $x);
		}
		else
		{
			array_push($f, $x);
		}
		$x++;
	}
	// Define $d as the number array so as to increase the numbers
	$d = $f;
	// Merge all the arrays
	$merge = array_merge($a,$b,$d,$f);
	// Seed generator for older version of php
	srand ((float) microtime() * 10000000);
	// Randomize the array
	shuffle($merge);
	// Take out random enteries out of array
	$rand = array_rand($merge, $pass_length);
	
	while ( list( $k,$r ) = each( $rand ) )
	{
		$number .= $merge[$r];
	}
	return $number;
}

// MAIL FUNCTION FOR ExoPHPDesk
function mail_it( $type='', $to, $subject, $ext='', $name='', $tid='', $message='', $title = '' )
{
	global $tpl_dir,$class,$_POST,$email, $site_name;

	$headers = "From: {$site_name}<{$email}>\r\n"
		      ."Reply-To: {$email}\r\n"
			  ."X-Mailer: PHP";

	_parse($tpl_dir.'mail.tpl')	;
	$read = $class->read;

	if($type == 'register')
	{
		$pos = (strpos($read, '[#'.$type)+strlen($type)+2);
		$read = substr($read, $pos);
		$pos = strpos($read, '/#'.$type.']');
		$read = substr($read, 0, $pos);
		$read = str_replace('^user^', $_POST['username'], str_replace('^name^', $_POST['name'], $read));
		$read = str_replace('^pass^', $_POST['password'], $read);
		mail($to,$subject,$read,$headers);
	}
	elseif($type == 'staff')
	{
		$pos = (strpos($read, '[#'.$type)+strlen($type)+2);
		$read = substr($read, $pos);
		$pos = strpos($read, '/#'.$type.']');
		$read = substr($read, 0, $pos);
		$read = str_replace('^user^', $_POST['username'], str_replace('^name^', $_POST['name'], $read));
		$read = str_replace('^pass^', $_POST['password'], $read);
		mail($to,$subject,$read,$headers);
	}
	elseif($type == 'newpm')
	{
		$pos = (strpos($read, '[#'.$type)+strlen($type)+2);
		$read = substr($read, $pos);
		$pos = strpos($read, '/#'.$type.']');
		$read = substr($read, 0, $pos);
		$read = str_replace('^name^', $name, str_replace('^php_file^', $ext, $read));
		mail($to,$subject,$read,$headers);
	}
	elseif($type == 'newresponse')
	{
		$pos = (strpos($read, '[#'.$type)+strlen($type)+2);
		$read = substr($read, $pos);
		$pos = strpos($read, '/#'.$type.']');
		$read = substr($read, 0, $pos);
		$read = str_replace('^name^', $name, str_replace('^php_file^', 'member.php', $read));
		$message = stripslashes( $message );
		$read = str_replace('^tid^', $tid, str_replace( '^message^', $message, $read ) );
		mail($to,$subject,$read,$headers);
	}
	elseif($type == 'newticket')
	{
		$pos = (strpos($read, '[#'.$type) + strlen($type)+2 );
		$read = substr($read, $pos);
		$pos = strpos($read, '/#'.$type.']');
		$read = substr($read, 0, $pos);
		$read = str_replace('^name^', $name, str_replace('^department^', $ext, $read));
		$read = rpl( '^TITLE^', $title, rpl( '^MESSAGE^', $message, $read ));
		mail($to,$subject,$read,$headers);
	}
	elseif($type == 'lostpass')
	{
		$pos = (strpos($read, '[#'.$type)+strlen($type)+2);
		$read = substr($read, $pos);
		$pos = strpos($read, '/#'.$type.']');
		$read = substr($read, 0, $pos);
		$read = str_replace('^name^', $name, str_replace('^key^', $ext, $read));
		mail($to,$subject,$read,$headers);
	}
	elseif($type == 'pipe_tcreate')
	{
		$data = template($read, '[#'.$type, '/#'.$type.']');
		$data = rpl(array('subject' => $title, 'message' => $message, 'tid' => $tid), '^', $data);
		mail($to,$subject,$data,$headers);
	}
	elseif($type == '' && $message != '')
	{
		mail($to,$subject,$message,$headers);		
	}
}

// FUNCTION TO MAIL ALL THE STAFF
function mail_all_staff( $group_t, $user_t = '', $message = '', $title = '', $tid = '', $type = '' )
{
	global $db,$general;

	$_Q = $db->query( "SELECT * FROM phpdesk_staff WHERE email<>''" );

	while($_F = $db->fetch($_Q))
	{
	
		$a_groups = $_F['groups'];
		if($a_groups == 'ALL')
		{
			$no_auth = 0;
		}
		else
		{
			$split = explode("|||", $a_groups);
			foreach ( $split as $group )
			{
				if($group_t == $group)
				{
					$no_auth = 0;
					break;
				}
				else
				{
					$no_auth = 1;
				}
			}
		}

		if($no_auth != 1 && $user_t != $_F['username'])
		{
			if( $type == 'response' && $_F['notify_response'] == '1' )
			{
				mail_it('newresponse', $_F['email'], $general['new_response'], $group_t, $name = $_F['name'], $tid, $message, $title );
			}
			elseif( $_F['notify_ticket'] == '1' )
			{
				mail_it('newticket', $_F['email'], $general['newticket'], $group_t, $name = $_F['name'], '', $message, $title );
			}
		}
	}
	
	$_Q = $db->query( "SELECT * FROM phpdesk_admin WHERE email<>''" );
	while($_F = $db->fetch($_Q))
	{
		if($user != $_F['name'])
		{
			if( $type == 'response' && $_F['notify_response'] == '1' )
			{
				mail_it('newresponse', $_F['email'], $general['new_response'], $group_t, $name = $_F['name'], $tid, $message, $title );
			}
			elseif( $_F['notify_ticket'] == '1' )
			{
				mail_it('newticket', $_F['email'], $general['newticket'], $group_t, $name = $_F['name'], '', $message, $title );
			}
		}
	}
	
}

// PRIVATE MESSAGING FUNCTION
function pm( $user,$type='',$id='',$sendto='',$message='',$title='' )
{
	global $db,$list,$error,$success,$_GET,$general,$SID;
	
	$over_all = "";
	
	if( $type == '' )
	{
		$_Q = $db->query( "SELECT * FROM phpdesk_pm WHERE sentfor = '".$user."' ORDER by sent DESC" );
		while( $_F = $db->fetch( $_Q ))
		{
			$x++;
			$bg = (is_float($x/2)) ? 'ticketbg' : 'ticketbg2';
			$sent = exo_date('d-m-y h:i a', $_F['sent']);
	
			if($_F['read'] == 0)
			{
				$_F['title'] = "<b>".$_F['title']."</b><font style='font-size: 9px'>  *NEW*</font>";
			}
			
			$out = str_replace('^class_right^', '', str_replace('^class_left^', '', str_replace('^class_middle^', '', $list)));
			$title = '<a href="'.$_SERVER['PHP_SELF'].'?action=pm&type=view&id='.$_F['id'].'&s='. $SID .'">'.$_F['title'].'</a>';
			$out = str_replace('^user^', $_F['sentby'], str_replace('^sent^', $sent, $out));
			$out = str_replace('^title^', $title, str_replace('^id^', $_F['id'], $out));
			$out = str_replace('^bg^', $bg, $out);
			$over_all .= $out;
		}		

		return $over_all;

	}
	elseif($type == 'view')
	{
		$_Q = $db->query("SELECT * FROM phpdesk_pm WHERE id = '{$id}' AND sentfor = '{$user}'");
		$_F = $db->fetch($_Q);
		
		if(!$db->num($_Q))
		{
			return $error['no_auth_or_record'];
		}
		else
		{
			$sent = exo_date('d-m-y h:i a', $_F['sent']);
			$list = str_replace('^class_right^', " class='ticketbg'", str_replace('^class_middle^', " class='ticketbg2'", str_replace('^class_left^', " class='ticketbg'", $list)));
			$one = str_replace('^id^', $_F['id'], $list);
			$one = str_replace('^user^',$_F['sentby'], str_replace('^sent^', $sent, str_replace('^title^', "<b>".$_F['title']."</b><br /><br />".str_replace("\n", "<br />", $_F['message']), $one)));
			$db->query( "UPDATE phpdesk_pm SET `read` = '1' WHERE `id` = '{$id}'" );
			return $one;
		}
	}
	elseif($type == 'send')
	{
		$_Q = $db->query("SELECT * FROM phpdesk_members WHERE username='".$sendto."'");
		$_Q1 = $db->query("SELECT * FROM phpdesk_staff WHERE username='".$sendto."'");
		$_Q2 = $db->query("SELECT * FROM phpdesk_admin WHERE name='".$sendto."'");
		
		if(!$sendto || !$message || !$title)
		{
			return $error['fields'];
		}
		elseif(!$db->num($_Q) && !$db->num($_Q1) && !$db->num($_Q2))
		{
			return $error['no_user'];
		}
		else
		{
			$where = ($db->num($_Q)) ? "phpdesk_members" : (($db->num($_Q1)) ? "phpdesk_staff" : (($db->num($_Q2)) ? "phpdesk_admin" : ""));
			$file = ($where == "phpdesk_admin") ? "admin.php" : (($where == "phpdesk_staff") ? "staff.php" : "index.php");
			
			$sql = ($where == "phpdesk_admin") ? "SELECT * FROM ".$where." WHERE name = '".$sendto."'" : "SELECT * FROM ".$where." WHERE username = '".$sendto."'";
			$Q = $db->query($sql);
			$F = $db->fetch($Q);
			
			if($F['notify_pm']!=0)
			{
				mail_it('newpm', $F['email'], $general['new_pm'], $file, $F['name']);
			}
			
			$sql = "INSERT INTO phpdesk_pm (sentby,sentfor,title,message,sent)
			VALUES('".$user."','".$sendto."','".$title."','".$message."','".time()."')";
			if($db->query($sql))
			{
				return $success['sent_pm'];
			}
		}
	}
	elseif($type == 'delete')
	{
		$_Q = $db->query("SELECT * FROM phpdesk_pm WHERE id='{$_GET['id']}' AND sentfor='{$user}'");

		if(empty($id))
		{
			return $error['id_missing'];
		}
		elseif(!$db->num($_Q))
		{
			return $error['no_auth_or_record'];
		}
		elseif($_GET['confirm'] != 'YES')
		{
			return $general['del_pm_confirm'];
		}
		elseif($_GET['confirm'] == 'YES')
		{
			$sql = "DELETE FROM phpdesk_pm WHERE id='{$_GET['id']}'";
			if($db->query($sql))
			{
				echo $success['del_pm'];
			}
		}
	}
}

// RESPONSE ADDITION/EDITING FUNCTION
function response($tid,$sname,$comment,$staff,$edit,$id,$EMAIL='')
{
	global $db,$a_id,$error,$success,$general;

	if(empty($tid) || empty($sname) || empty($comment))
	{
		return $error['fields'];
	}
	else
	{
		if($edit == 1)
		{
			$sql = "UPDATE phpdesk_responses SET `comment` = '".$comment."',`sname` = '".$sname."',
			`tid` = '".$tid."' WHERE id='{$_GET['id']}'";

		}		
		else
		{
			$Q_ = $db->query( "SELECT * FROM phpdesk_tickets WHERE id='". $tid ."'" );
			$F_ = $db->fetch($Q_);

			$db->query( "UPDATE phpdesk_tickets SET replies = replies+1, status = 'Open' WHERE id='". $tid ."'" );

			$sendto = $F_['admin_user'];

			if($EMAIL == 1 OR $sendto == 'Guest' OR $_F['admin_email'] != '' )
			{

				$who = "Member";
				
				$SUBJECT = $F_['title'].' ([Ticket ID: '.$tid.'])';
				
				$MESSAGE = $comment."\n--------------------\n".strip($F_['text'])."\n".'([Ticket ID: '.$tid.'])'."\n";
				
				mail_it('',$F_['admin_email'],$SUBJECT,'','','',$MESSAGE);

			}
			else
			{
				
				if ( $sname != 'Guest')
				{
					$where = where( $sendto );
					$file = ($where == "phpdesk_admin") ? "admin.php" : (($where == "phpdesk_staff") ? "staff.php" : "index.php");
	
					$sql = ($where == "phpdesk_admin") ? "SELECT * FROM ".$where." WHERE name = '".$sendto."'" : "SELECT * FROM ".$where." WHERE username = '".$sendto."'";
	
					$Q = $db->query($sql);
					$F = $db->fetch($Q);

					$where = where ( $sname );

					if( $staff == 1 )
					{
						$who = "Staff";
					}
					else
					{
						$who = ($where == "phpdesk_members") ? "Staff" : "Member";
					}

					if($F['notify_response']!=0 && $F['id']!=$a_id)
					{
						mail_it('newresponse', $F['email'], $general['new_response'], $file, $F['name'], $tid, $comment );
					}
					
					//
					// Mail all staff on ticket update!!
					//
					if( strtolower( USER ) == strtolower( $sendto ))
					{
						mail_all_staff( $F_['group'], NULL, $comment, NULL, $tid, 'response' );
					}
					
				}
				else
				{
					// Mail all staff on a new email -> ticket reply!
					mail_all_staff( 'EMAIL', NULL, $comment, NULL, $tid, 'response' );
				}
				
			}
			
			if ( $sname == 'Guest' )
			{
				$who = 'Staff';
			}
			
			$db->query("UPDATE phpdesk_tickets SET waiting = '".$who."',`update`='".time()."' WHERE id = '".$tid."'");
			$sql = "INSERT INTO phpdesk_responses (`comment`,`sname`,`posted`,`tid`)
				VALUES('".$comment."', '".$sname."', '".time()."', '".$tid."')";
		}
		if( $db->query($sql) )
		{
			return $success['add_response'];
		}
	}
}



?>