<?php

/*
+--------------------------------------------------------------------------
|   Invision Board v1.1
|   ========================================
|   by Matthew Mecham
|   (c) 2001,2002 Invision Power Services
|   http://www.ibforums.com
|   ========================================
|   Web: http://www.ibforums.com
|   Email: phpboards@ibforums.com
|   Licence Info: phpib-licence@ibforums.com
+---------------------------------------------------------------------------
|
|   > Admin Forum functions
|   > Module written by Matt Mecham
|   > Date started: 17th March 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/




$idx = new ad_groups();


class ad_groups {

	var $base_url;

	function ad_groups() {
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

		switch($IN['code'])
		{
			case 'doadd':
				$this->save_group('add');
				break;
				
			case 'add':
				$this->group_form('add');
				break;
				
			case 'edit':
				$this->group_form('edit');
				break;
			
			case 'doedit':
				$this->save_group('edit');
				break;
			
			case 'delete':
				$this->delete_form();
				break;
			
			case 'dodelete':
				$this->do_delete();
				break;
				
			case 'fedit':
				$this->forum_perms();
				break;
				
			case 'dofedit':
				$this->do_forum_perms();
				break;
						
			default:
				$this->main_screen();
				break;
		}
		
	}
	
	
	//+---------------------------------------------------------------------------------
	//
	// Delete a group
	//
	//+---------------------------------------------------------------------------------
	
	function forum_perms()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("Could not resolve the group ID, please try again");
		}
		
		//+----------------------------------
		
		$ADMIN->page_title = "User Group Forum Access Permissions";
		
		$ADMIN->page_detail = "This is a quick and convenient way to organise forum access for this member group. If you wish to change multiple group permissions, then you may wish to do so from forum management.<br><br>Simply check the box in the start column if you wish to allow that member group to start new topics in that forum, etc.<br><b>Global</b> indictates that the forum is set to allow all member groups to carry out that action. If you wish to change this, do so from forum control.";
		
		//+----------------------------------
		
		$DB->query("SELECT g_title, g_id FROM ibf_groups WHERE g_id='".$IN['id']."'");
		$group = $DB->fetch_row();
		
		$gid = $group['g_id'];
		
		//+-------------------------------
		
		$cats     = array();
		$forums   = array();
		$children = array();
		
		$DB->query("SELECT * from ibf_categories WHERE id > 0 ORDER BY position ASC");
		while ($r = $DB->fetch_row())
		{
			$cats[$r['id']] = $r;
		}
		
		$DB->query("SELECT * from ibf_forums ORDER BY position ASC");
		while ($r = $DB->fetch_row())
		{
			
			if ($r['parent_id'] > 0)
			{
				$children[ $r['parent_id'] ][] = $r;
			}
			else
			{
				$forums[] = $r;
			}
			
		}
		
		//+----------------------------------
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'dofedit' ),
												  2 => array( 'act'   , 'group'   ),
												  3 => array( 'id'    , $gid      ),
									     )      );
		
		$SKIN->td_header[] = array( "Forum Name"   , "20%" );
		$SKIN->td_header[] = array( "Posts"        , "10%" );
		$SKIN->td_header[] = array( "Topics"       , "10%" );
		$SKIN->td_header[] = array( "Start"        , "15%" );
		$SKIN->td_header[] = array( "Reply"        , "15%" );
		$SKIN->td_header[] = array( "Read"         , "15%" );
		$SKIN->td_header[] = array( "Upload"       , "15%" );
		
		$ADMIN->html .= $SKIN->start_table( "Forum Access for ".$group['g_title'] );
		
		$last_cat_id = -1;
		
		foreach ($cats as $c)
		{
			
			$ADMIN->html .= $SKIN->add_td_basic( $c['name'], 'center', 'catrow' );
													   
			$last_cat_id = $c['id'];
			
			
			foreach($forums as $r)
			{	
			
				if ($r['category'] == $last_cat_id)
				{
				
					$read   = "";
					$start  = "";
					$reply  = "";
					$upload = "";
					$global = '<center><i>Global</i></center>';
					
					if ($r['read_perms'] == '*')
					{
						$read = $global;
					}
					else if ( preg_match( "/(^|,)".$gid."(,|$)/", $r['read_perms'] ) )
					{
						$read = "<center><input type='checkbox' name='read_".$r['id']."' value='1' checked></center>";
					}
					else
					{
						$read = "<center><input type='checkbox' name='read_".$r['id']."' value='1'></center>";
					}
					
					//---------------------------
					
					if ($r['start_perms'] == '*')
					{
						$start = $global;
					}
					else if ( preg_match( "/(^|,)".$gid."(,|$)/", $r['start_perms'] ) )
					{
						$start = "<center><input type='checkbox' name='start_".$r['id']."' value='1' checked></center>";
					}
					else
					{
						$start = "<center><input type='checkbox' name='start_".$r['id']."' value='1'></center>";
					}
					
					//---------------------------
					
					if ($r['reply_perms'] == '*')
					{
						$reply = $global;
					}
					else if ( preg_match( "/(^|,)".$gid."(,|$)/", $r['reply_perms'] ) )
					{
						$reply = "<center><input type='checkbox' name='reply_".$r['id']."' value='1' checked></center>";
					}
					else
					{
						$reply = "<center><input type='checkbox' name='reply_".$r['id']."' value='1'></center>";
					}
					
					//---------------------------
					
					if ($r['upload_perms'] == '*')
					{
						$upload = $global;
					}
					else if ( preg_match( "/(^|,)".$gid."(,|$)/", $r['upload_perms'] ) )
					{
						$upload = "<center><input type='checkbox' name='upload_".$r['id']."' value='1' checked></center>";
					}
					else
					{
						$upload = "<center><input type='checkbox' name='upload_".$r['id']."' value='1'></center>";
					}
					
					//---------------------------
					
					if ($r['subwrap'] == 1)
					{
						$ADMIN->html .= $SKIN->add_td_basic( "&gt; ".$r['name'], 'left', 'catrow2' );
					}
					else
					{
						$ADMIN->html .= $SKIN->add_td_row( array(
															   "<b>".$r['name']."</b><br>".$r['description'],
															   "<center>".$r['posts']."</center>",
															   "<center>".$r['topics']."</center>",
															   $start,
															   $reply,
															   $read,
															   $upload
													 )      );
					}
													 
					if ( ( isset($children[ $r['id'] ]) ) and ( count ($children[ $r['id'] ]) > 0 ) )
					{
						foreach($children[ $r['id'] ] as $idx => $rd)
						{
							$read   = "";
							$start  = "";
							$reply  = "";
							$upload = "";
							$global = '<center><i>Global</i></center>';
							
							if ($rd['read_perms'] == '*')
							{
								$read = $global;
							}
							else if ( preg_match( "/(^|,)".$gid."(,|$)/", $rd['read_perms'] ) )
							{
								$read = "<center><input type='checkbox' name='read_".$rd['id']."' value='1' checked></center>";
							}
							else
							{
								$read = "<center><input type='checkbox' name='read_".$rd['id']."' value='1'></center>";
							}
							
							//---------------------------
							
							if ($rd['start_perms'] == '*')
							{
								$start = $global;
							}
							else if ( preg_match( "/(^|,)".$gid."(,|$)/", $rd['start_perms'] ) )
							{
								$start = "<center><input type='checkbox' name='start_".$rd['id']."' value='1' checked></center>";
							}
							else
							{
								$start = "<center><input type='checkbox' name='start_".$rd['id']."' value='1'></center>";
							}
							
							//---------------------------
							
							if ($rd['reply_perms'] == '*')
							{
								$reply = $global;
							}
							else if ( preg_match( "/(^|,)".$gid."(,|$)/", $rd['reply_perms'] ) )
							{
								$reply = "<center><input type='checkbox' name='reply_".$rd['id']."' value='1' checked></center>";
							}
							else
							{
								$reply = "<center><input type='checkbox' name='reply_".$rd['id']."' value='1'></center>";
							}
							
							//---------------------------
							
							if ($rd['upload_perms'] == '*')
							{
								$upload = $global;
							}
							else if ( preg_match( "/(^|,)".$gid."(,|$)/", $rd['upload_perms'] ) )
							{
								$upload = "<center><input type='checkbox' name='upload_".$rd['id']."' value='1' checked></center>";
							}
							else
							{
								$upload = "<center><input type='checkbox' name='upload_".$rd['id']."' value='1'></center>";
							}
							
							//---------------------------
					
							$ADMIN->html .= $SKIN->add_td_row( array(
															   "<b>".$rd['name']."</b><br>".$rd['description'],
															   "<center>".$rd['posts']."</center>",
															   "<center>".$rd['topics']."</center>",
															   $start,
															   $reply,
															   $read,
															   $upload
													 ) , 'subforum'     );
						}
					}					 
				}
			}
		}
		
		$ADMIN->html .= $SKIN->end_form("Update Forum Permissions");
		
		$ADMIN->html .= $SKIN->end_table();
		
		$ADMIN->output();
		
	}
	
	function do_forum_perms()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		//---------------------------
		// Check for legal ID
		//---------------------------
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("Could not resolve that group ID");
		}
		
		$gid = $IN['id'];
		
		//---------------------------
		
		$DB->query("SELECT g_id, g_title FROM ibf_groups WHERE g_id='".$IN['id']."'");
		
		if ( ! $gr = $DB->fetch_row() )
		{
			$ADMIN->error("Not a valid group ID");
		}
		
		//---------------------------
		// Pull the forum data..
		//---------------------------
		
		$forum_q = $DB->query("SELECT id, read_perms, start_perms, reply_perms, upload_perms FROM ibf_forums ORDER BY position ASC");
		
		while ( $row = $DB->fetch_row( $forum_q ) )
		{
		
			$read  = "";
			$reply = "";
			$start = "";
			$upload = "";
			//---------------------------
			// Is this global?
			//---------------------------
			
			if ($row['read_perms'] == '*')
			{
				$read = '*';
				
			}
			else
			{
				//---------------------------
				// Split the set IDs
				//---------------------------
				
				$read_ids = explode( ",", $row['read_perms'] );
				
				if ( is_array($read_ids) )
				{
				   foreach ($read_ids as $i)
				   {
					   //---------------------------
					   // If it's the current ID, skip
					   //---------------------------
					   
					   if ($gid == $i)
					   {
						   continue;
					   }
					   else
					   {
						   $read .= $i.",";
					   }
				   }
				}
				//---------------------------
				// Was the box checked?
				//---------------------------
				
				if ($IN[ 'read_'.$row['id'] ] == 1)
				{
					// Add our group ID...
					
					$read .= $gid.",";
				}
				
				// Tidy..
				
				$read = preg_replace( "/,$/", "", $read );
				$read = preg_replace( "/^,/", "", $read );
				
			}
			
			//---------------------------
			// Reply topics..
			//---------------------------
				
			if ($row['reply_perms'] == '*')
			{
				$reply = '*';
			}
			else
			{
				$reply_ids = explode( ",", $row['reply_perms'] );
				
				if ( is_array($reply_ids) )
				{
				
					foreach ($reply_ids as $i)
					{
						if ($gid == $i)
						{
							continue;
						}
						else
						{
							$reply .= $i.",";
						}
					}
				
				}
				
				if ($IN[ 'reply_'.$row['id'] ] == 1)
				{
					$reply .= $gid.",";
				}
				
				$reply = preg_replace( "/,$/", "", $reply );
				$reply = preg_replace( "/^,/", "", $reply );
			}
			
			//---------------------------
			// Start topics..
			//---------------------------
				
			if ($row['start_perms'] == '*')
			{
				$start = '*';
			}
			else
			{
				$start_ids = explode( ",", $row['start_perms'] );
				
				if ( is_array($start_ids) )
				{
				
					foreach ($start_ids as $i)
					{
						if ($gid == $i)
						{
							continue;
						}
						else
						{
							$start .= $i.",";
						}
					}
				
				}
				
				if ($IN[ 'start_'.$row['id'] ] == 1)
				{
					$start .= $gid.",";
				}
				
				$start = preg_replace( "/,$/", "", $start );
				$start = preg_replace( "/^,/", "", $start );
			}
			
			//---------------------------
			// Upload topics..
			//---------------------------
				
			if ($row['upload_perms'] == '*')
			{
				$upload = '*';
			}
			else
			{
				$upload_ids = explode( ",", $row['upload_perms'] );
				
				if ( is_array($upload_ids) )
				{
				
					foreach ($upload_ids as $i)
					{
						if ($gid == $i)
						{
							continue;
						}
						else
						{
							$upload .= $i.",";
						}
					}
				
				}
				
				if ($IN[ 'upload_'.$row['id'] ] == 1)
				{
					$upload .= $gid.",";
				}
				
				$upload = preg_replace( "/,$/", "", $upload );
				$upload = preg_replace( "/^,/", "", $upload );
			}
			
			//---------------------------
			// Update the DB...
			//---------------------------
			
			if (! $new_q = $DB->query("UPDATE ibf_forums SET read_perms='$read', reply_perms='$reply', start_perms='$start', upload_perms='$upload' WHERE id='".$row['id']."'") )
			{
				die ("Update query failed on Forum ID ".$row['id']);
			}
			
		}
		
		$ADMIN->save_log("Forum Access Permissions Edited for group '{$gr['g_title']}'");
		
		$ADMIN->done_screen("Forum Access Permissions Updated", "Group Control", "act=group" );
		
	}
	
	//+---------------------------------------------------------------------------------
	//
	// Delete a group
	//
	//+---------------------------------------------------------------------------------
	
	function delete_form()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("Could not resolve the group ID, please try again");
		}
		
		if ($IN['id'] < 5)
		{
			$ADMIN->error("You can not move the preset groups. You can rename them and edit the functionality");
		}
		
		$ADMIN->page_title = "Deleting a User Group";
		
		$ADMIN->page_detail = "Please check to ensure that you are attempting to remove the correct group.";
		
		
		//+-------------------------------
		
		$DB->query("SELECT COUNT(id) as users FROM ibf_members WHERE mgroup='".$IN['id']."'");
		$black_adder = $DB->fetch_row();
		
		if ($black_adder['users'] < 1)
		{
			$black_adder['users'] = 0;
		}
		
		$DB->query("SELECT g_title FROM ibf_groups WHERE g_id='".$IN['id']."'");
		$group = $DB->fetch_row();
		
		//+-------------------------------
		
		$DB->query("SELECT g_id, g_title FROM ibf_groups WHERE g_id <> '".$IN['id']."'");
		
		$mem_groups = array();
		
		while ( $r = $DB->fetch_row() )
		{
			$mem_groups[] = array( $r['g_id'], $r['g_title'] );
		}
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'dodelete'  ),
												  2 => array( 'act'   , 'group'     ),
												  3 => array( 'id'    , $IN['id']   ),
												  4 => array( 'name'  , $group['g_title'] ),
									     )      );
									     
		
		
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Removal Confirmation: ".$group['g_title'] );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Number of users in this group</b>" ,
												  "<b>".$black_adder['users']."</b>",
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Move users in this group to...</b>" ,
												  $SKIN->form_dropdown("to_id", $mem_groups )
									     )      );
		
		$ADMIN->html .= $SKIN->end_form("Delete this group");
										 
		$ADMIN->html .= $SKIN->end_table();
		
		$ADMIN->output();
			
			
	}
	
	function do_delete()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("Could not resolve the group ID, please try again");
		}
		
		if ($IN['to_id'] == "")
		{
			$ADMIN->error("No move to group ID was specified. /me cries.");
		}
		
		// Check to make sure that the relevant groups exist.
		
		$DB->query("SELECT g_id FROM ibf_groups WHERE g_id IN(".$IN['id'].",".$IN['to_id'].")");
		
		if ( $DB->get_num_rows() != 2 )
		{
			$ADMIN->error("Could not resolve the ID's passed to group deletion");
		}
		
		$DB->query("UPDATE ibf_members SET mgroup='".$IN['to_id']."' WHERE mgroup='".$IN['id']."'");
		
		$DB->query("DELETE FROM ibf_groups WHERE g_id='".$IN['id']."'");
		
		// Look for promotions in case we have members to be promoted to this group...
		
		$prq = $DB->query("SELECT g_id FROM ibf_groups WHERE g_promotion LIKE '{$IN['id']}&%'");
		
		while ( $row = $DB->fetch_row($prq) )
		{
			$nq = $DB->query("UPDATE ibf_groups SET g_promotion='-1&-1' WHERE g_id='".$row['g_id']."'");
		}
		
		$ADMIN->save_log("Member Group '{$IN['name']}' removed");
		
		$ADMIN->done_screen("Group Removed", "Group Control", "act=group" );
		
	}
	
	
	//+---------------------------------------------------------------------------------
	//
	// Save changes to DB
	//
	//+---------------------------------------------------------------------------------
	
	function save_group($type='edit')
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_VARS;
		
		if ($IN['g_title'] == "")
		{
			$ADMIN->error("You must enter a group title.");
		}
		
		if ($type == 'edit')
		{
			if ($IN['id'] == "")
			{
				$ADMIN->error("Could not resolve the group id");
			}
			
			if ($IN['id'] == $INFO['admin_group'] and $IN['g_access_cp'] != 1)
			{
				$ADMIN->error("You can not remove the ability to access the admin control panel for this group");
			}
		}
		
		// Build up the hashy washy for the database ..er.. wase.
		
		$prefix = preg_replace( "/&#39;/", "'" , stripslashes($HTTP_POST_VARS['prefix']) );
		$prefix = preg_replace( "/&lt;/" , "<" , $prefix          );
		$suffix = preg_replace( "/&#39;/", "'" , stripslashes($HTTP_POST_VARS['suffix']) );
		$suffix = preg_replace( "/&lt;/" , "<" , $suffix          );
		
		$promotion_a = '-1'; //id
		$promotion_b = '-1'; // posts
		
		if ($IN['g_promotion_id'] > 0)
		{
			$promotion_a = $IN['g_promotion_id'];
			$promotion_b = $IN['g_promotion_posts'];
		}
		
		
		$db_string = array(
							 'g_view_board'         => $IN['g_view_board'],
							 'g_mem_info'           => $IN['g_mem_info'],
							 'g_other_topics'       => $IN['g_other_topics'],
							 'g_use_search'         => $IN['g_use_search'],
							 'g_email_friend'       => $IN['g_email_friend'],
							 'g_invite_friend'      => $IN['g_invite_friend'],
							 'g_edit_profile'       => $IN['g_edit_profile'],
							 'g_post_new_topics'    => $IN['g_post_new_topics'],
							 'g_reply_own_topics'   => $IN['g_reply_own_topics'],
							 'g_reply_other_topics' => $IN['g_reply_other_topics'],
							 'g_edit_posts'         => $IN['g_edit_posts'],
							 'g_edit_cutoff'        => $IN['g_edit_cutoff'],
							 'g_delete_own_posts'   => $IN['g_delete_own_posts'],
							 'g_open_close_posts'   => $IN['g_open_close_posts'],
							 'g_delete_own_topics'  => $IN['g_delete_own_topics'],
							 'g_post_polls'         => $IN['g_post_polls'],
							 'g_vote_polls'         => $IN['g_vote_polls'],
							 'g_use_pm'             => $IN['g_use_pm'],
							 'g_is_supmod'          => $IN['g_is_supmod'],
							 'g_access_cp'          => $IN['g_access_cp'],
							 'g_title'              => trim($IN['g_title']),
							 'g_can_remove'         => $IN['g_can_remove'],
							 'g_append_edit'        => $IN['g_append_edit'],
							 'g_access_offline'     => $IN['g_access_offline'],
							 'g_avoid_q'            => $IN['g_avoid_q'],
							 'g_avoid_flood'        => $IN['g_avoid_flood'],
							 'g_icon'               => trim($IN['g_icon']),
							 'g_attach_max'         => $IN['g_attach_max'],
							 'g_avatar_upload'      => $IN['g_avatar_upload'],
							 'g_calendar_post'      => $IN['g_calendar_post'],
							 'g_max_messages'       => $IN['g_max_messages'],
							 'g_max_mass_pm'        => $IN['g_max_mass_pm'],
							 'g_search_flood'       => $IN['g_search_flood'],
							 'prefix'               => $prefix,
							 'suffix'               => $suffix,
							 'g_promotion'          => $promotion_a.'&'.$promotion_b,
							 'g_hide_from_list'     => $IN['g_hide_from_list'],
							 'g_post_closed'        => $IN['g_post_closed'],
							 
						  );
						  
		if ($type == 'edit')
		{
			$rstring = $DB->compile_db_update_string( $db_string );
			
			$DB->query("UPDATE ibf_groups SET $rstring WHERE g_id='".$IN['id']."'");
			
			// Update the title of the group held in the mod table incase it changed.
			
			$DB->query("UPDATE ibf_moderators SET group_name='".trim($IN['g_title'])."' WHERE group_id='".$IN['id']."'");
			
			$ADMIN->save_log("Edited Group '{$IN['g_title']}'");
			
			$ADMIN->done_screen("Group Edited", "Group Control", "act=group" );
			
		}
		else
		{
			$rstring = $DB->compile_db_insert_string( $db_string );
			
			$DB->query("INSERT INTO ibf_groups (" .$rstring['FIELD_NAMES']. ") VALUES (". $rstring['FIELD_VALUES'] .")");
			
			$ADMIN->save_log("Added Group '{$IN['g_title']}'");
			
			$ADMIN->done_screen("Group Added", "Group Control", "act=group" );
		}
	}
	
	
	//+---------------------------------------------------------------------------------
	//
	// Add / edit group
	//
	//+---------------------------------------------------------------------------------
	
	function group_form($type='edit')
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		$all_groups = array( 0 => array ('none', 'Don\'t Promote') );
		
		if ($type == 'edit')
		{
			if ($IN['id'] == "")
			{
				$ADMIN->error("No group id to select from the database, please try again.");
			}
			
			$form_code = 'doedit';
			$button    = 'Complete Edit';
				
		}
		else
		{
			$form_code = 'doadd';
			$button    = 'Add Group';
		}
		
		if ($IN['id'] != "")
		{
			$DB->query("SELECT * FROM ibf_groups WHERE g_id='".$IN['id']."'");
			$group = $DB->fetch_row();
			
			$query = "SELECT g_id, g_title FROM ibf_groups WHERE g_id <> {$IN['id']} ORDER BY g_title";
		}
		else
		{
			$group = array();
			
			$query = "SELECT g_id, g_title FROM ibf_groups ORDER BY g_title";
		}
		
		//-------------------------------------------
		// sort out the promotion stuff
		//-------------------------------------------
		
		list($group['g_promotion_id'], $group['g_promotion_posts']) = explode( '&', $group['g_promotion'] );
		
		if ($group['g_promotion_posts'] < 1)
		{
			$group['g_promotion_posts'] = '';
		}
		
		$DB->query($query);
		
		while ( $r = $DB->fetch_row() )
		{
			$all_groups[] = array( $r['g_id'], $r['g_title'] );
		}
		
		//-------------------------------------------
		
		if ($type == 'edit')
		{
			$ADMIN->page_title = "Editing User Group ".$group['g_title'];
		}
		else
		{
			$ADMIN->page_title = 'Adding a new user group';
			$group['g_title'] = 'New Group';
		}
		
		$guest_legend = "";
		
		if ($group['g_id'] == $INFO['guest_group'])
		{
			$guest_legend = "</b><br><i>(Does not apply to guests)</i>";
		}
		
		$ADMIN->page_detail = "Please double check the information before submitting the form.";
		
		
		//+-------------------------------
		
		$ADMIN->html .= "<script language='javascript'>
						 <!--
						  function checkform() {
						  
						  	isAdmin = document.forms[0].g_access_cp;
						  	isMod   = document.forms[0].g_is_supmod;
						  	
						  	msg = '';
						  	
						  	if (isAdmin[0].checked == true)
						  	{
						  		msg += 'Members in this group can access the Admin Control Panel\\n\\n';
						  	}
						  	
						  	if (isMod[0].checked == true)
						  	{
						  		msg += 'Members in this group are super moderators.\\n\\n';
						  	}
						  	
						  	if (msg != '')
						  	{
						  		msg = 'Security Check\\n--------------\\nMember Group Title: ' + document.forms[0].g_title.value + '\\n--------------\\n\\n' + msg + 'Is this correct?';
						  		
						  		formCheck = confirm(msg);
						  		
						  		if (formCheck == true)
						  		{
						  			return true;
						  		}
						  		else
						  		{
						  			return false;
						  		}
						  	}
						  }
						 //-->
						 </script>\n";
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , $form_code  ),
												  2 => array( 'act'   , 'group'     ),
												  3 => array( 'id'    , $IN['id']   ),
									     ) , 'adform', "onSubmit='return checkform()'" );
									     
		
		
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		//+-------------------------------
		
		$prefix = preg_replace( "/'/", "&#39;", $group['prefix'] );
		$prefix = preg_replace( "/</", "&lt;" , $prefix          );
		$suffix = preg_replace( "/'/", "&#39;", $group['suffix'] );
		$suffix = preg_replace( "/</", "&lt;" , $suffix          );
		
		$ADMIN->html .= $SKIN->start_table( "Global Settings" );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Group Title</b>" ,
												  $SKIN->form_input("g_title", $group['g_title'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Group Icon</b><br>(Can be omitted)" ,
												  $SKIN->form_input("g_icon", $group['g_icon'] )
									     )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Max upload file size (in KB)</b><br>(Leave blank to disallow uploads)" ,
												  $SKIN->form_input("g_attach_max", $group['g_attach_max'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Online List Format [Prefix]</b><br>(Can be left blank)<br>(Example:&lt;span style='color:red'&gt;)" ,
												  $SKIN->form_input("prefix", $prefix )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Online List Format [Suffix]</b><br>(Can be left blank)<br>(Example:&lt;/span&gt;)" ,
												  $SKIN->form_input("suffix", $suffix )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Hide this group from the member list?</b>" ,
												  $SKIN->form_yes_no("g_hide_from_list", $group['g_hide_from_list'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->end_table();
		
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Global Permissions" );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can view board?</b>" ,
												  $SKIN->form_yes_no("g_view_board", $group['g_view_board'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can view OFFLINE board?</b>" ,
												  $SKIN->form_yes_no("g_access_offline", $group['g_access_offline'] )
									     )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can view member profiles and the member list?</b>" ,
												  $SKIN->form_yes_no("g_mem_info", $group['g_mem_info'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can view other members topics?</b>" ,
												  $SKIN->form_yes_no("g_other_topics", $group['g_other_topics'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can use search?</b>" ,
												  $SKIN->form_yes_no("g_use_search", $group['g_use_search'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Number of seconds for search flood control</b><br>Stops search abuse, enter 0 or leave blank for no flood control" ,
												  $SKIN->form_input("g_search_flood", $group['g_search_flood'] )
									     )      );							     
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can mail members from the board?</b>" ,
												  $SKIN->form_yes_no("g_email_friend", $group['g_email_friend'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can edit own profile info?$guest_legend" ,
												  $SKIN->form_yes_no("g_edit_profile", $group['g_edit_profile'] )
									     )      );							     
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can use PM system?$guest_legend" ,
												  $SKIN->form_yes_no("g_use_pm", $group['g_use_pm'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Max. Number users allowed to mass PM?$guest_legend<br>(Enter 0 or leave blank to disable mass PM)" ,
												  $SKIN->form_input("g_max_mass_pm", $group['g_max_mass_pm'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Max. Number of storable messages?$guest_legend" ,
												  $SKIN->form_input("g_max_messages", $group['g_max_messages'] )
									     )      );
									     						     							     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can Upload avatars?$guest_legend" ,
												  $SKIN->form_yes_no("g_avatar_upload", $group['g_avatar_upload'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->end_table();
		
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Posting Permissions" );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can post new topics (where allowed)?</b>" ,
												  $SKIN->form_yes_no("g_post_new_topics", $group['g_post_new_topics'] )
									     )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can reply to OWN topics?</b>" ,
												  $SKIN->form_yes_no("g_reply_own_topics", $group['g_reply_own_topics'] )
									     )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can reply to OTHER members topics (where allowed)?</b>" ,
												  $SKIN->form_yes_no("g_reply_other_topics", $group['g_reply_other_topics'] )
									     )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can edit own posts?$guest_legend" ,
												  $SKIN->form_yes_no("g_edit_posts", $group['g_edit_posts'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Edit time restriction (in minutes)?$guest_legend<br>Denies user edit after the time set has passed. Leave blank or enter 0 for no restriction" ,
												  $SKIN->form_input("g_edit_cutoff", $group['g_edit_cutoff'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Allow user to remove 'Edited by' legend?</b>" ,
												  $SKIN->form_yes_no("g_append_edit", $group['g_append_edit'] )
									     )      );							     
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can delete own posts?$guest_legend" ,
												  $SKIN->form_yes_no("g_delete_own_posts", $group['g_delete_own_posts'] )
									     )      );
									     						     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can open/close own topics?$guest_legend" ,
												  $SKIN->form_yes_no("g_open_close_posts", $group['g_open_close_posts'] )
									     )      );							     
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can delete own topics?$guest_legend" ,
												  $SKIN->form_yes_no("g_delete_own_topics", $group['g_delete_own_topics'] )
									     )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can start new polls (where allowed)?$guest_legend</b>" ,
												  $SKIN->form_yes_no("g_post_polls", $group['g_post_polls'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can vote in polls (where allowed)?$guest_legend" ,
												  $SKIN->form_yes_no("g_vote_polls", $group['g_vote_polls'] )
									     )      );							     
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can avoid flood control?</b>" ,
												  $SKIN->form_yes_no("g_avoid_flood", $group['g_avoid_flood'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can avoid moderation queues?</b>" ,
												  $SKIN->form_yes_no("g_avoid_q", $group['g_avoid_q'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can add events to the calendar?$guest_legend</b>" ,
												  $SKIN->form_yes_no("g_calendar_post", $group['g_calendar_post'] )
									     )      );
									     					     							     
		$ADMIN->html .= $SKIN->end_table();
		
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Moderation Permissions" );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Is Super Moderator (can moderate anywhere)?$guest_legend" ,
												  $SKIN->form_yes_no("g_is_supmod", $group['g_is_supmod'] )
									     )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Can access the Admin CP?$guest_legend" ,
												  $SKIN->form_yes_no("g_access_cp", $group['g_access_cp'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Allow user group to post in 'closed' topics?" ,
												  $SKIN->form_yes_no("g_post_closed", $group['g_post_closed'] )
									     )      );
									     
		$ADMIN->html .= $SKIN->end_table();
		
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Group Promotion" );
		
		if ($group['g_id'] == $INFO['admin_group'])
		{
			$ADMIN->html .= $SKIN->add_td_row( array( "<b>Choose 'Don't Promote' to disable promotions</b><br>".$SKIN->js_help_link('mg_promote') ,
													  "Feature disable for the root admin group, after all - if you're at the top where can you be promoted to?"
											 )      );
		}
		else
		{
			$ADMIN->html .= $SKIN->add_td_row( array( "<b>Choose 'Don't Promote' to disable promotions</b><br>".$SKIN->js_help_link('mg_promote') ,
													  'Promote members of this group to: '.$SKIN->form_dropdown("g_promotion_id", $all_groups, $group['g_promotion_id'] )
													 .'<br>when they reach '.$SKIN->form_simple_input('g_promotion_posts', $group['g_promotion_posts'] ).' posts'
											 )      );
		}
									     
		$ADMIN->html .= $SKIN->end_form($button);
										 
		$ADMIN->html .= $SKIN->end_table();
		
		$ADMIN->output();
			
			
	}

	//+---------------------------------------------------------------------------------
	//
	// Show "Management Screen
	//
	//+---------------------------------------------------------------------------------
	
	function main_screen()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		$ADMIN->page_title = "User Groups";
		
		$ADMIN->page_detail = "User Grouping is a quick and powerful way to organise your members. There are 4 preset groups that you cannot remove (Validating, Guest, Member and Admin) although you may edit these at will. A good example of user grouping is to set up a group called 'Moderators' and allow them access to certain forums other groups do not have access to.<br>Forum access allows you to make quick changes to that groups forum read, write and reply settings. You may do this on a forum per forum basis in forum control.";
		
		$g_array = array();
		
		$SKIN->td_header[] = array( "Group Title"    , "30%" );
		$SKIN->td_header[] = array( "ACP"            , "5%" );
		$SKIN->td_header[] = array( "SMOD"           , "5%" );
		$SKIN->td_header[] = array( "Members"        , "10%" );
		$SKIN->td_header[] = array( "Edit Group"     , "20%" );
		$SKIN->td_header[] = array( "Forum Access"   , "20%" );
		$SKIN->td_header[] = array( "Delete"         , "10%" );
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "User Group Management" );
		
		$DB->query("SELECT ibf_groups.g_id, ibf_groups.g_access_cp, ibf_groups.g_is_supmod, ibf_groups.g_title,ibf_groups.prefix, ibf_groups.suffix, COUNT(ibf_members.id) as count FROM ibf_groups "
		          ."LEFT JOIN ibf_members ON (ibf_members.mgroup = ibf_groups.g_id) "
		          ."GROUP BY ibf_groups.g_id ORDER BY ibf_groups.g_title");
		
		while ( $r = $DB->fetch_row() )
		{
		
			$del  = "";
			$mod  = '&nbsp;';
			$adm  = '&nbsp;';
			
			if ($r['g_id'] > 4)
			{
				$del = "<center><a href='{$ADMIN->base_url}&act=group&code=delete&id=".$r['g_id']."'>Delete</a></center>";
			}
			//-----------------------------------
			if ($r['g_access_cp'] == 1)
			{
				$adm = '<center><span style="color:red">Y</span></center>';
			}
			//-----------------------------------
			if ($r['g_is_supmod'] == 1)
			{
				$mod = '<center><span style="color:red">Y</span></center>';
			}
			
			if ($r['g_id'] != 1 and $r['g_id'] != 2)
			{
				$total_linkage = "<a href='{$INFO['board_url']}/index.{$INFO['php_ext']}?act=Members&max_results=30&filter={$r['g_id']}&sort_order=asc&sort_key=name&st=0' target='_blank' title='List Users'>".$r['prefix'].$r['g_title'].$r['suffix']."</a>";
			}
			else
			{
				$total_linkage = $r['prefix'].$r['g_title'].$r['suffix'];
			}
			
			$ADMIN->html .= $SKIN->add_td_row( array( "<b>$total_linkage</b>" ,
												      $adm,
												      $mod,
												      "<center>".$r['count']."</center>",
												      "<center><a href='{$ADMIN->base_url}&act=group&code=edit&id=".$r['g_id']."'>Edit Group</a></center>",
												      "<center><a href='{$ADMIN->base_url}&act=group&code=fedit&id=".$r['g_id']."'>Forum Access</a></center>",
												      $del
												      
									     )      );
									     
			$g_array[] = array( $r['g_id'], $r['g_title'] );
		}
		
		$ADMIN->html .= $SKIN->add_td_basic("&nbsp;", "center", "title");

		$ADMIN->html .= $SKIN->end_table();
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'add' ),
												  2 => array( 'act'   , 'group'     ),
									     )      );
									     
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Add a new member group" );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Base new group on...</b>" ,
												  $SKIN->form_dropdown("id", $g_array, 3 )
									     )      );
									     
		$ADMIN->html .= $SKIN->end_form("Set up New Group");
										 
		$ADMIN->html .= $SKIN->end_table();
		
		$ADMIN->output();
		
		
	}
	
		
}


?>