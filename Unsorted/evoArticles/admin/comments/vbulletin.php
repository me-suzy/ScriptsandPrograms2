<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class Comments
{
	function Comments()
	{
		global $settings,$root;
		/* -- congfiguration -- */
		$this->conf_file = OUT_FOLDER."comment_vb.php";
		$this->data_file = OUT_FOLDER."comment_vb_data.php";
	}
	
	function process_addcomment()
	{
		global $_POST,$database,$admin,$udb,$evoLANG,$_COOKIE;
	}

	function process_addarticle($artlink,$category,$subject,$artid)
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$_POST,$evoLANG,$tpl,$script,$parser,$settings,$script,$art;
		//used when add article function is used

		if (file_exists($this->conf_file))
		{
			include($this->conf_file);
		}
		
		$vdb = new sqldb;
		$vdb->scriptname = "vBulletin Comments System";
		//$vdb->report = 1;
		$vdb->start($config['db_host'],$config['db_user'],$config['db_pass'],$config['db_name']);
			
		if ($vdb->report_error == '')
		{
					
			$config['msg'] = str_replace("{article_url}",$artlink,$config['msg']);
			
			if ($config['usemap'] == 0)
			{
				$cat = $config['defforum'];
			}
			else
			{
				$exmap = explode(",",$config['map']);
				foreach ($exmap as $cmap)
				{
					$val = explode("-",$cmap);
					if ($category == $val['1'])
					{
						$cat = $val['0'];
					}
				}

				$cat = intval($cat) == '' ? $config['defforum']:$cat;
			}	
			
			$message = str_replace("{article_url}",$artlink,$config['msg']);

			$time = time();
			$subject = $config['subjectprefix'].$subject;
			$forumuser = $vdb->query_once("SELECT * FROM user WHERE userid=".$config['userid']);
			$forum = $vdb->query_once("SELECT * FROM forum WHERE forumid='".$cat."'");

			$subject = addslashes($subject);
			$forumuser['username'] = addslashes($forumuser['username']);
			$message = addslashes($message);

			//echo $vdb->report_error;
			$vdb->query("INSERT INTO thread (title,lastpost,forumid,pollid,open,replycount,postusername,lastposter,dateline,views,iconid,notes,visible,sticky,votenum,votetotal,attach,postuserid) VALUES ('".$subject."','".$time."','".$cat."','0','1','0','".$forumuser['username']."','".$forumuser['username']."','".$time."','0','".$config['icons']."','','1','0','0','0','0','".$config['userid']."')");
			
			$threadid = $vdb->insert_id();

			$vdb->query("INSERT INTO post (threadid,username,dateline,attachmentid,pagetext,allowsmilie,showsignature,ipaddress,iconid,visible,edituserid,editdate,userid) VALUES ($threadid,'".$forumuser['username']."',".$time.",'0','$message','1','0','1','','1','0','0',".$config['userid'].")");
			
			$vdb->query("UPDATE user SET posts=posts+1,lastvisit='".$time."',lastactivity='".$time."',lastpost='".$time."' WHERE userid='".$config['userid']."'");

			$vdb->query("UPDATE forum SET replycount=replycount+1,threadcount=threadcount+1,lastpost='".$time."',lastposter='".$forumuser['username']."' WHERE forumid='".$forum['parentlist']."'");

			$this->write_data($artid,$threadid);
		}
	}

	function write_data($artid,$threadid)
	{
		global $admin;
		//thread url
		$thread_file = "showthread.php?threadid=".$threadid;
		$content = "<id_".$artid.">$thread_file</id_".$artid.">";
		
		if (file_exists($this->data_file))
		{
			$admin->write_file($this->data_file,$content,0,"a+");
		}
		else
		{
			$admin->write_file($this->data_file,$content);
		}
	}

	function redirect($loc)
	{
		if ($loc != "")
		{
			header("location: ".$loc);
		}
	}

	function showcomments($artid='')
	{
		// shouldnt need this
	}

	function showform($id='')
	{
		global $admin,$evoLANG,$settings;

		// show comment form
		if (file_exists($this->conf_file))
		{
			include($this->conf_file);
			
			$replace = $admin->fast_get_tag( $admin->get_file($this->data_file),"id_".$id );
			
			if ($replace != '')
			{
				if (!preg_match("#".$config['url']."#",$replace) )
				{
					$replace = $config['url']."/".$replace;
				}

				$content = str_replace("{article_url}",$admin->makelink($replace,$replace),$evoLANG['forumlink_msg']);
			}
			else
			{	
				$content = $evoLANG['forumlink_xavail'];
			}

			return $content;
		}
	}

	function manage()
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$_POST,$evoLANG,$tpl,$script,$parser,$settings,$script,$art;
		
		if (file_exists($this->conf_file))
		{
			include($this->conf_file);
			$this->loaded  = 1;
		}

		$_POST = $admin->slash_array($_POST);
		$config = $admin->strip_array($config);

		if ($_POST['db_settings'])
		{
			if ($this->loaded)
			{				
				foreach ($config as $optname => $value)
				{					
					switch($optname)
					{
						case "db_host":
							$value = $_POST['opt'][$optname];
						break;
						case "db_user":
							$value = $_POST['opt'][$optname];
						break;
						case "db_pass":
							$value = $_POST['opt'][$optname];
						break;
						case "db_name":
							$value = $_POST['opt'][$optname];
						break;				
					}

					$fval .= "\$config[".$optname."] = \"".$value."\";\n\r";
				}
			
			}
			else
			{
				foreach ($_POST['opt'] as $optname => $value)
				{
					$fval .= "\$config[".$optname."] = \"".$value."\";\n\r";
				}	
			}

			$file_value = "<?php \n\r ".$fval." ?>";
			//echo $fval;
			$admin->write_file($this->conf_file,$file_value);
			header("location: ".$_SERVER['PHP_SELF']);
			exit;
		}
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		$content .= "Please check your vBulletin config file if you are not sure about these database settings. <br /><br />";
		
		$content .= $admin->form_start("",$_SERVER['PHP_SELF']);
		$table .= $admin->add_spacer("Database Configuration");
		$table .= $admin->add_row("vBulletin's mySQL Host",$admin->form_input("opt[db_host]",$config['db_host']));
		$table .= $admin->add_row("vBulletin's mySQL Username",$admin->form_input("opt[db_user]",$config['db_user']));
		$table .= $admin->add_row("vBulletin's mySQL Password",$admin->form_input("opt[db_pass]",$config['db_pass']));
		$table .= $admin->add_row("vBulletin's mySQL Database Name",$admin->form_input("opt[db_name]",$config['db_name']));
		$table .= $admin->form_submit("db_settings");
		$content .= $admin->add_table($table)."</form>";

		if ($this->loaded)
		{
			$content .= "<br /><br />";
			$vdb = new sqldb;
			$vdb->scriptname = "vBulletin Comments System";
			$vdb->report = 1;
			$vdb->start($config['db_host'],$config['db_user'],$config['db_pass'],$config['db_name']);
			
			if ($vdb->report_error != '')
			{
				$content .= $admin->warning($vdb->report_error);
			}
			else
			{
				if ($_POST['configs'])
				{
					
					

					foreach ($config as $optname => $value)
					{
						if ( preg_match("#db_#",$optname) )
						{
							$fval .= "\$config[".$optname."] = \"".$value."\";\n\r";
						}
					}			
					
					foreach ($_POST['conf'] as $coptname => $value2)
					{
						$fval .= "\$config[".$coptname."] = \"".$value2."\"; \n\r";
					}
					
					if (is_array($_POST['map']))
					{

						foreach ($_POST['map'] as $maps => $new)
						{
							if (trim($new['forum'] != '') && trim($new['cat'] != ''))
							{
								$map_array[] = $new['forum']."-".$new['cat'];
							}
						}
						foreach ($map_array as $newmap)
						{
							$mapval = $newmap.",";
						}
						$mapval = substr($mapval,0,-1);
						//print_r($mapval);
						//exit;

						$fval .= "\$config['map'] = \"".$mapval."\"; \n\r";
					}

					//echo $fval;
					//exit;
					//print_r($_POST);
					$file_value = "<?php \n\r ".$fval." ?>";
					$admin->write_file($this->conf_file,$file_value);
					
					header("location: ".$_SERVER['PHP_SELF']);
					exit;
				}


				$icon_sql = $vdb->query("SELECT * FROM icon");
				$posticons .= "0|No Icon,";
				while ($irow = $vdb->fetch_array($icon_sql))
				{
					$posticons .= $irow['iconid']."|".$irow['title'].",";
					if ($irow['iconid'] == $config['icons'])
					{
						$image = " <img src=\"".$config['url']."/".$irow['iconpath']."\" alt=\"$irow[title]\" />";
					}
				}

				$posticons = $admin->form_select("conf[icons]",$posticons,$config['icons'],"");
				
				$content .= $admin->form_start("",$_SERVER['PHP_SELF']);
				$ctable .= $admin->add_spacer("New Thread Options");
				$ctable .= $admin->add_row("vBulletin URL",$admin->form_input("conf[url]",$config['url']),"No trailing slash");
				$ctable .= $admin->add_row("User ID",$admin->form_input("conf[userid]",$config['userid']),"In order for this addon comment system to work, you need to specify a valid User ID so posted thread will be under this user" );
				$ctable .= $admin->add_row("Default Post Icon",$posticons.$image);
				
				$ctable .= $admin->add_row("Thread Title Prefix",$admin->form_input("conf[subjectprefix]",$config['subjectprefix']));
				$ctable .= $admin->add_row("Post Message",$admin->form_textarea("conf[msg]",$config['msg']),"Default Message: <br />Discuss about this article [url]{article_url}[/url], here.<br /><br />Var used: <b>{article_url}</b>");
				
				$ctable .= $admin->add_row("Use Category->Forum Mapping (advance)",$admin->form_radio_yesno("conf[usemap]",$config['usemap']));
				if ($config['usemap'] == 0)
				{
					
					$ctable .= $admin->add_row("Default Forum ID",$admin->form_input("conf[defforum]",$config['defforum']),"All Comments/Feedback threads will be posted in this forum.");
				}
				else
				{
					$ctable .= $admin->add_spacer("Forum and Category->Forum Mapping");
					$exmap = explode(",",$config['map']);
					foreach ($exmap as $cmap)
					{
						$i++;
						$val = explode("-",$cmap);
						$ctable .= $admin->add_row("Mapped","Forum ".$admin->form_input("map[".$i."][forum]",$val[0])." -> Category ".$admin->form_input("map[".$i."][cat]",$val[1]) );
					}
					
					
					$ctable .= $admin->add_row("Add New Mapping","Forum ".$admin->form_input("map[new][forum]")." -> Category ".$admin->form_input("map[new][cat]") );
				}


				$ctable .= $admin->form_submit("configs");
				$content .= $admin->add_table($ctable)."</form>";
				
			}
		}
		else
		{
			$content .= $admin->warning("Please Complete Database Settings  in order to manage other settings");
		}
		return $content;
		// admin manage comments
	}
	
	// forum userid
	// forum password
	// forum url
	// forum mapping

}