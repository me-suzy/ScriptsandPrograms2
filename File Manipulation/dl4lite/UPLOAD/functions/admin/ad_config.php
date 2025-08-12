<?php

$loader = new admin_config();

class admin_config
{
	var $html	= "";
	var $output = "";
	function admin_config()
	{
		global $IN, $OUTPUT;

		switch($IN["act"])
		{
			case 'general':
				$this->config_gen();
				break;
			case 'security':
				$this->config_security();
				break;
			case 'gallery':
				$this->config_gallery();
				break;
			case 'date':
				$this->config_date();
				break;
			case 'phpinfo':
				$this->config_phpinfo();
				break;
			case 'offline':
				$this->config_offline();
				break;
			case 'resync':
				$this->config_resyncCats();
				break;
		}
		
		$OUTPUT->add_output($this->output);
	}

	function config_offline()
	{
		global $CONFIG, $IN, $rwdInfo, $std, $sid;
		$this->output .= admin_head(GETLANG("nav_config"), GETLANG("nav_offline"));

		if ( !empty($IN["posted"]) )
		{
			$CONFIG["isoffline"] = $IN["isoffline"]?1:0;
			$CONFIG["offlinemsg"] = $IN["offlinemsg"];
						
			$std->saveConfig($CONFIG);
			
			$this->output .= GETLANG("config_updated")."<br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=config&act=offline'>".GETLANG("backto")." ".GETLANG("nav_offline")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
		}
		
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=config&act=offline'>";
		
		$this->output .= new_table();
		
		$this->output .= new_row(2, "acptablesubhead");
		$this->output .= GETLANG("config_offline");
		$this->output .= new_row();
		$this->output .= "<strong>".GETLANG("turn_off").":</strong>";
		$this->output .= new_col();
		if ($CONFIG['isoffline']) 
			$off = " checked";
		else
			$on = " checked";
		$this->output .= GETLANG("yes")."<input type='radio' name='isoffline' value='1' {$off}> ".GETLANG("no")."<input type='radio' name='isoffline' value='0' {$on}> ";
		$this->output .= new_row();
		$this->output .= GETLANG("config_offmsg").":";
		$this->output .= new_col();
		$this->output .= "<textarea name='offlinemsg' cols='45' rows='5' wrap='soft'>";
		$CONFIG['offlinemsg']?$this->output .= $CONFIG['offlinemsg']:$this->output .= GETLANG("offlinemessage");
		$this->output .= "</textarea>";		
		$this->output .= end_table();
		
		$this->output .= "<center><input type='hidden' name='posted' value='1'>
		  <input type='submit' name='submit' value='".GETLANG("submit")."'> 
		  <input type='reset' name='reset' value='".GETLANG("reset")."'></center>";
		$this->output .= "</form>";
		
		$this->output .= admin_foot();
	}
	
	// =========================================
	// OK so for several years I've been trusting 
	// ini_set to do this for me only to find it
	// lied to me and stabbed me in the back. I
	// was such a trusting fool. WELL NO MORE! No
	// longer will I be lured in by the sleeky curves
	// of the newest function on the block. From 
	// now on if I want satisfaction I've got to 
	// give it to myself!... erm yeah this will 
	// write a .htaccess that will override any 
	// upload defaults
	// ==========================================
	function write_htaccess()
	{
		global $CONFIG, $std;
		
		$htaccess_output = "";
		$file = $CONFIG['overridetype'];
		if ($file == "none")
			return;
		if ($file != "php.ini" && $file != ".htaccess") 
		{
		    $std->error("Naughty naughty");
			return;
		}
		// So you think you can just put a .htaccess file in the script root
		// and get away with it do you? Fool. 
		if ( $fp = @fopen( ROOT_PATH."/$file", 'r' ) )
		{
			// read through the file
			while (!feof($fp)) 
			{
				// get a line from the file
				$line = fgets($fp, 4096);
				// if its one of the override elements then dont put it back - we'll add it later
				// otherwise its something unrelated to the script to dont touch it
				if (!stristr($line, "post_max_size") && !stristr($line, "upload_max_filesize"))
					$htaccess_output .= $line;

			}
			
			@fclose($fp);
		}
		
		if ($fp = @fopen( ROOT_PATH."/$file", 'w' ) )
		{
			// Now we'll add our override elements
			if ($file == "php.ini")
			{
				$htaccess_output .= "\npost_max_size = ".$CONFIG['post_max_size']."\n";
				$htaccess_output .= "upload_max_filesize = ".$CONFIG['ul_set']."\n";
			}
			else
			{
				$htaccess_output .= "\nphp_value post_max_size ".$CONFIG['post_max_size']."\n";
				$htaccess_output .= "php_value upload_max_filesize ".$CONFIG['ul_set']."\n";
			}
			
			// And relax
			@fwrite($fp, $htaccess_output, strlen($htaccess_output) );
			@fclose($fp);
		}
		else
		{
			$std->error(GETLANG("er_htaccesswrite"));
			return;
		}
		
	}
	
