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

function browse_submit()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_POST_FILES;
	global $gbl_sid,$gbl_id,$gbl_type, $gbl_name,$cperf;

        $tpl_template_url=$_cfig[url_tpl];
	
	$url_skins=$_cfig[url_skins];
	$url_upload=$_cfig[url_upload];
	$url_php=$_cfig[url_php];
	$url_tpl=$_cfig[url_tpl];
	
	if($cperf["post"] != 1)
	{
		if($gbl_type=='guest')
		{
			_err("Please <a href=browse.php?mod=signup>signup</a> or <a href=index.php?action=clear>log-in</a> before submit your article.");
		}
		else
		{
			_err("You don't have the permission to post.");
		}
	}

        if($gbl_env["opt"]=='')
        {
                if($gbl_env["editor"]!='')
                {
                        $result= mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1;")
                                 or die("Error #".mysql_errno().": ".mysql_error());

                        $nav_info=array();
                        while($data=mysql_fetch_row($result))
                        {
                                $nav_info[$data[0]]=$data[1];
                        }
                        mysql_free_result($result);
                        $result= mysql_query("SELECT `nnet_cid`,`nnet_name`,`nnet_nav`,`nnet_parent` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1 order by `nnet_child` ASC;")
                                 or die("Error #".mysql_errno().": ".mysql_error());
                        while($data=mysql_fetch_row($result))
                        {
                                $tmp_array=explode("|",$data[2]);
                                for($i=0;$i<count($tmp_array);$i++)
                                {
                                        $tmp_nav.="/".$nav_info[$tmp_array[$i]];
                                }
                                $tmp_nav.="/$data[1]";
                                $tmp_nav=preg_replace("/\/\//","/",$tmp_nav);
                                $catdisplay.="<option value=\"$data[0]\">$tmp_nav</option>\n";
                                $tmp_nav='';
                        }
                        mysql_free_result($result);
                }
                if($gbl_env["editor"]=='')
                {
                 	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_articles_chose.html",0));
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
                elseif($gbl_env["editor"]=='x')
                {
                 	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_articles_x.html",0));
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
                elseif($gbl_env["editor"]=='y')
                {
                 	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_articles_y.html",0));
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
                else {}
        }
        elseif($gbl_env["opt"]=='add_proceed')
        {
                $nnet_parent=mysql_fetch_row(mysql_query("SELECT `nnet_parent` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$gbl_mnv["nnet_cid"]."';"));
		if($nnet_parent[0]==0)
		{
			$nnet_parent[0]=$gbl_mnv["nnet_cid"];
		}
		if($gbl_type=='mod' || $_cfig[auto_approve]==1 || $cperf["aa"]==1){$capprove=1;}
		else                                                              {$capprove=0;}

		$result=mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_articles` (`nnet_cid`,`nnet_title`,`nnet_desc`,`nnet_data`,`nnet_uid`,`nnet_time`,`nnet_approval`,`nnet_parent`) VALUES ('".$gbl_mnv["nnet_cid"]."','".$gbl_mnv["nnet_title"]."','".$gbl_mnv["nnet_desc"]."','".$gbl_mnv["nnet_data"]."','$gbl_id','".time()."','$capprove','$nnet_parent[0]');")
                        or die("Error #".mysql_errno().": ".mysql_error());
                $data=mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID();"));
		
		mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_users` SET `nnet_posts`=`nnet_posts`+1 WHERE `nnet_uid`='$gbl_id' LIMIT 1;")
                or die("Error #".mysql_errno().": ".mysql_error());

                $file_icon="thumb_".$data[0];
                $file_pic ="full_" .$data[0];
		
		 mysql_free_result($result);

                if(getFileSize("nnet_icon") > 10)
                {
                        if(getFileSize("nnet_icon") > $_cfig{"upload_limit"})
                        {
                                _err("File size is too big.");
                        }
                        if(getFileMimeType("nnet_icon") == "image/gif")
                        {
                                $file_icon=$file_icon.".gif";
                                $data_contents = fread(fopen($HTTP_POST_FILES['nnet_icon']['tmp_name'], 'r'), filesize($HTTP_POST_FILES['nnet_icon']['tmp_name']));
                                $out=fopen ("$_cfig[dir_upload]/$file_icon", "w");
                                fputs($out,$data_contents,strlen($data_contents));
                                fclose ($out);
                        }
                        elseif(getFileMimeType("nnet_icon") =="image/pjpeg")
                        {
                                $file_icon=$file_icon.".jpg";
                                $data_contents = fread(fopen($HTTP_POST_FILES['nnet_icon']['tmp_name'], 'r'), filesize($HTTP_POST_FILES['nnet_icon']['tmp_name']));
                                $out=fopen ("$_cfig[dir_upload]/$file_icon", "w");
                                fputs($out,$data_contents,strlen($data_contents));
                                fclose ($out);
                        }
                        else
                        {
                                _err("Unknown file type.");
                        }
                   }
                if(getFileSize("nnet_pic") > 10)
                {
                        if(getFileSize("nnet_pic") > $_cfig{"upload_limit"})
                        {
                                _err("File size is too big.");
                        }
                        if(getFileMimeType("nnet_pic") == "image/gif")
                        {
                                $file_pic=$file_pic.".gif";
                                $data_contents = fread(fopen($HTTP_POST_FILES['nnet_pic']['tmp_name'], 'r'), filesize($HTTP_POST_FILES['nnet_pic']['tmp_name']));
                                $out=fopen ("$_cfig[dir_upload]/$file_pic", "w");
                                fputs($out,$data_contents,strlen($data_contents));
                                fclose ($out);
                        }
                        elseif(getFileMimeType("nnet_pic") =="image/pjpeg")
                        {
                                $file_pic=$file_pic.".jpg";
                                $data_contents = fread(fopen($HTTP_POST_FILES['nnet_pic']['tmp_name'], 'r'), filesize($HTTP_POST_FILES['nnet_pic']['tmp_name']));
                                $out=fopen ("$_cfig[dir_upload]/$file_pic", "w");
                                fputs($out,$data_contents,strlen($data_contents));
                                fclose ($out);
                        }
                        else
                        {
                                _err("Unknown file type.");
                        }
                   }
		   if($gbl_type=='mod' || $_cfig[auto_approve]==1 || $cperf["aa"]==1)
		   {
			if($_cfig[static_pages]==1)
                        {
                       		// include reviews update
               			if($_cfig[auto_update]==1)
                   		{
           				include("$_cfig[dir_library]/admin_built_static.php");
					gbl_build_document($data[0]);
    				}

                  		print "<script>alert('Article: `".$gbl_mnv["nnet_title"]."` is added successfully.');document.location='$_cfig[url_static]/article$data[0].html';</script>";
                  	}
                     	else
                  	{
                        	print "<script>alert('Article: `".$gbl_mnv["nnet_title"]."` is added successfully.');document.location='browse.php?mod=article&opt=view&id=$data[0]';</script>";
               		}
		   }
		   else
		   {
			print "<script>alert('Article: `".$gbl_mnv["nnet_title"]."` is pending for approval from `administrator`.');document.location='browse.php?opt=browse&catid=$data[0]';</script>";
		   }
        }       
}
?>