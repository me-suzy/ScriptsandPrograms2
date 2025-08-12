<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk IMAP Module
// >>
// >> IMAP . PHP File - Mail Fetching for HelpDesk
// >> Started : November 21, 2003
// >> Edited  : February 22, 2004
// << -------------------------------------------------------------------- >>

class IMAP
{

	// -- START VARIABLES -- //
	var $MB_EXTYPE;
	var $MAIL_USER;
	var $MAIL_PASS;
	var $MB_SRHOST;
	// -- END OF VARIABLES -- //
	
	/*********************************************************************
	*  GET_MAIL()
	*  First connects to the IMAP/POP3 Mail server, looks for mail if
	*  found converts it to tickets. Looks for ([Ticket ID: *]) in the
	*  title to see if its a reply to a pre-existing ticket.
	*  
	*  Then it inserts the ticket to the database and that's all.'
	*  @access public
	*********************************************************************/
	function get_mail()
	{
		
		global $db,$sel_ticket,$sel_admin,$sel_staff,$sel_mem,$ins_ticket,$Allowed_Ext,$Max_Upload,$Attach_dir,$Attach_pre;
		
		// --------------------------------- //
		
		$MB_ADDRS = ( $this->MB_EXTYPE == 'IMAP' ) ? '{'.$this->MB_SRHOST.':143}INBOX' : '{'.$this->MB_SRHOST.':110/pop3}INBOX';
		$MB = imap_open ( $MB_ADDRS, $this->MAIL_USER, $this->MAIL_PASS );	

		if(!$MB)
		{
			return imap_last_error();
		}

		$MAILS = imap_headers ( $MB );
		if( count($MAILS) != 0 )
		{

			if ( $MAILS == false )
			{
				return "NO_NEW";
			}

			
			foreach( $MAILS as $MAIL )
			{	
						
				$TICKETID = NULL;
				$X++;
				
				// GET THE MAIL INFO
				$HEAD    = imap_header($MB, $X);
				$FROM    = $HEAD->from[0];
				
				// GET SOME REQUIRED VARS
				$EMAIL   = $FROM->mailbox ."@". $FROM->host;
				$NAME    = $FROM->personal;
				$SUBJECT = $HEAD->Subject;
				$DATE    = (($HEAD->udate)>time()) ? time() : $HEAD->udate;
				$STRUCT  = imap_fetchstructure($MB, $X);

				// FIND OUT IF MULTIPARTs
				if( count($STRUCT->parts) > 1 )
				{	
					$Y = 0;
					while($Y < count($STRUCT->parts))
					{
						if( $STRUCT->parts[$Y]->type == 0 && $STRUCT->parts[$Y]->ifdisposition == FALSE )
						{
							// MESSAGE BODY
							$MESS = imap_fetchbody($MB, $X, ($Y+1));
						}
						// if this is an attachment!
						elseif( $STRUCT->parts[$Y]->ifdisposition == TRUE AND $STRUCT->parts[$Y]->ifdparameters == TRUE )
						{

							// first check for the size!
							if( $STRUCT->parts[$Y]->bytes > $Max_Upload )
							{
								continue;
							}
							
							// get file name from paramters!
							$dparam = $STRUCT->parts[$Y]->dparameters;
							for( $i = 0; $i <= count($dparam); $i++ )
							{
								if( stristr( $dparam[$i]->attribute, 'filename'))
								{
									$attachment = $dparam[$i]->value;
								}
							}
							
							// Find out whether this type of attachment is allowed or not!
							$Allowed = explode( ",", $Allowed_Ext );
							while( list( , $Type ) = each( $Allowed ) )
							{
								$Type = preg_replace( '/\s/', NULL, $Type );
								if( preg_match( '/(.+?)'. $Type .'$/i', $attachment ))
								{
									$valid = 1;
									break;
								}
								else
									$valid = 0;
							}
							
							// if its an allwed extension, collect the data..
							if( $valid == 1 )
							{
								$file_data = imap_fetchbody( $MB, $X, ($Y+1));
								if( $STRUCT->parts[$Y]->encoding == 3 )
								{
									$file_data = base64_decode( $file_data );
								}								
							}
							else
								$attachment = null;
						}
						$Y++;						
					}
				}
				else
				{					
					// MESSAGE BODY
					$MESS = imap_fetchbody($MB, $X, 1);
				}
				
				// CHECK TO SEE IF A TICKET ID EXISTS, IN SUBJECT
				if( preg_match("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", $SUBJECT, $MATCHES) )
				{
					$TICKETID = trim(str_replace('([','',str_replace('])','',str_replace('Ticket ID:','',$MATCHES[0]))));
					$SUBJECT  = preg_replace("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", '', $SUBJECT);
					$MESS     = preg_replace("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", '', $MESS);					
				}

				// CHECK TO SEE IF A TICKET ID EXISTS, IN BODY
				if( empty($TICKETID) && preg_match("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", $MESS, $MATCHES) )
				{
					$TICKETID = trim(str_replace('([','',str_replace('])','',str_replace('Ticket ID:','',$MATCHES[0]))));
					$MESS     = preg_replace("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", '', $MESS);
				}
				
				$TID_CHECK    = $db->query($sel_ticket." WHERE `id`='".$TICKETID."'");
				$FETCH_TID    = $db->fetch($TID_CHECK);
				
				// ADDSLASHES
				$MESS = addslashes( $MESS );
				$SUBJECT = addslashes( $SUBJECT );
				
				// find out a user with the incoming email address!
				$UQ      = $db->query ( $sel_mem . " WHERE `email` = '" . $EMAIL . "'" );
				$UF      = $db->fetch ( $UQ );
					
				// set the ticket owner ..
				$T_OWNER = ( $db->num( $UQ ) ) ? $UF['username'] : 'Guest';
				
				if( $db->num($TID_CHECK) )
				{
					$EMAIL_C  = $db->query($sel_ticket." WHERE `id`='".$TICKETID."' AND `admin_email`='".$EMAIL."'");
				}
				
				if( $db->num($TID_CHECK) && $db->num($EMAIL_C) )
				{
					imap_delete($MB, $X);
					response($TICKETID, $T_OWNER, 'Subject: '.$SUBJECT."\n".$MESS, 1, 0, 0, 0);
				}
				else
				{
					if( empty($SUBJECT) )
					{
						$SUBJECT = 'NO SUBJECT';
					}
					
					// If we have an attachment file over here!
					if( $attachment != null )
					{
						if( file_exists($Attach_dir . $attachment))
						{ // start file existance check
						
							$st = 0;
							while( $st != 1 )
							{
								$x++;
								if( !file_exists( $Attach_pre . $x . $attachment ))
								{
									$st = 1;
									$Attachment = $Attach_pre . $x . $attachment;
								}
							}
						} // end file existance check!
						else
						{
							$Attachment = $attachment;
						}
						
						// write file data to the attachment file!
						$fp = @fopen( $Attach_dir . $Attachment, 'w' );
						@fwrite( $fp, $file_data );
						fclose( $fp );
					}
					
					// owner id!
					$OID     = ( $db->num( $UQ ) ) ? $UF['id'] : '0';

					// PREPARE SQL
					$SQL = "'$OID', '$T_OWNER', '$EMAIL', '$DATE', '$SUBJECT', 'EMAIL', '$MESS', '2', '$DATE', 'Open', 'Staff', '', '','$Attachment'";
					$INSTICKET = str_replace('`admin_user`,', '`admin_user`,`admin_email`,', $ins_ticket);
	
					// Notify All Staff About New Ticket
					mail_all_staff ( 'EMAIL', $T_OWNER, $MESS, $SUBJECT );
					
					// delete the mail to prevent infinite loops!
					imap_delete($MB, $X);
					$db->query(str_replace('^sql^', $SQL, $INSTICKET));
					
					// open tickets counter.
					$open_tickets++;
					$total_tickets++;
					
				}

			} // -- END foreach LOOP -- //
			
			// update the total and open tickets counter..
			$db->query("UPDATE `phpdesk_groups` SET open_tickets = open_tickets + $open_tickets, 
							total_tickets = total_tickets + $total_tickets WHERE `name` = 'EMAIL'");
			
			imap_expunge($MB);
			imap_close($MB);	
		
		} // --  END COUNT MAIL -- //

	} // --  END OF FUNCTION -- //

} // -- END OF CLASS -- //


?>