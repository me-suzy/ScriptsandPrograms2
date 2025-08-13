<?php
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

if(!function_exists("_detect"))
{
        print "You can't make direct access to this file";
        exit();
}
function admin_members()
{
        global $_cfig,$gbl_env;
        $options=$gbl_env["opt"];
	$tpl_template_url=$_cfig[url_tpl];
	$url_php=$_cfig[url_php];
	$var_approval=$gbl_env["approval"];

        ///////////////////////////////////////////////////////////////////////////
        // POST-SPAN                                                             //
        ///////////////////////////////////////////////////////////////////////////
        if($gbl_env["page"] == '') {$gbl_env["page"]=1;}
        $op_span=$_cfig[op_span];  $gbl_env["page"]--;
        $startpoint=$op_span*$gbl_env["page"];
        //////////////////////////////////////////////////////////////////////////

	if($gbl_env["keywords"]==''){$gbl_env["keywords"]=$gbl_env["vkeywords"];}

        if($gbl_env["opt"] == "")
        {
                $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1 ORDER BY `nnet_order` ASC;")
                          or die("Error #".mysql_errno().": ".mysql_error());
                while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                {
                        extract($data);
			if(strlen($nnet_fname)>17){$p_fname=substr($p_fname,0,14)."...";}
			$formtags.="<option value=\"$nnet_fid\">$nnet_fname</option>";
                }
                mysql_free_result($result);

		if($gbl_env["keywords"]!='') { $extra="`nnet_able`='1' AND `".$gbl_env["stype"]."` REGEXP '".$gbl_env["keywords"]."'"; }
		else 
		{
			if($gbl_env["approval"]==1) { $extra="`nnet_able`='0'";}
			else                        { $extra="1";}
		}
		if($gbl_env["sort"]!='')            { $extra.=" ORDER BY `".$gbl_env["sort"]."`";}
		else                                { $extra.=" ORDER BY `nnet_uid`";}
		
		if($gbl_env["by"]==1)               { $extra.=" ASC";}
		else                                { $extra.=" DESC";}
		
		$line= mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_able`!='1';"));
		$pending=$line[0];
		
		$line = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_users` WHERE $extra;"));
                $totalsize=$line[0];
		
		$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users` WHERE $extra LIMIT $startpoint,$op_span;")
                            or die("Error #".mysql_errno().": ".mysql_error());
                while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                {
                        extract($data);
                        $nnet_date=_date($nnet_date);$tmp=$nnet_able;
			if($nnet_able!=1) { $nnet_able="(locked)";}
			else              { $nnet_able="";        }
			if($nnet_type==2)
			{
				$status="&nbsp;&nbsp;<img src=\"$tpl_template_url/gfx/status_admin.gif\">";
			}
			elseif($nnet_type==1)
			{
				$status="<img src=\"$tpl_template_url/gfx/status_mod.gif\">";
			}
			else
			{
				$status="&nbsp;<img src=\"$tpl_template_url/gfx/status_member.gif\">";
			}
                        $out_tables.="<tr>
                                      <td width=\"25%\" bgcolor=\"#EFEFEF\">&nbsp;$nnet_uname $nnet_able</td>
                                      <td width=\"13%\" bgcolor=\"#EFEFEF\">&nbsp;<input type=\"button\" value=\"E\" style=\"font-size: 8pt; font-family: Verdana; width: 20\" onclick=\"location='admin.php?mod=members&opt=edit&id=$nnet_uid'\">
                                      <input type=\"button\" value=\"D\" style=\"font-size: 8pt; font-family: Verdana; width: 20\" onclick=\"location='admin.php?mod=members&opt=del&id=$nnet_uid'\">
                                      <input type=\"button\" value=\"L\" style=\"font-size: 8pt; font-family: Verdana; width: 20\" onclick=\"location='admin.php?mod=members&opt=able&stage=$tmp&id=$nnet_uid'\"></td>
                                      <td width=\"37%\" bgcolor=\"#EFEFEF\"><table border=\"0\" width=\"100%\"> <tr><td width=\"50%\">&nbsp;$nnet_date</td><td width=\"50%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$status</td></tr></table></td>
                                      </tr>";
                }
                mysql_free_result($result);

		// SPANNING STYLE 1 STARTS HERE
                $gbl_env["page"]++;
                $totalpages=floor($totalsize/$_cfig[op_span]);
		if($totalsize%$_cfig[op_span]>0){ $totalpages++;}
                $out_page=$gbl_env["page"];
                for($i=1;$i<=$totalpages;$i++)
                {

                        if($i==$gbl_env["page"]) { $spandisplay.="[<b>$i</b>]";}
                        else                     { $spandisplay.="[<a href=\"$url_php/admin/admin.php?mod=members&page=$i&keywords=".$gbl_env["keywords"]."&stype=".$gbl_env["stype"]."&sort=".$gbl_env["sort"]."&by=".$gbl_env["by"]."\">$i</a>]";}
                }
		//////////////////////////////////////////////////////////////////

		$in_stype=$gbl_env["stype"];
		$in_keywords=$gbl_env["keywords"];
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_users.html",0));
        }
	elseif($gbl_env["opt"]=="options")
        {
		if($gbl_env["proceed"]!="true")
                {
                        $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1 ORDER BY `nnet_order` ASC;")
                                  or die("Error #".mysql_errno().": ".mysql_error());

                        $html_fields=_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_user_fields.txt",0);
			$p_counter=0;
                        while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                                extract($data);
                                $out_tables.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_fields);
				$p_counter++;
                        }
			mysql_free_result($result);
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_users_options.html",0));
                }
                else
                {
                        if($gbl_env["action"]!="order")
			{
			
				if($gbl_env["futype"] == "" || $gbl_env["funame"]=="")
				{
					_err("ID OR Required components are missing.");
				}
				$requestid="F".time();
				$result = mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_users_fields` (`nnet_fid`,`nnet_fname`,`nnet_type`) VALUES ('$requestid','".$gbl_env["funame"]."','".$gbl_env["futype"]."');")
	                                  or die("Error #".mysql_errno().": ".mysql_error());
				mysql_free_result($result);
				$result = mysql_query("ALTER TABLE `$_cfig[sql_db]`.`nnet_users` ADD `$requestid` CHAR(255) NOT NULL;")
	                                  or die("Error #".mysql_errno().": ".mysql_error());
				mysql_free_result($result);
				print "Form field has been added successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members&opt=options\">Click here</a> to continue.";
			}
			else
			{
				for($i=0;$i<$gbl_env["fieldtotal"];$i++)
				{
					print "UPDATE `$_cfig[sql_db]`.`nnet_users_fields` SET `nnet_order`='".$gbl_env["p_forder_$i"]."' WHERE `nnet_fid`='".$gbl_env["id_$i"]."';<Br>";
					$result = mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_users_fields` SET `nnet_order`='".$gbl_env["p_forder_$i"]."' WHERE `nnet_fid`='".$gbl_env["id_$i"]."';")
	                                  or die("Error #".mysql_errno().": ".mysql_error());
				}
				mysql_free_result($result);
				print "<br><br>Form field has been sorted successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members&opt=options\">Click here</a> to continue.";
			}
                }
        }
	elseif($gbl_env["opt"]=="add")
        {
		if($gbl_env["proceed"]!="true")
		{
			$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1 ORDER BY `nnet_order` ASC;")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                                extract($data);
				if($nnet_type==0)     { $formfield="<input type=\"text\" name=\"$nnet_fid\" size=\"52\">"; }
				elseif($nnet_type==1) {	$formfield="<textarea rows=\"4\" name=\"$nnet_fid\" cols=\"39\"></textarea>";}
				elseif($nnet_type==2) {	$formfield="<input type=\"checkbox\" name=\"$nnet_fid\" value=\"1\">";}
				else                  { $formfield="<input type=\"radio\" name=\"$nnet_fid\" value=\"1\">";}
				$out_tables.="<tr><td width=\"18%\" valign=\"top\" height=\"19\" bgcolor=\"#E5E5E5\" style=\"border-left-width: 1; border-right-style: solid; border-right-width: 1; border-top-width: 1; border-bottom-width: 1\" align=\"right\">&nbsp; $nnet_fname:&nbsp;&nbsp; </td><td width=\"82%\" valign=\"top\" height=\"19\" background=\"$tpl_template_url/gfx/front/main_zoom_grid.gif\">&nbsp;$formfield</td></tr>";
                        }
			mysql_free_result($result);
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_users_add.html",0));
		}
		else
		{
			
			$result = mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_user`='".$gbl_env["username"]."';")
                                  or die("Error #".mysql_errno().": ".mysql_error());
			$data = mysql_fetch_array($result);

			if($data[0] > 0) {_err("This username is already taken. Please return back and select another username.");}
			$result = mysql_query("SELECT `nnet_fid` FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1;")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
				extract($data);
				$tmp=$gbl_env["$nnet_fid"];
				$queriefields.=",`$nnet_fid`";
				$querievalues.=",'$tmp'";
			}
			
			$result = mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_users` (`nnet_user`,`nnet_pass`,`nnet_uname`,`nnet_email`,`nnet_able`,`nnet_type`,`nnet_posts`,`nnet_date` $queriefields) VALUES ('".$gbl_env["nnet_user"]."','".$gbl_env["nnet_pass"]."','".$gbl_env["nnet_name"]."','".$gbl_env["nnet_email"]."','".$gbl_env["enable"]."','".$gbl_env["nnet_staff"]."','".$gbl_env["nnet_posts"]."','".time()."' $querievalues);")
                                  or die("Error #".mysql_errno().": ".mysql_error());
			mysql_free_result($result);
			$tmp=$gbl_env["nnet_name"];
			print "<br><br>User '<b>$tmp</b>' has been added successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members\">Click here</a> to continue.";
		}
	}
	elseif($gbl_env["opt"]=="unreq")
        {
		if($gbl_env["id"]=="") { _err("ID OR Required components are missing.");}
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE `nnet_fid`='".$gbl_env["id"]."' LIMIT 1;")
                             or die("msg: Unabled to delete a from field");
		mysql_query("ALTER TABLE `$_cfig[sql_db]`.`nnet_users` DROP `".$gbl_env["id"]."`;");

		print "<br><br>Form field has been deleted successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members&opt=options\">Click here</a> to continue.";
	}
	elseif($gbl_env["opt"]=="fup")
	{
		$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_users_fields` SET `nnet_fname`='".$gbl_env["txt"]."' WHERE `nnet_fid`='".$gbl_env["id"]."' LIMIT 1;")
                             or die("msg: Unabled to update the specified field field.");
		mysql_free_result($result);
		print "<br><br>Form field has been updated successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members&opt=options\">Click here</a> to continue.";
	}
	elseif($gbl_env["opt"]=="edit")
        {
		if($gbl_env["proceed"]!="true")
		{
			$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_uid`='".$gbl_env["id"]."';")
                                  or die("Error #".mysql_errno().": ".mysql_error());			
			$data = mysql_fetch_array($result);
			extract($data);
			$dbhash=_db($result,$data);
			mysql_free_result($result);

			if($nnet_able==1) { $nnet_enable_check01="checked"; $nnet_enable_check02="";}
			else              { $nnet_enable_check01="";$nnet_enable_check02="checked"; }

			if($nnet_type==1) { $nnet_staff_check01="checked";$nnet_staff_check02="";$nnet_staff_check03="";   }
			elseif($nnet_type==2) { $nnet_staff_check03="checked";$nnet_staff_check02="";$nnet_staff_check01="";  }
			else              { $nnet_staff_check01=""; $nnet_staff_check03="";$nnet_staff_check02="checked";  }

			$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1 ORDER BY `nnet_order` ASC;")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                                extract($data);
				if($nnet_type==0)     { $formfield="<input type=\"text\" name=\"$nnet_fid\" size=\"52\" value=\"$dbhash[$nnet_fid]\">";}
				elseif($nnet_type==1) {	$formfield="<textarea rows=\"4\" name=\"$nnet_fid\" cols=\"39\">$dbhash[$nnet_fid]</textarea>";}
				elseif($nnet_type==2) { if($dbhash[$nnet_fid]==1){$check="checked";}else{$check="";}$formfield="<input type=\"checkbox\" name=\"$nnet_fid\" value=\"1\" $check>";}
				else                  { if($dbhash[$nnet_fid]==1){$check="checked";}else{$check="";}$formfield="<input type=\"radio\" name=\"$nnet_fid\" value=\"1\" $check>";}
                                $out_tables.="<tr><td width=\"18%\" valign=\"top\" height=\"19\" bgcolor=\"#E5E5E5\" style=\"border-left-width: 1; border-right-style: solid; border-right-width: 1; border-top-width: 1; border-bottom-width: 1\" align=\"right\">&nbsp; $nnet_fname:&nbsp;&nbsp; </td><td width=\"82%\" valign=\"top\" height=\"19\"  background=\"$tpl_template_url/gfx/front/main_zoom_grid.gif\">&nbsp;$formfield</td></tr>";
                        }
			mysql_free_result($result);
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_users_edit.html",0));
		}
		else
		{
			
			$result = mysql_query("SELECT `nnet_fid` FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1;")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
				extract($data);
				$queriefields.=",`$nnet_fid`='".$gbl_env["$nnet_fid"]."'";
			}
			$result = mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_users` SET `nnet_user`='".$gbl_env["nnet_user"]."',`nnet_pass`='".$gbl_env["nnet_pass"]."',`nnet_uname`='".$gbl_env["nnet_name"]."',`nnet_email`='".$gbl_env["nnet_email"]."',`nnet_able`='".$gbl_env["enable"]."',`nnet_type`='".$gbl_env["nnet_staff"]."',`nnet_posts`='".$gbl_env["nnet_posts"]."' $queriefields WHERE `nnet_uid`='".$gbl_env["id"]."';")
                                  or die("Error #".mysql_errno().": ".mysql_error());
			mysql_free_result($result);
			$tmp=$gbl_env["nnet_name"];
			print "<br><br>User '<b>".$gbl_env["nnet_name"]."</b>' has been updated successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members\">Click here</a> to continue.";
		}
	}
	elseif($gbl_env["opt"]=="able")
        {
		if($gbl_env["stage"]==1) { $gbl_env["stage"]=0;}
		else                     { $gbl_env["stage"]=1;}

		$result = mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_users` SET `nnet_able`='".$gbl_env["stage"]."' WHERE `nnet_uid`='".$gbl_env["id"]."' LIMIT 1;")
                                  or die("Query failed");
		mysql_free_result($result);
		print "<br><br>This account locked/unlocked successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members\">Click here</a> to continue.";
	}
	elseif($gbl_env["opt"]=="del")
        {
		if($gbl_env["id"]=="")
		{
			_err("Please specify ID or required components.");
		}
		print "<Br><br>Deleting Profiles...<br>";
		print "--------------------------------------------------------------------------<br>";
		$result = mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_uid`='".$gbl_env["id"]."' LIMIT 1;")
                                  or die("Query failed");
		mysql_free_result($result);
		print "This account have been deleted successfully. <a href=\"$_cfig[url_php]/admin/admin.php?mod=members\">Click here</a> to continue.";
	}
}
?>