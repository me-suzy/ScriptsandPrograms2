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
function browse_edit()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_POST_FILES;
	global $gbl_sid,$gbl_id,$gbl_type, $gbl_name,$cperf;

        $tpl_template_url=$_cfig[url_tpl];
	
	$url_skins=$_cfig[url_skins];
	$url_upload=$_cfig[url_upload];
	$url_php=$_cfig[url_php];
	$url_tpl=$_cfig[url_tpl];

	$id=$gbl_env['id'];
	
	if($cperf["edit"] != 1)
	{
		if($gbl_type=='guest')
		{
			_err("Please <a href=browse.php?mod=signup>signup</a> or <a href=index.php?action=clear>log-in</a> before submit your article.");
		}
		else
		{
			_err("You don't have the permission to write review.");
		}
	}

	if($gbl_env["opt"]=='')
        {
                if($gbl_env["editor"]=='')
                {
                 	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_articles_edit_chose.html",0));
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
                else
		{
			if($gbl_env["id"]==''){ _err("Puh-lease specify your id.");}
			extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."';")));
			
			if($gbl_id!=$nnet_uid && $cperf["medit"]!=1) { _err("This article is not yours."); }

			////////////////////////////////////////////////////////////////////
			// MODERATOR CONTROL MOD CAN BE INSERT HERE                       //
			////////////////////////////////////////////////////////////////////
			$nnet_time=_date($nnet_time);
			if($gbl_env["editor"]=='x')
			{
				$nnet_data=preg_replace("/\r/","",$nnet_data);
				$nnet_data=preg_replace("/\n/","",$nnet_data);
				$nnet_data=preg_replace("/\"/","\\\"",$nnet_data);
			}
			elseif($gbl_env["editor"]=='y')
			{
				$nnet_data=htmlspecialchars($nnet_data);
			}
			else{}
			if($nnet_approval==1) { $app_checked="checked";}
			$tpl_icon=cif("file_exists(\"$_cfig[dir_upload]/thumb_$nnet_aid.jpg\") || file_exists(\"$_cfig[dir_upload]/thumb_$nnet_aid.gif\")","<br><a href=\"browse.php?mod=edit&&opt=img_remove&type=thumb&id=$id\"><img border=0 src=\"$tpl_template_url/gfx/tinyicons/remove.gif\"></a> { File-uploaded } the thumbnail image file.","<br><br>Upload Thumb-nail Image<br><input type=\"file\" name=\"nnet_icon\" size=\"54\" maxlength=\"80\" style=\"border: 1px inset #000000; background-color: #CCCCFF; font-size:8pt; font-family:Verdana\"><br>");
			$tpl_pic =cif("file_exists(\"$_cfig[dir_upload]/full_$nnet_aid.jpg\") || file_exists(\"$_cfig[dir_upload]/full_$nnet_aid.gif\")","<br><a href=\"browse.php?mod=edit&&opt=img_remove&type=full&id=$id\"><img border=0 src=\"$tpl_template_url/gfx/tinyicons/remove.gif\"></a> { File-uploaded } the fullsize image file.<br>","<br><br>Upload Full-Size Image<br><input type=\"file\" name=\"nnet_pic\" size=\"54\" maxlength=\"80\" style=\"border: 1px inset #000000; background-color: #CCCCFF; font-size:8pt; font-family:Verdana\"><br>");
			if($gbl_env["editor"]=='x')
			{
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_articles_edit_x.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
			}
			else
			{
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_articles_edit_y.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
			}
		}
        }
	elseif($gbl_env["opt"]=='img_remove')
	{
		// IMAGE GATEWAY CHECK
		$info=mysql_fetch_row(mysql_query("SELECT `nnet_uid` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1"));
		if($info[0]!=$gbl_id && $cperf["medit"]!=1) { _err("This image is not yours. Action ignored."); }

		if(file_exists("$_cfig[dir_upload]/".$gbl_env["type"]."_".$gbl_env["id"].".jpg"))
		{
			unlink("$_cfig[dir_upload]/".$gbl_env["type"]."_".$gbl_env["id"].".jpg");
		}
		elseif (file_exists("$_cfig[dir_upload]/".$gbl_env["type"]."_".$gbl_env["id"].".gif"))
		{
			unlink("$_cfig[dir_upload]/".$gbl_env["type"]."_".$gbl_env["id"].".gif");
		}
		else {}
		Header("Location: ".getenv("HTTP_REFERER"));
	}
	elseif($gbl_env["opt"]=='edit_proceed')
	{
		// IMAGE GATEWAY CHECK
		$info=mysql_fetch_row(mysql_query("SELECT `nnet_uid`,`nnet_cid` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1"));
		if($gbl_id!=$info[0] && $cperf["medit"]!=1) { _err("This article is not yours."); }

		if($gbl_type=='mod' || $_cfig[auto_approve]==1 || $cperf["aa"]==1){$capprove=1;}
		else                                                              {$capprove=0;}

		$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_title`='".$gbl_mnv["nnet_title"]."',`nnet_desc`='".$gbl_mnv["nnet_desc"]."',`nnet_views`='".$gbl_mnv["nnet_views"]."',`nnet_data`='".$gbl_mnv["nnet_data"]."',`nnet_approval`='$capprove' WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;")
			or die("Error #".mysql_errno().": ".mysql_error());
		mysql_free_result($result);
		
		$file_icon="thumb_".$gbl_env["id"];
                $file_pic ="full_" .$gbl_env["id"];
		
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
		if($_cfig[auto_approve]==1  || $cperf["aa"]==1)
		{
			if($_cfig[static_pages]==1)
                        {
                       		// include reviews update
               			if($_cfig[auto_update]==1)
                   		{
           				include("$_cfig[dir_library]/admin_built_static.php");
					gbl_build_document($gbl_env["id"]);
    				}

                  		print "<script>alert('Article: `".$gbl_mnv["nnet_title"]."` is edited successfully.');document.location='$_cfig[url_static]/article".$gbl_env["id"].".html';</script>";
                  	}
                     	else
                  	{
                        	print "<script>alert('Article: `".$gbl_mnv["nnet_title"]."` is edited successfully.');document.location='browse.php?mod=article&opt=view&id=".$gbl_env["id"]."';</script>";
               		}
		}
		else
		{
			print "<script>alert('Article: `".$gbl_mnv["nnet_title"]."` is pending for approval from `administrator`.');document.location='browse.php?opt=browse&catid=$info[1]';</script>";
		}
	}
}
?>