<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Staff Module
// >>
// >> STAFF . PHP File - Staff Interface Of HelpDesk
// >> Started : November 12, 2003
// >> Edited  : June 02, 2004
// << -------------------------------------------------------------------- >>

ob_start();

// LOGIN TYPE
$L_TYPE = 'staff';
include_once('common.php');

if( $NO_AUTH == 0 )
{
		$a_id = $_F['id'];
		$a_email = $_F['email'];
		$a_web = $_F['website'];
		$a_groups = $_F['groups'];
		$a_tppage = $_F['tppage'];
		if(ACT != 'livechat')
		{		
			_parse($tpl_dir.'staff_head.tpl');
			echo $class->read;
		}
		
		if($mailtype != 'None' && ACT == '' && function_exists('imap_open'))
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
				
		// If no action
		if(ACT == '')
		{

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
		
			// prepare permissions!
			if($_F['groups'] == 'ALL') {
				$allow_groups = 'ALL';
			} else {
				$allow_groups = explode( "|||", $_F['groups'] );
			}
		
			while( $GR = $db->fetch( $GRPQ ))
			{
				// staff have access to this group?
				if ($allow_groups != 'ALL') {
					if(!in_array($GR['name'], $allow_groups)) {
						continue;
					}
				}
		
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
			
			_parse( $tpl_dir . 'list.tpl' );
			$Top  = template( $class->read, NULL, $T_ST );
			$Read = template( $class->read, $T_ST.'m_announce', '/#m_announce]' );
			$List = template( $Read, $T_ST, $T_ED );
			$Read = template( $Read, NULL, $T_ST );			
			
			$ALQ  = $db->query( "SELECT title,added,expire,access,id FROM phpdesk_announce WHERE 
									`expire` >= UNIX_TIMESTAMP() OR `expire` = '0' ORDER 
									 by `added` DESC" );
			if( $db->num( $ALQ ))
			{
				echo $Top . $Read;
				while( $ALF = $db->fetch( $ALQ ))
				{
					$H++;
					
					$BG  = ( is_float( $H / 2 )) ? 'ticketbg' : 'ticketbg2';
					$ADD = exo_date( 'F d, Y', $ALF['added'] );
					$OUT = rpl( '^ID^', $ALF['id'], rpl( '^TITLE^', $ALF['title'], rpl( '^ADD^', $ADD, $List ) ));
					$OUT = rpl( '^td_bg^', $BG, $OUT );
					
					echo $OUT;
					
				}
			}
			
			echo '</table>';

			// PARSE VIEW TPl FILE To Get Online List
			_parse( $tpl_dir.'view.tpl' );
			$READ  = getBlock( $class->read, 'ONLINE' );
			$EVENT = getBlock( $class->read, 'UP_EVENTS' );
			echo $READ . $EVENT;
			
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
				{ echo $error['id_missing']; }
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
		elseif(ACT == 'profile')
		{
			if(SUBM == "")
			{
				$_Q = $db->query("SELECT * FROM phpdesk_staff WHERE id='{$a_id}' AND password='".PASS."'");
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
					{	$s = $z = $y = "";
						if($_F['notify_pm'] == $x)
						{
							$s = " selected";
						}
						if($_F['notify_response'] == $x)
						{
							$z = " selected";
						}
						if($_F['notify_ticket'] == $x)
						{
							$y = " selected";
						}						
						$n_pm.= str_replace('^s^', $s, $opt);
						$n_response.= str_replace('^s^', $z, $opt);
						$n_ticket.= str_replace('^s^', $y, $opt);
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
					$signature = $_F['signature'];
					
					_parse( $tpl_dir . 'profile.tpl' );
					$read = getBlock( $class->read, 'STAFF' );
					$read = str_replace('^n_pm^', $n_pm, str_replace('^n_response^', $n_response, $read));
					$read = str_replace('^n_ticket^', $n_ticket, $read);
					echo $read;
				}
			}
			else
			{
				$VALIDATE = validate('staff',$_POST,'profile');
				
				if($VALIDATE != "")
				{
					echo $VALIDATE;
				}
				else
				{
					if($_POST['password'] != '')
					{ 
						$claus = ",password='".md5($_POST['password'])."'";
					}
					$sql = $up_staff." name='".$_POST['name']."'".$claus.",email='".$_POST['email']."',
						website='".$_POST['website']."',notify_pm='".$_POST['n_pm']."',notify_response='".$_POST['n_response']."',
						notify_ticket='".$_POST['n_ticket']."', tppage = '".$_POST['tppage']."', `signature`='".$_POST['signature']."' 
						WHERE id='".$a_id."'";
						
					if($db->query($sql))
					{
						echo $success['p_update'];
					}

				}
			}
		}
		elseif(ACT == 'kb')
		{
			$KB->VIEW = 'staff';
			
			if(TYPE == '')
			{
				if(isset($_GET['group']))
				{
					$KB->GROUP = $_GET['group'];
				}
				echo $KB->kb_view();
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
					echo '[ <a href="'.SELF.'?action='.ACT.'?s='. $SID .'">No</a> ] <br />';
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
				    parent.window.tops.location.href = "<? echo SELF."?action=livechat&type=update&windowed=1&chat=1&cid=".$_GET['cid']."&s={$SID}"; ?>";
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
				<input type=\"text\" size=\"30\" name=\"send\"><input type=\"submit\" name=\"submit\" value=\"submit\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".SELF."?action=livechat&type=logout&cid=".$_GET['cid']."&user=".USER."&s={$SID}\">End Chat</a></form>";

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
						$db->query("INSERT INTO phpdesk_liveonline (ip,user,negotiated,timeout,utype)
						VALUES('".$_SERVER['REMOTE_ADDR']."', '".USER."', '".time()."', '".(time()+15)."', 'Staff')");					
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
		elseif(ACT == 'logout')
		{
			Dologout( $SID );
			header( MAIN . "?login=out");			
		}
}

// INCLUDE FOOTER FILE
include_once ( 'footer.php' );

// Flush all the headers
ob_end_flush();

?>