	function config_gen()
	{
		global $CONFIG, $IN, $rwdInfo, $std, $sid, $guser;
		$this->output .= admin_head(GETLANG("nav_config"), GETLANG("nav_genconfig"));

		if ( !empty($IN["posted"]) )
		{
            if ( !DEMO )
            {
			    $CONFIG["sitepath"] = $IN["sitepath"];
			    $CONFIG["siteurl"] = $IN["siteurl"];
            }
			$CONFIG["links_per_page"] = $IN["links_per_page"];
			$CONFIG["default_sort"] = $IN["sortvalue"];
			$CONFIG["default_order"] = $IN["order"];
			$CONFIG["ul_set"] = $IN["ulset"];
			$CONFIG['post_max_size'] = $IN['post_max_size'];
			$CONFIG["post_set"] = $IN["postset"];
			$CONFIG["debuglevel"] = $IN["debuglevel"];
			$CONFIG["max_word_length"] = $IN["max_word_length"];
			$CONFIG["usegzip"] = $IN["usegzip"] ? 1 : 0;
			$CONFIG["sitename"] = $IN["sitename"];
			$CONFIG["php_timeout"] = $IN["php_timeout"];
            $CONFIG['defaultSkin'] = $IN['skinchoice'];
            $CONFIG['defaultLang'] = $IN['langchoice'];
			$CONFIG['partial_transfers'] = $IN['partial_transfers'];
			$CONFIG['nopassthrough'] = $IN['nopassthrough'];
			$CONFIG['speedlimit'] = $IN['speedlimit'];
			$CONFIG['overridetype'] = $IN['overridetype'];
			$CONFIG['approve_uploads'] = $IN['approve_uploads'];
			$CONFIG['guest_uploads'] = $IN['guest_uploads'];
			
			$std->saveConfig($CONFIG);
			
			$this->write_htaccess();
			
			$this->output .= GETLANG("config_updated")."<br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=config&act=general'>".GETLANG("backto")." ".GETLANG("nav_genconfig")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
		}
		
		$this->output .= GETLANG("config_gen")."<br>";
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=config&act=general'>";
		
		$this->output .= new_table();
		if (!DEMO)
		{
			$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("config_server");
			$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_path").":</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='sitepath' type='text' size='30' value='".$CONFIG['sitepath']."'>";
			$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_url").":</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='siteurl' type='text' size='30' value='".$CONFIG['siteurl']."'>";
        }
		$this->output .= new_row();
		$this->output .= "<strong>".GETLANG("op_sitename").":</strong>";
		$this->output .= new_col();
		$this->output .= "<input name='sitename' type='text' size='30' value='".$CONFIG['sitename']."'>";
        $this->output .= new_row();
		$this->output .= "<strong>".GETLANG("op_defaultskin").":</strong>";
		$this->output .= new_col();
		$this->output .= $std->skinListBox($CONFIG['defaultSkin']);
        $this->output .= new_row();
		$this->output .= "<strong>".GETLANG("op_defaultlang").":</strong>";
		$this->output .= new_col();
		$this->output .= $std->langListBox($CONFIG['defaultLang']);

		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("config_listing");
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_lpp")."</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='links_per_page' type='text' size='3' value='".$CONFIG["links_per_page"]."'>";
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_sort")."</strong>";
			$this->output .= new_col();
			// saves code doing this here. Select current option in list box
			if ($CONFIG["default_sort"] == "date")
				$selectdate = "selected";
			else if ($CONFIG["default_sort"] == "author")
				$selectauthor = "selected";
			else if ($CONFIG["default_sort"] == "name")
				$selectname = "selected";
			else if ($CONFIG["default_sort"] == "downloads")
				$selectdownloads = "selected";
			// Display listbox
			$this->output .= "<select name=sortvalue>
				<option value='date' $selectdate>".GETLANG("dateSub")."</option>
				<option value='author' $selectauthor>".GETLANG("author")."</option>
				<option value='name' $selectname>".GETLANG("name")."</option>
				<option value='downloads' $selectdownloads>".GETLANG("nodl")."</option>
			  </select>";
			  
			if ( $CONFIG["default_order"] == "ASC" )
				$selectasc = "selected";
			else
				$selectdesc = "selected";
			$this->output .= "<select name=order>
				<option value='ASC' $selectasc>".GETLANG("asc")."</option>
				<option value='DESC' $selectdesc>".GETLANG("desc")."</option>
			  </select>";  
		
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= "<b>".GETLANG("op_phpoverride").":</b><br>";
		$this->output .= new_row(2);
			$this->output .= GETLANG("op_phpoverrideDesc");
		if (!DEMO) 
		{
			$this->output .= new_row();
				$this->output .= "<b>".GETLANG("op_overridetype").":</b>";
				$this->output .= new_col();
				if ($CONFIG['overridetype'] == "php.ini")
					$select1 = "selected";
				else if ($CONFIG['overridetype'] == ".htaccess")
					$select2 = "selected";
				else
					$select0 = "selected";
				$this->output .= "<select name='overridetype'>
									<option value='none' $select0>None</option>
									<option value='php.ini' $select1>php.ini</option>
									<option value='.htaccess' $select2>.htaccess</option>
								</select>";	
		}
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_maxsize").":</b>";
			$this->output .= new_col();
			if (!DEMO)
				$this->output .= "<input name='ulset' type='text' size='4' value='".$CONFIG["ul_set"]."'>";
			else
				$this->output .= "<input name='ulset' type='text' size='4' value='1M' disabled='true'>";
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_phptimeout").":</b>";
			$this->output .= new_col();
			if (!DEMO)
				$this->output .= "<input name='php_timeout' type='text' size='4' value='".$CONFIG["php_timeout"]."'>";
			else
				$this->output .= "<input name='php_timeout' type='text' size='4' value='30' disabled='true'>";

		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_postsize").":</b>";
			$this->output .= new_col();
			if (!DEMO)
				$this->output .= "<input name='post_max_size' type='text' size='4' value='{$CONFIG['post_max_size']}'>";
			else
				$this->output .= "<input name='post_max_size' type='text' size='4' value='{$CONFIG['post_max_size']}' disabled='true'>";
			
        $this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("config_uploads");
        $this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_guestupload")."</strong>";
			$this->output .= new_col();
            if ( $CONFIG['guest_uploads'] )
				$this->output .= "<input type='checkbox' name='guest_uploads' value='1' checked>";
			else
				$this->output .= "<input type='checkbox' name='guest_uploads' value='1'>";
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_approveuploads")."</strong>";
			$this->output .= new_col();
            if ( $CONFIG['approve_uploads'] )
				$this->output .= "<input type='checkbox' name='approve_uploads' value='1' checked>";
			else
				$this->output .= "<input type='checkbox' name='approve_uploads' value='1'>";
				
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("config_debug");
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_debugoutput")."</strong>";
			$this->output .= new_col();
			if ( $CONFIG["debuglevel"] == 0 )
				$select0 = "selected";
			if ( $CONFIG["debuglevel"] == 1 )
				$select1 = "selected";
			if ( $CONFIG["debuglevel"] == 2 )
				$select2 = "selected";
			if ( $CONFIG["debuglevel"] == 3 )
				$select3 = "selected";
			$this->output .= "<select name='debuglevel'>
				<option value='0' $select0>0: No Debug Output</option>
				<option value='1' $select1>1: Query Count & Server Load</option>
				<option value='2' $select2>2: Level 1 + SQL Queries</option>
				<option value='3' $select3>3: Level 2 + GET & POST Data</option>
			  </select>";
			
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= "<b>".GETLANG("op_optimisation").":</b><br>";
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_gzip").":</b>";
			$this->output .= new_col();
			if ( $CONFIG['usegzip'] )
				$this->output .= "<input type='checkbox' name='usegzip' value='1' checked>";
			else
				$this->output .= "<input type='checkbox' name='usegzip' value='1'>";
				
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= "<b>".GETLANG("op_streaming").":</b><br>";
		$this->output .= new_row(2);
			$this->output .= GETLANG("op_streamingDesc");
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_partialtransfer").":</b>";
			$this->output .= new_col();
			if ( $CONFIG['partial_transfers'] )
				$this->output .= "<input type='checkbox' name='partial_transfers' value='1' checked>";
			else
				$this->output .= "<input type='checkbox' name='partial_transfers' value='1'>";
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_nopassthru").":</b>";
			$this->output .= new_col();
			if ( $CONFIG['nopassthrough'] )
				$this->output .= "<input type='checkbox' name='nopassthrough' value='1' checked>";
			else
				$this->output .= "<input type='checkbox' name='nopassthrough' value='1'>";
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_speedlimit").":</b>";
			$this->output .= new_col();
			$this->output .= "<input name='speedlimit' type='text' size='8' value='{$CONFIG['speedlimit']}'> k";
		
		$this->output .= end_table();
		
		$this->output .= "<center><input type='hidden' name='posted' value='1'>
		  <input type='submit' name='submit' value='".GETLANG("submit")."'> 
		  <input type='reset' name='reset' value='".GETLANG("reset")."'></center>";
		$this->output .= "</form>";
		
		$this->output .= admin_foot();
	}
	
