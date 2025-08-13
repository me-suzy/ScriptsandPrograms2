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
|   > Skin -> Templates functions
|   > Module written by Matt Mecham
|   > Date started: 15th April 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/




$idx = new ad_settings();


class ad_settings {

	var $base_url;

	function ad_settings() {
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;

		switch($IN['code'])
		{
			case 'add':
				$this->add_splash();
				break;
				
			case 'edit':
				$this->show_cats();
				break;
				
			case 'dedit':
				$this->do_form();
				break;
				
			case 'doedit':
				$this->do_edit();
				break;
				
			case 'remove':
				$this->remove();
				break;
				
			case 'tools':
				$this->tools();
				break;
				
			case 'editinfo':
				$this->edit_info();
				break;
				
			case 'export':
				$this->export();
				break;
			
			case 'edit_bit':
				$this->edit_bit();
				break;
				
			case 'download':
				$this->download_group();
				break;
				
			case 'upload':
				$this->upload_form();
				break;
				
			case 'do_upload':
				$this->upload_single();
				break;
				
			
			//-------------------------
			default:
				$this->list_current();
				break;
		}
		
	}
	
	//------------------------------------------------------
	
	function add_splash()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_FILES;
		
		$FILE_NAME = $HTTP_POST_FILES['FILE_UPLOAD']['name'];
		$FILE_SIZE = $HTTP_POST_FILES['FILE_UPLOAD']['size'];
		$FILE_TYPE = $HTTP_POST_FILES['FILE_UPLOAD']['type'];
		
		// Naughty Opera adds the filename on the end of the
		// mime type - we don't want this.
		
		$FILE_TYPE = preg_replace( "/^(.+?);.*$/", "\\1", $FILE_TYPE );
		
		// Naughty Mozilla likes to use "none" to indicate an empty upload field.
		// I love universal languages that aren't universal.
		
		if ($HTTP_POST_FILES['FILE_UPLOAD']['name'] == "" or !$HTTP_POST_FILES['FILE_UPLOAD']['name'] or ($HTTP_POST_FILES['FILE_UPLOAD']['name'] == "none") )
		{
			// We're adding new templates based on another set
			
			$this->add_templates();
			exit();
		}
		
		if (! is_dir($INFO['upload_dir']) )
		{
			$ADMIN->error("Could not locate the uploads directory - make sure the 'uploads' path is set correctly");
		}
		
		//-------------------------------------------------
		// Copy the upload to the uploads directory
		//-------------------------------------------------
		
		if (! @move_uploaded_file( $HTTP_POST_FILES['FILE_UPLOAD']['tmp_name'], $INFO['upload_dir']."/".$FILE_NAME) )
		{
			$ADMIN->error("The upload failed");
		}
		else
		{
			@chmod( $INFO['upload_dir']."/".$FILE_NAME, 0777 );
		}
		
		//-------------------------------------------------
		// Attempt to open the file..
		//-------------------------------------------------
		
		$filename = $INFO['upload_dir']."/".$FILE_NAME;
		
		if ( $FH = @fopen( $filename, 'r' ) )
		{
			$data = @fread( $FH, filesize($filename) );
			@fclose($FH);
			
			@unlink($filename);
		}
		else
		{
			$ADMIN->error("Could not open the uploaded file for reading!");
		}
		
		//-------------------------------------------------
		// If we're here, we'll assume that we've read the
		// file and the contents are in $data
		// So, lets make sure its the correct template file..
		//-------------------------------------------------
		
		if ( ! preg_match( "/<!--TEMPLATE_SET\|(.+?)-->/", $data, $matches ) )
		{
			$ADMIN->error("This file does not appear to be a valid Invision Board Template Set file");
		}
		
		list($pack_name,$author,$email,$url) = explode( ",", trim($matches[1]) );
		
		//-------------------------------------------------
		// Find the new set ID by inserting the data for the
		// template names, we can always remove it later if
		// we get an error
		//-------------------------------------------------
		
		$pack_name .= "(Upload ID: ".substr( time(), -6 ).")";
		$pack_name = str_replace( "'", "", $pack_name );
		$author    = str_replace( "'", "", $author );
		$email     = str_replace( "'", "", $email );
		$url       = str_replace( "'", "", $url );
		
		$DB->query("INSERT INTO ibf_tmpl_names (skname, author, email, url) VALUES('$pack_name', '$author', '$email', '$url')");
		$setid = $DB->get_insert_id();
		
		//-------------------------------------------------
		// Divide the file up into different sections
		//-------------------------------------------------
		
		preg_match_all( "/<!--IBF_GROUP_START:(\S+?)-->(.+?)<!--IBF_GROUP_END:\S+?-->/s", $data, $match );
		
		for ($i=0; $i < count($match[0]); $i++)
		{
			$match[1][$i] = trim($match[1][$i]);
			
			$match[2][$i] = trim($match[2][$i]);
			
			// Pass it on to our handler..
		
			$this->process_upload($match[2][$i], $setid, $match[1][$i], 1 );
			
			
		}
		
		// Insert this new data into the template names thingy
	
