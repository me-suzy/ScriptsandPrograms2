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

function browse_signup()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_COOKIE_VARS;
        $tpl_template_url=$_cfig[url_tpl];

        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];

        $in_date=_date (time());

        if($gbl_env["opt"] == "")
        {
                if($_cfig{"isvb"}==1)
                {
                       Header("Location: $vb_url/register.php?action=signup");
                }
                else
                {

                        $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1 ORDER BY `nnet_order` ASC;")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                                extract($data);
                                if($nnet_type==0)     { $formfield="<input type=\"text\" name=\"$nnet_fid\" size=\"52\">"; }
                                elseif($nnet_type==1) {        $formfield="<textarea rows=\"4\" name=\"$nnet_fid\" cols=\"39\"></textarea>";}
                                elseif($nnet_type==2) {        $formfield="<input type=\"checkbox\" name=\"$nnet_fid\" value=\"1\">";}
                                else                  { $formfield="<input type=\"radio\" name=\"$nnet_fid\" value=\"1\">";}
                                $out_tables.="<tr><td width=\"18%\" valign=\"top\" height=\"19\" bgcolor=\"#E5E5E5\" style=\"border-left-width: 1; border-right-style: solid; border-right-width: 1; border-top-width: 1; border-bottom-width: 1\" align=\"right\">&nbsp; $nnet_fname:&nbsp;&nbsp; </td><td width=\"82%\" valign=\"top\" height=\"19\" background=\"$tpl_template_url/gfx/front/main_zoom_grid.gif\">&nbsp;$formfield</td></tr>";
                        }
                        mysql_free_result($result);
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_users_signup.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
        }
        else
        {
                if($_cfig{"isvb"}==1)
                {
                       Header("Location: $vb_url/register.php?action=signup");
                }
                else
                {
                        if($gbl_env["nnet_user"]=='' || $gbl_env["nnet_pass"]=='' || $gbl_env["nnet_name"]==''||$gbl_env["nnet_email"] =='')
                        {
                                _err("Required field(s) is empty.");
                        }
                        if($gbl_env["nnet_pass"] != $gbl_env["re_nnet_pass"])
                        {
                                _err("Your password don't match");
                        }
                        $data=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_user`='".$gbl_env["nnet_user"]."'"));
                        if($data[0]>0){_err("This username already exist. Please pick another.");}

                        // ADDING USERS CODE
                        $result = mysql_query("SELECT `nnet_fid` FROM `$_cfig[sql_db]`.`nnet_users_fields` WHERE 1;")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
                        {
                                extract($data);
                                $tmp=$gbl_env["$nnet_fid"];
                                $queriefields.=",`$nnet_fid`";
                                $querievalues.=",'$tmp'";
                        }
			mysql_free_result($result);
                        if($_cfig[auto_sign_up]==1) { $auto_signup=1; }
                        else                        { $auto_signup=0; }

                        $result = mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_users` (`nnet_user`,`nnet_pass`,`nnet_uname`,`nnet_email`,`nnet_able`,`nnet_type`,`nnet_posts`,`nnet_date` $queriefields) VALUES ('".$gbl_env["nnet_user"]."','".$gbl_env["nnet_pass"]."','".$gbl_env["nnet_name"]."','".$gbl_env["nnet_email"]."','$auto_signup','0','0','".time()."' $querievalues);")
                                  or die("Error #".mysql_errno().": ".mysql_error());
                        mysql_free_result($result);

                        print "<br><br>User '<b>{$gbl_env["nnet_user"]}</b>' has been added successfully. <a href=\"index.php?action=login&username={$gbl_env["nnet_user"]}&password={$gbl_env["nnet_pass"]}&opt=o0o\">Click here</a> to continue.";

                        //header("Location: index.php?action=login&username=$in_username&password=$in_password");
                }
        }
}

?>