	function config_security()
	{
		global $CONFIG, $IN, $sid, $std;
		$this->output .= admin_head(GETLANG("nav_config"), GETLANG("nav_security"));
		
		if ( !empty($IN["posted"]) )
		{
			$CONFIG["session"] = $IN["session"];
            $CONFIG["hta_user"] = $IN["hta_user"];
            $CONFIG["hta_pass"] = $IN["hta_pass"];
			$CONFIG["doscriptcheck"] = $IN["doscriptcheck"];
			
			$std->saveConfig();
			
			$this->output .= GETLANG("config_updated")."<br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=config&act=security'>".GETLANG("backto")." ".GETLANG("nav_security")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
		}
		
		$this->output .= GETLANG("config_security")."<br>";
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=config&act=security'>";
		
		$this->output .= new_table();
		
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= "<b>".GETLANG("op_session")."</b><br>";
		$this->output .= new_row(2);
			$this->output .= GETLANG("op_sessionDesc");
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_session").":</b>";
			$this->output .= new_col();
			$this->output .= "<input name='session' type='text' size='4' value='".$CONFIG["session"]."'> ".GETLANG("minutes");

        $this->output .= new_row(2, "acptablesubhead");
			$this->output .= "<b>".GETLANG("op_htaccess")."</b><br>";
        $this->output .= new_row(2);
			$this->output .= GETLANG("op_htaccessDesc");
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("username").":</b>";
		    $this->output .= new_col();
			$this->output .= "<input name='hta_user' type='text' size='20' value='".$CONFIG["hta_user"]."'> ";
        $this->output .= new_row();
			$this->output .= "<b>".GETLANG("password").":</b>";
		    $this->output .= new_col();
			$this->output .= "<input name='hta_pass' type='password' size='20' value='".$CONFIG["hta_pass"]."'> ";

		
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= "<b>".GETLANG("op_scriptcheck")."</b><br>";
		$this->output .= new_row(2);
			$this->output .= GETLANG("op_scriptcheckDesc");
		$this->output .= new_row();
			$this->output .= "<b>".GETLANG("op_doscriptcheck").":</b>";
			$this->output .= new_col();
			if ($CONFIG["doscriptcheck"])
				$this->output .= "<input type='checkbox' name='doscriptcheck' value='1' checked>";
			else
				$this->output .= "<input type='checkbox' name='doscriptcheck' value='1'>";

		$this->output .= end_table();
		
		$this->output .= "<center><input type='hidden' name='posted' value='1'>
		  <input type='submit' name='submit' value='".GETLANG("submit")."'> 
		  <input type='reset' name='reset' value='".GETLANG("reset")."'></center>";
		$this->output .= "</form>";
		
		$this->output .= admin_foot();
	}
	
