<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Administration Module
// >>
// >> ADMIN . PHP File - For Administration Of HelpDesk
// >> Started : November 11, 2003
// >> Edited  : June 15, 2004
// << -------------------------------------------------------------------- >>

ob_start();

// LOGIN TYPE
$L_TYPE = 'admin';

// Define as Admin Area
define( 'ADMIN_AREA', 1 );

include_once( 'common.php' );

/* CHECK FOR AUTHORIZATION */
if( $NO_AUTH == 0 )
{
	/* PARSE HEADER FILE IF NO LIVECHAT */
	if( ACT != 'livechat' )
	{
		_parse($tpl_dir.'admin_head.tpl');
		echo $class->read;
	}
		
	/* IF MAILFETCHING ENABLED AND IMAP LIBRARY INSTALLED */
	if( $mailtype != 'None' && ACT == '' && function_exists('imap_open') )
	{
		//
		// Do a check if the server we are connecting to is online
		// as the imap function will just show a timeout error or
		// page cant be displayed. The default timeout here is 4
		// means that if the remote server didn't respond within
		// 4 seconds, it will be considered offline.
		//
		$PORT   = ( $mailtype == 'IMAP' ) ? 143 : 110;
		$STATUS = @fsockopen( $mailhost, $PORT, $ERROR, $ERROR_STR, 4 );

		if( $STATUS )
		{
			/* SET REQUIRED VARS */
			$IMAP = new IMAP;
			$IMAP->MB_EXTYPE = $mailtype;
			$IMAP->MAIL_USER = $mailuser;
			$IMAP->MAIL_PASS = base64_decode($mailpass);
			$IMAP->MB_SRHOST = $mailhost;
			
			/* FETCH MAIL */
			$IMAP->get_mail();
		}
	}
		
	/* NO ACTION */
	if( ACT == '' )
	{

		/* SQL STATS QUERIES */
		$TQ = $db->query( $sel_ticket );
		$Closed = $Opened = $WaitingS = 0;
		while( $TF = $db->fetch( $TQ ))
		{
			if( $TF['status'] == 'Closed' )
			{
				$Closed++;
			}
			
			if( $TF['status'] != 'Closed' )
			{
				$Opened++;
			}
			
			if( $TF['waiting'] == 'Staff' )
			{
				$WaitingS++;
			}
		}

		$MQ = $db->query( $sel_mem );
		$SQ = $db->query( $sel_staff );
		$AQ = $db->query( $sel_admin );
		
		/* STATISTICS VARIABLES */
		$t_open    = $Opened;
		$t_closed  = $Closed;
		$t_waiting = $WaitingS;
		$t_members = $db->num( $MQ );
		$t_staff   = $db->num( $SQ );
		$t_admins  = $db->num( $AQ );
		
		/* ASSIGN THE APPROPRIATE VALUE FOR WAITING PERCENT */
		if( ( $t_open + $t_closed ) != 0 )
		{
			// PERCENT OF TICKETS AWAITING STAFF
			$t_wpercent = ceil( $t_waiting / ( $t_open + $t_closed ) * 100 ) . "%";
		}
		else
		{
			$t_wpercent = "0%";
		}
			
		// PARSE ADMIN_MAIN.TPL FILE
		_parse( $tpl_dir.'admin_main.tpl' );
		echo $class->read;

		$PM_S  = $db->query( $sel_pm . " WHERE `sentfor` = '". USER ."' AND `read`<>'1'" );
		$P_NUM = $db->num( $PM_S );
			
		$TIME  = time() - ( 86400 * 7 );
		$MEM_S = $db->query( "SELECT id,username FROM `phpdesk_members` WHERE `registered` >= {$TIME}" );
		$M_NUM = $db->num( $MEM_S );
		$USERS = NULL;
			
		while( $US = $db->fetch( $MEM_S ))
		{
			$USERS .= '&nbsp; - '. ucfirst( $US['username'] ). '<br>';
		}
			
		$USERS = ( empty( $USERS )) ? '<br>' : $USERS;
		
		// ++ New optimized query in v1.2 final for better performance!
		// It might still become a SLOW Query if there are enormous amount of assigned 
		// tickets in groups, like 2k etc..
		$GRPQ  = $db->query( "
				SELECT g.*, g.id as gid, t.id, t.priority, t.owner, t.title, t.waiting FROM `phpdesk_groups` g 
					LEFT JOIN `phpdesk_tickets` t ON(t.group = g.name AND t.status = 'Open' AND t.owner = '".USER."') 
				ORDER by g.name
							" );
		$GROUP = NULL;
		$O_ASSIGNED = NULL;
		
		while( $GR = $db->fetch( $GRPQ ))
		{
			// unset if required..
			if ($GR['gid'] != $gid) 
			{
				// Unset/Reset
				$HIGH   =  $ASSIGNED  =  $TICKETS = 0;
			}
			
			// set the name if req.
			if (!$groups[$GR['gid']]->name) {
				$groups[$GR['gid']]->name = $GR['name'];
			}
			
			// set open tickets
			if(!$groups[$GR['gid']]->tickets) {
				$groups[$GR['gid']]->tickets = $GR['open_tickets'];
			}
			
			// Set High Priority tickets
			if(!$groups[$GR['gid']]->high) {
				$groups[$GR['gid']]->high = $GR['high_tickets'];
			}
			
			// store group id!
			$gid = $GR['gid'];			
			
			if ($GR['id'] != NULL)
			{
				if( strtolower($GR['owner']) == strtolower(USER) )
				{
					$X++;
					
					//
					// Find out who needs to reply to the ticket now..
					//
					if( $GR['waiting'] == 'Staff' )
					{
						$GR['waiting'] = $tpl['font_rd'] . $GR['waiting'] . '</font>';
					}
					else
					{
						$GR['waiting'] = $tpl['font_gr'] . $GR['waiting'] . '</font>';
					}
					
					// Assigned to the owner :)
					$O_ASSIGNED .= $X . '. <a href="index.php?fn=ticket&type=view&id='. $GR['id'] .'&s='. $SID .'">'
								 . $GR['title'] . '</a> ( <font size="1">Waiting: '. $GR['waiting'] .'</font> )<br />';

					$groups[$GR['gid']]->assigned++;
				}
			}
		}
		
		while(list($key,$g) = each($groups)) 
		{
			$GROUP .= '= <a href="index.php?fn=ticket&type=list&Group='. ucfirst( $g->name ) . '&s='. $SID . '">'
					 .'<b>'. ucfirst( $g->name ) .'</b></a>';
				
			$ASSIGNED = ( $g->assigned > 0 ) ? '<b>' . $g->assigned . '</b>' : '0';
			$HIGH   = ( $g->high > 0 ) ? '<b>' . $g->high . '</b>' : '0';
			$TICKS  = ( $g->tickets > 0 ) ? '<b>' . $g->tickets . '</b>' : '0';
				
			$GROUP .= '<br>There are <a href="index.php?fn=ticket&type=list&Group='. ucfirst( $g->name ). '&s='. $SID 
					 .'">'. $TICKS .'</a> Open Tickets in this Department, Including '. $HIGH
					 .' High Priority Open tickets and '. $ASSIGNED .' tickets assigned to you.'
					 .'<br><br>';		
		}
			
		// Set None If Empty
		$O_ASSIGNED = ( $O_ASSIGNED != NULL ) ? $O_ASSIGNED : $tpl['none'];
		$GROUP      = ( $GROUP != NULL ) ? $GROUP : $tpl['none'];
		
		// DIARY STUFF HERE
		$DIAR_Q = $db->query( "SELECT text FROM phpdesk_diary WHERE `admin_user` = '" . USER . "'" );
		$DIAR_F = $db->fetch( $DIAR_Q );
		$DIARY  = $DIAR_F['text'];
			
		// PARSE MAIN Template File
		_parse( $tpl_dir.'main.tpl' );
		$READ = getBlock( $class->read, 'SUPER_USERS' );
		echo $READ;
			
		// PARSE VIEW TPl FILE To Get Online List
		_parse( $tpl_dir.'view.tpl' );
		$READ  = getBlock( $class->read, 'ONLINE' );
		$EVENT = getBlock( $class->read, 'UP_EVENTS' );
		echo $READ . $EVENT;

	} // New Feature in v1.1 ..
	elseif( ACT == 'massmail' )
	{
		if( SUBM == NULL )	
		{
			// Parse template file
			_parse( $tpl_dir . 'add.tpl' );
			$Read = template( $class->read, NULL, $T_ST );
			$Down = template( $class->read, $T_ST.'massmail', '/#massmail]' );
			echo $Read . $Down;
		}
		else
		{
			if( !$_POST['title'] || !$_POST['message'] )
			{
				echo $error['fields'];
			}
			elseif( !$_POST['admin'] AND !$_POST['staff'] AND !$_POST['member'] )
			{
				echo $error['check_one'];
			}
			else
			{
				
				//
				// If we are mailing all the admins..
				//
				if( $_POST['admin'] == 'checked' )
				{
					$query = $db->query( "SELECT email FROM phpdesk_admin" );
					while( $f = $db->fetch( $query ))
					{
						if( strtolower( $f['username'] ) != strtolower( USER ))
						{
							mail_it( '', $f['email'], $_POST['title'], '', '', '', $_POST['message'] );
						}
					}
				}
				
				//
				// If we are mailing all the staff..
				//
				if( $_POST['staff'] == 'checked' )
				{
					$query = $db->query( "SELECT email FROM phpdesk_staff" );
					while( $f = $db->fetch( $query ))
					{
						mail_it( '', $f['email'], $_POST['title'], '', '', '', $_POST['message'] );
					}
				}
				
				//
				// If we are mailing all the members..
				//
				if( $_POST['member'] == 'checked' )
				{
					$query = $db->query( "SELECT email FROM phpdesk_members" );
					while( $f = $db->fetch( $query ))
					{
						mail_it( '', $f['email'], $_POST['title'], '', '', '', $_POST['message'] );
					}
				}
				
				// print out the success!
				echo $success['massmail_sent'];
							
			}
		}
	}
    elseif( ACT == 'staff' || ACT == 'groups' || ACT == 'members' || ACT == 'admin' || ACT == 'fields' )
    {
		// If not editing/adding/emailing
        if( TYPE == "" )
        {
         	// Parse List File
			_parse($tpl_dir.'list.tpl');
			$read = $class->read;
				
			// PREPARE TEMPLATE VARIABLES TO BE USED
			$STS  = template ( $read, NULL, $T_ST );
			$read = $STS . template ( $read, $T_ST . ACT, '/#'.ACT.']' );
			$list = template ( $read, $T_ST, $T_ED );
			$read = template ( $read, NULL, $T_ST );
				
            $_Q = $db->query ( "SELECT * FROM phpdesk_" . ACT );
				
			// CHECK TO SEE IF ANY RECORD EXISTS
			if( !$db->num ( $_Q ) )
			{
				echo "<b>0 " . ucfirst ( ACT ) . " Found</b>";
			}
			else
			{
				echo $read . " ";
			}
	
			// List all contents from database
			while( $_F = $db->fetch ( $_Q ) )
			{
				$x++;

				// GET BACKGROUND COLOR
				$bg = ( is_float( $x / 2 ) ) ? 'tdbg1' : 'tdbg2';
				
				//
				// VIEW( LIST ) GROUPS
				//
				if( ACT == 'groups' )
				{

					$out = str_replace ( '^g_name^', $_F['name'], str_replace ( '^td_bg^', $bg, $list ) );
						
					// DISABLE EDIT/DELETE FOR EMAIL GROUP
					if($_F['name']=='EMAIL')
					{
						$out = str_replace ( '^edit^', 'N/A', $out );
						$out = str_replace ('^delete^', 'N/A', $out );
					}
					else
					{
						// TEMPLATE TAMPERING
						$out = str_replace ( '^edit^', '<a href="'.SELF.'?action=groups&type=edit&s='. $SID .'&id='.$_F['id'].'">Edit</a>', $out ); // Editing URL
						$out = str_replace ( '^delete^', '<a href="'.SELF.'?action=groups&type=delete&s='. $SID .'&id='.$_F['id'].'">Delete</a>', $out ); // Delete URL						
					}
				}
				//
				// VIEW ( LIST ) MEMBERS
				//
				elseif(ACT == 'members')
				{
						
					// SUSPEND LINK
					$SUSPEND = ( $_F['disabled'] == 1 ) ? ' [ <a href="'.SELF.'?action=members&type=suspend&sub=un&id='.$_F['id'].'&s='. $SID .'">Un-Suspend</a> ]' : ' [ <a href="'.SELF.'?action=members&type=suspend&id='.$_F['id'].'&s='. $SID .'">Suspend</a> ]';
					$LINK = '<a href="'.SELF.'?action=members&type=delete&id='.$_F['id'].'&s='. $SID .'">Delete</a> ]'
							. $SUSPEND;
						
					// TEMPLATE PREPARATION
					$out = str_replace('^s_user^', $_F['username'], str_replace('^s_email^', $_F['email'], str_replace('^s_website^', $_F['website'], $list)));
					$out = str_replace('^s_registered^', exo_date('d-m-y', $_F['registered']), str_replace('^s_name^', $_F['name'], str_replace('^td_bg^', $bg, $out)));
					$out = str_replace('^edit^', '<a href="'.SELF.'?action=members&type=edit&id='.$_F['id'].'&s='. $SID .'">Edit</a>', $out); // Editing URL
					$out = str_replace('^delete^ ]', $LINK , $out); // Delete URL
					$out = str_replace('^email^', '<a href="'.SELF.'?action=members&type=email&id='.$_F['id'].'&s='. $SID .'">Email</a>', $out); // Email URL
					$out = str_replace('^id^', $_F['id'], $out);
						
				}
				//
				// VIEW( LIST ) FIELDS
				//
				elseif(ACT == 'fields')
				{
					$out = str_replace('^f_type^', $_F['type'], str_replace('^td_bg^', $bg, $list));
					$out = str_replace('^edit^', '<a href="'.SELF.'?action=fields&type=edit&name='.$_F['type'].'&s='. $SID .'">Edit</a>', $out); // Editing URL
				}
				// IF NOT ANY OF ABOVE
				else
				{
					//
					// VIEW ADMINS
					//
					if( ACT == 'admin' )
					{
						$out = str_replace( '^s_name^', $_F['name'], str_replace( '^s_email^', $_F['email'],  str_replace( '^td_bg^', $bg, $list ) ) );
						$out = str_replace( '^s_tppage^', $_F['tppage'], $out );
					}
					//
					// VIEW STAFF
					//
					else
					{
						$out = str_replace('^s_name^', $_F['username'], str_replace('^s_email^', $_F['email'], str_replace('^s_website^', $_F['website'], $list)));
						$out = str_replace('^s_closed^', $_F['closed'], str_replace('^s_responses^', $_F['responses'], str_replace('^td_bg^', $bg, $out)));
					}
						
					// Get Staff Rating
					$RATING  =  ( !empty ( $_F['rating'] ) ) ? $_F['rating'] . $tpl['out_five'] : $tpl['none_yet'];
						
					// ADMIN / STAFF LIST
					$out = str_replace('^edit^', '<a href="'.SELF.'?action='.ACT.'&type=edit&id='.$_F['id'].'&s='. $SID .'">Edit</a>', $out); // Editing URL
					$out = str_replace('^delete^', '<a href="'.SELF.'?action='.ACT.'&type=delete&id='.$_F['id'].'&s='. $SID .'">Delete</a>', $out); // Delete URL
					$out = str_replace('^email^', '<a href="'.SELF.'?action='.ACT.'&type=email&id='.$_F['id'].'&s='. $SID .'">Email</a>', $out); // Email URL
					$out = str_replace( '^RATED^', $RATING, $out );
					$out = str_replace('^id^', $_F['id'], $out);
				}
					
				echo $out . "\n ";
			}
			
			// Ending </table> tag not included in the list_staff.tpl, but required
			echo '</table>';
          
		}
		//
		// ADDING STAFF/ADMIN/MEMBER/GROUP
		//
		elseif( TYPE == "add" ) 
		{
			// If the form wasnt submitted
			if( SUBM == "" )
			{	
				if(ACT == 'members') // IF ADDING MEMBER
				{
					_parse( $tpl_dir . 'profile.tpl' );
					$read = getBlock( $class->read, 'MEMBER' );
					$n_pm = '<option value="1">Yes</option><option value="0">No</option>';
					$read = str_replace('^n_pm^',$n_pm, str_replace('^n_response^', $n_pm, $read));
		
					// PARSE ADD TICKET . TPL FILE
					_parse ( $tpl_dir . 'tickets.tpl' );
		
					// PREPARE VARIABLES
					$READ  =  getBlock( $class->read, 'ADD_TICKET' );
					$READ  =  rpl( '^e_text^', NULL, $READ );

					// THE OUTPUT VARIABLES
					$LIST  =  template ( $READ, $T_ST, $T_ED );
					$READ  =  template ( $READ, NULL, $T_ST );
			
					// GET FIELDS USING FUNCTION
					$FIELDS = get_fields( $LIST,'profile' );
		
					echo rpl( '^FIELDS^', $FIELDS, $read );
				}
				else
				{
					// IF ADDING STAFF
					if(ACT == 'staff') 
					{
						$_Q = $db->query ( $sel_group );
						$g_groups = "<input type=\"checkbox\" name=\"g[1]\" value=\"ALL\"> ALL<br/>\n";

						$x = 1;

						while($_F = $db->fetch($_Q))
						{	
							$x++;
							$g_groups .= "<input type=\"checkbox\" name=\"g[{$x}]\" value=\"{$_F['name']}\"> {$_F['name']}<br />\n";
						}
					}
						
					// PARSE THE ADD TPL FILE
					_parse($tpl_dir.'add.tpl');
					$read = $class->read;
					
					// check the permission boxes
					$check_et = ' checked';
					$check_er = ' checked';

					// PREPARE TEMPLATE VARIABLES TO BE USED
					$END  = template ( $read, NULL, $T_ST );
					$read = $END . template ( $read, $T_ST . ACT, '/#'.ACT.']' );
						
					echo $read;
						
				}
			}
			// ADDING FORM SUBMITTED
			else
			{	
				if(ACT == 'staff') // IF INSERTING STAFF RECORD
				{
					$query = $db->query($sel_staff." WHERE username = '{$_POST['username']}'");
					$_Q1 = $db->query($sel_admin." WHERE name='{$_POST['username']}'");
					$_Q2 = $db->query($sel_mem." WHERE username='{$_POST['username']}'");
						
					$VALIDATE = validate('staff', $_POST);
						
					// Verification, check to see if everything is filled up
					if($VALIDATE != "")
					{
						echo $VALIDATE;
					}
					elseif($db->num($query) || $db->num($_Q1) || $db->num($_Q2)) // If the record already exists
					{
						echo 'A staff/user/admin with that username already exists. <br />';
					}
					else
					{	
						$_POST['groups'] = "";
						foreach ( $_POST['g'] as $group )
						{
							if($group == 'ALL')
							{
								$_POST['groups'] = 'ALL';
								break;
							}
							$_POST['groups'] .= $group."|||";
						}
						if(substr($_POST['groups'], -3) == "|||")
						{
							$_POST['groups'] = substr($_POST['groups'], 0, -3);
						}
						// Get an ID
						$Q = $db->query( $sel_staff );
						$Q1 = $db->query( $sel_mem );
						$Q2 = $db->query( $sel_admin );
						$total = $db->num( $Q ) + $db->num( $Q1 ) + $db->num( $Q2 ) + 2;

						// LOOP TO GET AN ID
						while( $x < $total )
						{	
							$x++;
							
							$Q = $db->query($sel_staff." WHERE id='{$x}'");
							$Q1 = $db->query($sel_mem." WHERE id='{$x}'");
							$Q2 = $db->query($sel_admin." WHERE id='{$x}'");
															
							if(!$db->num($Q) && !$db->num($Q1) && !$db->num($Q2))
							{
								$id_got = $x;
								break;
							}
						}
						
						// prepare extra vars..
						$ed_ticket = $_POST['edit_ticket'] == 'yes' ? '1' : '0';
						$ed_response = $_POST['edit_response'] == 'yes' ? '1' : '0';
						
						// Prepare the SQL Query to be executed
						$sql = "INSERT INTO phpdesk_staff (id,username,name,password,email,website,`groups`,edit_ticket,edit_response)
								VALUES('{$id_got}','{$_POST['username']}','{$_POST['name']}', md5('{$_POST['password']}'),
								'{$_POST['email']}', '{$_POST['website']}', '{$_POST['groups']}', '$ed_ticket', '$ed_response')";

						// Check to see if the records were added succesfully
						if($db->query($sql))
						{
							echo 'Staff Member Added Successfully.<br />';
						}

						// MAIL ABOUT STAFF ACCOUNT CREATION
						echo mail_it('staff', $_POST['email'], $general['mail_title']);
						
					}
				}
				elseif(ACT == 'admin') // IF INSERTING AN ADMIN RECORD
				{
					// SQL VALIDATION
					$query = $db->query($sel_admin." WHERE name = '{$_POST['username']}'");
					$_Q1 = $db->query($sel_staff." WHERE name='{$_POST['username']}'");
					$_Q2 = $db->query($sel_mem." WHERE username='{$_POST['username']}'");
					
					$VALIDATE = validate('admin',$_POST);
					
					// Verification, check to see if everything is filled up
					if($VALIDATE != "")
					{
						echo $VALIDATE;
					}
					elseif($db->num($query) || $db->num($_Q1) || $db->num($_Q2)) // If the record already exists
					{
						echo 'A staff/member/admin with that username already exists. <br />';
					}
					else
					{
						// Get an ID
						$Q = $db->query( $sel_staff );
						$Q1 = $db->query( $sel_mem );
						$Q2 = $db->query( $sel_admin );
						$total = $db->num( $Q ) + $db->num( $Q1 ) + $db->num( $Q2 ) + 2;

						while($x < $total)
						{	
							$x++;
							$Q = $db->query($sel_staff." WHERE id='{$x}'");
							$Q1 = $db->query($sel_mem." WHERE id='{$x}'");
							$Q2 = $db->query($sel_admin." WHERE id='{$x}'");
															
							if(!$db->num($Q) && !$db->num($Q1) && !$db->num($Q2))
							{
								$id_got = $x;
								break;
							}
						}
							
						// Prepare the SQL Query to be executed
						$sql = "INSERT INTO phpdesk_admin (id,name,pass,email,tppage)
								VALUES('{$id_got}','{$_POST['username']}', md5('{$_POST['password']}'),
								'{$_POST['email']}', '{$_POST['tppage']}')";

						// Check to see if the records were added succesfully
						if($db->query($sql))
						{
							echo 'Administrator Added Successfully.<br />';
						}

						// MAIL ADMIN ABOUT ACCOUNT CREATION
						echo mail_it('admin', $_POST['email'], $general['mail_title']);
					
					}
				}
				elseif(ACT == 'members') // IF INSERTING MEMBER RECORD
				{
					// DO THE VALIDATION
					$VALIDATE = validate('user',$_POST);
					
					if($VALIDATE!="")
					{
						echo $VALIDATE;						
					}
					else
					{
						// Get an ID
						$Q = $db->query($sel_staff);
						$Q1 = $db->query($sel_mem);
						$Q2 = $db->query($sel_admin);
						$total = $db->num($Q)+$db->num($Q1)+$db->num($Q2)+2;
						while($x < $total)
						{	
						
							$x++;
							
							$Q = $db->query($sel_staff." WHERE id='{$x}'");
							$Q1 = $db->query($sel_mem." WHERE id='{$x}'");
							$Q2 = $db->query($sel_admin." WHERE id='{$x}'");
																
							if(!$db->num($Q) && !$db->num($Q1) && !$db->num($Q2))
							{
								$id_got = $x;
								break;
							}
						}		
			
						// Get Fields and Values for mySQL
						$FIELDS  =  get_fields ( '', 'profile', 'SQL' );
						$VALUES  =  val_fields ( $_POST, 'profile' );
						
						// PREPARE SQL QUERY
						$sql = "INSERT INTO phpdesk_members (id,username,name,password,email,website,notify_pm,notify_response,registered,tppage,`FIELDS`,`VALUES`)
							VALUES('".$id_got."','".trim($_POST['username'])."', '".$_POST['name']."', '".md5($_POST['password'])."', '".$_POST['email']."', '".$_POST['website']."',
							'".$_POST['n_pm']."', '".$_POST['n_response']."', '".time()."', '".$_POST['tppage']."', '$FIELDS', '$VALUES')";

						// EXECUTE SQL QUERY
						if($db->query($sql))
						{
							echo $success['register'];
						}
							
						// MAIL ABOUNT ACCOUNT CREATION TO MEMBER
						echo mail_it('register', $_POST['email'], $general['mail_title']);
					}
						
				}
				elseif(ACT == 'groups') // IF INSERTING A GROUP RECORD
				{
					// SQL VALIDATION
					$query = $db->query($sel_group." WHERE name = '{$_POST['group_name']}'");
						
					// FIELDS VALIDATION
					$VALIDATION = validate('groups',$_POST);

					if($VALIDATION != "")
					{
						echo $VALIDATION;
					}
					elseif($db->num($query)) // If the record already exists
					{
						echo 'A group with that name already exists.<br />';
					}
					else
					{
						// EXECUTE SQL QUERY
						if($db->query("INSERT INTO phpdesk_groups SET name='{$_POST['group_name']}'"))
						{
							echo 'Group Added Successfully.';
						}
					}
				}
			} // End if Submit
		
		}
		elseif( TYPE == 'edit' ) // IF EDITING STAFF/ADMIN/MEMBER/GROUP
		{
			// IF NO ID ASSOCIATED AND ACTION ISNT TO EDIT PROFILE
			// AND ACTION NOT EQUALS FIELDS
			if(!isset($_GET['id']) && $_GET['do']!='profile' && ACT != 'fields')
			{
				echo 'ERROR : An ID is required for editing. <br />';
			}
			else
			{
				// IF FORM NOT SUBMITTED FOR EDITING
				if(SUBM == "")		
				{
					if(ACT == 'groups') // EDITING A GROUP RECORD
					{
						// PREPARE SQL STUFF
						$_Q = $db->query($sel_group." WHERE id = '{$_GET['id']}'");
						$_F = $db->fetch($_Q);
						$g_name = $_F['name'];
						$read_only = " readonly";
							
						// PARSE TEMPLATE FILE
						_parse($tpl_dir.'add.tpl');
						$read = $class->read;
						$tmp = substr($read,0, strpos($read,'[#'));
						$read = $tmp.substr($read, strpos($read, '[#'.ACT) + strlen(ACT) + 2);
						$read = substr($read, 0, strpos($read, '/#'.ACT.']'));
						echo $read;
					}
					elseif(ACT == 'fields') // EDITING FIELDS
					{
						// PREPARE SQL STUFF
						$_Q = $db->query("SELECT * FROM phpdesk_fields WHERE type = '$_GET[name]'");
						$_F = $db->fetch($_Q);

						$type = strtolower( $_GET['name'] );
						$_GET['id'] = $type;
						
						// PARSE TEMPLATE FILE
						_parse($tpl_dir.'add.tpl');
						$read = $class->read;
						$tmp = substr($read,0, strpos($read,'[#'));
						$read = $tmp.substr($read, strpos($read, '[#'.ACT) + strlen(ACT) + 2);
						$read = substr($read, 0, strpos($read, '/#'.ACT.']'));
						$list = substr($read, strpos($read, '[#')+2);
						$list = substr($list, 0, strpos($list, '/#]'));
						$read = substr($read, 0, strpos($read, '[#'));
						
						// GET ALL THE FIELDS USING THE GET_FIELDS FUNCTION
						$FIELDS = get_fields($list, $type,'fill',$_F['fields'],'',$_F['mandatory']);

						// REPLACE ^fields^ in TEMPLATE WITH FIELDS		
						$read = str_replace('^fields^', $FIELDS, $read);
						echo $read;							
					}
					elseif(ACT == 'admin' || $_GET['do']=='profile') // IF EDITING PROFILE OR ADMIN
					{
						if($_GET['do']=='profile') // IF EDITING PROFILE
						{
							// PREPARE SQL
							$_Q = $db->query($sel_admin." WHERE name='".USER."'");
						}
						else // IF EDITING AN ADMIN
						{
							// PREPARE SQL
							$_Q = $db->query($sel_admin." WHERE id='".$_GET['id']."'");
						}

						// FETCH
						$_F = $db->fetch($_Q);
				
						// Get the right content selected
						$a = array('<option value="0"^s^>No</option>','<option value="1"^s^>Yes</option>');
						$x = 0;
						
						foreach( $a as $opt )
						{
							$s = $z = $y = "";
								
							if( $_F['notify_pm'] == $x )
							{
								$s = " selected";
							}

							if( $_F['notify_response'] == $x )
							{
								$z = " selected";
							}

							if( $_F['notify_ticket'] == $x )
							{
								$y = " selected";
							}						

							$n_pm.= str_replace('^s^', $s, $opt);
							$n_response.= str_replace('^s^', $z, $opt);
							$n_ticket.= str_replace('^s^', $y, $opt);
							$x++;
						}

						// Required Variables
						$edit_view = "Editing Administrator";
						$m_tppage = $_F['tppage'];
						$m_user = $_F['name'];
						$m_email = $_F['email'];
						$m_edit_print = $general['p_edit_pass'];
						$read_only = " readonly";
							
						$signature = ( $_GET['do'] == 'profile' ) ? $_F['signature'] : "";
						// PARSE TEMPLATE FILE
						_parse( $tpl_dir . 'profile.tpl' );
						$read = getBlock( $class->read, 'ADMIN' );
						$read = str_replace('^n_pm^', $n_pm, str_replace('^n_response^', $n_response, $read));
						$read = str_replace('^n_ticket^', $n_ticket, $read);

						// PRINT OUT
						echo $read;
					}
					elseif(ACT == 'members') // IF EDITING A MEMBER
					{
					
						// PREPARE SQL
						$_Q = $db->query($sel_mem." WHERE id='".$_GET['id']."'");
							
						if(!$db->num($_Q))
						{
							echo $error['no_auth_or_record'];
						}
						else
						{
							$_F = $db->fetch($_Q);
				
							// Get the right content selected
							$a = array('<option value="0"^s^>No</option>','<option value="1"^s^>Yes</option>');
							$x = 0;
					
							foreach($a as $opt)
							{
								$s = $z = "";
								
								if( $_F['notify_pm'] == $x )
								{
									$s = " selected";
								}
									
								if( $_F['notify_response'] == $x )
								{
									$z = " selected";
								}
									
								$n_pm.= str_replace('^s^', $s, $opt);
								$n_response.= str_replace('^s^', $z, $opt);
								$x++;
							}
		
							// Required Variables
							$m_tppage = $_F['tppage'];
							$m_user = $_F['username'];
							$m_name = $_F['name'];
							$m_email = $_F['email'];
							$m_website = $_F['website'];
							$m_edit_print = $general['p_edit_pass'];
							$read_only = " readonly";
						
							// PARSE ADD TICKET . TPL FILE
							_parse ( $tpl_dir . 'tickets.tpl' );
		
							// PREPARE VARIABLES
							$READ  =  getBlock( $class->read, 'ADD_TICKET' );
							$READ  =  rpl( '^e_text^', NULL, $READ );

							// THE OUTPUT VARIABLES
							$LIST  =  template ( $READ, $T_ST, $T_ED );
							$READ  =  template ( $READ, NULL, $T_ST );
							
							// GET FIELDS USING FUNCTION
							$FIELDS = get_fields ( $LIST, 'profile', 'edit', $_F['VALUES'], $_F['FIELDS'] );
					
							_parse( $tpl_dir . 'profile.tpl' );
							$read = getBlock( $class->read, 'MEMBER' );
							$read = rpl('^n_pm^', $n_pm, rpl('^n_response^', $n_response, $read));
							
							// Print Out!
							echo rpl( '^FIELDS^', $FIELDS, $read );
						}						
					}
					elseif(ACT == 'staff') // IF EDITING STAFF
					{
						// PREPARE SQL
						$_Q = $db->query($sel_staff." WHERE id = '{$_GET['id']}'");
						$_F = $db->fetch($_Q);
						
						// PREPARE REQUIRED VARS
						$s_user = $_F['username'];
						$s_name = $_F['name'];
						$s_email = $_F['email'];
						$s_website = $_F['website'];
						$s_groups = $_F['groups'];
						$s_edit_print = ' Leave blank for the old one';
						
						// GROUPS
						if($s_groups == 'ALL')
						{	
							$checks = " checked";
						}
						else
						{
							$checks = "";
						}
							
						$g_groups = "<input type=\"checkbox\" name=\"g[1]\" value=\"ALL\"{$checks}> ALL<br />\n";
						$split = explode("|||", $s_groups);
						
						$Q = $db->query($sel_group."");
						$x = 1;
						while($F = $db->fetch($Q))
						{
							$x++;
							foreach( $split as $group )
							{
								if($group == $F['name'])
								{
									$checked = ' checked';
									break;
								}
								else
								{
									$checked = '';
								}
							}
							$g_groups .= "<input type=\"checkbox\" name=\"g[{$x}]\" value=\"{$F['name']}\"{$checked}> {$F['name']}<br />\n";
						}
						$read_only = " readonly";
						
						// check the edit-ticket box according to the value
						
						$check_et = $_F['edit_ticket'] == '1' ? 'checked' : null;
						$check_er = $_F['edit_response'] == '1' ? 'checked' : null;

						// PARSE TEMPLATE
						_parse($tpl_dir.'add.tpl');
						$read = $class->read;
						$tmp = substr($read,0, strpos($read,'[#'));
						$read = $tmp.substr($read, strpos($read, '[#'.ACT) + strlen(ACT) + 2);
						$read = substr($read, 0, strpos($read, '/#'.ACT.']'));
						
						// PRINT OUT
						echo $read;									
						
					}

				}
				else // IF UPDATING A RECORD
				{
					if(ACT == 'staff') // IF UPDATING STAFF RECORD
					{
						// PREPARE SQL
						$query = $db->query($sel_staff." WHERE id = '{$_GET['id']}'");
						
						$VALIDATE = validate('staff',$_POST, 'edit');
						// Verification, check to see if everything is filled up
						if($VALIDATE!="")
						{
							echo $VALIDATE;
						}
						elseif(!$db->num($query)) // If the record already exists
						{
							echo 'No such staff exists. <br />';
						}
						else
						{	
							$_POST['groups'] = "";
							foreach ( $_POST['g'] as $group )
							{
								if($group == 'ALL')
								{
									$_POST['groups'] = 'ALL';
									break;
								}
								$_POST['groups'] .= $group."|||";
							}
							
							if(substr($_POST['groups'], -3) == "|||")
							{
								$_POST['groups'] = substr($_POST['groups'], 0, -3);
							}
						
							$pass = (empty($_POST['password'])) ? "" : "password = '".md5($_POST['password'])."',";
							$ed_ticket = $_POST['edit_ticket'] == 'yes' ? '1' : '0';
							$ed_response = $_POST['edit_response'] == 'yes' ? '1' : '0';
							
							// Prepare the SQL Query to be executed
							$sql = $up_staff." name = '{$_POST['name']}',{$pass}email = '{$_POST['email']}',
									   website = '{$_POST['website']}', groups = '{$_POST['groups']}', edit_ticket = '$ed_ticket',
									   edit_response = '$ed_response' 
									WHERE id = '{$_GET['id']}'";

							// Check to see if the records were added succesfully
							if($db->query($sql))
							{
								echo $success['edit_staff'];
							}
						
						}
					}
					elseif(ACT == 'admin') // IF UPDATING AN ADMIN RECORD
					{
						// DO FORM VALIDATION
						$VALIDATE = validate('admin',$_POST,'edit');
							
						if($VALIDATE != "")
						{
							echo $VALIDATE;
						}
						else
						{
							if($_POST['password'] != '')
							{ 
								$claus = "pass='".md5($_POST['password'])."',";
							}
							if($_GET['do']=='profile')
							{
								$_GET['id'] = $a_id;
							}
							
							$UPD = ( !empty ( $_POST['signature' ] ) ) ? ",`signature` = '".$_POST['signature']."' " : " ";

							// PREPARSE SQL QUERY TO BE EXECUTED
							$sql = $up_admin." ".$claus."email='".$_POST['email']."',
									notify_pm='".$_POST['n_pm']."',notify_response='".$_POST['n_response']."',
									notify_ticket='".$_POST['n_ticket']."', tppage = '".$_POST['tppage']."'". $UPD ."WHERE id='".$_GET['id']."'";
								
							// EXECUTE SQL QUERY
							if($db->query($sql))
							{
								echo $success['p_update'];
							}
						}
					}
					elseif(ACT == 'members') // IF UPDATING MEMBERS RECORD
					{
						// VALIDATION, MOSTLY FORM BASED
						if(!$_POST['username'] || !$_POST['email'] || !$_POST['name'])
						{
							echo $error['fields'];
						}
						elseif($_POST['password'] != $_POST['confirm'])
						{
							echo $error['pass_match'];
						}
						else
						{
							if($_POST['password'] != '')
							{ 
								$claus = ",password='".md5($_POST['password'])."'";
							}
							
							// Get Fields and Values for mySQL
							$FIELDS  =  get_fields ( '', 'profile', 'SQL' );
							$VALUES  =  val_fields ( $_POST, 'profile' );
							
							// PREPARE SQL QUERY
							$sql = $up_mem." name='". $_POST['name'] ."'". $claus .",
								email='". $_POST['email'] ."',
								website='". $_POST['website'] ."',
								notify_pm='". $_POST['n_pm'] ."',
								notify_response='". $_POST['n_response'] ."',
								tppage='". $_POST['tppage'] ."',
								`VALUES` = '$VALUES',
								`FIELDS` = '$FIELDS' 
								WHERE id='". $_GET['id'] ."'";
								
							// EXECUTE SQL QUERY
							if($db->query($sql))
							{
								echo $success['p_update'];
							}

						}						
					}
					elseif(ACT == 'groups') // UPDATING A GROUP RECORD
					{
						// SQL QUERY
						$query = $db->query($sel_group);

						// CHECK IF IT EXISTS
						while($f = $db->fetch($query))
						{
							if($_POST['group_name'] == $f['name'] && $_GET['id'] != $f['id'])
							{
								$exists = 'YES';
								break;								
							}
						}
						if(!$_POST['group_name']) 
						{
							echo 'A group name is required to create.<br />'; 
						}
						elseif(!empty($exists)) // If the record already exists
						{
							echo 'A group with that name already exists.<br />';
						}
						else
						{
							// UPDATE GROUP RECORD, QUERY EXECUTED HERE
							if($db->query("UPDATE phpdesk_groups SET name='{$_POST['group_name']}' WHERE id='{$_GET['id']}'"))
							{
								echo 'Group Edited Successfully.';
							}
						}
					}
					elseif(ACT == 'fields') // IF EDITING A FIELD
					{
						$x = 0;
						
						// VALIDATION AND PREPARATION OF FIELDS
						foreach ( $_POST['field'] as $FIELD )
						{
							if( !empty($FIELD) )
							{
								//
								// Bug Fix!
								// Covnert spaces to ^SPC^ which is later shown as a space
								//
								$FIELD = rpl( '^SPC^', NULL, $FIELD );
								$FIELD = preg_replace( '/\s/', '^SPC^', $FIELD );
								
								// Attach separator!
								$FIELDS .= $FIELD."|||";
								$_POST['mand_field'][$x] = ($_POST['mand_field'][$x] == 0) ? '0' : '1';
								$MAND .= $_POST['mand_field'][$x]."|||";
							}
							$x++;
						}
						
						$type = strtolower( $_GET['id'] );

						// PREPARE SQL QUERY
						$SQL = "UPDATE `phpdesk_fields` SET
									`field` = '". $FIELDS ."',
									`mandatory` = '". $MAND ."'
									WHERE type='$type'";
									
						// EXECUTE SQL QUERY
						if($db->query($SQL))
						{
							echo "Successfully edited field.<br />";
						}
														
					}
					
				}
			} // End if ID exists
			
		}
		elseif( TYPE == 'suspend' )
		{
			if ( empty ( $_GET['id'] ) )
			{
				echo $error['id_missing'];
			}
			else
			{
			
				$SQL = "UPDATE phpdesk_members SET disabled = '1' WHERE id = '" . $_GET['id'] . "'";
					
				if ( $_GET['sub'] == 'un' )
				{
					$SQL = "UPDATE phpdesk_members SET disabled = '0' WHERE id = '" . $_GET['id'] . "'";
				}
					
				if ( $db->query ( $SQL ) )
				{
					$SUSPEND = ( $_GET['sub'] == 'un' ) ? $success['unsuspend'] : $success['suspend'];
					echo $SUSPEND;
				}
			}
		}
		elseif(TYPE == 'delete') // IF DELETING A RECORD
		{
			// SQL QUERY
			$_Q = $db->query("SELECT * FROM phpdesk_".ACT." WHERE id = '{$_GET['id']}'");
				
			// IF NO ID ASSOCIATED WITH URL
			if($_GET['id'] == NULL)
			{
				echo 'An ID was missing.<br />';
			}
			elseif(!$db->num($_Q)) // IF NO DB RECORD
			{
				echo 'No such record, possibly deleted.<br />';
			}
			else
			{
				if($_GET['confirm'] != 'YES') // IF DELETION NOT YET CONFIRMED
				{
					echo 'Do you really want to delete the record : <b>'.$_GET['id'].'</b><br />';
					echo '[ <a href="'.SELF.'?action='.ACT.'&confirm=YES&type=delete&id='.$_GET['id'].'&s='. $SID .'">Yes</a> ] ';
					echo '[ <a href="'.SELF.'?action='.ACT.'&s='. $SID .'">No</a> ] <br />';
				}
				elseif($_GET['confirm'] == 'YES') // IF CONFIRMED
				{
					// DELETE QUERY
					$sql = "DELETE FROM phpdesk_".ACT." WHERE id=".$_GET['id'];
					
					// EXECUTE QUERY
					if($db->query($sql))
					{
						echo 'Record <b>'.$_GET['id'].'</b> Deleted Successfully.<br />';
					}
				}
			}
			// Below ends delete stuff
		}
		elseif(TYPE == 'email' || TYPE == 'send') // SENDING AN EMAIL
		{
			if(!isset($_GET['id'])) // IF ID NOT AVAILABLE IN URL
			{
				echo $error['id_missing'];
			}
			else
			{
				if(ACT != '')
				{
					// SQL QUERY
					$_Q = $db->query("SELECT * FROM phpdesk_".ACT." WHERE id = '".$_GET['id']."'");
				}

				// FETCH
				$_F = $db->fetch($_Q);
				
				// IF FORM NOT SUBMITTED
				if(SUBM == "")
				{
					if(ACT == 'admin')
					{
						$SEND_OPT = "<option value='{$_F['name']}'>{$_F['name']}</option>\n";
					}
					else
					{
						$SEND_OPT = "<option value='{$_F['username']}'>{$_F['username']}</option>\n";
					}

					// PARSE TEMPLATE
					_parse($tpl_dir.'pm.tpl');
					echo getBlock( $class->read, 'SEND' );
						
				}
				else
				{
					// FORM BASED VALIDATION
					if(!$_POST['sendto'] || !$_POST['title'] || !$_POST['message'])
					{
						echo $error['fields'];
					}
					else
					{
						// DO THE REQUIRED E-MAILING
						mail_it('',$_F['email'],$_POST['title'],'','','',$_POST['message']);
						echo 'Mail sent successfully. <br />';
					}
				}
			}
		}			
                            
	}
	elseif(ACT == 'pm')
	{
		_parse($tpl_dir.'pm.tpl');
		$read = getBlock( $class->read, 'LIST' );
		$list = substr($read, strpos($read, '[#')+2);
		$list = substr($list, 0, strpos($list, '/#]'));
		$end = substr($read, strpos($read, '/#]') + 3);
		$read = substr($read, 0, strpos($read, '[#'));
		
		$_Q = $db->query($sel_pm." WHERE sentfor = '".USER."'");
		$Q1 = $db->query($sel_pm." WHERE `sentfor` = '".USER."' AND `read`<>'1'");
		if(!$db->num($_Q))
		{
			echo $general['no_pms'];
		}
			
		$pms = $db->num($_Q)." ( Unread: <i>".$db->num($Q1)."</i> )";
		echo str_replace('^pms^', $pms, $read);
			
		if(TYPE == "")
		{
			echo pm(USER);
			echo $end;
		}
		elseif(TYPE == 'view')
		{
			if(!isset($_GET['id']))
			{
				echo $error['id_missing'];
			}
			else
			{
				echo pm(USER,'view',$_GET['id']);
			}
			
			echo $end;				
			
		}
		elseif(TYPE == 'send')
		{
			echo $end;
				
			$id = $_GET['id'];
				
			if(!empty($id)) 
			{
				$_Q = $db->query($sel_pm." WHERE id='{$id}' AND sentfor = '".USER."'");
			}
				
			if(!empty($id) && !$db->num($_Q))
			{
				echo $error['no_auth_or_record'];
			}
			else
			{
				if(SUBM == "")
				{
					if(!empty($id))
					{
						$_F = $db->fetch($_Q);
						$pm_sendto = $_F['sentby'];
						$SEND_OPT .= "<option value='{$_F['sentby']}'>{$_F['sentby']}</option>\n";	
						$pm_title = "Re: ".$_F['title'];
						$pm_message = "\n\n\n"."------------------------- \n".$_F['message'];
					}
					
					//
					// Prepare A List Of Admins, Staff & Members
					//
					$USERS = $db->query( $sel_staff );
					while( $UF = $db->fetch( $USERS ))
					{
						$NAME = ( $UF['username'] == NULL ) ? $UF['name'] : $UF['username'];
						$SEND_OPT .= "<option value='$NAME'>$NAME (STAFF)</option>\n";
					}

					$USERS = $db->query( $sel_admin );
					while( $UF = $db->fetch( $USERS ))
					{
						$NAME = ( $UF['username'] == NULL ) ? $UF['name'] : $UF['username'];
						$SEND_OPT .= "<option value='$NAME'>$NAME (ADMIN)</option>\n";
					}
					
					$USERS = $db->query( $sel_mem );
					while( $UF = $db->fetch( $USERS ))
					{
						$NAME = ( $UF['username'] == NULL ) ? $UF['name'] : $UF['username'];
						$SEND_OPT .= "<option value='$NAME'>$NAME</option>\n";
					}
									
					_parse($tpl_dir.'pm.tpl');
					echo getBlock( $class->read, 'SEND' );
				}
				else
				{
					echo pm(USER,'send',$_GET['id'],$_POST['sendto'],$_POST['message'],$_POST['title']);
				}
			}
		}
		elseif(TYPE == 'delete')
		{
			echo $end;
			echo pm(USER,'delete',$_GET['id']);
		}
			
	}
	elseif(ACT == 'configuration')
	{
		if(SUBM == "")
		{
			// GET DIR LISTING FROM TEMPLATE DIRECTORY				
			$DIR_LIST = NULL;
				
			$DIR = opendir('tpl');
			while ( $CONT = readdir($DIR) )
			{
				if ( $CONT != '.' && $CONT != '..' )
				{
					if ( $tpl_dir == 'tpl/'. $CONT . '/' )
					{
						$SEL = "selected";
					}
					else
					{
						$SEL = NULL;
					}
					
					$DIR_LIST .= "<option value='tpl/". $CONT ."/' ". $SEL .">". $CONT ."</option>\n";
				}
			}
				
			// MEMBER STATUS PERMISSION OPTIONS
			$SEL1  =  ( $MEM_SERV == 1 ) ? " selected" : "";
			$SEL2  =  ( $MEM_SERV == 0 ) ? " selected" : "";				
			$MEM_SERVs = '<option value="1"'.$SEL1.'>Yes</option>' . "\n" . '<option value="0"'.$SEL2.'>No</option>';
				
			// ALLOW ATTACHMENTS OPTION
			$AT_ALLOW = "<option value='{$Attach_Allow}'>". (( $Attach_Allow == 1 ) ? 'Yes' : 'No') .'</option>';
			$ST_ANNOUNCE = "<option value='{$StaffAnnounce}'>". (( $StaffAnnounce == 1 ) ? 'Yes' : 'No') .'</option>';
			
			// Desk Offline Stuff..
			$offreason = rpl( "<br />", "\n", $off_reason );
			$off_check = $desk_offline == 1 ? ' checked' : null;
			
			// PARSE CONFIGS TEMPLATE
			_parse($tpl_dir.'configs.tpl');
			$mail_t = ($mailtype == 'None') ? "Disabled" : $mailtype;
			$READ = $class->read;

			$READ = str_replace( '^option^', "<option value=\"".$mailtype."\">".$mail_t."</option>", $READ );
			$READ = str_replace( '^tpldir_list^', $DIR_LIST, str_replace ( '^mem_serv^', $MEM_SERVs, $READ ) );
			echo $READ;
		}
		else
		{

			if( !$_POST['tpldir'] || !$_POST['langfile'] || !$_POST['helpurl'] || !$_POST['sitename'] || !$_POST['remail'] || 
				!$_POST['chatdir'] || !$_POST['registrations'] || !$_POST['at_dir'] || !$_POST['at_ext'] || !$_POST['at_prefix'] )
			{
				echo $error['fields'];
			}
			elseif(($_POST['mailtype']!='None') && (!$_POST['mailuser']))
			{
				echo $error['fields'];				
			}
			elseif( $_POST['deskoffline'] == 'yes' && !$_POST['offreason'] )
			{
				echo $error['fields'];
			}
			else
			{
				$PASS = (!empty($_POST['mailpass'])) ? ", mailpass='".base64_encode($_POST['mailpass'])."'" : "";
				$END  = ($_POST['mailtype']!='None') ? ", mailtype='".$_POST['mailtype']."', mailhost='".$_POST['mailhost']."',
				mailuser='".$_POST['mailuser']."'".$PASS : ", mailtype='None'";
					
				// Attachment Stuff..
				$AT_ALLOW = ( $_POST['at_allow'] == 0 ) ? '0' : '1';
				$ST_ANNOUNCE = ( $_POST['st_announce'] == 0 ) ? '0' : '1';
				$AT_SIZE  = ( $_POST['at_size'] < 1024 ) ? 5024 : $_POST['at_size'];
				
				// Desk offline stuff..
				$deskoff = $_POST['deskoffline'] == 'yes' ? '1' : '0';
				$offreason = rpl( "\n", '<br />', $_POST['offreason'] );
					
				$sql = "UPDATE phpdesk_configs SET tpldir='".$_POST['tpldir']."', langfile='".$_POST['langfile']."',
						helpurl='".$_POST['helpurl']."', sitename='".$_POST['sitename']."', remail='".$_POST['remail']."',
						chatdir='".$_POST['chatdir']."', registrations='".$_POST['registrations']."', mem_serv = '" . 
						$_POST['mem_stats'] ."', st_announce = '$ST_ANNOUNCE', at_allow = '$AT_ALLOW', 
						at_dir = '{$_POST['at_dir']}', at_size = '$AT_SIZE', at_ext = '{$_POST['at_ext']}', 
						at_prefix = '{$_POST['at_prefix']}', desk_offline = '$deskoff', off_reason = '$offreason' "
					 .  $END;
					
				if($db->query($sql))
				{
					echo $success['update_configs'];
				}
			}
		}
	}
	elseif(ACT == 'kb')
	{
		if(TYPE == '')
		{
			echo $KB->kb_view();
		}
		elseif(TYPE == 'add_group')
		{
			if ( !empty ( $_GET['id'] ) )
			{
				$KB->ID = $_GET['id'];
			}
				
			if ( SUBM == '' )
			{
				echo $KB->kb_group_form();
			}
			else
			{
				echo $KB->kb_group_add();
			}
		}
		elseif(TYPE == 'list_groups')
		{
			echo $KB->kb_group_list();
		}
		elseif(TYPE == 'del_group')
		{
			if($_GET['confirm'] != 'YES')
			{
				echo 'Do you really want to delete the record : <b>'.$_GET['id'].'</b><br />';
				echo '[ <a href="'.SELF.'?action='.ACT.'&confirm=YES&type=del_group&id='.$_GET['id'].'&s='. $SID .'">Yes</a> ] ';
				echo '[ <a href="javascript:history.back(0)">No</a> ] <br />';
			}
			elseif($_GET['confirm'] == 'YES')
			{				
				$KB->ID = $_GET['id'];
				echo $KB->kb_group_delete();
			}
		}			
		elseif(TYPE == 'add')
		{
			if(SUBM == '')
			{
				echo $KB->kb_add_form();
			}
			else
			{
				echo $KB->kb_add();
			}
		}
		elseif(TYPE == 'edit')
		{
			if(SUBM == '')
			{
				if(isset($_GET['id']))
				{
					$Q = @$db->query("SELECT * FROM phpdesk_kb WHERE id = '".$_GET['id']."'");
					$F = @$db->fetch($Q);
				
					$KB->MESSAGE = $F['message'];
					$KB->TITLE = $F['title'];
					$KB->ID = $_GET['id'];
					$KB->GROUP = $F['group'];
				}
				
				echo $KB->kb_add_form();
			}
			else
			{
				echo $KB->kb_edit();
			}
		}
		elseif(TYPE == 'view')
		{
			$KB->ID = $_GET['id'];
			echo $KB->kb_view_in();
		}
		elseif(TYPE == 'delete')
		{
			if($_GET['confirm'] != 'YES')
			{
				echo 'Do you really want to delete the record : <b>'.$_GET['id'].'</b><br />';
				echo '[ <a href="'.SELF.'?action='.ACT.'&confirm=YES&type=delete&id='.$_GET['id'].'&s='. $SID .'">Yes</a> ] ';
				echo '[ <a href="'.SELF.'?action='.ACT.'&s='. $SID .'">No</a> ] <br />';
			}
			elseif($_GET['confirm'] == 'YES')
			{				
				$KB->ID = $_GET['id'];
				echo $KB->kb_delete();
			}
		}
	}
	elseif(ACT == 'livechat')	
	{
		if(TYPE == 'get_messages')
		{
			echo '<link rel="stylesheet" href="'. $tpl_dir .'style.css" type="text/css">';
			if(file_exists($chat_dir.$_GET['cid'].'.txt'))
			{
				$fp = fopen($chat_dir.$_GET['cid'].'.txt', 'r');
				$read = fread($fp, filesize($chat_dir.$_GET['cid'].'.txt'));
				$read = explode("\n", $read);
				$over_all = "";

				foreach ( $read as $reads )
				{
					if($reads != "")
					{
						if(strstr($reads,USER))
						{
							$over_all .= "<font class='chatown'>".$reads."<br />\n";
						}
							else
							{
								$over_all .= "<font class='chatelse'>".$reads."<br />\n";							
							}
						}
					}
					fclose($fp);
					echo $over_all;
				}				
			}
			elseif(TYPE == 'ended')
			{
				echo 'The chat was ended either due to a user getting offline or by you.';
				$db->query("DELETE FROM phpdesk_liveonline WHERE timeout<".time());					
			}
			elseif(TYPE == 'down')
			{
				echo "<body onunload=\"window.open('".SELF."?action=livechat&type=logout&cid=".$_GET['cid']."&user=".USER."&s={$SID}','exitstaff','toolbar=no,status=no,scrollbars=no,location=no,menubar=no,directories=no,width=1,height=1');\">";
			}
			elseif(TYPE == 'head')
			{
				echo "<body topmargin=\"0\" leftmargin=\"0\">";
				_parse($tpl_dir.'chat_head_frame.tpl');
				echo $class->read;
			}
			elseif(TYPE == 'logout')
			{
				$db->query($up_lchat." status='Ended' WHERE chatid='".$_GET['cid']."'");
				echo '<body onload=\'window.close()\'>';
			}
			elseif(TYPE == 'update')
			{
			?>
				<script language="JavaScript">
				<!--

				function doLoad()
				{
				    setTimeout( "refresh()", 1000 );
				}

				function refresh()
				{
				    parent.window.tops.location.href = "<? echo SELF."?action=livechat&type=update&windowed=1&chat=1&cid=".$_GET['cid'].'&s='. $SID; ?>";
				}
				function update(message)
				{

					parent.window.display.document.body.innerHTML = parent.window.display.document.body.innerHTML + message + "";
					parent.window.display.scrollTo(0,parent.display.document.body.scrollHeight);
					parent.window.focus();
					parent.window.type.send_chat.send.focus();
				}
				//-->
				</script>			
			<?
				echo "<body onload=\"doLoad()\">";
				$db->query($up_lchat." timeout = '".(time()+30)."' WHERE chatid='".$_GET['cid']."'");
				$db->query($up_lonline." timeout = '".(time()+30)."' WHERE user='".USER."'");
				$db->query($up_lchat." status='Ended' WHERE timeout<'".time()."'");
				$db->query("DELETE FROM phpdesk_liveonline WHERE timeout<".time());				
				
				$_Q = $db->query($sel_lchat." WHERE chatid='".$_GET['cid']."'");
				$_F = $db->fetch($_Q);
				
				$Q = $db->query($sel_lonline." WHERE user='".$_F['starter']."'");

				if(!$db->num($Q) || $_F['status']=='Ended')
				{
					echo "<script>parent.location.href = '".SELF."?action=livechat&type=ended&user=".$_F['starter']."&s={$SID}';</script>";
				}				
				
				$fp = fopen($chat_dir.'last_staff_'.$_GET['cid'].'.txt', 'r');
				$last = fread($fp, filesize($chat_dir.'last_staff_'.$_GET['cid'].'.txt'));
				fclose($fp);
				
				$fp = fopen($chat_dir.$_GET['cid'].'.txt', 'r');
				$read = fread($fp, filesize($chat_dir.$_GET['cid'].'.txt'));
				fclose($fp);
				
				$ex = explode("\n", $read);
				$count = count($ex);
				
				if($ex[$count-2] != $last)
				{
					if($pos = strpos($read, $last))
					{
					$explode = str_replace($last, '', substr($read,$pos));
					$explode = explode("\n", $explode);
					$count = count($explode);
					$__all = "";

					foreach($explode as $split)
					{
						if(!empty($split))
						{
							$__all .= "<font class='chatelse'>".$split."</font><br />";
							$tmp = $split;
						}
					}
					$fp = fopen($chat_dir.'last_staff_'.$_GET['cid'].'.txt', 'w');
					fwrite($fp, $tmp);
					fclose($fp);
					echo "<script>update(\"".$__all."\")</script>";
					}
				}

			}
			elseif(TYPE == 'type')
			{
				echo "<link rel=\"stylesheet\" href=\"{$tpl_dir}style.css\" type=\"text/css\">";
				echo "<form method=\"POST\" action=\"".SELF."?action=livechat&windowed=1&chat=1&type=type&cid=".$_GET['cid']."&s={$SID}\" name=\"send_chat\">\n
				Chat :<input type=\"text\" size=\"40\" name=\"send\">&nbsp;<input type=\"submit\" name=\"submit\" value=\"submit\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".SELF."?action=livechat&type=logout&cid=".$_GET['cid']."&user=".USER."&s={$SID}\">End Chat</a></form>";

				?>
				<script>
				function addmsg(){
					var text = "<?echo $_POST['send'];?>";
					if(text !== "")
					{
						var message = "<b><font class='chatown'><?echo exo_date('h:i:s', time());?> - <?echo USER;?> Says:</b> <?echo $_POST['send'];?></font><br>";
						parent.window.display.document.body.innerHTML = parent.window.display.document.body.innerHTML + message + "";
					}
						parent.window.display.scrollTo(0,parent.display.document.body.scrollHeight);
						parent.window.type.send_chat.send.focus();
					}
				</script>
				<?
				if(!empty($_POST['send']))
				{
					$fp = fopen($chat_dir.$_GET['cid'].'.txt', 'a');
					fwrite($fp, "<b>".exo_date('h:i:s', time())." - ".USER." Says:</b>  {$_POST['send']}"."\n");
					fclose($fp);
		
					$fp = fopen($chat_dir.'last_staff_'.$_GET['cid'].'.txt', 'w');
					fwrite($fp, "<b>".exo_date('h:i:s', time())." - ".USER." Says:</b>  {$_POST['send']}");
					fclose($fp);
					echo "<body onLoad=\"addmsg()\">";						
				}

			}
			else
			{
				if($_GET['windowed'] != 1)				
				{
					echo "<script>window.open('".SELF."?action=livechat&windowed=1&s={$SID}', 'ChatWindowStaff', 'resizable,height=".$cwin_height.",width=".$cwin_width."');</script>";				
					echo "<script>parent.window.location.href=\"".SELF."?s={$SID}\";</script>";					
				}
				if($_GET['chat'] != 1)
				{
					echo $HEADER;
				?>
					<script language="JavaScript">
					<!--

					function doLoad()
					{
					    setTimeout( "refresh()", 1000 );
					}

					function refresh()
					{
					    parent.location.href = "<? echo SELF."?action=livechat&windowed=1&chat=0&s={$SID}"; ?>";
					}
					//-->
					</script>				
				<?
						
					if(TYPE == 'decline' && !empty($_GET['cid']))
					{
						$db->query($up_lchat." status='Declined' WHERE chatid='".$_GET['cid']."'");
					}
					$db->query($up_lchat." status='Ended' WHERE timeout<".time());
					$db->query("DELETE FROM phpdesk_liveonline WHERE timeout<".time()."");
					
					echo "<body onLoad=\"doLoad()\">";

					$Q = $db->query($sel_lonline." WHERE user='".USER."'");
					if(!$db->num($Q))
					{
						$db->query($ins_lonline."'".$_SERVER['REMOTE_ADDR']."', '".USER."', '".time()."', '".(time()+15)."', 'Staff')");					
					}
					else
					{
						$db->query($up_lonline." timeout='".(time()+30)."' WHERE user='".USER."'");
					}

					echo 'Checking to see if someone is waiting to be responsed.<br />';

					$_Q = $db->query($sel_lchat." WHERE status='waiting'");
					while($_F = $db->fetch($_Q))
					{
						echo "<script>parent.window.focus();</script>";
						echo $_F['starter']." - [ <a href='' OnClick=\"window.open('".SELF."?action=livechat&cid=".$_F['chatid']."&windowed=1&chat=1&s={$SID}', 'StaffChat".time()."', 'resizable,height=500,width=500')\">Accept</a> ]";
						echo " [ <a href='".SELF."?action=livechat&type=decline&cid=".$_F['chatid']."&windowed=1&chat=0&s={$SID}'>Decline</a> ]<br />";
					}
				}
				else
				{
					$db->query($up_lchat." status='Ended' WHERE timeout<'".time()."'");
					$db->query("DELETE FROM phpdesk_liveonline WHERE timeout<'".time()."'");
					$db->query($up_lchat." status='Running',chatter='".USER."' WHERE chatid='".$_GET['cid']."'");

					$num = $_GET['cid'];

					$fp = fopen($chat_dir.'last_staff_'.$num.'.txt', 'a');
					fwrite($fp, exo_date('h:i', time()).' - WELCOME TO '.$site_name.' LIVE SUPPORT');
					fclose($fp);

					if(!file_exists($chat_dir.$num.'.txt'))
					{
						$fp = fopen($chat_dir.$num.'.txt', 'a');
						fwrite($fp, "--------- \n".exo_date('h:i', time()).' - WELCOME TO '.$site_name.' LIVE SUPPORT'."\n");
						fclose($fp);
					}
					
					_parse($tpl_dir.'chat_main.tpl');
					echo $class->read;
				}
			}
		}
		//
		// Idea by Daniel Sink a.k.a. Coolkat
		// He wanted me to introduce this feature although I insisted
		// that phpMyAdmin is what people use for mysql editing.
		//
		elseif( ACT == 'mytoolbox' )
		{
			// check for type
			if( !$_GET['type'] )
			{
				_parse( $tpl_dir . 'list.tpl' );
				$Top = template( $class->read, NULL, $T_ST );
				$Mid = template( $class->read, $T_ST.'mysql', '/#mysql]' );
				$List = template( $Mid, $T_ST, $T_ED );
				$Down = template( $Mid, $T_ED, NULL );
				$Mid = template( $Mid, NULL, $T_ST );
				
				echo $Top . $Mid;
				
				$x = 0;
				
				// set the database table extension
				
				$db_ext = ( $db->db['db_ext'] == NULL ) ? 'phpdesk_' : $db->db['db_ext'];
				
				// query to get all data from the table...
				
				$query = $db->query( "SHOW TABLE STATUS FROM {$db->db['db_datab']}" );
				while( $f = $db->fetch( $query ))
				{
					
					$x++;
					
					// make sure the table is for phpdesk
					
					if( !preg_match( '/' . $db_ext .'/', $f['Name'] ) )
					{
						CONTINUE;
					}
					
					$number = exo_Format( $f['Index_length'] + $f['Data_length'], $dec = 1 );
					$tbl_size = $number[0] . ' ' . $number[1];
					
					$OUT = rpl( array( 'RECORDS' =>  $f['Rows'],
									   'SIZE'	 =>  $tbl_size,
									   'tbl'   =>  $f['Name'],
									   'td_bg' =>  ((is_float( $x / 2 )) ? 'tdbg1' : 'tdbg2' )
									 ), '^', $List );
					echo $OUT;
				}
				
				// down part here...
				
				echo $Down;
			}
			
			if( TYPE == 'query' )
			{
				// if empty query
				if( empty( $_POST['query'] ))
				{
					echo $error['field'];
				}
				else
				{
					$query = trim( stripslashes($_POST['query']) );
					
					// execute the query and post result
					if( preg_match( '/^CREATE|INSERT|UPDATE|DELETE|ALTER/i', $query ))
					{
						// execute the query
						$sql = $db->query( $query );
						
						// check to see if query was successfull
						if( $sql )
						{
							echo rpl( '^query^', $query, $success['query_execute'] );
						}
						else
						{
							echo $error['query_failed'];
						}
					}
					else
					{
						// print out error message ...
						echo $error['query_failed'];
					}
				}
			} // end query stuff....
			
			//
			// mySQL Dump Functionality of ExoPHPDesk
			// This took 5 hours+ because it required some research
			// the ideas taken are from phpBB and phpMyAdmin so there
			// might be some similarities.
			//
			if( TYPE == 'export' && !$_GET['verified'] )
			{
				if( !$_POST['submit'] )
				{
					$dump_table = ( $_GET['tbl'] != NULL ) ? $_GET['tbl'] : 'All Tables';
					_parse( $tpl_dir . 'view.tpl' );
					echo getBlock( $class->read, 'SQLDUMP_FORM' );
				}
				else
				{

					//
					// check for the fields
					// check if specified dir exists
					// check if the directory is writeable
					//
					$type = $_POST['etype'];
					if( empty( $_POST['location'] ) && $type == 'server' )
					{
						echo $error['fields'];
					}
					elseif(! file_exists( $_POST['location'] ) && $type == 'server' )
					{
						echo $error['incorrect_path'];
					}
					elseif(! is_writeable( $_POST['location'] ) && $type == 'server' )
					{
						echo $error['path_writeable'];
					}
					else
					{
						// move on.. YAY!
						header( "Location: admin.php?action=mytoolbox&type=export&verified=1&type2=$type&location=$_POST[location]&tbl=$_GET[tbl]&s=$SID" );
					}
				}
			}
			
			if( TYPE == 'export' && $_GET['verified'] == '1' )
			{
				// find crlf according to the operating system ...
				if( strstr($HTTP_USER_AGENT, 'Win') )
				{
        			$crlf = "\r\n";
				}
				elseif( strstr($HTTP_USER_AGENT, 'Mac') )
				{
					$crlf = "\r";
				}
				else
				{
					$crlf = "\n";
				}
				
				//
				// Function to generate table dump..
				// Some code taken from phpbb and phpMyAdmin
				//
				function get_dump( $table )
				{
					global $db, $crlf;
					
					$fields = $db->query( "SHOW FIELDS FROM $table" );
					$keys   = $db->query( "SHOW KEYS FROM $table" );
					$sCreate = "CREATE TABLE $table($crlf";
					
					while( $f = $db->fetch( $fields ))
					{
						$sCreate .= ' `' . $f['Field'] . '` ' . $f['Type'];
						$sCreate .= (!empty($f['Default'])) ? " DEFAULT '$f[Default]'" : NULL;
						$sCreate .= ( $f['Null'] != 'YES' ) ? ' NOT NULL' : NULL;
						$sCreate .= ( $f['Extra'] != NULL ) ? ' ' . $f['Extra'] : NULL;
						$sCreate .= ",$crlf";
					}
					
					$sCreate = preg_replace( '/,'. $crlf .'$/', NULL, $sCreate );
					
					while( $k = $db->fetch($keys))
					{
						$kname = $k['Key_name'];

						if( ($kname != 'PRIMARY') && ($k['Non_unique'] == 0) )
						{
							$kname = "UNIQUE|$kname";
						}

						if( !is_array($index[$kname]) )
						{
							$index[$kname] = array();
						}

						$index[$kname][] = $k['Column_name'];
					}

					while(list($x, $columns) = @each($index))
					{
						$sCreate .= ", $crlf";

						if($x == 'PRIMARY')
						{
							$sCreate .= '	PRIMARY KEY (' . implode($columns, ', ') . ')';
						}
						elseif (substr($x,0,6) == 'UNIQUE')
						{
							$sCreate .= '	UNIQUE ' . substr($x,7) . ' (' . implode($columns, ', ') . ')';
						}
						else
						{
							$sCreate .= "	KEY $x (" . implode($columns, ', ') . ')';
						}
					}

					$sCreate .= "$crlf);";
					
					if( get_magic_quotes_runtime() )
					{
						return stripslashes( $sCreate );
					}
					else
					{
						return $sCreate;
					}
				}
				// end of function
				
				//
				// Function to generate dumping data
				//
				function get_data( $table )
				{
					global $db, $crlf;
					
					// select data from table
					$query2 = $db->query( "SELECT * FROM $table" );
					$field = $db->result_fields();
					
					// count the records ..
					$count = count( $field );
					
					// if 0 records then return nothing..
					
					if( $count < 1 )
					{
						return TRUE;
					}

					// for loop to create fields list
					for( $i = 0; $i < $count; $i++ )
					{
						$fields .= '`' . $field[$i]->name . "`, ";
					}
					
					$fields = substr( $fields, 0, -2 );
					
					unset( $ret );
					
					// loop to create the insert data
					
					while( $f = $db->fetch($query2) )
					{
						unset( $values );
						for( $i = 0; $i < $count; $i++ )
						{
							if( !isset( $f[ $field[$i]->name ] ))
							{
								$values .= "NULL,";
							}
							elseif( $f[$field[$i]->name] != '' )
							{
								$values .= "'". doslashes( $f[$field[$i]->name], 1 ). "',";
							}
							else
							{
								$values .= "'',";
							}
						}
			
						$values = substr( $values, 0, -1 );
			
						$ret .= "INSERT INTO $table ($fields) VALUES($values);$crlf";
					}
					
					return $ret;
				}
				
				// Get mySQL version
				
				$db->query( "SELECT VERSION() as version" );
				
				if( !$f = $db->fetch() )
				{
					$db->query( "SHOW VARIABLES LIKE 'version'" );
					$f = $db->fetch();
				}
				
				$myver = preg_replace( "/^(.+?)(-|\_).+?$/", '\\1', $f['version'] );
				
				// The default headers of the dump file
				$vers  = preg_replace( '#<i>(.+?)</i>#', '\\1', $VERSION );
				$data  = "#$crlf";
				$data .= "# ExoPHPDesk mySQL Dump - Version $vers$crlf";
				$data .= "# Database: {$db->db['db_datab']}$crlf";
				$data .= "# PHP Version: ". phpversion() ."$crlf";
				$data .= "# mySQL Version: $myver $crlf";
				$data .= "# By Admin: " . USER ."$crlf";
				$data .= "#$crlf# DATE : " .  gmdate("d-m-Y H:i:s", time()) . " GMT$crlf";
				$data .= "#$crlf $crlf";
				
				// if no table var defined, then we are listing all the tables
				if(! $_GET['tbl'] )
				{
					
					// unset tbl_list and below get all the tables in the database

					$tbl_list = NULL;
					$query = $db->query( "SHOW TABLE STATUS FROM ". $db->db['db_datab'] );
					
					// start the loop to get all the information

					while( $f = $db->fetch( $query ))
					{
						// set the database table extension
						$db_ext = ( $db->db['db_ext'] == NULL ) ? 'phpdesk_' : $db->db['db_ext'];
						
						if( !preg_match( '/' . $db_ext .'/', $f['Name'] ) )
						{
							CONTINUE;
						}
						
						$tbl_list .= $f['Name'] . "|||";
					}
					
					$tables = explode( "|||", $tbl_list );
					foreach( $tables as $table )
					{
						if( empty( $table ))
						{
							continue;
						}
						
						// prepare data....
						
						$data .= "$crlf#$crlf# TABLE: " . $table . "$crlf#$crlf";
						$data .= get_dump( $table );
						$data .= "$crlf#$crlf# Dumping data for table - $table $crlf#$crlf";
						$data .= get_data( $table );
					}
					
				}
				else
				{
					$table = $_GET['tbl'];
					$check = @$db->query( "SELECT * FROM $table" );
					
					// check if there are records in the table..
					if( $db->num() )
					{
						// prepare data....
						
						$data .= "$crlf#$crlf# TABLE: " . $table . "$crlf#$crlf";
						$data .= get_dump( $table );
						$data .= "$crlf#$crlf# Dumping data for table - $table $crlf#$crlf";
						$data .= get_data( $table );

					}
				}
				// end data preparation

				$output_browser = ( $_GET['type2'] == 'browser' ) ? 1 : 0;
				$output_server  = ( $_GET['type2'] == 'server' )  ? 1 : 0;
				$output_user  = ( $_GET['type2'] == 'file' ) ? 1 : 0;

				if( $output_browser )
				{
					$dump_data = '<pre>' . htmlspecialchars( $data ) . '</pre>';
					_parse( $tpl_dir . 'view.tpl' );
					$all_tpl = getBlock( $class->read, 'SQLDUMP' );
					$top_tpl = template( $all_tpl, $T_ST, $T_ED );
					$bot_tpl = template( $all_tpl, $T_ED, NULL );
					
					echo $top_tpl . $dump_data . $bot_tpl;
				}
				
				if( $output_server )
				{
					$fp = @fopen( $_GET['location'].'exophpdesk_db_dump.sql', 'w' );
					fwrite( $fp, $data );
					fclose( $fp );
					
					// success, w00t!!
					
					echo rpl( '^file^', '<i>'.$_GET['location'].'exophpdesk_db_dump.sql</i>', $success['saved_on_server'] );
				}

				if( $output_user )
				{
					ob_end_clean();
					
					// send the file to the user...
					header( "Content-Type: text/x-delimtext; name=\"exophpdesk_db_dump.sql\"" );
					header( "Content-disposition: attachment; filename=exophpdesk_db_dump.sql" );
					
					echo $data;
					
					// we dont want anymore execution...
					exit;
				}
			}
			
			// to be added in future...
			if( TYPE == 'view' )
			{
				
				// if no table, then redirect back to where the user was
				if( !$_GET['tbl'] )
				{
					header( "Location: ". $_SERVER['HTTP_REFERER'] );
				}
				
				$max = 50;
				
				// parse the template file...
				_parse( $tpl_dir . 'list.tpl' );
				$Top = template( $class->read, NULL, $T_ST );
				$Mid = template( $class->read, $T_ST.'view_tbl', '/#view_tbl]' );
				$List1 = template( $Mid, $T_ST.'l', '/#l]' );
				$List2 = template( $Mid, '/#l]', NULL );
				$List2 = template( $List2, $T_ST, $T_ED );
				$Bot = template( $Mid, '/#l]', $T_ST );
				$Mid = template( $Mid, NULL, $T_ST );
				
				// top part and middle part
				echo $Top . $Mid;
				
				// prepare the query
				$query = "SELECT * FROM $tbl $extra";
				$db->query( $query );
				
				// count the rows
				$count1 = $db->num();
				
				// get the page and prepare the start limit for query
				$page = intval( $_GET['page'] );
				$start = $page != NULL ? ( $page > 1  ? ($_GET['page']-1) * $max : 0 ) : 0;
				
				// if count is greather than max, then put a limit in query
				if( $count1 > $max )
				{
					$q = $db->query( $query . " LIMIT $start, $max" );
				}
				
				// prepare top data with the fields names
				$top_data = "";
				$field = $db->result_fields();
				$count = count( $field );
				for( $i = 0; $i < $count; $i++ )
				{
					$top_data .= rpl( '^field^', $field[$i]->name, $List1 );
				}
				
				// print out top data and the bottom data
				echo $top_data . $Bot;
				
				// print the values of the fields
				$down_data = "";
				$x = 0;
				while( $f = $db->fetch( $q ) )
				{
					$x++;
					$temp_data = "";
					
					for( $i = 0; $i < $count; $i++ )
					{
						$name = $f[ $field[$i]->name ];
						$this_name = strlen( $name ) > 200 ? substr( $name, 0, 200 ).'...' : $name;

						// code from ipb..
						$value = wordwrap( htmlspecialchars( nl2br( $this_name ) ), 50, "<br>", 1 );
						$temp_data .= '<td>'. $value .'</td>';
					}

					$bg = is_float( $x / 2 ) ? 'tdbg1' : 'tdbg2';
					$down_data .= rpl( array( 'values' => $temp_data, 'td_bg' => $bg ), '^', $List2 ) . "\n";
				}
				
				// generate the page list
				$pages = ceil( $count1 / $max );
				$z = 0;
				while( $z < $pages )
				{
					$z++;
					$pagelist .= "[ <a href='$_SERVER[PHP_SELF]?action=mytoolbox&type=view&tbl=$tbl&page=$z&s=$SID'>$z</a> ] ";
				}
				
				// print out ... yay! mysqltoolbox completed.. w00t..
				echo $down_data . "<tr class='tdbg1'><td colspan='$count'><b>Pages:</b> $pagelist</td></tr></table>\n";
				
			}
		}
		elseif(ACT == 'logout')
		{
			Dologout( $SID );
			header( MAIN . "?login=out");
		}
		
}

// DEBUGGING!! List all queries
/*foreach ($db->query_collect as $query)
{
	echo "[ {$query[1]} ] {$query[0]}<br>";
}*/

// INCLUDE FOOTER FILE
include_once ( 'footer.php' );

// Flush all the headers
ob_end_flush();

?>