		$ADMIN->done_screen("Template set import complete", "Manage Template Sets", "act=templ" );
		
	}
	
	//------------------------------------------------------
	
	function upload_single()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_FILES;
		
		$FILE_NAME = $HTTP_POST_FILES['FILE_UPLOAD']['name'];
		$FILE_SIZE = $HTTP_POST_FILES['FILE_UPLOAD']['size'];
		$FILE_TYPE = $HTTP_POST_FILES['FILE_UPLOAD']['type'];
		
		// Naughty Opera adds the filename on the end of the
		// mime type - we don't want this.
		
		$FILE_TYPE = preg_replace( "/^(.+?);.*$/", "\\1", $FILE_TYPE );
		
		if (! is_dir($INFO['upload_dir']) )
		{
			$ADMIN->error("Could not locate the uploads directory - make sure the 'uploads' path is set correctly");
		}
							
		// Naughty Mozilla likes to use "none" to indicate an empty upload field.
		// I love universal languages that aren't universal.
		
		if ($HTTP_POST_FILES['FILE_UPLOAD']['name'] == "" or !$HTTP_POST_FILES['FILE_UPLOAD']['name'] or ($HTTP_POST_FILES['FILE_UPLOAD']['name'] == "none") )
		{
			$ADMIN->error("No file was chosen to upload!");
		}
		
		//-------------------------------------------------
		// Copy the upload to the uploads directory
		//-------------------------------------------------
		
		if (! @move_uploaded_file( $HTTP_POST_FILES['FILE_UPLOAD']['tmp_name'], $INFO['upload_dir']."/".$FILE_NAME) )
		{
			$ADMIN->error("The upload failed");
		}
		else
		{
			@chmod( $INFO['upload_dir']."/".$FILE_NAME, 0777 );
		}
		
		//-------------------------------------------------
		// Attempt to open the file..
		//-------------------------------------------------
		
		$filename = $INFO['upload_dir']."/".$FILE_NAME;
		
		if ( $FH = @fopen( $filename, 'r' ) )
		{
			$data = @fread( $FH, filesize($filename) );
			@fclose($FH);
			
			@unlink($filename);
		}
		else
		{
			$ADMIN->error("Could not open the uploaded file for reading!");
		}
		
		//-------------------------------------------------
		// If we're here, we'll assume that we've read the
		// file and the contents are in $data
		// So, lets make sure its the correct template file..
		//-------------------------------------------------
		
		preg_match( "/<!--IBF_GROUP_START:(\S+?)-->/", $data, $matches );
		
		$found_group = trim($matches[1]);
		
		if ($found_group != $IN['group'])
		{
			$ADMIN->error("The uploaded file does not appear to be the correct type for this template group. Looking for template group '{$IN['group']}', found '$found_group'");
		}
		
		//-------------------------------------------------
		// If we're here, then lets proceed, first lets
		// remove the END GROUP statement.
		//-------------------------------------------------
		
		$data = preg_replace( "/<!--IBF_GROUP_END:\S+-->/", "", $data );
		
		// Pass it on to our handler..
		
		$this->process_upload($data, $IN['setid'], $IN['group'] );
		
		$ADMIN->done_screen("Template set update complete", "Manage Template Sets", "act=templ" );
		
	}
	
	//------------------------------------------------------
	
	function upload_form()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		require './sources/Admin/skin_info.php';
		
		if ($IN['setid'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		if ($IN['group'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//-----------------------------------
		// Get the info from the DB
		//-----------------------------------
		
		$DB->query("SELECT * FROM ibf_skin_templates WHERE set_id='".$IN['setid']."' AND group_name='".$IN['group']."'");
		
		if ( ! $DB->get_num_rows() )
		{
			$ADMIN->error("Can't query the information from the database");
		}
		
		$DB->query("SELECT skname FROM ibf_tmpl_names WHERE skid='".$IN['setid']."'");
		
		$row = $DB->fetch_row();
		
		//+-------------------------------
	
		$ADMIN->page_detail = "Please check all the information carefully before continuing.";
		$ADMIN->page_title  = "Upload a template file for template set: {$row['skname']}";
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'do_upload' ),
												  2 => array( 'act'   , 'templ'     ),
												  3 => array( 'MAX_FILE_SIZE', '10000000000' ),
												  4 => array( 'setid' , $IN['setid']  ),
												  5 => array( 'group' , $IN['group']  ),
									     ) , "uploadform", " enctype='multipart/form-data'"     );
									     
		$SKIN->td_header[] = array( "&nbsp;"   , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"   , "60%" );

		$ADMIN->html .= $SKIN->start_table("Upload template file to replace: ".$skin_names[ $IN['group'] ][0]);
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Choose a file from your computer to upload</b><br>Note: Uploading this file will replace all data currently held, there is no undo!.",
												  $SKIN->form_upload(),
										 )      );
									     
		$ADMIN->html .= $SKIN->end_form('Upload File');
										 
		$ADMIN->html .= $SKIN->end_table();
		
		$ADMIN->nav[] = array( 'act=templ' ,'Template Control Home' );
		$ADMIN->nav[] = array( "act=templ&code=edit&id={$IN['setid']}" ,$row['skname'] );
		
		$ADMIN->output();						
		
	}
	
	//------------------------------------------------------
	
	function export()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		$DB->query("SELECT * FROM ibf_tmpl_names WHERE skid='".$IN['id']."'");
			
		if ( ! $set = $DB->fetch_row() )
		{
			$ADMIN->error("Could not find a template set with that ID in the database, please try again");
		}
			
		//-----------------------------------
		// Get the info from the DB
		//-----------------------------------
		
		$groups = $DB->query("SELECT DISTINCT(group_name) FROM ibf_skin_templates WHERE set_id='".$IN['id']."'");
		
		if ( ! $DB->get_num_rows($groups) )
		{
			$ADMIN->error("Can't query the information from the database");
		}
		
		// Loop and pass it to the download compiler
		
		$author = str_replace( ",", "-", $set['author'] );
		$email  = str_replace( ",", "-", $set['email'] );
		$url    = str_replace( ",", "-", $set['url'] );
		$skname = str_replace( ",", "-", $set['skname'] );
		
		$output .= "<!--TEMPLATE_SET|$skname,$author,$email,$url-->\n\n";
		
		while ( $row = $DB->fetch_row($groups) )
		{
			$output .= $this->download_group(1, $IN['id'], $row['group_name'] );
		}
		
		$name = str_replace( " ", "_", $set['skname'] );
		
		@header("Content-type: unknown/unknown");
		@header("Content-Disposition: attachment; filename={$name}.SET.html");
		
		print $output;
		
		exit();
		
	}
	
	//------------------------------------------------------
	
	//------------------------------------------------------
	
	function download_group($return=0, $setid="", $group="")
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		if ($setid != "")
		{
			$IN['setid'] = $setid;
		}
		
		if ($group != "")
		{
			$IN['group'] = $group;
		}
		
		if ($IN['setid'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		if ($IN['group'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//-----------------------------------
		// Get the info from the DB
		//-----------------------------------
		
		$aq = $DB->query("SELECT * FROM ibf_skin_templates WHERE set_id='".$IN['setid']."' AND group_name='".$IN['group']."'");
		
		if ( ! $DB->get_num_rows($aq) )
		{
			$ADMIN->error("Can't query the information from the database");
		}
		
		$output = "<!-- PLEASE LEAVE ALL 'IBF' COMMENTS IN PLACE, DO NOT REMOVE THEM! -->\n<!--IBF_GROUP_START:{$IN['group']}-->\n\n";
		
		while ( $row = $DB->fetch_row($aq) )
		{
			$text = $this->convert_tags($row['section_content']);
			
			$output .= "<!--IBF_START_FUNC|{$row['func_name']}|{$row['func_data']}-->\n\n";
			$output .= $text."\n";
			$output .= "<!--IBF_END_FUNC|{$row['func_name']}-->\n\n";
		}
		
		$output .= "\n<!--IBF_GROUP_END:{$IN['group']}-->\n";
		
		if ($return == 0)
		{
			$DB->query("SELECT skname FROM ibf_tmpl_names WHERE skid='".$IN['setid']."'");
			
			$set = $DB->fetch_row();
			
			$name = str_replace( " ", "_", $set['skname'] );
			
			@header("Content-type: unknown/unknown");
			@header("Content-Disposition: attachment; filename={$name}.{$IN['group']}.html");
			
			print $output;
			
			exit();
		}
		else
		{
			return $output;
		}
		
	}
	
	//------------------------------------------------------
	
	function show_cats()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//+-------------------------------
		
		$DB->query("SELECT * from ibf_tmpl_names WHERE skid='".$IN['id']."'");
		
		if ( ! $row = $DB->fetch_row() )
		{
			$ADMIN->error("Could not query the information from the database");
		}
		
		// Get $skin_names stuff
		
		require './sources/Admin/skin_info.php';
		
		
		if ($row['author'] and $row['email'])
		{
			$author = "<br><br>This template set <b>'{$row['skname']}'</b> was created by <a href='mailto:{$row['email']}' target='_blank'>{$row['author']}</a>";
		}
		else if ($row['author'])
		{
			$author = "<br><br>This template set <b>'{$row['skname']}'</b> was created by {$row['author']}";
		}
		
		if ($row['url'])
		{
			$url = " (website: <a href='{$row['url']}' target='_blank'>{$row['url']}</a>)";
		}
		
		//+-------------------------------
	
		$ADMIN->page_detail = "Please choose which section you wish to edit below.<br><br><b>Download</b> this HTML section This option allows you to download all of the HTML for this template section for offline editing.<br><b>Upload</b> HTML for this section This option allows you to upload a saved HTML file to replace this template section.$author $url";
		$ADMIN->page_title  = "Edit Template sets";
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->js_checkdelete();
		
		$all_cats = $DB->query("select group_name, set_id, suid, count(group_name) as number_secs, group_name FROM ibf_skin_templates WHERE set_id='".$IN['id']."' group by group_name");
		
		$SKIN->td_header[] = array( "Skin Category Title"   , "40%" );
		$SKIN->td_header[] = array( "View Options"          , "20%" );
		$SKIN->td_header[] = array( "Manage"                , "30%" );
		$SKIN->td_header[] = array( "# Bits"                , "10%" );

		//+-------------------------------
		
		$ADMIN->html .= "<script language='javascript'>
						 function pop_win(theUrl) {
						 	
						 	window.open('{$SKIN->base_url}&'+theUrl,'Preview','width=400,height=450,resizable=yes,scrollbars=yes');
						 }
						 </script>";
		
		$ADMIN->html .= $SKIN->start_table("Template: ".$row['skname']);
			
		while ( $group = $DB->fetch_row($all_cats) )
		{
		
			$name = "<b>".$group['group_name']."</b>";
			$desc = "";
			
			$expand = 'Expand to Edit';
			$eid    = $group['suid'];
			$exp_content = "";
			
			if ( isset($skin_names[ $group['group_name'] ]) )
			{
				$name = "<b>".$skin_names[ $group['group_name'] ][0]."</b>";
				$desc = "<br>".$skin_names[ $group['group_name'] ][1];
			}
			else
			{
				$name .= " (Non-Default Group)";
				$desc = "<br>This group is not part of the standard Invision Board installation and no description is available";
			}
			
			if ($IN['expand'] == $group['suid'])
			{
				$expand = 'Collapse';
				$eid    = '';
				
				$new_q = $DB->query("SELECT func_name, LENGTH(section_content) as sec_length, suid FROM ibf_skin_templates WHERE set_id='{$IN['id']}' AND group_name='{$group['group_name']}'");
				
				//----------------------------
				
				if ( $DB->get_num_rows($new_q) )
				{
					$exp_content .= $SKIN->add_td_basic( "<table cellspacing='2' cellpadding='4' width='100%' align='center'>
														  <tr>
														   <td align='left' id='catrow2'><a style='font-weight:bold;font-size:12px;color:#000033' href='{$SKIN->base_url}&act=templ&code=edit&id={$IN['id']}&expand=' title='Collapse' alt='Collapse'>$name</a></td>
														   <td colspan='3'>&nbsp;</td>
														  <tr>
														   <td width='30%' id='catrow2'></td>
														   <td width='20%' id='catrow2' align='center'># Characters</td>
														   <td width='20%' id='catrow2'align='center'>Edit</td>
														   <td width='30%' id='catrow2'align='center'>Preview Options</td>
														  </tr>
														  <!--CONTENT--></table>", "left", "tdrow2" );
				
				
					$temp = "";
					
					while ( $sec = $DB->fetch_row($new_q) )
					{
						$temp .= "
									<tr>
									 <td width='30%' id='subforum'><b>{$sec['func_name']}</b></td>
									 <td width='20%' id='subforum' align='center'>{$sec['sec_length']}</td>
									 <td width='20%' id='subforum' align='center'><a href='{$SKIN->base_url}&act=templ&code=edit_bit&suid={$sec['suid']}&expand={$group['suid']}'>Edit</a></td>
									 <td width='30%' id='subforum' align='center'>(<a href='javascript:pop_win(\"act=rtempl&code=preview&suid={$sec['suid']}&type=html\")'>HTML</a> | <a href='javascript:pop_win(\"act=rtempl&code=preview&suid={$sec['suid']}&type=text\")'>Text</a> | <a href='javascript:pop_win(\"act=rtempl&code=preview&suid={$sec['suid']}&type=css\")'>With CSS</a>)</td>
									</tr>
								";
					}
					
					$exp_content = str_replace( "<!--CONTENT-->", $temp, $exp_content );
					
					$desc = "";
				
				}
				
				//----------------------------
				
			}
			else
			{
			
				$ADMIN->html .= $SKIN->add_td_row( array( 
															"<span style='font-weight:bold;font-size:12px;color:#000033'><a href='{$SKIN->base_url}&act=templ&code=edit&id={$IN['id']}&expand=$eid'>".$name."</a></span>".$desc,
															"<center><a href='{$SKIN->base_url}&act=templ&code=edit&id={$IN['id']}&expand=$eid'>$expand</a></center>",
															"<center><a href='{$SKIN->base_url}&act=templ&code=download&setid={$group['set_id']}&group={$group['group_name']}' title='Download a HTML file of this section for offline editing'>Download</a> | <a href='{$SKIN->base_url}&act=templ&code=upload&setid={$group['set_id']}&group={$group['group_name']}' title='Upload a saved HTML file to replace this section'>Upload</a></center>",
															"<center>".$group['number_secs']."</center>",
												 )      );
			}
											 
			$ADMIN->html .= $exp_content;
											 
		}
		
		$ADMIN->html .= $SKIN->end_table();
									     
		//+-------------------------------
		//+-------------------------------
		
		$ADMIN->nav[] = array( 'act=templ' ,'Template Control Home' );
		$ADMIN->nav[] = array( '' ,'Managing Template Set "'.$row['skname'].'"' );
		
		$ADMIN->output();
		
		
	}
	
	
	//+--------------------------------------------------------------------------------
	//+--------------------------------------------------------------------------------
	
	
	function edit_info()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_VARS;
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//+-------------------------------
		
		$DB->query("SELECT * from ibf_tmpl_names WHERE skid='".$IN['id']."'");
		
		if ( ! $row = $DB->fetch_row() )
		{
			$ADMIN->error("Could not query the information from the database");
		}
		
		$final['skname'] = stripslashes($HTTP_POST_VARS['skname']);
		
		if (isset($HTTP_POST_VARS['author']))
		{
			$final['author'] = str_replace( ",", "", stripslashes($HTTP_POST_VARS['author']) );
			$final['email']  = str_replace( ",", "", stripslashes($HTTP_POST_VARS['email']) );
			$final['url']    = str_replace( ",", "", stripslashes($HTTP_POST_VARS['url']) );
		}
		
		$db_string = $DB->compile_db_update_string( $final );
		
		$DB->query("UPDATE ibf_tmpl_names SET $db_string WHERE skid='".$IN['id']."'");
		
		$ADMIN->done_screen("Template information updated", "Manage Template sets", "act=templ" );
		
	}
	
	//+-------------------------------
	//+-------------------------------
	
	function do_edit()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_VARS;
		
		$text = stripslashes($HTTP_POST_VARS['template']);
		
		if ($IN['suid'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//+-------------------------------
		// Get the group name, etc
		//+-------------------------------
		
		$DB->query("SELECT * FROM ibf_skin_templates WHERE suid='".$IN['suid']."'");
		
		if ( ! $template = $DB->fetch_row() )
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		$real_name = $template['group_name'];
		
		//+-------------------------------
		// Get the template set info
		//+-------------------------------
		
		$DB->query("SELECT * from ibf_tmpl_names WHERE skid='".$template['set_id']."'");
		
		if ( ! $row = $DB->fetch_row() )
		{
			$ADMIN->error("Could not query the information from the database");
		}
		
		//+-------------------------------
		
		$phpskin  = $root_path."Skin/s".$template['set_id']."/".$real_name.".php";
		
		//+-------------------------------
		
		
		if ($INFO['safe_mode_skins'] != 1)
		{
		
			if (SAFE_MODE_ON == 1)
			{
				$ADMIN->error("Safe mode detected, you will need to change the board configuration to switch 'Safe Mode Skins' on. To do this, click on the 'Board Settings' menu and choose 'Basic Config' when the sub menu appears.");
			}
		
		   if ( ! is_writeable($phpskin) )
		   {
			   $ADMIN->error("Cannot write into '$phpskin', please check the CHMOD value, and if needed, CHMOD to 0777 via FTP. IBF cannot do this for you.");
		   }
		
		}
		
		//+-------------------------------
		// Ok, make sure we actually have
		// some info to parse here.
		//+-------------------------------
		
		if ($text == "")
		{
			$ADMIN->error("You can't delete the template in this manner");
		}
		
		//+-------------------------------
		// Swop back < and >
		//+-------------------------------
		
		$text = preg_replace("/&#60;/", "<", $text);
		$text = preg_replace("/&#62;/", ">", $text);
		$text = preg_replace("/&#38;/", "&", $text);
		
		//+-------------------------------
		// Convert \r to nowt
		//+-------------------------------
		
		$text = preg_replace("/\r/", "", $text);
		
		$text = $this->unconvert_tags($text);
		
		//+-------------------------------
		//Update the DB
		//+-------------------------------
		
		$string = $DB->compile_db_update_string( array ( 'section_content' => $text ) );
		
		$DB->query("UPDATE ibf_skin_templates SET $string WHERE suid='".$IN['suid']."'");
		
		
		//+-------------------------------
		// Start parsing the php skin file
		//+-------------------------------
		
		$final = "<"."?php\n\n".
				 "class $real_name {\n\n";
				 
		//+------------------------------------------
		// Get all the data from the DB that matches
		// the group name (filename) and set_id
		//+------------------------------------------
		
		if ($INFO['safe_mode_skins'] != 1)
		{
		
			if (SAFE_MODE_ON == 1)
			{
				$ADMIN->error("Safe mode detected, you will need to change the board configuration to switch 'Safe Mode Skins' on. To do this, click on the 'Board Settings' menu and choose 'Basic Config' when the sub menu appears.");
			}
		
			$DB->query("SELECT * FROM ibf_skin_templates WHERE group_name='$real_name' AND set_id='{$template['set_id']}'"); 
			
			while ( $data = $DB->fetch_row() )
			{
			
				$final .= "\n\nfunction {$data['func_name']}({$data['func_data']}) {\n".
					      "global \$ibforums;\n".
					      "return <<<EOF\n";
					   
				$final .= $data['section_content'];
					   
				$final .= "\nEOF;\n}\n";
			
			}
			
			$final .= "\n\n}\n?".">";
			
			if ($fh = fopen( "$phpskin", 'w' ) )
			{
				fwrite($fh, $final, strlen($final) );
				fclose($fh);
			}
			else
			{
				$ADMIN->error("Could not save information to $phpskin, please ensure that the CHMOD permissions are correct.");
			}
		
		}
		
		$ADMIN->done_screen("Template file updated", "Manage Templates in template set: {$row['skname']}", "act=templ&code=edit&id={$template['set_id']}" );
		
	}
	
	//------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------
	
	function tools()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_VARS;
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("You must choose a valid skin file to perform this operation on");
		}
		
		if ($IN['tool'] == 'tmpl')
		{
			$this->tools_build_tmpl();
		}
		else
		{
			$this->tools_rebuildphp();
		}
		
	}
	
	//------------------------------------------------------------------------------------
	
	function tools_build_tmpl()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_VARS;
		
		$insert = 1;
		
		// Rebuilds the data editable files from the PHP source files
		
		$skin_dir     = $root_path."Skin/s".$IN['id'];
		
		$curr_groups = array();
		
		// Are we updating or inserting?
		
		$DB->query("SELECT group_name, func_name FROM ibf_skin_templates WHERE set_id='".$IN['id']."'");
		
		if ( $DB->get_num_rows() )
		{
			$insert = 0;
		
			while ( $gname = $DB->fetch_row() )
			{
				$curr_group[ $gname['group_name'] ][ $gname['func_name'] ] = 1;
			}
		}
		
		$errors = array();
		
		$flag = 0;
		
		//------------------------------------------------
		// Is this a safe mode only skinny poos?
		//------------------------------------------------
		
		if ( ! file_exists( $skin_dir ) )
		{
			$ADMIN->error("This template set is a safe mode only skin and no PHP skin files exist, there is no need to run this tool on this template set.");
		}
		
		
		if ( ! is_readable($skin_dir) )
		{
			$ADMIN->error("Cannot write into '$skin_dir', please check the CHMOD value, and if needed, CHMOD to 0777 via FTP. IBF cannot do this for you.");
		}
		
		if ( is_dir($skin_dir) )
		{
			if ( $handle = opendir($skin_dir) )
			{
			
				while (($filename = readdir($handle)) !== false)
				{
					if (($filename != ".") && ($filename != ".."))
					{
					
						if ( preg_match( "/\.php$/", $filename ) )
						{
							
							$name = preg_replace( "/^(\S+)\.(\S+)$/", "\\1", $filename );
							
							if ($FH = fopen($skin_dir."/".$filename, 'r') )
							{
								$fdata = fread( $FH, filesize($skin_dir."/".$filename) );
								fclose($FH);
							}
							else
							{
								$errors[] = "Could not open $filename for reading, skipping file...";
								continue;
							}
							
							$fdata = str_replace( "\r", "\n", $fdata );
							$fdata = str_replace( "\n\n", "\n", $fdata );
							
							if ( ! preg_match( "/\n/", $fdata ) )
							{
								$errors[] = "Could not find any line endings in $filename, skipping file...";
								continue;
							}
							
							$farray = explode( "\n", $fdata );
							
							//----------------------------------------------------
							
							$functions = array();
							
							foreach($farray as $f)
							{
								
								// Skip javascript functions...
								
								if ( preg_match( "/<script/i", $f ) )
								{
									$script_token = 1;
								}
								
								if ( preg_match( "/<\/script>/i", $f ) )
								{
									$script_token = 0;
								}
								
								//-------------------------------
								
								if ($script_token == 0)
								{
									if ( preg_match( "/^function\s*([\w\_]+)\s*\((.*)\)/i", $f, $matches ) )
									{
										$functions[$matches[1]] = '';
										$config[$matches[1]]    = $matches[2];
										$flag = $matches[1];
										continue;
									}
								}
									
								if ($flag)
								{
									$functions[$flag] .= $f."\n";
									continue;
								}
								 
							}
							
							//----------------------------------------------------
							// Remove current templates for this group...
							//----------------------------------------------------
							
							//$DB->query("DELETE FROM ibf_skin_templates WHERE set_id='".$IN['id']."' AND group_name='$name'");
							
							$final = "";
							$flag  = 0;
							
							foreach($functions as $fname => $ftext)
							{
								preg_match( "/return <<<(EOF|HTML)(.+?)(EOF|HTML);/s", $ftext, $matches );
								
								// Are we updating a current set, but have a new group to add?
								// Who knows, but it's bloody exciting
								
								if ($insert == 0)
								{
									if ($curr_group[$name][$fname] != 1)
									{
										// Not a current group..
										
										$insert = 1;
									}
								}
								
								// Swap fake newlines
								
								$matches[2] = str_replace( '\n', '\\\n', $matches[2] );
								
								// Swap real newlines
								//$matches[2] = str_replace( "\r", '\\r', $matches[2] );
								//$matches[2] = str_replace( "\n", '\\n', $matches[2] );
								
								if ($insert == 0)
								{
								
									$db_update = $DB->compile_db_update_string( array (
																					'section_content' => str_replace( '\n', '\\\n', $matches[2] ),
																					'func_data'       => trim($config[$fname]),
																					'updated'         => time(),
																		  )       );
																		  									  
									$DB->query("UPDATE ibf_skin_templates SET $db_update WHERE func_name='$fname' AND set_id='".$IN['id']."' AND group_name='$name'");
								}
								else
								{
								
									$db_update = $DB->compile_db_update_string( array (
																					'set_id'          => $IN['id'],
																					'group_name'      => $name,
																					'section_content' => str_replace( '\n', '\\\n', $matches[2] ),
																					'func_name'       => $fname,
																					'func_data'       => trim($config[$fname]),
																					'updated'         => time(),
																		  )       );
									$DB->query("INSERT INTO ibf_skin_templates SET $db_update");
								}
								
								
							}
							
							$functions = array();
							
							//----------------------------------------------------
							
						} // if *.php
						
					} // if not dir
					
				} // while loop
				
				closedir($handle);
				
			}
			else
			{
				$ADMIN->error("Could not open directory $skin_dir for reading!");
			}
		}
		else
		{
			$ADMIN->error("$skin_dir is not a directory, please check the \$root_path variable in admin.php");
		}
		
		$ADMIN->done_screen("Editable templates updated from source PHP skin files", "Manage Template sets", "act=templ" );
		
		if (count($errors > 0))
		{
			$this->html .= $SKIN->start_table("Errors and warnings");
		
			$this->html .= $SKIN->add_td_basic( implode("<br>", $errors) );
											 
			$this->html .= $SKIN->end_table();
		}
		
		
	}
	
	
	//-------------------------------------------------------------
	// Add templates
	//-------------------------------------------------------------
	
	
	function add_templates()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_VARS;
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//-------------------------------------
		
		if ($INFO['safe_mode_skins'] != 1)
		{
		
			if (SAFE_MODE_ON == 1)
			{
				$ADMIN->error("Safe mode detected, you will need to change the board configuration to switch 'Safe Mode Skins' on. To do this, click on the 'Board Settings' menu and choose 'Basic Config' when the sub menu appears.");
			}
		
			if ( ! is_writeable($root_path.'Skin') )
			{
				$ADMIN->error("The directory 'Skin' is not writeable by this script. Please check the permissions on that directory. CHMOD to 0777 if in doubt and try again");
			}
			
			//-------------------------------------
			
			if ( ! is_dir($root_path.'Skin/s'.$IN['id']) )
			{
				$ADMIN->error("Could not locate the original template set to copy, please check and try again");
			}
		
		}
		
		//-------------------------------------
		
		$DB->query("SELECT * FROM ibf_tmpl_names WHERE skid='".$IN['id']."'");
		
		//-------------------------------------
		
		if ( ! $row = $DB->fetch_row() )
		{
			$ADMIN->error("Could not query that template set from the DB, so there");
		}
		
		//-------------------------------------
		
		$row['skname'] = $row['skname'].".NEW";
		
		// Insert a new row into the DB...
		
		$final = array();
		
		foreach($row as $k => $v)
		{
			if ($k == 'skid')
			{
				continue;
			}
			else
			{
				$final[ $k ] = $v;
			}
		}
		
		$db_string = $DB->compile_db_insert_string( $final );
		
		$DB->query("INSERT INTO ibf_tmpl_names (".$db_string['FIELD_NAMES'].") VALUES(".$db_string['FIELD_VALUES'].")");
		
		$new_id = $DB->get_insert_id();
		
		//-------------------------------------
		
		if ($INFO['safe_mode_skins'] != 1)
		{
		
			if (SAFE_MODE_ON == 1)
			{
				$ADMIN->error("Safe mode detected, you will need to change the board configuration to switch 'Safe Mode Skins' on. To do this, click on the 'Board Settings' menu and choose 'Basic Config' when the sub menu appears.");
			}
		
			//-------------------------------------
			
			if ( ! $ADMIN->copy_dir( $INFO['base_dir'].'Skin/s'.$IN['id'] , $INFO['base_dir'].'Skin/s'.$new_id ) )
			{
				$DB->query("DELETE FROM ibf_tmpl_names WHERE skid='$new_id'");
				
				$ADMIN->error( $ADMIN->errors );
			}
		
		}
		
		// Copy over the templates stored inthe database...
		
		$get = $DB->query("SELECT * FROM ibf_skin_templates WHERE set_id='".$IN['id']."'");
		
		while( $r = $DB->fetch_row($get) )
		{
			$row = $DB->compile_db_insert_string( array (
														'set_id'          =>  $new_id,
														'group_name'      =>  $r['group_name'],
														'section_content' =>  stripslashes($r['section_content']),
														'func_name'       =>  $r['func_name'],
														'func_data'       =>  $r['func_data'],
														'updated'         => time(),
														'can_remove'      => $r['can_remove'],
											 )       );
											 
			$put = $DB->query("INSERT INTO ibf_skin_templates ({$row['FIELD_NAMES']}) VALUES({$row['FIELD_VALUES']})");
		}
		
		//-------------------------------------
		// All done, yay!
		//-------------------------------------
		
		$ADMIN->done_screen("New Template Set", "Manage Template sets", "act=templ" );
	
	}
	
	//-------------------------------------------------------------
	// REMOVE WRAPPERS
	//-------------------------------------------------------------
	
	function remove()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_POST_VARS;
		
		//+-------------------------------
		
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		
		if ($INFO['safe_mode_skins'] != 1)
		{
		
			if (SAFE_MODE_ON == 1)
			{
				$ADMIN->error("Safe mode detected, you will need to change the board configuration to switch 'Safe Mode Skins' on. To do this, click on the 'Board Settings' menu and choose 'Basic Config' when the sub menu appears.");
			}
			
			if ( ! $ADMIN->rm_dir( $INFO['base_dir']."Skin/s".$IN['id'] ) )
			{
				$ADMIN->error("Could not remove the template files, please check the CHMOD permissions to ensure that this script has the correct permissions to allow this");
			}
			
		}
		
		$DB->query("DELETE FROM ibf_tmpl_names WHERE skid='".$IN['id']."'");
		$DB->query("DELETE FROM ibf_skin_templates WHERE set_id='".$IN['id']."'");
		
		$std->boink_it($SKIN->base_url."&act=templ");
		exit();
		
		
	}
	
	//-------------------------------------------------------------
	// EDIT TEMPLATES, STEP TWO
	//-------------------------------------------------------------
	
	function edit_bit()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP, $HTTP_COOKIE_VARS;
		
		//-----------------------------------
		// Check for valid input...
		//-----------------------------------
		
		if ($IN['suid'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		$DB->query("SELECT * FROM ibf_skin_templates WHERE suid='".$IN['suid']."'");
		
		//-----------------------------------
		
		if ( ! $template = $DB->fetch_row() )
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//-----------------------------------
		
		if ( $cookie = $HTTP_COOKIE_VARS['ad_tempform'] )
		{
			list($rows, $cols) = explode( '-', $cookie );
		}
		
		$cols = $cols ? $cols : 80;
		$rows = $rows ? $rows : 40;
		$wrap = 'soft';
		
		//+-------------------------------
		
		$DB->query("SELECT * from ibf_tmpl_names WHERE skid='".$template['set_id']."'");
		
		if ( ! $row = $DB->fetch_row() )
		{
			$ADMIN->error("Could not query the information from the database");
		}
		
		//+-------------------------------
		// Swop < and > into ascii entities
		// to prevent textarea breaking html
		//+-------------------------------
		
		$templ = $this->convert_tags($template['section_content']);
		
		$templ = preg_replace("/&/", "&#38;", $templ );
		$templ = preg_replace("/</", "&#60;", $templ );
		$templ = preg_replace("/>/", "&#62;", $templ );
		
		//+-------------------------------
	
		$ADMIN->page_detail = "You may edit the HTML of this template.";
		$ADMIN->page_title  = "Template Editing";
		
									     
		//+-------------------------------
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->js_template_tools();
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'doedit'    ),
												  2 => array( 'act'   , 'templ'     ),
												  3 => array( 'suid'  , $IN['suid'] ),
									     )  , "theform"    );
									     
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"   , "100%" );

		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Template: ".$template['func_name'] );
		
		$ADMIN->html .= $SKIN->add_td_basic( "<input type='button' value='Macro Look-up' id='editbutton' title='View a macro definition' onClick='pop_win(\"code=macro_one&suid={$template['suid']}\", \"MacroWindow\", 400, 200)'>".
											 "&nbsp;<input type='button' value='Compare' id='editbutton' title='Compare the edited version to the original' onClick='pop_win(\"act=rtempl&code=compare&suid={$template['suid']}\", \"CompareWindow\", 500,400)'>".
											 "&nbsp;<input type='button' value='Restore' id='editbutton' title='Restore the original, unedited template bit' onClick='restore(\"{$template['suid']}\",\"{$IN['expand']}\")'>".
											 "&nbsp;<input type='button' value='View Original' id='editbutton' title='View the HTML for the unedited template bit' onClick='pop_win(\"act=rtempl&code=preview&suid={$template['suid']}&type=html\", \"OriginalPreview\", 400,400)'>".
											 "&nbsp;<input type='button' value='Search' id='editbutton' title='Search the templates for a string' onClick='pop_win(\"act=rtempl&code=search&suid={$template['suid']}&type=html\", \"Search\", 500,400)'>".
										     "&nbsp;<input type='button' value='Edit Box Size' id='editbutton' title='Change the size of the edit box below' onClick='edit_box_size(\"$cols\", \"$rows\")'>",
											 "center", "catrow");
											 
		$ADMIN->html .= $SKIN->add_td_basic( "<b>Show me the HTML code for:&nbsp;".
											 "<select name='htmlcode' onChange=\"document.theform.res.value='&'+document.theform.htmlcode.options[document.theform.htmlcode.selectedIndex].value+';'\" id='multitext'><option value='copy'>&copy;</option>
											 <option value='raquo'>&raquo;</option>
											 <option value='laquo'>&laquo;</option>
											 <option value='#149'>&#149;</option>
											 <option value='reg'>&reg;</option>
											 </select>&nbsp;&nbsp;<input type='text' name='res' size=20 id='multitext'>&nbsp;&nbsp;<input type='button' value='select' id='editbutton' onClick='document.theform.res.focus();document.theform.res.select();'>"
			
											, "center", "tdrow1");
		
		$ADMIN->html .= $SKIN->add_td_row( array( 
													"<center>".$SKIN->form_textarea('template', $templ, $cols, $rows, $wrap)."</center>",
									     )      );
									     
		
									     
		$ADMIN->html .= $SKIN->end_form("Update this file");
									     
		$ADMIN->html .= $SKIN->end_table();
		
		$ADMIN->nav[] = array( 'act=templ' ,'Template Control Home' );
		$ADMIN->nav[] = array( "act=templ&code=edit&id={$template['set_id']}" ,$row['skname'] );
		$ADMIN->nav[] = array( "act=templ&code=edit&id={$template['set_id']}&expand={$IN['expand']}", $template['group_name'] );
		$ADMIN->nav[] = array( "", $template['func_name'] );
		
		$ADMIN->output();
		
		
	}
	
	//-------------------------------------------------------------
	// EDIT TEMPLATES, STEP ONE
	//-------------------------------------------------------------
	
	function do_form()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		
		if ($IN['id'] == "")
		{
			$ADMIN->error("You must specify an existing template set ID, go back and try again");
		}
		
		//+-------------------------------
		
		$DB->query("SELECT * from ibf_tmpl_names WHERE skid='".$IN['id']."'");
		
		if ( ! $row = $DB->fetch_row() )
		{
			$ADMIN->error("Could not query the information from the database");
		}
		
		
		$form_array = array();
	
		//+-------------------------------
	
		$ADMIN->page_detail = "Please choose which section you wish to edit below.";
		$ADMIN->page_title  = "Edit Template Set Data";
		
		//+-------------------------------
		
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->js_no_specialchars();
		
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'editinfo'    ),
												  2 => array( 'act'   , 'templ'       ),
												  3 => array( 'id'    , $IN['id']     ),
									     ), "theAdminForm", "onSubmit=\"return no_specialchars('templates')\""      );
									     
		//+-------------------------------
		
		$SKIN->td_header[] = array( "&nbsp;"   , "60%" );
		$SKIN->td_header[] = array( "&nbsp;"   , "40%" );

		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_table( "Edit template information" );
		
									     
		$ADMIN->html .= $SKIN->add_td_row( array( 
													"<b>Template Set Name</b>",
													$SKIN->form_input('skname', $row['skname']),
									     )      );
									     
		
									     
		$ADMIN->html .= $SKIN->add_td_row( array( 
													"<b>Template set author name:</b>",
													$SKIN->form_input('author', $row['author']),
										 )      );
										 
		$ADMIN->html .= $SKIN->add_td_row( array( 
													"<b>Template set author email:</b>",
													$SKIN->form_input('email', $row['email']),
										 )      );
										 
		$ADMIN->html .= $SKIN->add_td_row( array( 
													"<b>Template set author webpage:</b>",
													$SKIN->form_input('url', $row['url']),
										 )      );
									     
		
									     
		$ADMIN->html .= $SKIN->end_form("Edit template set details");
									     
		$ADMIN->html .= $SKIN->end_table();
									     
		
		$ADMIN->nav[] = array( 'act=templ' ,'Template Control Home' );
		
		$ADMIN->output();
		
		
	}
	
	//-------------------------------------------------------------
	// SHOW CURRENT TEMPLATE PACKS
	//-------------------------------------------------------------
	
	function list_current()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		$form_array = array();
	
		$ADMIN->page_detail = "The skin templates contain the all the board HTML. You can edit each section individually via the online editor, or download the template files to edit in another editor.";
		$ADMIN->page_title  = "Manage Template Sets";
		
		//+-------------------------------
		
		$SKIN->td_header[] = array( "Title"        , "30%" );
		$SKIN->td_header[] = array( "Allocation"   , "20%" );
		$SKIN->td_header[] = array( "Edit&nbsp;Properties"    , "20%" );
		$SKIN->td_header[] = array( "Manage HTML"  , "20%" );
		$SKIN->td_header[] = array( "Remove"       , "10%" );
		
		//+-------------------------------
		
		$DB->query("SELECT DISTINCT(s.set_id), t.skid, t.skname from ibf_tmpl_names t, ibf_skins s WHERE s.set_id=t.skid ORDER BY t.skname ASC");
		
		$used_ids = array();
		$show_array = array();
		
		if ( $DB->get_num_rows() )
		{
		
			$ADMIN->html .= $SKIN->start_table( "Current Template sets In Use" );
			
			while ( $r = $DB->fetch_row() )
			{
			
				$show_array[ $r['skid'] ] .= stripslashes($r['skname'])."<br>";
			
				if ( in_array( $r['skid'], $used_ids ) )
				{
					continue;
				}
				
				$ADMIN->html .= $SKIN->add_td_row( array( "<b>".stripslashes($r['skname'])."</b><br>[ <a href='{$SKIN->base_url}&act=templ&code=export&id={$r['skid']}' title='Download this complete template set'>Export</a> ]",
														  "<#X-{$r['skid']}#>",
														  //"<center><a href='".$SKIN->base_url."&act=templ&code=export&id={$r['skid']}'>Download</a></center>",
														  "<center><a href='".$SKIN->base_url."&act=templ&code=dedit&id={$r['skid']}' title='Edit Template Set Name'>Edit Properties</a></center>",
														  "<center><a href='".$SKIN->base_url."&act=templ&code=edit&id={$r['skid']}' title='Edit, upload and download'>Manage HTML</a></center>",
														  "<i>Deallocate before removing</i>",
												 )      );
												   
				$used_ids[] = $r['skid'];
				
				$form_array[] = array( $r['skid'], $r['skname'] );
				
			}
			
			foreach( $show_array as $idx => $string )
			{
				$string = preg_replace( "/<br>$/", "", $string );
				
				$ADMIN->html = preg_replace( "/<#X-$idx#>/", "$string", $ADMIN->html );
			}
			
			$ADMIN->html .= $SKIN->end_table();
		}
		
		if ( count($used_ids) > 0 )
		{
		
			$DB->query("SELECT skid, skname FROM ibf_tmpl_names WHERE skid NOT IN(".implode(",",$used_ids).")");
		
			if ( $DB->get_num_rows() )
			{
			
				$SKIN->td_header[] = array( "Title"          , "50%" );
				$SKIN->td_header[] = array( "Edit&nbsp;Properties"    , "20%" );
				$SKIN->td_header[] = array( "Manage HTML"    , "20%" );
				$SKIN->td_header[] = array( "Remove"         , "10%" );
			
				$ADMIN->html .= $SKIN->start_table( "Current Unallocated Template sets" );
				
				$ADMIN->html .= $SKIN->js_checkdelete();
				
				while ( $r = $DB->fetch_row() )
				{
					
					$ADMIN->html .= $SKIN->add_td_row( array( "<b>".stripslashes($r['skname'])."</b>",
															  //"<center><a href='".$SKIN->base_url."&act=templ&code=export&id={$r['skid']}'>Download</a></center>",
															  "<center><a href='".$SKIN->base_url."&act=templ&code=dedit&id={$r['skid']}'>Edit Properties</a></center>",
															  "<center><a href='".$SKIN->base_url."&act=templ&code=edit&id={$r['skid']}' title='Edit, upload and download'>Manage HTML</a></center>",
															  "<center><a href='javascript:checkdelete(\"act=templ&code=remove&id={$r['skid']}\")'>Remove</a></center>",
													 )      );
													 
					$form_array[] = array( $r['skid'], $r['skname'] );
													   
				}
				
				$ADMIN->html .= $SKIN->end_table();
			}
		}
		
		//+-------------------------------
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'add'     ),
												  2 => array( 'act'   , 'templ'    ),
												  3 => array( 'MAX_FILE_SIZE', '10000000000' ),
									     ) , "uploadform", " enctype='multipart/form-data'"     );
												  
		$SKIN->td_header[] = array( "&nbsp;"  , "40%" );
		$SKIN->td_header[] = array( "&nbsp;"  , "60%" );
		
		$ADMIN->html .= $SKIN->start_table( "Create New Template Set" );
			
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->add_td_row( array( "<b>Base new Template set on...</b>" ,
										  		  $SKIN->form_dropdown( "id", $form_array)
								 )      );
								 
		$ADMIN->html .= $SKIN->add_td_row( array( "<b><u>OR</u> Choose a file from your computer to import</b><br>Note: This must be a template group set.",
												  $SKIN->form_upload(),
										 )      );
		
		$ADMIN->html .= $SKIN->end_form("Create new Template set");
										 
		$ADMIN->html .= $SKIN->end_table();
		
		//+-------------------------------
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->start_form( array( 1 => array( 'code'  , 'tools'     ),
												  2 => array( 'act'   , 'templ'    ),
									     )      );
		
		$SKIN->td_header[] = array( "Tool"  , "50%" );
		$SKIN->td_header[] = array( "run on template set"  , "50%" );
		
		$extra = "";
		
		if ( SAFE_MODE_ON == 1)
		{
			$extra = "<br><span id='detail'>WARNING: Safe mode restrictions detected, some of these tools will not work</span>";
		}
		
		$ADMIN->html .= $SKIN->start_table( "Template Tools".$extra );
			
		//+-------------------------------
		
		$ADMIN->html .= $SKIN->add_td_row( array( $SKIN->form_dropdown( "tool",
																		array(
																				1 => array( 'tmpl', 'Resynchronise the database templates FROM the PHP skin files'   ),
																			 )
												                      ) ,
										  		  $SKIN->form_dropdown( "id", $form_array)
								 )      );
		
		$ADMIN->html .= $SKIN->end_form("Run Tool");
										 
		$ADMIN->html .= $SKIN->end_table();
		
		//+-------------------------------
		//+-------------------------------
		
		$ADMIN->output();
	
	}
	
	function convert_tags($t="")
	{
		if ($t == "")
		{
			return "";
		}
		
		$t = preg_replace( "/{?\\\$ibforums->base_url}?/"            , "{ibf.script_url}"   , $t );
		$t = preg_replace( "/{?\\\$ibforums->session_id}?/"          , "{ibf.session_id}"   , $t );
		$t = preg_replace( "/{?\\\$ibforums->skin\['?(\w+)'?\]}?/"   , "{ibf.skin.\\1}"      , $t );
		$t = preg_replace( "/{?\\\$ibforums->lang\['?(\w+)'?\]}?/"   , "{ibf.lang.\\1}"      , $t );
		$t = preg_replace( "/{?\\\$ibforums->vars\['?(\w+)'?\]}?/"   , "{ibf.vars.\\1}"      , $t );
		$t = preg_replace( "/{?\\\$ibforums->member\['?(\w+)'?\]}?/" , "{ibf.member.\\1}"    , $t );
		
		return $t;
		
	}
	
	function unconvert_tags($t="")
	{
		if ($t == "")
		{
			return "";
		}
		
		$t = preg_replace( "/{ibf\.script_url}/i"   , '{$ibforums->base_url}'         , $t);
		$t = preg_replace( "/{ibf\.session_id}/i"   , '{$ibforums->session_id}'       , $t);
		$t = preg_replace( "/{ibf\.skin\.(\w+)}/"   , '{$ibforums->skin[\''."\\1".'\']}'   , $t);
		$t = preg_replace( "/{ibf\.lang\.(\w+)}/"   , '{$ibforums->lang[\''."\\1".'\']}'   , $t);
		$t = preg_replace( "/{ibf\.vars\.(\w+)}/"   , '{$ibforums->vars[\''."\\1".'\']}'   , $t);
		$t = preg_replace( "/{ibf\.member\.(\w+)}/" , '{$ibforums->member[\''."\\1".'\']}' , $t);
		
		return $t;
		
	}
	
	/*
	<!--IBF_START_FUNC|calendar_events|$events = ""-->
        <tr>
           <td id='category' colspan='2'>{ibf.lang.calender_f_title}</td>
    	</tr>
    	<tr>
          <td id='forum1' width='5%' valign='middle'>{ibf.skin.F_ACTIVE}</td>
          <td id='forum2' width='95%'>$events</td>
        </tr>
    <!--IBF_END_FUNC|calendar_events-->
*/
	
	
	
	function process_upload($raw, $setid, $group, $isnew=0)
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		$skin_dir     = $root_path."Skin/s".$setid;
		
		//-------------------------------------------
		// If we are not using safe mode skins, lets
		// test to make sure we can write to that dir
		//-------------------------------------------
		
		if ($INFO['safe_mode_skins'] != 1)
		{
		
			if (SAFE_MODE_ON == 1)
			{
				if ($isnew == 1)
				{
					$DB->query("DELETE FROM ibf_tmpl_names WHERE skid='$setid'");
				}
				$ADMIN->error("Safe mode detected, you will need to change the board configuration to switch 'Safe Mode Skins' on. To do this, click on the 'Board Settings' menu and choose 'Basic Config' when the sub menu appears.");
			}
			
			// Are we creating a new template set? 
			// if so, lets create the directory
			
			if ($isnew == 1)
			{
				if ( ! is_writeable($root_path.'Skin') )
				{	
					$DB->query("DELETE FROM ibf_tmpl_names WHERE skid='$setid'");
					$ADMIN->error("The directory 'Skin' is not writeable by this script. Please check the permissions on that directory. CHMOD to 0777 if in doubt and try again");
				}
				
				if ( ! file_exists($skin_dir) )
				{
					// Directory does not exist, lets create it
					
					if ( ! @mkdir($skin_dir, 0777) )
					{
						$DB->query("DELETE FROM ibf_tmpl_names WHERE skid='$setid'");
						$ADMIN->error("Could not create directory '$skin_dir' please check the CHMOD permissions and re-try");
					}
					else
					{
						@chmod($skin_dir, 0777);
					}
				}
			}
			else
			{
				if ( ! is_writeable($skin_dir) )
				{
					$ADMIN->error("Cannot write into '$skin_dir', please check the CHMOD value, and if needed, CHMOD to 0777 via FTP. IBF cannot do this for you.");
				}
			}
		
		}
		
		//--------------------------------
		// Remove everything up until the
		// first <!--START tag...
		//--------------------------------
		
		$raw = preg_replace( "/^.*?(<!--IBF_START_FUNC)/s", "\\1", trim($raw));
		
		$raw = str_replace( "\r\n", "\n", $raw);
		
		//+-------------------------------
		// Convert the tags back to php native
		//+-------------------------------
		
		$raw = $this->unconvert_tags($raw);
		
		//+-------------------------------
		// Grab our vars and stuff
		//+-------------------------------
		
		$DB->query("SELECT func_name, group_name FROM ibf_skin_templates WHERE set_id='$setid'");
		
		if ( $DB->get_num_rows() )
		{
			while ( $gname = $DB->fetch_row() )
			{
				$curr_group[ $gname['group_name'] ][ $gname['func_name'] ] = 1;
			}
		}
		
		$master = array();
		$flag   = 0;
		
		$eachline = explode( "\n", $raw );
		
		foreach ($eachline as $line)
		{
			if ($flag == 0)
			{
				// We're not gathering HTML, lets see if we have a new
				// function start..
				
				if ( preg_match( "/\s*<!--IBF_START_FUNC\|(\S+?)\|(.*?)-->\s*/", $line, $matches) )
				{
					$func = trim($matches[1]);
					$data = trim($matches[2]);
					
					if ($func != "")
					{
					
						$flag = $func;
						
						$master[$func] = array( 'func_name'  => $func,
												'func_data'  => $data,
												'content'    => ""
											  );
					}
					continue;
					
				}
				
			}
			
			if ( preg_match("/\s*?<!--IBF_END_FUNC\|$flag-->\s*?/", $line) )
			{
				 // We have found the end of the subbie..
				 // Reset the flag and feed the next line.
				 
				 $flag = 0;
				 continue;
			}
			else
			{
				// Carry on feeding the HTML...
				
				if ( isset($master[$flag]['content']) )
				{
					$master[$flag]['content'] .= $line."\n";
					continue;
				}
			}
			
		}
		
		//+-------------------------------
		// Start parsing the php skin file
		//+-------------------------------
		
		if ($INFO['safe_mode_skins'] != 1)
		{
		
			if (SAFE_MODE_ON == 1)
			{
				$ADMIN->error("Safe mode detected, you will need to change the board configuration to switch 'Safe Mode Skins' on. To do this, click on the 'Board Settings' menu and choose 'Basic Config' when the sub menu appears.");
			}
		
			$final = "<"."?php\n\n".
					 "class $group {\n\n";
			
			foreach( $master as $func_name => $data )
			{
			
				$final .= "\n\nfunction ".trim($data['func_name'])."(".trim($data['func_data']).") {\n".
					   	  "global \$ibforums;\n".
					   	  "return <<<EOF\n";
					   	  
			    $final .= trim($data['content']);
					   
				$final .= "\nEOF;\n}\n";
				
			}
			
			$final .= "\n\n}\n?".">";
			
			if ($fh = fopen( $skin_dir."/".$group.".php", 'w' ) )
			{
				fwrite($fh, $final, strlen($final) );
				fclose($fh);
			}
			else
			{
				if ($isnew == 1)
				{
					$DB->query("DELETE FROM ibf_tmpl_names WHERE skid='$setid'");
				}
				$errors[] = "Could not save information to $phpskin, please ensure that the CHMOD permissions are correct.";
			}
		
		}
		
		//+-------------------------------
		// Update the DB
		//+-------------------------------
		
		
		foreach( $master as $func_name => $data )
		{
		
			if ($isnew == 0)
			{
				if ($curr_group[$group][$func_name] != 1)
				{
					// Not a current group/ func..
					
					$isnew = 1;
				}
			}
		
			if ($isnew == 0)
			{
				$str = $DB->compile_db_update_string( array(
															  'section_content' => stripslashes(trim($data['content'])),
															  'func_data'       => stripslashes(trim($data['func_data']))
													)      );
													
				$DB->query("UPDATE ibf_skin_templates SET $str WHERE set_id='$setid' AND group_name='$group' AND func_name='".trim($data['func_name'])."'");
			}
			else
			{
				$str = $DB->compile_db_insert_string( array(
															  'section_content' => stripslashes(trim($data['content'])),
															  'func_data'       => stripslashes(trim($data['func_data'])),
															  'set_id'          => $setid,
															  'group_name'      => $group,
															  'func_name'       => trim($data['func_name']),
															  'can_remove'      => 0,
													)      );
													
				$DB->query("INSERT INTO ibf_skin_templates ({$str['FIELD_NAMES']}) VALUES({$str['FIELD_VALUES']})");
			}
		}
		
		
		return TRUE;
		
		
	}
	
	
}


?>