	function config_gallery()
	{
		global $CONFIG, $IN, $sid, $std;
		$this->output .= admin_head(GETLANG("nav_config"), GETLANG("nav_gallery"));
		
		if ( !empty($IN["posted"]) )
		{
			$CONFIG["thumbWidth"] = $IN["thumbWidth"];
			$CONFIG["thumbHeight"] = $IN["thumbHeight"];
			$CONFIG["thumb_generate"] = $IN["thumb_generate"];
            $CONFIG["copyright"] = $IN["copyright"];
			$CONFIG["copystring"] = $IN["copystring"];

			$std->saveConfig();
			
			$this->output .= GETLANG("config_updated")."<br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=config&act=gallery'>".GETLANG("backto")." ".GETLANG("nav_gallery")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
		}
		
		$this->output .= GETLANG("config_gallery")."<br>";
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=config&act=gallery'>";
		
		$this->output .= new_table();
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("config_thumbnail");
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_thumbwidth")."</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='thumbWidth' type='text' size='4' value='".$CONFIG["thumbWidth"]."'>";
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_thumbheight")."</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='thumbHeight' type='text' size='4' value='".$CONFIG["thumbHeight"]."'>";
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_autothumb")."</strong>";
			$this->output .= new_col();
			// saves code doing this here. Select current option in list box
			if ($CONFIG["thumb_generate"] == "gd2")
				$selectdate = "selected";
			else if ($CONFIG["thumb_generate"] == "gd")
				$selectauthor = "selected";
			else if ($CONFIG["thumb_generate"] == "none")
				$selectname = "selected";
			// Display listbox
			$this->output .= "<select name='thumb_generate'>
				<option value='gd2' $selectdate>GD Library v2</option>
				<option value='gd' $selectauthor>GD library v1</option>
				<option value='none' $selectname>".GETLANG("config_noautothumb")."</option>
			  </select>";
        $this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("config_imgcopy");
		$this->output .= new_row(2);
			$this->output .= GETLANG("config_imgcopydesc");
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_autocopy").":</strong>";
			$this->output .= new_col();
			if ($CONFIG["copyright"])
				$this->output .= "<input type='checkbox' name='copyright' value='1' checked>";
			else
				$this->output .= "<input type='checkbox' name='copyright' value='1'>";
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_copystring")."</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='copystring' type='text' size='25' value='".$CONFIG["copystring"]."'>";

		$this->output .= end_table();
		
		$this->output .= "<center><input type='hidden' name='posted' value='1'>
		  <input type='submit' name='submit' value='".GETLANG("submit")."'> 
		  <input type='reset' name='reset' value='".GETLANG("reset")."'></center>";
		$this->output .= "</form>";
		
		$this->output .= admin_foot();
	}
	
