<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Language Module
// >>
// >> LANG_EN . PHP File - English Language File Of HelpDesk
// >> Started : November 11, 2003
// >> Edited  : July 04, 2004
// << -------------------------------------------------------------------- >>

// << -------------------------------------------------------------------- >>
// >> Error Variables - All errors produced are listed here. Except the
// >> ones that appear in the Admin CP.
// << -------------------------------------------------------------------- >>

$error['login_user']        =  'Either your username or password wasn\'t right. <br />';
$error['disabled']          =  'Sorry, but your account is disabled by the Administrator. <br />';
$error['no_tickets']        =  '<tr align="center"><td colspan="7">You have no Open tickets</td></tr>';
$error['no_ticketsclosed']  =  'You have no Closed tickets';
$error['fields']            =  'Please fill all the fields and re-submit the form. <br />';
$error['id_missing']        =  'No ID was associated with the url. An ID is required to proceed.<br />';
$error['no_auth_or_record'] =  'Either that record doesn\'t belongs to you or is deleted.<br />';
$error['no_user']           =  'No user exists with such username as "'.$sendto.'".<br />';
$error['pass_match']        =  'The submitted password didn\'t match. <br />';
$error['illegal_user']      =  'Username contained illegal characters, allowed are : a-z, 0-9 and _<br />';
$error['illegal_email']     =  'The email address you specified was not in a valid known format.<br />';
$error['user_exists']       =  'A user with that name already exists.<br />';
$error['no_staff']          =  'No staff is currently available for livechat.<br />';
$error['c_declined']        =  'Sorry, but your chat request was declined by a Staff. <br />';
$error['register_close']    =  'Registrations are closed.<br />';
$error['wrong_key']         =  'The key you specified was not right, please double check it. <br />';
$error['passm_ago']         =  'Your validation key has already been mailed to you. <br />';
$error['min_pass']          =  'The password cannot be shorter than 4 characters.<br />';
$error['tppage_zero']       =  'Tickets/Page must be greater than 0. <br />';
$error['no_group']          =  'At least 1 group must be selected. <br />';
$error['illega_group']      =  'Group name contained illegal characters, allowed are : a-z,0-9 and "_"<br />';
$error['kb_exists']         =  'A F.A.Q. already exists with that title. <br />';
$error['no_such_faq']       =  'No such F.A.Q. exists in the knowledge base. <br />';
$error['no_such_serv']      =  'No server exists with that ID. Please re-try. <br />';
$error['mem_serv']          =  'Members are not allowed to see the server status. <br />';
$error['rated_ago']			=  'You have already rated. <br />';
$error['child_exists']		=  'Cant Delete Parent While Childs/Sub-Childs Exists. <br />';
$error['check_one']			=  'Please check at least one checkbox to continue. <br />';
$error['attach_size']		=  'Attachment size can\'t be greater than '. ( $Max_Upload / 1024 ) .' KB.<br />';
$error['attach_type']		=  'The file type you are trying to upload is not in the allowed list.<br />';
$error['trouble_no_view'] 	=  'You need to register to view this troubleshooter.'
							   .'Please go to <a href="'.$help_url.'register.php">Here</a> to register.<br />';
$error['banned']			=  'You are banned from accessing this area. <br />';
$error['keyword']			=  'You didnt enter a valid keyword to search. <br />';
$error['query_failed']		=  'mySQL Query failed!, The query might not be supported by the toolbox. The '
							.  'Queries supported are: <br /> CREATE, INSERT, DELETE, UPDATE, ALTER <br />';
$error['incorrect_path']    =  'You have specified an incorrect location. <br />';
$error['path_writeable']	=  'The path you specified is not writeable. <br />';
$error['cal_month']			=  'Some error occured while generating the date. You might see this error if the day '
							.  'specified in the URL is wrong. <br />';
$error['no_kb_groups']      =  'No Knowledge Base groups exist. Please contact Administrator to create some!<br />';
							  
// << -------------------------------------------------------------------- >>
// >> Success Variables - All success messages produced are listed here. 
// >> Except some that appear in the Admin CP.
// << -------------------------------------------------------------------- >>

