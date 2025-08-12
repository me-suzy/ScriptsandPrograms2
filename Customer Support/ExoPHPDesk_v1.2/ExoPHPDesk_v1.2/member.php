<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk User Module
// >>
// >> INDEX . PHP File - User Interface Of HelpDesk
// >> Started : November 12, 2003
// >> Edited  : June 02, 2004
// << -------------------------------------------------------------------- >>

ob_start();

// LOGIN TYPE
$L_TYPE = 'members';

// Required Files
include_once( 'common.php' );

if( $NO_AUTH == 0 )
{

		// Some Variables For User Such
		// As Email, Website etc..
		$a_id = $_F['id'];
		$a_email = $_F['email'];
		$a_web = $_F['website'];
		
		// Parse Member Head File
		_parse( $tpl_dir . 'member_head.tpl' );
		
		if( ACT != 'livechat' )
		{
			echo $class->read;
		}
		else
		{
			$MEM_HEAD = $class->read;
		}

		// If no action
		if( ACT == '' )
		{
			// Find out what user requested to see
			if( $_GET['view'] == 'Closed' )
			{
				$view = " AND `status` = 'Closed'";
			}
			elseif( $_GET['view'] == '' )
			{
				$view = " AND `status`<>'Closed'";
			}
			
			// Parse ticket tpl file
			_parse($tpl_dir.'tickets.tpl');
			$read = getBlock( $class->read, 'MEM_TICKET' );
			$list = substr($read, strpos($read, '[#')+2);
			$list = substr($list, 0, strpos($list, '/#]'));
			$end  = substr($read, strpos($read, '/#]')+3);
			$read = substr($read, 0, strpos($read, '[#'));
			$tppage = $_F['tppage'];
			
			// Check Page No. In URL
			if(!isset( $_GET['page'] ))
			{
				$start = 0;
				$finish = $_F['tppage'];
			}
			else
			{
				$start = $_GET['last'];
				$finish = $_F['tppage'] * $_GET['page'];
			}
			
			// SQL Queries Used Here
			$Q1 = $db->query($sel_ticket." WHERE admin_user = '".USER."' AND `status`<>'Closed'");
			$Q2 = $db->query($sel_ticket." WHERE admin_user = '".USER."' AND `status`<>'Open'");
			$_Q = $db->query($sel_ticket." WHERE admin_user = '".USER."'{$view} ORDER by `priority`,`update` DESC,`waiting`");
			
			$t_count = $db->num($_Q);
			$c1 = $db->num($Q1);
			$c2 = $db->num($Q2);

			$read = rpl('^t_closed^', $c2, rpl('^t_open^', $c1, $read));
			$read = rpl("^top^", 'trmain', $read);
			echo $read;

			if( !$db->num( $_Q ) )
			{
				echo $general['no_tickets' . strtolower($_GET['view'])];
			}
			
			// WHILE RECORDS EXISTS
			while( $_F = $db->fetch( $_Q ) )
			{
				$x++;
				// DO THE PAGES TRICK
				if($x >= $start && $x <= $finish)
				{
				
				// GET BACKGROUND COLOR
				$bg = ( is_float( $x / 2 ) ) ? 'tdbg1' : 'tdbg2';
				
				// Set color according the priority
				if( $_F['priority'] == '1' )
				{
					$color = "<font color='red'>".$_F['title']."</font>";
				}
				elseif( $_F['priority'] == '2' )
				{
					$color = "<font color='green'>".$_F['title']."</font>";
				}
				elseif( $_F['priority'] == '3' )
				{
					$color = "<font color='black'>".$_F['title']."</font>";
				}
				
				// Assign the right close/open action
				if( $_F['status'] == 'Closed' )
				{
				
					$list = rpl( '^closeoropen^', $tpl['open'], $list );
				
				}
				else
				{
				
					$list = rpl( '^closeoropen^', $tpl['close'], $list );
				
				}
				
				// Find How much time has passed since the ticket was created.
				$opened = opened( $_F['opened'] );
				
				$replies = $_F['replies'];
				
				// Do the required replacing in the template and.. move
				if($_F['waiting'] == 'Member')
				{
					$_F['waiting'] = '<font color="red">'.$_F['waiting'].'</font>';
				}
				
				// REPLACING REQUIRED TEXT/LINKS
				$out = rpl('^tdbg^', $bg, rpl('^id^', $_F['id'], rpl('^title^', $color, $list)));
				$out = rpl('^replies^', $replies, rpl('^department^', $_F['group'], $out));
				$out = rpl('^status^', $_F['waiting'], rpl('^opened^', $opened, $out));
				$out = rpl('^del^', '', $out);
				
				// SET VARS TO NULL
				$no_temp = $opened = "";
				
				// PRINT OUT
				echo $out;
				
				}
			}
			
			// GET PAGES COUNT AND LINKS
			if($t_count > $tppage)
			{	$pages = "";
				$do_it = ceil(($t_count / $tppage));
				$x = 1;
				while($x <= $do_it)
				{
					$x++;
					$_GET['last'] = ($tppage * ($x-2)) + 1;
					$pages .=' [ <a href="'.$_SERVER['PHP_SELF'].'?page='.($x-1).'&view='.$_GET['view'].'&last='.$_GET['last'].'&s='. $SID .'">'.($x-1).'</a> ]';
				}
			}
			$end = rpl('^pages^', $pages, $end);
			echo $end;
			
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
					if( $ALF['access'] != 'All' )
					{
						CONTINUE;
					}
					
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
			_parse( $tpl_dir . 'pm.tpl' );
			$read = getBlock( $class->read, 'LIST' );
			$list = substr($read, strpos($read, '[#')+2);
			$list = substr($list, 0, strpos($list, '/#]'));
			$end  = substr($read, strpos($read, '/#]') + 3);
			$read = substr($read, 0, strpos($read, '[#'));
			
			$_Q = $db->query($sel_pm." WHERE sentfor = '".USER."'");
			$Q1 = $db->query($sel_pm." WHERE `sentfor` = '".USER."' AND `read`<>'1'");
			if(!$db->num($_Q))
			{
				echo $general['no_pms'];
			}
			
			$pms = $db->num($_Q)." ( Unread: <i>".$db->num($Q1)."</i> )";
			echo rpl('^pms^', $pms, $read);
			
			if( TYPE == NULL )
			{
				echo pm(USER);
				echo $end;
			}
			elseif( TYPE == 'view' )
			{
				if( !isset( $_GET['id'] ))
				{
					echo $error['id_missing'];
				}
				else
				{
					echo pm( USER,'view',$_GET['id'] );
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
					if( SUBM == NULL )
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
						// Prepare A List Of Admins, Staff 
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
											
						_parse( $tpl_dir.'pm.tpl' );
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
				$_Q = $db->query($sel_mem." WHERE id='{$a_id}' AND password='".PASS."'");
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
					{	$s = $z = "";
						if($_F['notify_pm'] == $x)
						{
							$s = " selected";
						}
						if($_F['notify_response'] == $x)
						{
							$z = " selected";
						}
						$n_pm.= rpl('^s^', $s, $opt);
						$n_response.= rpl('^s^', $z, $opt);
						$x++;
					}

					// Required Variables
					$m_user = $_F['username'];
					$m_name = $_F['name'];
					$m_email = $_F['email'];
					$m_website = $_F['website'];
					$m_edit_print = $general['p_edit_pass'];
					$m_tppage = $_F['tppage'];
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
					echo rpl( '^FIELDS^', $FIELDS, $read );
				}
			}
			else
			{
				$VALIDATE = validate('user',$_POST,'edit');
				$valFields = validate('fields', $_POST, 'Profile');
		
				if( $valFields == 1 )
				{
					echo $error['fields'];
				}
				elseif( $VALIDATE != "" )
				{
					echo $VALIDATE;
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
					
					// SQL Query
					$sql = $up_mem." name='".$_POST['name']."'".$claus.",email='".$_POST['email']."',
						website='".$_POST['website']."',notify_pm='".$_POST['n_pm']."',notify_response='".$_POST['n_response']."',
						tppage='".$_POST['tppage']."', `FIELDS` = '$FIELDS', `VALUES` = '$VALUES' WHERE id='".$a_id."'";
						
					if($db->query($sql))
					{
						echo $success['p_update'];
					}

				}
			}
		}
		elseif(ACT == 'kb')
		{
			$KB->VIEW = 'member';
			
			if(TYPE == '')
			{
				if(isset($_GET['group']))
				{
					$KB->GROUP = $_GET['group'];
					echo $KB->kb_view();
				}
				else
				{
					echo $KB->kb_list_group();
				}
			}
			elseif(TYPE == 'view')
			{
				$KB->ID = $_GET['id'];
				echo $KB->kb_view_in();
			}		
		}
		elseif(ACT == 'livechat')
		{
			if(TYPE == 'type')
			{
				echo "<link rel=\"stylesheet\" href=\"{$tpl_dir}style.css\" type=\"text/css\">";			
				echo "<form method=\"post\" action='".$_SERVER['PHP_SELF']."?action=livechat&type=type&cid=".$_GET['cid']."&s={$SID}' name='send_chat'>
				Chat : <input type=\"text\" size=\"40\" name=\"send\">&nbsp;<input type=\"submit\" name=\"submit\" value=\"submit\">&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?action=livechat&type=logout&cid=".$_GET['cid']."&user=".USER."&s={$SID}\">End Chat</a></form>";

				?>
				<script>
					function addmsg()
					{
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
					echo "<body onLoad=\"addmsg()\">";
					if(!empty($_POST['send']))
					{
						$fp = fopen($chat_dir.$_GET['cid'].'.txt', 'a');
						fwrite($fp, "<b>".exo_date('h:i:s', time())." - ".USER." Says:</b>  {$_POST['send']}"."\n");
						fclose($fp);
					
						$fp = fopen($chat_dir.'last_'.$_GET['cid'].'.txt', 'w');
						fwrite($fp, "<b>".exo_date('h:i:s', time())." - ".USER." Says:</b>  {$_POST['send']}");
						fclose($fp);
					}
			}
			elseif(TYPE == 'down')
			{
				echo "<body onunload=\"window.open('".$_SERVER['PHP_SELF']."?action=livechat&type=logout&cid=".$_GET['cid']."&user=".USER."&s={$SID}','exit','toolbar=no,status=no,scrollbars=no,location=no,menubar=no,directories=no,width=1,height=1');\">";
			}
			elseif(TYPE == 'get_messages')
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
				if($_GET['why']=='declined')
				{
					echo $error['c_declined'];
				}
				elseif($_GET['why']=='running')
				{
					echo $success['running_chat'];
				}
				else
				{
					echo $general['chat_ended'];
				}
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
				    parent.window.tops.location.href = "<? echo $_SERVER['PHP_SELF']."?action=livechat&type=update&chat=1&windowed=1&cid=".$_GET['cid']."&s={$SID}"; ?>";
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
				$db->query($up_lonline." timeout = '".((time())+30)."' WHERE user='".USER."'");
				$db->query("DELETE FROM phpdesk_liveonline WHERE timeout<".time()."");					
				
				$_Q = $db->query($sel_lchat." WHERE chatid='".$_GET['cid']."'");
				$_F = $db->fetch($_Q);
				$Q = $db->query($sel_lonline." WHERE user='".$_F['chatter']."'");
				
				if(!$db->num($Q) || $_F['status']=='Ended')
				{
					echo "<script>parent.location.href = '".$_SERVER['PHP_SELF']."?action=livechat&type=ended&s={$SID}';</script>";
				}

				$fp = fopen($chat_dir.'last_'.$_GET['cid'].'.txt', 'r');
				$last = fread($fp, filesize($chat_dir.'last_'.$_GET['cid'].'.txt'));
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

					$explode = rpl($last, '', substr($read,$pos));
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
					$fp = fopen($chat_dir.'last_'.$_GET['cid'].'.txt', 'w');
					fwrite($fp, $tmp);
					fclose($fp);
					echo "<script>update(\"".$__all."\")</script>";
					}
				}				
	
			}
			else
			{
				if($_GET['windowed'] != 1)
				{
					echo "<script>window.open('".$_SERVER['PHP_SELF']."?action=livechat&windowed=1&s={$SID}', 'ChatWindow', 'resizable,height=".$mwin_height.",width=".$mwin_width."');</script>";				
					echo "<script>parent.window.location.href=\"".$_SERVER['PHP_SELF']."?s={$SID}\";</script>";
				}
				if($_GET['chat'] != 1)
				{
					$Q = $db->query($sel_lonline." WHERE utype='Staff'");
					if(!$db->num($Q))
					{
						echo $error['no_staff'];
					}
					else
					{
						$db->query("DELETE FROM phpdesk_liveonline WHERE timeout<".time()."");
						if(!isset($_GET['cid']))
						{
							$Q = $db->query($sel_lchat);
							$num = $db->num($Q);
							$Q2 = $db->query($sel_lchat." WHERE starter='".USER."' AND status='waiting'");
							if(!$db->num($Q2))
							{
								$db->query($ins_lchat."'".($num+2)."','".(time()+5)."','".USER."','waiting', '".time()."')");
								$cid = ($num+2);
							}
							else
							{
								$F2 = $db->fetch($Q2);
								$cid = $F2['chatid'];
							}

						}
						if(empty($cid)) { $cid = $_GET['cid']; }
						?>

						<script language="JavaScript">
						<!--
	
						function doLoads()
						{
						    setTimeout( "refresh()", 1*1000 );
						}
	
						function refresh()
						{
						    parent.location.href = "<? echo $_SERVER['PHP_SELF']."?action=livechat&chat=0&windowed=1&cid=".$cid."&s={$SID}"; ?>";	
						}
						//-->
						</script>

						<?
						
						_parse($tpl_dir.'chat_head_frame.tpl');
						echo $class->read;
						
						echo "<body onLoad=\"doLoads()\">";
						$Q = $db->query($sel_lonline." WHERE user='".USER."'");
						if(!$db->num($Q))
						{
							$db->query($ins_lonline."'".$_SERVER['REMOTE_ADDR']."', '".USER."', '".time()."', '".(time()+30)."', 'Member')");
						}						
						
						$db->query($up_lchat." timeout='".(time()+20)."' WHERE chatid='".$cid."'");
						$db->query($up_lonline." timeout='".(time()+30)."' WHERE user='".USER."'");
						$_Q = $db->query($sel_lchat." WHERE chatid='".$cid."'");
						$_F = $db->fetch($_Q);

						echo $general['waiting_staff'];
						
						if($_F['status'] == 'Running')
						{
							header("Location: ".$_SERVER['PHP_SELF']."?action=livechat&windowed=1&chat=1&cid=". $cid ."&s={$SID}");
						}
						elseif($_F['status'] == 'Declined')
						{
							echo "<script>parent.location.href = '".$_SERVER['PHP_SELF']."?action=livechat&type=ended&why=declined&s={$SID}';</script>";
						}
					}
				}
				else
				{			
					$db->query($up_lchat." `status`='Ended' WHERE timeout<".time()."");
					$db->query("DELETE FROM phpdesk_liveonline WHERE timeout<'".time()."'");
				
					$num = ($_GET['cid']);
				
					$fp = fopen($chat_dir.'last_'.$num.'.txt', 'a');
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