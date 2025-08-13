<?
///////////////////////////////////////////////////////////////////////////////
//      =   =       ====  =   = ====                                         //
//      =   =       =   = =   = =   =                                        //
//      ==  =  ===  =   = =   = =   =                                        //
//      = = = =   = ====  ===== ====                                         //
//      =  == ===== =     =   = =                                            //
//      =   = =     =     =   = =                                            //
//      =   =  ==== =     =   = =                                            //
//      ------------------------------------------------------               //
//      ====        =     ===     =         =                                //
//      =   =       =       =               =                                //
//      =   = =   = ====    =   ===    ==== ====   ===   ===                 //
//      ====  =   = =   =   =     =   =     =   = =   = =   =                //
//      =     =   = =   =   =     =    ===  =   = ===== =                    //
//      =     =   = =   =   =     =       = =   = =     =                    //
//      =      ===  ====  ===== ===== ====  =   =  ==== =                    //
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
// Program Name         : Nephp Publisher Enterprise                         //
// Release Version      : 3.04                                               //
// Program Author       : Kenny Ngo     (CTO of Nelogic Technologies.)       //
// Program Author       : Ewdision Then (CEO of Nelogic Technologies.)       //
// Retail Price         : $499.00 United States Dollars                      //
// WebForum Price       : $000.00 Always 100% Free                           //
// ForumRu Price        : $000.00 Always 100% Free                           //
// xCGI Price           : $000.00 Always 100% Free                           //
// Supplied by          : Scoons [WTN]                                       //
// Nullified by         : CyKuH [WTN]                                        //
// Distribution         : via WebForum, ForumRU and associated file dumps    //
///////////////////////////////////////////////////////////////////////////////