$success['logout_user']     =  'You are successfully logged out now. <br />';
$success['add_ticket']      =  'Ticket Added Successfully. <br />';
$success['edit_ticket']     =  'Ticket Edited Successfully. <br />';
$success['edit_staff']		=  'Staff Member Edited Successfully.<br />';
$success['add_response']    =  'Records Updated Successfully. <br />';
$success['close_ticket']    =  'Ticket Closed Successfully. <br />';
$success['open_ticket']     =  'Ticket Opened Successfully. <br />';
$success['sent_pm']         =  'Private Message sent Successfully. <br />';
$success['del_pm']          =  'Private Message deleted Successfully.<br />';
$success['p_update']        =  'Profile Updated Successfully.<br />';
$success['register']        =  'User Registeration Completed Successfully. <br />';
$success['running_chat']    =  'Your chat is running now.<br />';
$success['update_configs']  =  'Configurations updated successfully.<br />';
$success['pass_reset']      =  'Your password has been resetted successfully. Please <a href="'.$help_url.'^file^">Click Here</a> to login.<br />';
$success['added_kb']        =  'F.A.Q. added successfully. <br />';
$success['update_kb']       =  'F.A.Q. updated successfully. <br />';
$success['kb_deleted']      =  'The FAQ was deleted succesfully. <br />';
$success['add_edit_note']   =  'Note Added/Edited Successfully. <br />';
$success['del_note']        =  'Note deleted Successfully.<br />';
$success['suspend']         =  'User Suspended Successfully.<br />';
$success['unsuspend']       =  'User Un-Suspended Successfully.<br />';
$success['server']          =  'Server Record ^ACTED^ Successfully.<br />';
$success['save_response']   =  'Response Saved Successfully. <br />';
$success['del_saved']       =  'Saved Response Deleted Successfully. <br />';
$success['update_kbg']      =  'KB Group Records Updated Successfully. <br />';
$success['kbg_deleted']		=  'KB Group Deleted Successfully. <br />';
$success['rated']			=  'Rated Successfully. <br />';
$success['troubleshooter']	=  'TroubleShooter ^ACTED^ Successfully. <br />';
$success['del_troubles']	=  'TroubleShooter Deleted Successfully. <br />';
$success['update_diary']	=  'Diary Text updated Successfully. <br />';
$success['announce']		=  'Announcement Added/Edited Successfully. <br />';
$success['del_announce']	=  'Announcement Deleted Successfully. <br />';
$success['massmail_sent']	=  'Mass mail sent successfully. <br />';
$success['query_execute']	=  'mySQL Query was executed successfully : <br /> <i>^query^</i> <br />';
$success['saved_on_server'] =  'The file was saved on the server successfully as : <br />^file^<br />';
$success['cal_event']		=  'Event ^what^ successfully.<br />';

// << -------------------------------------------------------------------- >>
// >> General Variables - General Messages which dont fall in success or
// >> error category are listed below.
// << -------------------------------------------------------------------- >>

$general['no_tickets']        =  '<tr align="center"><td colspan="7">You have no Open tickets</td></tr>';
$general['no_ticketsclosed']  =  '<tr align="center"><td colspan="7">You have no Closed tickets</td></tr>';
$general['no_response']     = 'No responses associated with this ticket. <br />';
$general['close_confirm']   = 'Do you really want to close the Ticket?<br />';
$general['open_confirm']    = 'Do you really want to open the Ticket?<br />';
$general['p_edit_pass']     = 'Leave blank for the old one';
$general['mail_title']      = 'Welcome to '.$site_name;
$general['lostpass']        = 'Your Validation Key';
$success['assigned']        = 'The ticket has been assigned to you AKA ownership.<br />';
$success['assign_to']		= 'The ticket has been successfully assigned to ^TO^.<br />';
$general['new_pm']          = 'You have got a new pm at '.$site_name;
$general['new_response']    = 'New Response at '.$site_name;
$general['newticket']       = 'New Ticket at '.$site_name;
$general['waiting_staff']   = 'Waiting for a staff to respond.... <br />';
$general['chat_ended']      = 'The chat was ended. <br />';
$general['no_ports']        = 'NO PORTS FOR THIS SERVER. <br />';
$general['no_more_opt']		= '<tr><td colspan="2">No More Options Available. This troubleshooter was not able to help you. Please open a ticket. <br /></td></tr>';
$general['no_ticket_staff'] = '<tr class="tdbg1"><td width="100%" colspan="6">There are no open tickets available.</td></tr>';
$general['no_categories']   = '<tr><td colspan="2" align="center">No Categories exists.</td></tr>';
$general['kb_empty']        = '<tr><td align="center" colspan="3">Knowledge Base is empty.</td></tr>';
$general['no_pms']          = '<tr><td></td><td>You have no Private Messages at this time. </td></tr>';

$general['kb_noaccess']     = '<tr><td align="center" colspan="3">Knowledge Base consists of all F.A.Q. which can be only viewed by registered members.'
							  .'Please go to <a href="'.$help_url.'register.php">Here</a> to register.</td></tr>';

$general['close_open_url']  = '[ <a href="^URL^type='.$_GET['type'].'&confirm=YES&id='.$_GET['id'].'&s='. $SID .'">YES</a> ]'
						 	  .'[ <a href="javascript:history.back(0)">NO</a> ]<br />';