	function config_date()
	{
		global $CONFIG, $IN, $sid, $std;
		$this->output .= admin_head(GETLANG("nav_config"), GETLANG("nav_date"));
		
		if ( !empty($IN["posted"]) )
		{
			$CONFIG["dateformat"] = $IN["dateformat"];
			$CONFIG["timeadjust"] = $IN["timeadjust"];
			$CONFIG["timezone"] = $IN["timezone"];
			
			$std->saveConfig();
			
			$this->output .= GETLANG("config_updated")."<br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=config&act=date'>".GETLANG("backto")." ".GETLANG("nav_date")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
		}
		
		$this->output .= GETLANG("config_date")."<br>";
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=config&act=date'>";
		
		$this->output .= new_table();
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("config_dateadj");
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_servertime").":</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='timeadjust' type='text' size='4' value='".$CONFIG["timeadjust"]."'><br>";
			$this->output .= GETLANG("config_timenow").date($CONFIG["dateformat"]);
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_timezone").":</strong>";
			$this->output .= new_col();
			$this->output .= "<input name='timezone' type='text' size='6' value='".$CONFIG["timezone"]."'>";
		$this->output .= new_row();
			$this->output .= "<strong>".GETLANG("op_timeformat").":</strong><br>";
			$this->output .= "Info: <a href='http://www.php.net/date' target='_blank'>PHP Date</a>";
			$this->output .= new_col();
			$this->output .= "<input name='dateformat' type='text' size='20' value='".$CONFIG["dateformat"]."'>";
		
		$this->output .= end_table();
		
		$this->output .= "<center><input type='hidden' name='posted' value='1'>
		  <input type='submit' name='submit' value='".GETLANG("submit")."'> 
		  <input type='reset' name='reset' value='".GETLANG("reset")."'></center>";
		$this->output .= "</form>";
		
		$this->output .= admin_foot();
	}
			
	function config_phpinfo()
	{
        if  (!DEMO)
		    phpinfo();
        else
            $this->output .= "Disabled for security";
	}

	function config_resyncCats()
	{
	    global $DB, $rwdInfo, $std;

		$cats = $DB->query("SELECT * FROM dl_categories");
	    if ($myrow = $DB->fetch_row($cats)) 
		{
			do
			{
				$std->resyncCats($myrow['cid']);
			} while($myrow = $DB->fetch_row($cats));
	    };

	    $std->info(GETLANG("catsresynced"));
	}
}

?>