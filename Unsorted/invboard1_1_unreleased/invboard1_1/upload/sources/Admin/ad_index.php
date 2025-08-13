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
|   > Admin "welcome" screen functions
|   > Module written by Matt Mecham
|   > Date started: 1st march 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/




$idx = new index_page();


class index_page {


	function index_page() {
		global $DB, $IN, $INFO, $ADMIN, $SKIN, $std;
		
		$DB->query("SELECT * FROM ibf_stats");
		
		$row = $DB->fetch_row();
		
		if ($row['TOTAL_REPLIES'] < 0) $row['TOTAL_REPLIES'] = 0;
		if ($row['TOTAL_TOPICS']  < 0) $row['TOTAL_TOPICS']  = 0;
		if ($row['MEM_COUNT']     < 0) $row['MEM_COUNT']     = 0;
		
		$DB->query("SELECT COUNT(*) as reg FROM ibf_members WHERE mgroup='".$INFO['auth_group']."' AND (new_pass='' or new_pass IS NULL)");
		$reg = $DB->fetch_row();
		
		if ($reg['reg'] < 1 ) $reg['reg'] = 0;
		
		$DB->query("SELECT COUNT(*) as coppa FROM ibf_members WHERE mgroup='".$INFO['auth_group']."' AND coppa_user=1");
		$coppa = $DB->fetch_row();
		
		if ($coppa['coppa'] < 1 ) $coppa['coppa'] = 0;
		
		//-------------------------------------------------
		// Make sure the uploads path is correct
		//-------------------------------------------------
		
		$uploads_size = 0;
		
		if ($dh = opendir( $INFO['upload_dir'] ))
		{
			while ( $file = readdir( $dh ) )
			{
				if ( !preg_match( "/^..?$|^index/i", $file ) )
				{
					$uploads_size += @filesize( $INFO['upload_dir'] . "/" . $file );
				}
			}
			closedir( $dh );
		}
		
		// This piece of code from Jesse's (jesse@jess.on.ca) contribution
		// to the PHP manual @ php.net
		
		if ($uploads_size >= 1048576)
		{
			$uploads_size = round($uploads_size / 1048576 * 100 ) / 100 . " mb";
		}
		else if ($uploads_size >= 1024)
		{
			$uploads_size = round($uploads_size / 1024 * 100 ) / 100 . " k";
		}
		else
		{
			$uploads_size = $uploads_size . " bytes";
		}
		
		//+-----------------------------------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Administrators using the CP" );
		
		$t_time = time() - 60*10;
		
		$DB->query("SELECT DISTINCT(MEMBER_NAME) FROM ibf_admin_sessions WHERE RUNNING_TIME > $t_time");
		
		$mem_array = array();
		
		while ( $r = $DB->fetch_row() )
		{
			$mem_array[] = $r['MEMBER_NAME'];
		}
		
		$ADMIN->html .= $SKIN->add_td_basic( implode(", ", $mem_array ) );
		
		$ADMIN->html .= $SKIN->end_table();
		
		//+-----------------------------------------------------------
		
		$ADMIN->html .= $SKIN->add_td_spacer();
		
		//+-----------------------------------------------------------
		
		$SKIN->td_header[] = array( "Definition", "25%" );
		$SKIN->td_header[] = array( "Value"     , "25%" );
		$SKIN->td_header[] = array( "Definition", "25%" );
		$SKIN->td_header[] = array( "Value"     , "25%" );
		
		$ADMIN->html .= $SKIN->start_table( "System Overview" );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "Total Unique Topics" , $row['TOTAL_TOPICS'],
												  "Total Replies to topics"         , $row['TOTAL_REPLIES']
		 								 )      );
		 								 
		$ADMIN->html .= $SKIN->add_td_row( array( "Total Members" , $row['MEM_COUNT'], "Public Upload Folder Size", $uploads_size ) );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<a href='{$SKIN->base_url}&act=mem&code=mod'>Users awaiting validation</a>" , $reg['reg'],
												  "<a href='{$SKIN->base_url}&act=mem&code=mod'>COPPA Requests</a> from 'Users awaiting validation' total", $coppa['coppa'],
									     )      );
		
		$ADMIN->html .= $SKIN->end_table();
		
		//+-----------------------------------------------------------
		
		$ADMIN->html .= $SKIN->add_td_spacer();
		
		//+-----------------------------------------------------------
		
		$ADMIN->html .= $SKIN->start_form();
		
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "30%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "30%" );
		
		$ADMIN->html .= $SKIN->start_table( "Quick Clicks" );
		
		$ADMIN->html .= "
				
					<script language='javascript'>
					<!--
					  function edit_member() {
						
						if (document.forms[0].username.value == \"\") {
							alert(\"You must enter a username!\");
						} else {
							window.parent.body.location = '{$SKIN->base_url}' + '&act=mem&code=stepone&USER_NAME=' + escape(document.forms[0].username.value);
						}
					  }
					  
					  function new_cat() {
						
						if (document.forms[0].cat_name.value == \"\") {
							alert(\"You must enter a category name!\");
						} else {
							window.parent.body.location = '{$SKIN->base_url}' + '&act=cat&code=new&name=' + escape(document.forms[0].cat_name.value);
						}
					  }
					  
					  function new_forum() {
						
						if (document.forms[0].forum_name.value == \"\") {
							alert(\"You must enter a forum name!\");
						} else {
							window.parent.body.location = '{$SKIN->base_url}' + '&act=forum&code=new&name=' + escape(document.forms[0].forum_name.value);
						}
					  }
					//-->
					
					</script>
					<form name='DOIT' action=''>
						
		";
		
		$ADMIN->html .= $SKIN->add_td_row( array( "Edit Member:",
												  "<input type='text' style='width:100%' id='textinput' name='username' value='Enter name here' onfocus='this.value=\"\"'>",
												  "<input type='button' value='Find Member' id='button' onClick='edit_member()'>"
										 )      );
		
		$ADMIN->html .= $SKIN->add_td_row( array( "Add New Category:",
												  "<input type='text' style='width:100%' name='cat_name' id='textinput' value='Category title here' onfocus='this.value=\"\"'>",
												  "<input type='button' value='Add Category' id='button' onClick='new_cat()'>"
										 )      );
										 
		$ADMIN->html .= $SKIN->add_td_row( array( "Add New Forum:",
												  "<input type='text' style='width:100%' name='forum_name' id='textinput' value='Forum title here' onfocus='this.value=\"\"'>",
												  "<input type='button' value='Add Forum' id='button' onClick='new_forum()'>"
										 )      );
		
		$ADMIN->html .= "</form>";
										 
		$ADMIN->html .= $SKIN->end_table();
		
		$ADMIN->output();
		
	}
	
}


?>