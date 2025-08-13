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

if(!function_exists("_detect"))
{
        print "You can't make direct access to this file";
        exit();
}

function browse_profile()
{
	global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
	global $gbl_sid,$gbl_id,$gbl_type, $gbl_name,$cperf;

        $tpl_template_url=$_cfig[url_tpl];
	
	$url_skins=$_cfig[url_skins];
	$url_upload=$_cfig[url_upload];
	$url_php=$_cfig[url_php];
	$url_tpl=$_cfig[url_tpl];
        $in_date=_date (time());
        if($gbl_env["opt"]=="")
        {
                if($gbl_type=='guest'){_err("You have to logged in before you can modify your profile. <a href='index.php?action=clear'>Click here</a> to login.");}
		if($_cfig{"isvb"}==1) {Header("Location: {$_cfig{"vb_url"}}/usercp.php");}
		if($gbl_env["proceed"]!="true")
                {
                        $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_uid`='$gbl_id';")
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
                                elseif($nnet_type==1) {        $formfield="<textarea rows=\"4\" name=\"$nnet_fid\" cols=\"39\">$dbhash[$nnet_fid]</textarea>";}
                                elseif($nnet_type==2) { if($dbhash[$nnet_fid]==1){$check="checked";}else{$check="";}$formfield="<input type=\"checkbox\" name=\"$nnet_fid\" value=\"1\" $check>";}
                                else                  { if($dbhash[$nnet_fid]==1){$check="checked";}else{$check="";}$formfield="<input type=\"radio\" name=\"$nnet_fid\" value=\"1\" $check>";}
                                $out_tables.="<tr><td width=\"18%\" valign=\"top\" height=\"19\" bgcolor=\"#E5E5E5\" style=\"border-left-width: 1; border-right-style: solid; border-right-width: 1; border-top-width: 1; border-bottom-width: 1\" align=\"right\">&nbsp; $nnet_fname:&nbsp;&nbsp; </td><td width=\"82%\" valign=\"top\" height=\"19\"  background=\"$tpl_template_url/gfx/front/main_zoom_grid.gif\">&nbsp;$formfield</td></tr>";
                        }
                        mysql_free_result($result);
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_users_edit.html",0));
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
                        $result = mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_users` SET `nnet_pass`='".$gbl_env["nnet_pass"]."',`nnet_uname`='".$gbl_env["nnet_name"]."',`nnet_email`='".$gbl_env["nnet_email"]."' $queriefields WHERE `nnet_uid`='$gbl_id';")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        mysql_free_result($result);
                        $tmp=$gbl_env["nnet_name"];
                        print "<br><br>User '<b>".$gbl_env["nnet_name"]."</b>' has been updated successfully. <a href=\"$_cfig[url_php]/browse.php?mod=profile\">Click here</a> to continue.";
                }
        }
}

?>