function admin_options()
{

        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$_dfig;
	$tpl_template_url=$_cfig[url_tpl];
	
	if($gbl_env["opt"]=='')
	{
		if($gbl_env["proceed"]!=true)
		{
			$int_variables=0;
	
			while(list($key,$value)=each($_dfig))
			{
				$int_variables++;
				$var_fields.="<input type=hidden name=var$int_variables value=\"$key\"><tr><td width=\"30%\"><p align=\"left\">$key&nbsp;&nbsp; </td><td width=\"70%\"><input type=\"text\" name=\"$key\" size=\"60\" style=\"font-size: 8pt; font-family: Verdana; border: 1px solid #000080; background-color: #CCCCCC\" value=\"$value\"></td></tr>\n";
			}
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_main.html",0));
		}
		else
		{
			$fout=fopen ("$_cfig[dir_php]/config.php", "w");
			if ($fout)
			{
				fputs ($fout,"<?\n//////////////////////////////////////////////////////////\n// DYNAMIC SETTINGS                                     //\n//////////////////////////////////////////////////////////\n");
				for($i=1;$i<=$gbl_env["var_total"];$i++)
				{
					fputs ($fout,"\$_dfig[".$gbl_env["var$i"]."]=\"".$gbl_env[$gbl_env["var$i"]]."\";\n");
				}
				fputs ($fout,"?>");
			}
			else
			{
				_err("Unabled to update \"config.php\" file. Please check for the file permission.");
			}
			fclose ($fout);
			print "<br><br>The configuration file has been updated sucessfully.&nbsp;&nbsp; <a href=\"admin.php?mod=options\" target=\"_self\">Click here</a> to continue.";
		}
	}
	elseif($gbl_env["opt"]=='editor')
	{
		
		if ($dir = @opendir($_cfig[dir_tpl]."/html/"))
		{
  			while (($file = readdir($dir)) !== false)
			{
				if(strlen($file)>2 && !is_dir($_cfig[dir_tpl]."/html/$file"))
				{
					if($gbl_env["templatename"] == $file)
					{
						$ht_files.="<option selected>$file</option>\n";
					}
					else
					{
						$ht_files.="<option>$file</option>\n";
					}
				}
			}  
			closedir($dir);
		}
		if($gbl_env["action"]=='')
		{
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_template.html",0));
		}
		elseif($gbl_env["action"]=='LOAD')
		{
			if($gbl_env["templatename"]=="")
			{
				_err("Please specify file name");
			}
			$filename=$gbl_env["templatename"];
			$htmlvalue=_html($_cfig[dir_tpl]."/html/$filename",1);
			$editstatus="File '$filename' loaded successfully.";
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_template.html",0));
		}
		elseif($gbl_env["action"]=='SAVE')
		{
			if($gbl_env["templatename"]=="") {	_err("Please specify file name"); }
			if($gbl_env["htmlvalue"]  == "") {	_err("Empty HTML values");        }
			$filename=$gbl_env["templatename"];
			$htmlvalue=$gbl_env["htmlvalue"];
			$htmlvalue=stripslashes($htmlvalue);
			$out=fopen ($_cfig[dir_tpl]."/html/$filename", "w");
			if ($out)
			{
				fwrite ($out,$htmlvalue);
				fclose ($out);
			}
			else
			{
				_err("unabled to update the file '$filename'.");
			}
			$editstatus="File '$filename' saved successfully.";
			$htmlvalue=htmlspecialchars($htmlvalue);
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_template.html",0));
		}
	}
	elseif($gbl_env['opt']=='info')
	{
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_info.html",0));
	}
	elseif($gbl_env['opt']=='contact')
	{
		if($gbl_env['proceed']!='true')
		{
			$result=mysql_query("SELECT `nnet_uid`,`nnet_uname`,`nnet_email` FROM `$_cfig[sql_db]`.`nnet_users` WHERE 1 ORDER BY `nnet_uname` ASC")
				or die("Error #".mysql_errno().": ".mysql_error());
			$txt=new form_droplist("nnet_uid",1);
			while($data=mysql_fetch_array($result,MYSQL_ASSOC))
			{
				extract($data);
				$txt->add_items($nnet_uname,"$nnet_uname|$nnet_email");
			}
			mysql_free_result($result);
			$members=$txt->build_form();
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_contact.html",0));
		}
		else
		{
			$nnet_hash=explode("|",$gbl_env["nnet_uid"]);
			if($_cfig[smtp]!='' && $_cfig[smtp]=='localhost')
			{
				mail("$nnet_hash[1]", $gbl_env["mail_subject"], $gbl_env["emailmsg"],"From: $_cfig[admin_email]\r\n"."Reply-To: $_cfig[admin_email]\r\n"."X-Mailer: PHP/" . phpversion());
			}
			if($_cfig[sendmail]!='')
			{
			
				_email($nnet_hash[1],$_cfig[admin_email],$_cfig[admin_name],$gbl_env["mail_subject"],$gbl_env["emailmsg"]);
			}
			print "Your email message has been dispatched to '<b>$nnet_hash[1]</b>'.";			
		}
	}
	elseif($gbl_env['opt']=='optimize')
	{
		$sql_table=$_cfig[sql_db];
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_optimize.html",0));
		print "<script>document.ne_catdb.src=\"$tpl_template_url/gfx/cnet/processing.gif\";</script>";
	
		if($result = mysql_query("OPTIMIZE TABLE `$sql_table`.`nnet_articles`;"))
		{
			print "<script>setTimeout(\"ne_catdb()\",300);function ne_catdb(){document.ne_catdb.src=\"$tpl_template_url/gfx/cnet/check.gif\";}</script>";
		}
		else
		{
			print "<script>setTimeout(\"ne_catdb()\",300);function ne_catdb(){document.ne_catdb.src=\"$tpl_template_url/gfx/cnet/failed.gif\";}</script>";
		}		
		mysql_free_result($result);
	
		print "<script>document.ne_polls.src=\"$tpl_template_url/gfx/cnet/processing.gif\";</script>";
		if($result = mysql_query("OPTIMIZE TABLE `$sql_table`.`nnet_category`;"))
		{
			print "<script>setTimeout(\"ne_polls()\",300);function ne_polls(){document.ne_polls.src=\"$tpl_template_url/gfx/cnet/check.gif\";}</script>";
		}
		else
		{
			print "<script>setTimeout(\"ne_polls()\",400);function ne_polls(){document.ne_polls.src=\"$tpl_template_url/gfx/cnet/failed.gif\";}</script>";		
		}
		mysql_free_result($result);
	
		print "<script>document.ne_posts.src=\"$tpl_template_url/gfx/cnet/processing.gif\";</script>";
		if($result = mysql_query("OPTIMIZE TABLE `$sql_table`.`nnet_polls`;"))
		{
			print "<script>setTimeout(\"ne_posts()\",500);function ne_posts(){document.ne_posts.src=\"$tpl_template_url/gfx/cnet/check.gif\";}</script>";
		}
		else
		{
			print "<script>setTimeout(\"ne_posts()\",500);function ne_posts(){document.ne_posts.src=\"$tpl_template_url/gfx/cnet/failed.gif\";}</script>";
		}
		mysql_free_result($result);
		
		print "<script>document.ne_rating.src=\"$tpl_template_url/gfx/cnet/processing.gif\";</script>";
		if($result = mysql_query("OPTIMIZE TABLE `$sql_table`.`nnet_reviews`;"))
		{
			print "<script>setTimeout(\"ne_rating()\",600);function ne_rating(){document.ne_rating.src=\"$tpl_template_url/gfx/cnet/check.gif\";}</script>";
		}
		else
		{
			print "<script>setTimeout(\"ne_rating()\",600);function ne_rating(){document.ne_rating.src=\"$tpl_template_url/gfx/cnet/failed.gif\";}</script>";
		}
		mysql_free_result($result);
		
		print "<script>document.ne_reviews.src=\"$tpl_template_url/gfx/cnet/processing.gif\";</script>";
		if($result = mysql_query("OPTIMIZE TABLE `$sql_table`.`nnet_session`;"))
		{
			print "<script>setTimeout(\"ne_reviews()\",700);function ne_reviews(){document.ne_reviews.src=\"$tpl_template_url/gfx/cnet/check.gif\";}</script>";
		}
		else
		{
			print "<script>setTimeout(\"ne_reviews()\",700);function ne_reviews(){document.ne_reviews.src=\"$tpl_template_url/gfx/cnet/failed.gif\";}</script>";
		}	
		mysql_free_result($result);
		
		print "<script>document.ne_session.src=\"$tpl_template_url/gfx/cnet/processing.gif\";</script>";
		if($result = mysql_query("OPTIMIZE TABLE `$sql_table`.`nnet_users`;"))
		{
			print "<script>setTimeout(\"ne_session()\",800);function ne_session(){document.ne_session.src=\"$tpl_template_url/gfx/cnet/check.gif\";}</script>";
		}
		else
		{
			print "<script>setTimeout(\"ne_session()\",800);function ne_session(){document.ne_session.src=\"$tpl_template_url/gfx/cnet/failed.gif\";}</script>";
		}
		mysql_free_result($result);
		
		print "<script>document.ne_subdb.src=\"$tpl_template_url/gfx/cnet/processing.gif\";</script>";
		if($result = mysql_query("OPTIMIZE TABLE `$sql_table`.`nnet_users_fields`;"))
		{
			print "<script>setTimeout(\"ne_subdb()\",900);function ne_subdb(){document.ne_subdb.src=\"$tpl_template_url/gfx/cnet/check.gif\";}</script>";
		}
		else
		{
			print "<script>setTimeout(\"ne_subdb()\",900);function ne_subdb(){document.ne_subdb.src=\"$tpl_template_url/gfx/cnet/failed.gif\";}</script>";
		}
		mysql_free_result($result);
	}
	else{print "<br><br>Due to the demo, this feature is disabled. <a href='javascript:history.back(1)'>Return</a>";}
}
?>