$general['del_pm_confirm']  = 'Do you really want to delete the Private Message?<br />'
							 .'[ <a href="'.SELF.'?action=pm&type=delete&id='.$_GET['id'].'&confirm=YES&s='. $SID .'">YES</a> ] '
							 .'[ <a href="'.SELF.'?action=pm&s='. $SID .'">NO</a> ]';

$general['desk_offline']    = '<table><tr><td height="22" class="tdup" background="tpl/Blue/images/bg_td.jpg">'
							. 'HelpDesk Offline</td></tr><td align="center">'. $off_reason .'</td></tr></table>';
							 
// << -------------------------------------------------------------------- >>
// >> Template Variables - Most of the required template variables are 
// >> listed below. Though there are still some left out but planned to
// >> be done in the next versions.
// >>  ----------------------------
// >> **NEW** - ADDED IN V0.5 Beta 1
// << -------------------------------------------------------------------- >>
							 
$tpl['title']        = 'Title';
$tpl['username']	 = 'Username';
$tpl['password'] 	 = 'Password';
$tpl['name'] 		 = 'Name';
$tpl['real_name']    = 'Real Name';
$tpl['email']        = 'Email';
$tpl['website']      = 'WebSite';
$tpl['groups']		 = 'Groups';
$tpl['confirm']		 = 'Confirm';
$tpl['owners'] 		 = ' Owners';
$tpl['owner']		 = 'Owner';
$tpl['ticket'] 		 = 'Ticket';
$tpl['signature']	 = 'Signature';
$tpl['tppage'] 		 = 'Tickets/Page';
$tpl['gname'] 		 = 'Group Name';
$tpl['department']   = 'Department';
$tpl['view']		 = 'View';
$tpl['details'] 	 = 'Details';
$tpl['field'] 		 = 'Field';
$tpl['note'] 		 = 'Note';
$tpl['priority'] 	 = 'Priority';
$tpl['medium'] 		 = 'Medium';
$tpl['high'] 		 = 'High';
$tpl['low'] 		 = 'Low';
$tpl['text'] 		 = 'Text';
$tpl['all'] 		 = 'All';
$tpl['t_create'] 	 = 'Create A Ticket';
$tpl['tickets'] 	 = 'Tickets';
$tpl['open']		 = 'Open';
$tpl['closed']		 = 'Closed';
$tpl['close']		 = 'Close';
$tpl['twsr']		 = 'Tickets Waiting Staff Response';
$tpl['count']		 = 'Count';
$tpl['members']		 = 'Members';
$tpl['staff']		 = 'Staff';
$tpl['nod']			 = 'No. Of Administrators';
$tpl['id']			 = 'ID';
$tpl['added'] 		 = 'Added';
$tpl['category']	 = 'Category';
$tpl['faqs']		 = 'F.A.Qs';
$tpl['ladd']		 = 'Last Add';
$tpl['faq']			 = 'F.A.Q.';
$tpl['poster']		 = 'Poster';
$tpl['responses']	 = 'Responses';
$tpl['actions']		 = 'Actions';
$tpl['registered']	 = 'Registered';
$tpl['full']		 = 'Full';
$tpl['type']		 = 'Type';
$tpl['lost']		 = 'Lost';
$tpl['register'] 	 = 'Register';
$tpl['login']		 = 'Login Now';
$tpl['pcym']		 = 'Please check your emails for the validation key.';
$tpl['val_key']		 = 'Vadidation Key';
$tpl['new']			 = 'New';
$tpl['reset']		 = 'Reset';
$tpl['smessage']	 = 'Send Message';
$tpl['messages']	 = 'Messages';
$tpl['message']		 = 'Message';
$tpl['from']		 = 'From';
$tpl['sent']		 = 'Sent';
$tpl['delete']		 = 'Delete';
$tpl['reply']		 = 'Reply';
$tpl['notify']		 = 'Notify';
$tpl['pm']			 = 'PM';
$tpl['response']	 = 'Response';
$tpl['clb']			 = 'Can leave blank';
$tpl['search']		 = 'Search';
$tpl['shead']		 = 'If you want to list all the tickets belonging to a particular user, then'
						.' please fill in the "Member Based" field (Staff Only). To search in ticket responses'
						.' as well, then please do check the "Response" checkbox.<br><br>'
						.'You are allowed to use wildcards (*) in your search keyword.<br>';
