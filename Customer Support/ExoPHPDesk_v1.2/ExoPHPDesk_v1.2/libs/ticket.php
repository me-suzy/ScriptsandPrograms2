<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> TICKET . PHP File - Ticket Creation/Editing/Responses File
// >> Started : January 04, 2004
// >> Edited  : June 17, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

// Check The Type
switch( $_GET['type'] )
{
	// If Listing
	case 'list':
		
		if( $L_TYPE == 'admin' || $L_TYPE == 'staff' )
		{
			
			/* WHAT WAS REQUESTED? */
			if( $_GET['view'] == 'Closed' )
			{
				$view = " AND status = 'Closed'";
			}
			elseif( $_GET['view'] == '' )
			{
				$view = " AND status<>'Closed'";
			}
					
			// Parse ticket tpl file
			_parse( $tpl_dir.'tickets.tpl' );
			$READ = getBlock( $class->read, 'ST_TICKET' );
			
			// TEMPLATE STUFF
			$LIST = template ( $READ, $T_ST, $T_ED );
			$TOP  = template ( $READ, NULL, $T_ST );
			$DOWN = template ( $READ, $T_ED, NULL );
			
			//
			// BUG FIX of no closed ticket view link...
			// There was no good place for this piece of bug fix to reside in so
			// I just made it live in here. :-D
			//
			echo "[ <a href='index.php?fn=ticket&type=list&view=Closed&Group={$_GET[Group]}&s=$SID'>$tpl[view] $tpl[closed]</a> ] 
					[ <a href='index.php?fn=ticket&type=list&Group={$_GET[Group]}&s=$SID'>{$tpl[view]} {$tpl[open]}</a> ]\n<br />";

			$GQ = $db->query( "SELECT `name` FROM `phpdesk_groups` ORDER by `name`" );
			$SPLIT = array();
			
			if( $L_TYPE == 'staff' )
			{
				if($_F['groups'] == 'ALL')
				{
					while( $F = $db->fetch( $GQ ) )
					{
						array_push( $SPLIT, $F['name'] );
					}
				}
				else
				{
					$SPLIT = explode( "|||", $_F['groups'] );
				}
			}
			else
			{
				// GENERATE GROUP ARRAY
				while( $F = $db->fetch( $GQ ) )
				{
					array_push( $SPLIT, $F['name'] );
				}
			}
			
			if( $_GET['Group'] != NULL )
			{
				if( $L_TYPE == 'staff' && $_F['groups'] != 'ALL' )
				{
					if( preg_match( '#\b'. $_GET['Group'] .'\b#i', $_F['groups'] ))
					{
						$SPLIT = array( $_GET['Group'] );
					}
				}
				else
				{
					$SPLIT = array( $_GET['Group'] );
				}
			}
			
			// GET LAST PAGE
			$get_last = $_GET['last'];
			$get_page = $_GET['page'];
			
			/* SET START AND FINISH VARS */
			if( !isset( $_GET['page'] ) )
			{
				$start = 0;
				$finish = $a_tppage;
			}
			else
			{
				$start = $get_last;
				$finish = $a_tppage * $get_page;
			}
									
			while( list ( ,$group ) = each ( $SPLIT ) )
			{
				/* UNSET ALL THE VARIABLES */
				$x = $y = $t_count = $tmp_down = "";
				
				/* COUNT NO. OF TICKETS */
				$_Q = $db->query ( $sel_ticket." WHERE `group`='{$group}'{$view}ORDER by waiting DESC, `priority` ASC,`update` DESC" );
				$t_count = $db->num($_Q);
				
				/* PRINT OUT GROUP HEADER */
				$OUT = str_replace('^group^', $group, $TOP);
				$OUT = str_replace('^top^', "trmain", $OUT);
				echo $OUT;
				
				/* UNSET Z AND X VARS */
				$z = $x = 0;

				while( $_F = $db->fetch( $_Q ) )
				{
				
				  $x++;

				  // WHILE END LIMIT HASN'T REACHED
				  if( $x >= $start && $x <= $finish )
				  {	
				
					$z++;				

					// GET BACKGROUND
					$bg = ( is_float( $x / 2 ) ) ? 'tdbg1' : 'tdbg2';
					
					// IF OWNER IS THE USER LOGGED IN
					if( $_F['owner'] == USER )
					{
						$_F['title'] = "<b>" . $_F['title'] . "</b>";
					}
				
					// SET COLOR ACCORDING TO PRIORITY
					$COLOR = ( $_F['priority'] == '1' ) ? $tpl['font_rd'] : (( $_F['priority'] == '2' ) ? 
								$tpl['font_gr'] : $tpl['font_bk'] );

					$color = $COLOR . $_F['title'] . $tpl['font_en'];

					// OPEN OR CLOSE?
					$LIST  = ( $_F['status'] == 'Closed' ) ? str_replace ( '^closeoropen^', 'Open', $LIST ) : 
								str_replace ( '^closeoropen^', 'Close', $LIST );
									
					// TIME PASTE SINCE TICKET CREATED
					$opened = opened( $_F['opened'] );
					
					// REPLIES COUNT
					$replies = $_F['replies'];
			
					// IF WAITING FOR STAFF, THEN RED COLOR
					if( $_F['waiting'] == 'Staff' )
					{
						$_F['waiting'] = $tpl['font_rd'] . $_F['waiting'] . $tpl['font_en'];
					}
					
					// REQUIRED REPLACING IN TEMPLATE
					$out = str_replace ( '^tdbg^', $bg, str_replace ( '^id^', $_F['id'], str_replace ( 
										 '^title^', $color, $LIST ) ));
					$out = str_replace ( '^status^', $_F['waiting'], str_replace ( '^opened^', $opened, $out ) );
					
					if( $L_TYPE == 'admin' )
					{
						// DELETE URL
						$del = ' [ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $L_TYPE .'&type=delete&id='.$_F['id'].'">Delete</a> ]';
					}
					
					$out = str_replace ( '^del^', $del, str_replace ( '^replies^', $replies, $out ) );
					$out = str_replace ( '^del^', '', $out );

					// PRINT OUT WHOLE OF IT
					echo $out;
					
					// UNSET VARIABLES
					$no_temp = $opened = NULL;
				 
				 }

			   }

			   // PAGES NUMBER LIST
			   if( $t_count > $tppage )
			   {	
					$pages = NULL;
					$do_it = ceil ( ( $t_count / $a_tppage ) );
					$y = 1;

					if( $do_it > 1 )
					{
						while( $y <= $do_it )
						{
							$y++;
							$_GET['last'] = ( $a_tppage * ( $y - 2 ) ) + 1;
							$pages .=' [ <a href="'.$_SERVER['PHP_SELF'].'Group='. $_GET['Group'] .'&type=list&page='.($y-1).'&view='.$_GET['view'].'&last='.$_GET['last'].'">'.($y-1).'</a> ]';
						}
					}
					
					// MERGE PAGES LIST WITH FOOTER OF TEMPLATE
					$tmp_down = str_replace( '^pages^', $pages, $DOWN );
				}

				if( $z < 1 )
				{
					echo $general['no_ticket_staff'] . str_replace( '^pages^', '', $DOWN );
				}

				echo $tmp_down;
				
			}
		}
		
		break;
	
	//
	// If Adding( Creating ) A Ticket
	//
	case 'add':
			
		// If Form Not Submitted
		if( SUBM == '' )
		{
			// UNSET GROUPS
			$groups =  NULL;

			// SQL QUERY
			$_Q     =  $db->query ( $sel_group );
				
			// Loop To Get Groups
			while( $_F = $db->fetch ( $_Q ) )
			{
				// If Group Named EMAIL
				if ( $_F['name'] == 'EMAIL' )
				{
					// Leave This Name And Move
					CONTINUE;
				}
				
				// Add Group Option
				$groups .= "<option value='" . $_F['name'] . "'>" . $_F['name'] . "</option>";
			}
				
			// PARSE ADD TICKET . TPL FILE
			_parse ( $tpl_dir . 'tickets.tpl' );
			
			// PREPARE VARIABLES
			$READ  =  getBlock( $class->read, 'ADD_TICKET' );
			$READ  =  rpl( '^e_text^', NULL, $READ );

			// THE OUTPUT VARIABLES
			$LIST  =  template ( $READ, $T_ST, $T_ED );
			$READ  =  template ( $READ, NULL, $T_ST );
			$READ  =  str_replace ( '^groups^', $groups, $READ );
				
			// GET FIELDS USING FUNCTION
			$FIELDS = get_fields( $LIST,'ticket' );
										
			$READ = str_replace ( '^extras^', $FIELDS, $READ );
			echo $READ;
			
		}
		else
		{
			// Validate Using Validate Function
			$VALIDATE = validate('fields',$_POST);
			
			/* ATTACHMENTS START */
			$DO_ATTACH  = 1;
			$VALID_FILE = 0;
			$Allowed = explode( ",", $Allowed_Ext );
			
			while( list( , $Type ) = each( $Allowed ) )
			{
				$Type = preg_replace( '/\s/', NULL, $Type );
				if( preg_match( '/(.*)'. $Type .'$/i', $_FILES['attach']['name'] ))
				{
					$VALID_FILE = 1;
				}
			}
			
			if( !is_uploaded_file( $_FILES['attach']['tmp_name'] ))
			{
			
				$DO_ATTACH = 0;
				
			}
			elseif( $_FILES['attach']['size'] > $Max_Upload )
			{
			
				$DA_ERROR  = $error['attach_size'];
				$DO_ATTACH = 0;
				
			}
			elseif( $VALID_FILE != 1 )
			{
				
				$DA_ERROR  = $error['attach_type'];
				$DO_ATTACH = 0;
				
			}
			elseif( file_exists( $Attach_dir . $_FILES['attach']['name'] ))
			{
				$st = 0;
				
				while( $st != 1 )
				{
					$x++;

					if( !file_exists( $Attach_pre . $x . $_FILES['attach']['name'] ))
					{
						$st = 1;
	
						$Attachment = $Attach_pre . $x . $_FILES['attach']['name'];
	
					}
				}
				
			}
			else
			{
		
				$Attachment = $_FILES['attach']['name'];
					
			}			
			/* ATTACHMENTS END */
			
			// Validation
			if( $VALIDATE == 1 )
			{
				// Print Error Message
				echo $error['fields'];
			}
			elseif( $DA_ERROR != NULL )
			{
				// Print Error Message
				echo $DA_ERROR;
			
			}
			elseif( !$_POST['title'] || !$_POST['group'] || !$_POST['text'] || !$_POST['priority'] )
			{
				// Print Error Message
				echo $error['fields'];
			}
			else
			{
			
				if( $DO_ATTACH == 1 )
				{
					move_uploaded_file( $_FILES['attach']['tmp_name'],  $Attach_dir . $Attachment );
				}
				
				// Notify All Staff About New Ticket
				mail_all_staff ( $_POST['group'], USER, $_POST['text'], $_POST['title'] );
				
				// Get Fields	
				$FIELDS  =  get_fields ( '', 'ticket', 'SQL' );
				$VALUES  =  val_fields ( $_POST, 'ticket' );
				
				// Prepare SQL
				$sql = "'".$a_id."', '".USER."' ,'".time()."', '".$_POST['title']."', '".$_POST['group']."', 
						'".$_POST['text']."', '".$_POST['priority']."', '".time()."', 'Open', 'Staff', '".$FIELDS."',
						'". addslashes( $VALUES ) . "', '". $Attachment ."'";
					
				// Replacing ^sql^ With Query
				$sql = str_replace ( '^sql^', $sql, $ins_ticket );

				// If Successfull Execution
				if ( $db->query($sql ) )
				{
					// increment group's tickets count!
					$ext = $_POST['priority'] == 1 ? ', high_tickets = high_tickets + 1' : '';
					$db->query("
						UPDATE `phpdesk_groups` SET total_tickets = total_tickets + 1, 
								open_tickets = open_tickets + 1{$ext} 
							WHERE `name` = '{$_POST[group]}'
							  ");

					// Print Success Message
					echo $success['add_ticket'];
				}
			}
				
		}
			
		break;
			
	// If Editing
	case 'edit':
				
		// SQL QUERY
		$_Q = $db->query ( $sel_ticket . " WHERE id = '" . $_GET['id'] . "'" );
		$_F = $db->fetch ( $_Q );
					
		// START STAFF AUTH CHECK
		if ( $L_TYPE == 'staff' )
		{
			// Query To Get User Information
			$US = $db->query ( "SELECT * FROM `phpdesk_staff` WHERE username = '" . USER ."'" );
			$US = $db->fetch ( $US );

			// Get Group List
			$a_groups  =  $US['groups'];

			// check whether staff is allowed to edit tickets or not
			if( $US['edit_ticket'] != '1' && strtolower( $_F['admin_user'] ) != strtolower( USER ))
			{
				$no_auth = 1;
			}
			elseif( $a_groups == 'ALL' )
			{
				$no_auth = 0;
			}
			else
			{
				$split = explode ( "|||", $a_groups );
				foreach ( $split as $group )
				{
				
					if( $_F['group'] == $group )
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
				
		} // END STAFF AUTH CHECK
				
		// START USER AUTH CHECK
		if ( $L_TYPE == 'members' )
		{
			// SQL Query
			$Q  =  $db->query ( $sel_ticket . " WHERE `id` = '" . $_GET['id'] ."' AND `admin_user` = '" . USER . "'" );
					
			if ( !$db->num ( $Q  ) )
			{
				$no_auth = 1;
			}
		}
				
		// Look for an ID
		if( !isset( $_GET['id'] ) )
		{
			// Print Error Message
			echo $error['id_missing'];
		}
		// Check for record
		elseif( $no_auth == 1 )
		{
			// Print Error Message
			echo $error['no_auth_or_record'];
		}
		else
		{
			// IF FORM NOT SUBMITTED
			if ( SUBM == NULL )
			{
				// PREPARE VARIABLES
				$e_text  = $_F['text'];
				$t_title = $_F['title'];
					
				// SQL QUERY
				$Q  = $db->query ( $sel_group );

				while ($F = $db->fetch ( $Q ) )
				{
					if( $F['name'] == $_F['group'] )
					{
						$selected = " selected";
					}
					else
					{
						$selected = NULL;
					}
							
					$groups .= "<option value='{$F['name']}'{$selected}>{$F['name']}</option>";
				}
						
				if( $_F['attach'] != NULL )
				{
					$ATTACH = $_F['attach'];
				}
					
				// PARSE TICKET . TPL FILE
				_parse ( $tpl_dir . 'tickets.tpl' );
				
				// PREPARE VARIABLES
				$READ  =  getBlock( $class->read, 'ADD_TICKET' );
				$READ  =  str_replace ( '^e_text^', $e_text, $READ );
				
				// THE OUTPUT VARIABLES
				$LIST  =  template ( $READ, $T_ST, $T_ED );
				$READ  =  template ( $READ, NULL, $T_ST );
				$READ  =  str_replace ( '^groups^', $groups, $READ );
								
				$FIELDS = get_fields ( $LIST, 'ticket', 'edit', $_F['values'], $_F['fields'] );
										
				$READ = str_replace ( '^extras^', $FIELDS, $READ );
				echo $READ;
			}
			else
			{
				// Do the Validation
				$VALIDATE = validate('fields',$_POST);

				// Validation
				if( $VALIDATE == 1 )
				{
					// Print Error Message
					echo $error['fields'];
				}
				elseif( !$_POST['title'] || !$_POST['group'] || !$_POST['text'] || !$_POST['priority'] )
				{
					// Print Error Message		
					echo $error['fields'];
				}
				else
				{
					$FIELDS = get_fields ( '','ticket', 'SQL' );
					$VALUES = val_fields ($_POST, 'ticket' );						
			
					$sql = $up_ticket." `update` = '".time()."', `title` = '".$_POST['title']."', `group` = '".$_POST['group']."',
							`text` = '".$_POST['text']."', `priority` = '".$_POST['priority']."', `fields` = '".$FIELDS."',
							`values` = '".$VALUES."' WHERE `id` = '".$_GET['id']."'";
							
					if( $db->query ( $sql ) )
					{
						echo $success['edit_ticket'];
					}
				}
			} // End if submit exists
		} // End Verificaton
				
		break;
			
		case 'view':
			
			$_Q  =  $db->query ( $sel_ticket . " WHERE id = '". $_GET['id'] ."'" );
			$_F  =  $db->fetch ( $_Q );

			// START STAFF AUTH CHECK
			if ( $L_TYPE == 'staff' )
			{
	
				$US = $db->query ( "SELECT * FROM `phpdesk_staff` WHERE username = '" . USER ."'" );
				$US = $db->fetch ( $US );
					
				$a_groups  =  $US['groups'];
					
				if( $a_groups == 'ALL' )
				{
					$no_auth = 0;
				}
				else
				{
					$split = explode ( "|||", $a_groups );
					foreach ( $split as $group )
					{
						if( $_F['group'] == $group )
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
			
			} // END STAFF AUTH CHECK
				
			// START USER AUTH CHECK
			if ( $L_TYPE == 'members' )
			{
				$Q  =  $db->query ( $sel_ticket . " WHERE `id` = '" . $_GET['id'] ."' AND `admin_user` = '" . USER . "'" );
				
				if ( !$db->num ( $Q  ) )
				{
					$no_auth = 1;
				}
			}			

			if( !isset ( $_GET['id'] ) )
			{
				echo $error['id_missing'];
			}
			elseif ( $no_auth == 1 )
			{
				echo $error['no_auth_or_record'];
			}				
			else
			{
				// Prepare some variables to get parsed in template
				$t_admin = $_F['admin_user'];
				$sendto  = $_F['admin_user'];

				// IF ITS A TICKET FROM EMAIL
				if( $_F['group'] == 'EMAIL' || $sendto == 'Guest' )
				{
					$a_email = $_F['admin_email'];
					$a_web   = "N/A";
				}
				else
				{
					// FIND THE TABLE WHICH CONTAINS THE USER
					$where =  where ( $sendto );

					// PREPARE SQL
					$sql   =  ( $where == "phpdesk_admin" ) ? "SELECT * FROM ".$where." WHERE name = '".$sendto."'" : "SELECT * FROM ".$where." WHERE username = '".$sendto."'";
						
					// SQL QUERY AND FETCH
					$Q  =  @$db->query ( $sql );
					$F  =  @$db->fetch ( $Q );
						
					// OTHER VARIABLES
					$a_email  =  $F['email'];
					$a_web    =  $F['website'];
				}
					
				// GET FIELDS
				$FIELDS = $_F['fields'];
				$VALUES = $_F['values'];
					
				// TICKET RELATED VARIABLES					
				$t_opened = exo_date( 'd M y  H:i', $_F['opened'] );
				$t_status = $_F['status'];
				$t_group  = $_F['group'];
				$t_owner  = ( empty( $_F['owner'] ) ) ? "None" : $_F['owner'];
				$t_title  = $_F['title'];
					
				// MORE VARIABLES
				$text     = strip( $_F['text'] );
				$c_text   = str_replace("\n", "<br />", $text);
				$t_id     = $_F['id'];
				$ATTACH   = $_F['attach'];
					
				// PARSE THE VIEW TICKET TPL FILE
				_parse($tpl_dir.'tickets.tpl');

				if( $_GET['print'] == 1 )
				{
					$read   =  getBlock ( $class->read, 'PRINTABLE' );
				}
				else
				{
					$read   =  getBlock ( $class->read, 'VIEW_TICKET' );					
				}

				// ALL TEMPLATE VARIABLES
				$list   =  template ( $read, $T_ST, $T_ED );
				$end    =  template ( $read, $T_ED, NULL );
				$end    =  template ( $end, NULL, $T_ST . 'extra' );
				$xtra   =  template ( $read, $T_ST . 'extra', '/#extra]' );
				$NOTE   =  template ( $read, $T_ST . 'note', '/#note]' );
				$read   =  template ( $read, NULL, $T_ST );
				$EXTRAS =  reverse_ticket ( $FIELDS, $VALUES, $xtra );
				$read   =  str_replace ( '^extras^', $EXTRAS, $read );
				$read   =  str_replace( '^c_text^', $c_text, $read );
					
				if( $_F['status'] == 'Closed' ) // Check Open/Close
				{
					$read = str_replace ( '^openorclose^', 'Open', $read );
				}
				else
				{
					$read = str_replace ( '^openorclose^', 'Close', $read );
				}

				if ( $L_TYPE != 'members' )
				{
					if ( $L_TYPE == 'admin' )
					{
						$assign = ' [ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $L_TYPE .'&type=delete&id='.$t_id.'"><font class="specialurls">Delete</font></a> ]';
					}
						
					unset ( $S_Options );
						
					$SR_Q    = $db->query( "SELECT * FROM `phpdesk_saved` WHERE `type` = 'Response'" );
					while ( $SR_F = $db->fetch ( $SR_Q ) )
					{
						$S_Options  .= '<option value="' . $SR_F['title'] ."\">" . $SR_F['title'] ."</option>\n";
						$SR_F['text'] = str_replace ( "\r\n", '\n', $SR_F['text'] );
						$JavaScript .= 'if ( document.response.saved.value == "'. $SR_F['title'] .'" ) { ' . "\n"
									. 'document.response.comment.value = "'. $SR_F['text'] ."\" \n } \n ";
					}

					$assign .= ' [ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $L_TYPE .'&type=assign&id='.$t_id.'"><font class="specialurls">Take Assignment</font></a> ]';
					$n_link  = ' [ <a href="index.php?s='.$SID.'&fn=notes&type=add&tid='. $_F['id'] .'"><font class="specialurls">Add Note</font></a> ] ';
					$Saved_r = '<tr><td>'. $tpl['saved_r'] .':</td><td><select name="saved" onChange="change();"><option value="0">-- None --</option>'
							   . $S_Options .'</select></td></tr>';
												
				}

				$read = rpl ( '^assign^', $assign, $read );
				$read = rpl ( '^n_link^', $n_link, $read );
				$read = rpl ( '^jscript^', $JavaScript, $read );
				$end  = rpl ( '^saved_r^', $Saved_r, $end );
				$read = rpl ( '^TID^', $_GET['id'], $read );
					
				// OUTPUT
				echo $read;

				$Q = $db->query ( $sel_response." WHERE tid = '". $_GET['id'] ."' ORDER by posted" );

				if( !$db->num ( $Q ) ) // If no record
				{
					echo $general['no_response'];
				}
				else
				{
					while($F = $db->fetch($Q))
					{
						$x++;
						
						if( $F['sname'] == USER || $L_TYPE == 'admin' || $edit_response == '1' )
						{
							$bg = 'ticketbg2';
							$edit = '[ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $L_TYPE .'&type=response&do=edit&id='.$F['id'].'&tid='.$F['tid'].'">Edit</a> ]';
							if ( $L_TYPE == 'admin' )
							{
								$edit .= '[ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $L_TYPE .'&type=response&do=delete&id='.$F['id'].'&tid='.$F['tid'].'">Delete</a> ]';
							}
						}
						else
						{
							$bg = 'ticketbg';							
							$edit = "";
						}

						$F['comment']  =  strip ( $F['comment'] );
							
						$WHERE = where ( $F['sname'] );
													
						if ( $WHERE != 'phpdesk_members' && $F['sname'] != 'Guest' )
						{
							$NAME = ( $WHERE == 'phpdesk_admin' ) ? 'name' : 'username';
							$RQ = $db->query ( "SELECT * FROM `". $WHERE ."` WHERE ". $NAME ." = '".$F['sname']."'" );
							$RF = $db->fetch ( $RQ );
							$F['comment'] = $F['comment'] . "\n" . "---------------------------------------" 
											. "\n" .$RF['signature'];
						}
							
						if ( $L_TYPE == 'members' && $WHERE == 'phpdesk_staff' )
						{
							$LINK = '<a href="index.php?s='.$SID.'&fn=rate&action=staff&id='. $RF['id'] .'">';
							$F['comment'] = $F['comment'] . '<p align="right"> [ ' . $LINK . $tpl['rate_staff'] . '</a> ]</p>';
						}

						$Post = NULL;

						if ( $WHERE == 'phpdesk_staff' )
						{
							$Post  =  '<img src="'. $tpl_dir . 'images/' . round ( $RF['rating'] ) .'rank.gif"><br>';
						}
							
						$out = str_replace('^s_name^', $F['sname'], str_replace('^s_comment^', str_replace("\n", "<br />", $F['comment']), $list));
						$out = str_replace('^tbg^', $bg, str_replace('^edit_comment^', $edit, $out));
						$out = str_replace('^post^', $Post . exo_date('d/m/y H:i', $F['posted']), $out);
						echo $out;
					}
				}
				if ( $L_TYPE != 'members' )
				{
					$N = $db->query($sel_note." WHERE tid = '". $_GET['id'] ."' ORDER by posted");
					if( $db->num( $N ) )
					{
						while( $NF = $db->fetch( $N ) )
						{
							$x++;
								
							if($NF['sname'] == USER || $L_TYPE == 'admin' )
							{
								$BG = 'ticketbg2';
								$EDIT = '[ <a href="index.php?s='.$SID.'&fn=notes&l_type='.$L_TYPE.'&type=edit&id='.$NF['id'].'&tid='.$NF['tid'].'">Edit</a> ] ';
								$EDIT .= '[ <a href="index.php?s='.$SID.'&fn=notes&l_type='.$L_TYPE.'&type=delete&id='.$NF['id'].'&tid='.$NF['tid'].'">Delete</a> ]';
							}
							else
							{
								$BG = 'ticketbg';							
								$EDIT = "";
							}
							
							$OUT = str_replace('^s_name^', $NF['sname'], str_replace('^s_note^', str_replace("\n", "<br />", $NF['note']), $NOTE));
							$OUT = str_replace('^tbg^', $BG, str_replace('^edit_note^', $EDIT, $OUT));
							$OUT = str_replace('^post^', exo_date('d/m/y H:i', $NF['posted']), $OUT);
							echo $OUT;
						}
					}
				}
						
				echo $end;
			}
				
			break;
				
		case 'response':
	
			// Edit a response
			if($_GET['do'] == 'edit')
			{
				
				// SQL QUERIES
				$_Q = $db->query ( $sel_response . " WHERE id='{$_GET['id']}'" );
				$_F = $db->fetch ( $_Q );
				
				// START STAFF AUTH CHECK
				if ( $L_TYPE == 'staff' )
				{

					$US = $db->query ( "SELECT * FROM `phpdesk_staff` WHERE username = '" . USER ."'" );
					$US = $db->fetch ( $US );
					
					$a_groups  =  $US['groups'];
					
					// check whether staff is allowed to edit tickets or not
					if( $US['edit_response'] != '1' && strtolower( $_F['sname'] ) != strtolower( USER ))
					{
						$no_auth = 1;
					}
					elseif( $a_groups == 'ALL' )
					{
						$no_auth = 0;
					}
					else
					{
						$split = explode ( "|||", $a_groups );
						foreach ( $split as $group )
						{
							if( $F['group'] == $group )
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
				
				} // END STAFF AUTH CHECK
				
				// START USER AUTH CHECK
				if ( $L_TYPE == 'members' )
				{
					if ( strtolower( USER ) != strtolower( $_F['sname'] ))
					{
						$no_auth = 1;
					}
				}
					
				if(!isset($_GET['id']))
				{
					echo $error['id_missing'];
				}
				elseif($no_auth == 1)
				{
					echo $error['no_auth_or_record'];
				}
				else
				{
					if( SUBM == NULL )
					{
						// PREPARE TPL VARS
						$e_comment = $_F['comment'];
						$t_id = $_F['tid'];

						// PARSE TEMPLATE FILE
						_parse($tpl_dir.'tickets.tpl');
						$read = getBlock( $class->read, 'VIEW_TICKET' );
						$read = template ( $read, $T_ED, $T_ST );
						echo rpl ( '^saved_r^', NULL, $read );
					}
					else
					{
						//
						// Open ticket if its closed!
						// This is done per users request as some stupid members add response to a ticket
						// but forget to open the ticket. Done to prevent troubles!
						//
						if( $L_TYPE == 'member' )
						{
							$query = $db->query( "SELECT status FROM phpdesk_tickets WHERE id = '$_GET[id]'" );
							$fetch = $db->fetch( $query );
								
							if( $fetch['status'] == 'Closed' )
							{
								$db->query( "UPDATE phpdesk_tickets SET status = 'Open' WHERE id = '$_GET[id]'" );
							}
						}
						echo response ( $_GET['tid'], USER, $_POST['comment'], 0, 1, $_GET['id'] );
					}
				}
				
			}
			elseif($_GET['do'] == 'delete')
			{
				if ( $L_TYPE == 'admin' )
				{
					if( !isset( $_GET['id'] ) )
					{
						echo $error['id_missing'];
					}
					elseif( $_GET['confirm'] != 'YES' )
					{
						echo 'Do you really want to delete the response: <b>'.$_GET['id'].'</b><br />';
						echo '[ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $L_TYPE .'&type=response&confirm=YES&do=delete&id='.$_GET['id'].'">Yes</a> ] ';
						echo '[ <a href="javascript:history.back(0)">No</a> ] <br />';
					}
					else
					{
						if($db->query ( "DELETE FROM phpdesk_responses WHERE id = '" . $_GET['id'] ."'" ) )
						{
							echo 'Record deleted successfully. <br />';
						}
					}
				}
			}				
			else
			{ 
				// Add response
				if ( $L_TYPE == 'staff' )
				{
					$db->query ( $up_staff . " responses = responses + 1 WHERE username = '". USER ."'" );
				}
					
				$Q  =  $db->query( $sel_ticket . " WHERE id = '{$_GET['id']}'" );
				$F  =  $db->fetch( $Q );

				if( $F['group'] == 'EMAIL' && !empty( $F['admin_email'] ) )
				{
					echo response($_GET['tid'], USER, $_POST['comment'], 0, 0, 0, 1);
				}
				else
				{
					echo response($_GET['tid'], USER, $_POST['comment'], 0, 0, 0);					
				}
			}
				
		break;
				
	case 'assign':
		
		if ( $L_TYPE != 'members' )
		{
			if( empty($_GET['id']) )
			{
				echo $error['id_missing'];
			}
			else
			{
				$sql = $up_ticket . " `owner`='". USER ."' WHERE `id` = '{$_GET['id']}'";
				if( $db->query( $sql ) )
				{
					echo $success['assigned'];
				}
			}
		}
				
		break;
		
	case 'assign_to':

		if( !isset( $_GET['id'] ))
		{
			echo $error['id_missing'];
		}
		elseif( $L_TYPE != 'admin' )
		{
			echo $error['no_auth_or_record'];
		}
		elseif( SUBM == NULL )
		{
			$OPTIONS = NULL;
				
			//
			// Get the administrators and stafflist!!
			// Two queries are being used below because mysql 3.*
			// doesnt supports SubQueries... :(
			//
			$ALQ = $db->query( $sel_admin . ' ORDER by name' );
			while( $ALF = $db->fetch( $ALQ ))
			{
				$OPTIONS.= "<option value='{$ALF['name']}'>{$ALF['name']}+</option>\n";
			}

			$SLQ = $db->query( $sel_staff . ' ORDER by username' );
			while( $SLF = $db->fetch( $SLQ ))
			{
				$OPTIONS.= "<option value='{$SLF['username']}'>{$SLF['username']}</option>\n";
			}
			
			//
			// Parse the template file...
			//
			_parse( $tpl_dir . 'add.tpl' );
			$Top = template( $class->read, NULL, $T_ST );
			$End = template( $class->read, $T_ST.'assign', '/#assign]' );
			
			echo $Top . $End;
		}
		else
		{
			//
			// Finally!, Update the ticket assignment....
			//
			if( $db->query( "UPDATE phpdesk_tickets SET owner = '{$_POST['assign_to']}' WHERE id = '{$_GET['id']}'" ))
			{
				echo rpl( '^TO^', $_POST['assign_to'], $success['assign_to'] );
			}
				
		}
			
		break;
		
	case 'delete':
		
		if ( $L_TYPE == 'admin' )
		{
			if( !isset ( $_GET['id'] ) )
			{
				echo $error['id_missing'];
			}
			elseif ( $_GET['confirm'] != 'YES' )
			{
				echo 'Do you really want to delete the ticket and all of its responses: <b>'.$_GET['id'].'</b><br />';
				echo '[ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $L_TYPE .'&confirm=YES&type=delete&id='.$_GET['id'].'">Yes</a> ] ';
				echo '[ <a href="javascript:history.back(0)">No</a> ] <br />';
			}
			else
			{
				// fetch the ticket for group!
				$q = $db->query("SELECT `group`, status FROM `phpdesk_tickets` WHERE id = '{$_GET[id]}'");
				$f = $db->fetch($q);
				
				// decrease the counters where required!
				$db->query("UPDATE `phpdesk_groups` SET total_tickets = total_tickets - 1"
							.($f['status'] == 'Open' ? ', open_tickets = open_tickets - 1' : '')
					  		.($f['priority'] == '1' ? ', high_tickets = high_tickets - 1'  : '') 
							. " WHERE `name` = '{$f[group]}'");
				
				// delete queries!
				$sql  = "DELETE FROM phpdesk_responses WHERE `tid` = '". $_GET['id'] ."'";
				$sql2 = "DELETE FROM phpdesk_tickets WHERE `id` = '". $_GET['id'] ."'";
				$sql3 = "DELETE FROM phpdesk_notes WHERE `tid` = '". $_GET['id'] ."'";
				
				if( $db->query( $sql ) && $db->query( $sql2 ) && $db->query( $sql3 ) )
				{
					echo 'Successfully deleted tickets and responses.<br />';
				}
			}				
		}
		break;
							
	default:
			
		/* START CLOSE/OPEN TICKET */
		if ( preg_match ( '/Close|Open/i', TYPE ) )
		{
			$_Q  =  $db->query ( $sel_ticket . " WHERE id = '". $_GET['id'] ."'" );
			$_F  =  $db->fetch ( $_Q );

			// START STAFF AUTH CHECK
			if ( $L_TYPE == 'staff' )
			{
				$US = $db->query ( "SELECT * FROM `phpdesk_staff` WHERE username = '" . USER ."'" );
				$US = $db->fetch ( $US );

				$a_groups  =  $US['groups'];
				
				if( $a_groups == 'ALL' )
				{
					$no_auth = 0;
				}
				else
				{
					$split = explode ( "|||", $a_groups );
					foreach ( $split as $group )
					{
						if( $_F['group'] == $group )
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
				
			} // END STAFF AUTH CHECK
				
			// START USER AUTH CHECK
			if ( $L_TYPE == 'members' )
			{
				$Q  =  $db->query ( $sel_ticket . " WHERE `id` = '" . $_GET['id'] ."' AND `admin_user` = '" . USER . "'" );
				
				if ( !$db->num ( $Q  ) )
				{
					$no_auth = 1;
				}
			}
								
			if( !$_GET['id'] )
			{
				$error['id_missing'];
			}
			elseif( $no_auth == 1 )
			{
				echo $error['no_auth_or_record'];
			}
			elseif( $_GET['confirm'] != 'YES' )
			{
				if(TYPE == 'Close' || TYPE == 'close')
				{
					echo $general['close_confirm'];
					echo str_replace ( '^URL^', $_SERVER['PHP_SELF'] , $general['close_open_url'] );
				}
				elseif(TYPE == 'Open')
				{
					echo $general['open_confirm'];
					echo str_replace ( '^URL^', $_SERVER['PHP_SELF'] , $general['close_open_url'] );
				}
			}
			elseif( $_GET['confirm'] == 'YES' )
			{
				$s  =  ( $_GET['type'] == 'Open' ) ? 'Open' : 'Closed';
				if ($L_TYPE == 'staff')
				{
					$db->query ( $up_staff." closed = closed + 1 WHERE username = '" . USER . "'" );
				}
				
				// decrease open tickets count in the groups table if required!
				if ($s == 'Closed') {
					$db->query("UPDATE `phpdesk_groups` SET open_tickets = open_tickets - 1"
								. ($_F['priority'] == 1 ? ',high_tickets = high_tickets - 1' : '')
								. "	WHERE `name` = '{$_F[group]}'");
				} else {
					// increase open tickets count and hight priority tickets if required
					$db->query("UPDATE `phpdesk_groups` SET open_tickets = open_tickets + 1"
								. ($_F['priority'] == 1 ? ',high_tickets = high_tickets + 1' : '')
								. " WHERE `name` = '{$_F[group]}'");
				}
				
				$sql = $up_ticket . " status='{$s}' WHERE id = '{$_GET['id']}'";
				if ($db->query($sql))
				{
					$st = strtolower($_GET['type']);
					echo $success[$st.'_ticket'];
				}
			}			
		}
		/* END CLOSE/OPEN TICKET */
		break;
	
	case 'download':
		
		if( empty( $_GET['tid'] ) && empty( $_GET['rid'] ))
		{
			echo $error['id_missing'];
		}
		else
		{
			$TABLE = ( !empty( $_GET['tid'] )) ? 'phpdesk_tickets' : 'phpdesk_responses';
			$DL_ID = ( !empty( $_GET['tid'] )) ? $_GET['tid'] : $_GET['rid'];
			
			$DownQ = $db->query( "SELECT attach FROM ". $TABLE ." WHERE `id` = '". $DL_ID ."'" );
			$DownF = $db->fetch( $DownQ );
			
			header( "Location: " . $help_url . $Attach_dir . $DownF['attach'] );
		}

		break;
		
} // END SWITCH

?>