$tpl['sfhead']		 = 'You can use wildcards (*) to make your search flexible.<br>';
$tpl['keyword']		 = 'Keyword';
$tpl['created']		 = 'Created';
$tpl['day_ago']		 = 'Days Ago';
$tpl['any_date']	 = 'Any Date';
$tpl['again']		 = 'Again';
$tpl['waiting']		 = 'Waiting For';
$tpl['replies']		 = 'Replies';
$tpl['edit']		 = 'Edit';
$tpl['sendto']		 = 'Send To';
$tpl['sdate']		 = 'Start Date';
$tpl['status']		 = 'Status';
$tpl['assigned']	 = 'Assigned';
$tpl['assign']		 = 'Assign To';
$tpl['s_u_r']		 = 'Staff/User Responses';
$tpl['font_gr']		 = "<font color='green'>";
$tpl['font_rd']		 = "<font color='red'>";
$tpl['font_bk']		 = "<font color='black'>";
$tpl['font_en']		 = '</font>';
$tpl['news']		 = 'Server News';
$tpl['ip']			 = 'IP Address';
$tpl['server_h']	 = 'Please fill in the information required below, News field is optional.'
					  .'You can specify anything as the Server Name, its just for ease of identification.<br><br>';
$tpl['saved_rt']	 = 'Please fill in all the information required below. <br>This will let you save responses '
					  .'that can be used for future. <br><br>';
$tpl['rate_t']		 = 'Please select a number from 0 - 5';
$tpl['server_m']	 = 'SERVICES PORTS<br>Fill in the ports below, set to 0 for no lookup for the port.<br>';
$tpl['web_service']	 = 'Web Service';
$tpl['mysql_service']= 'MySQL Service';
$tpl['ftp_service']  = 'FTP Service';
$tpl['pop3_service'] = 'POP3 Service';
$tpl['smtp_service'] = 'SMTP Service';
$tpl['imap_service'] = 'IMAP Service';
$tpl['telnet_service']= 'TelNet Service';
$tpl['ssh_service']  = 'SSH Service';
$tpl['s_offline']    = 'Service <b>Offline</b>';
$tpl['s_online']	 = 'Service Online';
$tpl['serv_down']	 = 'It looks like one of the service is down, please open a new ticket.<br />';
$tpl['opened']       = 'Opened';
$tpl['saved_r']		 = 'Saved Response';
$tpl['staff_kbg']    = '<font size="1">Check this to allow staff access to this group.</font>';
$tpl['allowed']		 = 'Allowed';
$tpl['disallowed']   = 'Disallowed';
$tpl['rate']		 = 'Rating';
$tpl['none_yet']	 = 'None Yet';
$tpl['out_five']	 = ' / 5.00';
$tpl['rate_now']	 = 'Rate Now';
$tpl['rate_staff']   = 'Rate This Staff';
$tpl['t_shooter']	 = 'Trouble Shooters';
$tpl['tshoot_t']	 = 'Please click on the most appropriate troubleshooter below. If you can\'t find the troubleshooter '
					  .'you are looking for, then please open a Trouble Ticket in the HelpDesk. <br /><br />';
$tpl['resources']	 = 'Additional Resources';
$tpl['fill_info']	 = 'Please fill in the fields below. Fields named in Italics can be left blank.<br />';
$tpl['line_conv']	 = 'All line breaks will be converted to &lt;br&gt;.';
$tpl['trail_end']	 = 'All the Directories and URLs must have a slash at end.';
$tpl['attach']		 = 'Attachment';
$tpl['none']		 = 'None';
$tpl['expire']		 = 'Expire';
$tpl['rtime']		 = 'Av. Reply Time';

/* User Registration TPL Variables */
$tpl['reg_user']	 = '<font size="1">Type in your desired username</font>';
$tpl['reg_pass']	 = '<font size="1">Min. 4 Chars.</font>';
$tpl['reg_confirm']	 = '<font size="1">Re-Type Password.</font>';
$tpl['reg_rname']	 = '<font size="1">Your Real Name.</font>';
$tpl['reg_email']	 = '<font size="1">Enter a valid email here.</font>';
$tpl['reg_tppage']	 = '<font size="1">Viewable Tickets/Page in number.</font>';
$tpl['reg_notipm']	 = '<font size="1">Email notification for new pm?</font>';
$tpl['reg_notirp']	 = '<font size="1">Email notification for new response?</font>';

// calender tpl
$tpl['cal_border']	 = "style='border:2px solid black'";
$tpl['caleventfont'] = '<font style="font-size: 10px">';
$tpl['no_up_event']  = 'No UpComing Events!';
$tpl['date']		 = 'Date';

// << -------------------------------------------------------------------- >>
// >> Other Variables - Any which dont fall in the above categories.
// >> Though these are available here, they are not meant to be edited
// >> unless you know what you are doing. If you ever change the [# or /#]
// >> in a tpl file, you must edit these.
// >>
// >>  CHANGES NOT RECOMMENDED
// << -------------------------------------------------------------------- >>

// Simple Vars
$T_ST = '[#';
$T_ED = '/#]';

// Block Vars
$B_ST = '<!-';
$B_ED = '-!>';
$B_SS = '</!-';

?>