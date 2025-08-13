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

function admin_articles()
{

        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_POST_FILES;
	$tpl_template_url=$_cfig[url_tpl];

        /////////////////////////////////////////////////////////////////
        // POST-SPAN                                                   //
        /////////////////////////////////////////////////////////////////
        if($gbl_env["page"] == '')
        {
                $gbl_env["page"]=1;
        }
        $gbl_env["page"]--;
        $startpoint=$_cfig[op_span]*$gbl_env["page"];

        $id=$gbl_env["id"];

        if($gbl_env["keywords"] != "" && strlen($gbl_env["keywords"])<3)
        {
                _err("Your keyword is too short");
        }
        ///////////////////////////////////////////////////////////////////////////

        if($gbl_env["opt"]=='')
        {
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles.html",0));
        }
        elseif($gbl_env["opt"]=='add')
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
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_chose.html",0));
                }
                elseif($gbl_env["editor"]=='x')
                {
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_x.html",0));
                }
                elseif($gbl_env["editor"]=='y')
                {
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_y.html",0));
                }
                else {}
        }
        elseif($gbl_env["opt"]=='add_proceed')
        {
                // IMAGE SEARCH ////////////////////////////////////////////
		$gbl_env["nnet_data"]=stripslashes($gbl_env["nnet_data"]);
		
		$tmp_data=preg_replace("/\n/","",$gbl_env["nnet_data"]);	
		$tmp_data=preg_replace("/\r/","",$tmp_data);
		$tmp_data=preg_replace("/>/",">\n",$tmp_data);
		$tmp_hash=explode("\n",$tmp_data);
		$int_counter=0;
		
		while(list($key,$value)=each($tmp_hash))
		{
			$cint=time().$int_counter;
			preg_match("/<img.*src=.*\"(.*?)\".*>/i",$value, $matches);
			$matches[1]=stripslashes(addslashes($matches[1]));
			$key=$matches[1];
		
			$var_extention=substr($matches[1],strlen($matches[1])-3,strlen($matches[1]));
			
			if($matches[1]!='' && !preg_match("/http:\/\//i",$matches[1]))
			{
				$var_form.="<font size=\"1\" face=\"Verdana\">Set Path to: {$matches[1]}<br><input type=hidden name=\"{$int_counter}_name\" value=\"$cint\"> <input type=\"file\" name=\"{$int_counter}_file\" size=\"90\" maxlength=\"100\" value=\"\"/></font><br>";
				$gbl_env["nnet_data"]=str_replace($key,"{$_cfig[url_upload]}/$cint.$var_extention",$gbl_env["nnet_data"]);
				$int_counter++;
			}
		}
		$var_form.="<input type=hidden name='file_totals' value='$int_counter'>";
		$gbl_mnv["nnet_data"]=addslashes(stripslashes($gbl_env["nnet_data"]));
	  	///////////////////////////////////////////////////////////////////////////////////////
		
		

		$nnet_parent=mysql_fetch_row(mysql_query("SELECT `nnet_parent` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$gbl_mnv["nnet_cid"]."';"));
		if($nnet_parent[0]==0)
		{
			$nnet_parent[0]=$gbl_mnv["nnet_cid"];
		}
		$result=mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_articles` (`nnet_cid`,`nnet_title`,`nnet_desc`,`nnet_data`,`nnet_time`,`nnet_approval`,`nnet_parent`) VALUES ('".$gbl_mnv["nnet_cid"]."','".$gbl_mnv["nnet_title"]."','".$gbl_mnv["nnet_desc"]."','".$gbl_mnv["nnet_data"]."','".time()."','1','$nnet_parent[0]');")
                        or die("Error #".mysql_errno().": ".mysql_error());
                $data=mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID();"));

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
				move_uploaded_file($HTTP_POST_FILES['nnet_icon']['tmp_name'],"$_cfig[dir_upload]/$file_icon");
                        }
                        elseif(getFileMimeType("nnet_icon") =="image/pjpeg")
                        {
                                $file_icon=$file_icon.".jpg";
				move_uploaded_file($HTTP_POST_FILES['nnet_icon']['tmp_name'],"$_cfig[dir_upload]/$file_icon");
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
				move_uploaded_file($HTTP_POST_FILES['nnet_pic']['tmp_name'],"$_cfig[dir_upload]/$file_pic");
                        }
                        elseif(getFileMimeType("nnet_pic") =="image/pjpeg")
                        {
                                $file_pic=$file_pic.".jpg";
				move_uploaded_file($HTTP_POST_FILES['nnet_pic']['tmp_name'],"$_cfig[dir_upload]/$file_pic");
                        }
                        else
                        {
                                _err("Unknown file type.");
                        }
              	}

		if($int_counter>0)
		{
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_upload.html",0));
		}
		else
		{
			print "<script>alert('Article: ".$gbl_mnv["nnet_title"]." is added successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
		}		
        }
        elseif($gbl_env["opt"]=='view')
        {
                $var_show=$gbl_env["show"];
		$keywords=$gbl_env["keywords"];
		if($gbl_env["keywords"]!='') { $extra="`nnet_title` REGEXP '".$gbl_env["keywords"]."' OR `nnet_desc` REGEXP '".$gbl_env["keywords"]."'"; }
                else                         { $extra="1"; }

                // GET TOTAL DATA CNETz Jackal hahahaha
                $totalsize=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE $extra;"));

                if($gbl_env["by"] !='')      { $extra.=" ORDER BY `".$gbl_env["by"]."`"; }
                else                         { $extra.=" ORDER BY `nnet_aid`";           }

                if($gbl_env["type"]=='')     { $extra.=" DESC";                          }
                else                         { $extra.=" ".$gbl_env["type"];             }

		$tpl_span=_span($_cfig[op_span],$gbl_env["page"]+1,$totalsize[0],"admin.php?mod=articles&opt=view&keywords=".$gbl_env["keywords"]."&by=".$gbl_env["by"]."&show=$var_show&type=".$gbl_env["type"]);
		
		if($gbl_env["show"]=='details')
		{
			$html_docs=_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_listings_details.txt",0);
			$result= mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1;")
			//$result= mysql_query("SELECT `nnet_nav` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='$nnet_cid' LIMIT 1;")
                                 or die("Error #".mysql_errno().": ".mysql_error());

                        $nav_info=array();
                        while($data=mysql_fetch_row($result))
                        {
                                $data[1]=preg_replace("/\//"," or ",$data[1]);
				$nav_info[$data[0]]=$data[1];
                        }
                        mysql_free_result($result);
			
		}
		else
		{
			$html_docs=_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_listings.txt",0);
		}
		$result= mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE $extra LIMIT $startpoint,$_cfig[op_span];")
                         or die("Error #".mysql_errno().": ".mysql_error());

                while($data=mysql_fetch_array($result,MYSQL_ASSOC))
                {
                	extract($data);
			if($gbl_env["show"]=='details')
			{
				$nnet_time=_date($nnet_time);
				$nnet_author=cif("$nnet_uid==0","<font size=\"1\" face=\"Verdana\" color=\"#00FF00\">By Administrator</font>&nbsp;","<a href=\"admin.php?mod=members&opt=members_edit&id={%nnet_uid%}\"><font size=\"1\" face=\"Verdana\" color=\"#00FF00\">Author info</font></a>&nbsp;");
				$cid_nav=mysql_fetch_row(mysql_query("SELECT `nnet_nav` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='$nnet_cid';"));
				$tmp_array=explode("|",$cid_nav[0]);
				for($i=0;$i<count($tmp_array);$i++)
                                {
                                        $tpl_nav.="/".$nav_info[$tmp_array[$i]];
                                }
                                $tpl_nav.="/$nav_info[$nnet_cid]";
				$tpl_nav=preg_replace("/\/\//","/",$tpl_nav);
				
				if($nnet_feature!=0)
				{
					if($nnet_feature<0){$tplc_feature="Frontpage feature";}
					else
					{
						$ffetch=mysql_fetch_row(mysql_query("SELECT `nnet_nav` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='$nnet_feature';"));
						$tmp_array=explode("|",$ffetch[0]);
						for($i=0;$i<count($tmp_array);$i++)
                                		{
                                        		$tplc_feature.="/".$nav_info[$tmp_array[$i]];
                                		}
                                		$tplc_feature.="/$nav_info[$nnet_cid]";
					}
					$tplc_feature="<br><font color=\"#FF0000\"><b>Feature at</b>:<br>".preg_replace("/\/\//","/",$tplc_feature);
				}
				
			}
			$tpl_icon   =cif("file_exists(\"$_cfig[dir_upload]/thumb_$nnet_aid.jpg\") || file_exists(\"$_cfig[dir_upload]/thumb_$nnet_aid.gif\")","<img border=\"0\" src=\"$tpl_template_url/gfx/tinyicons/check.gif\">","");
			$tpl_pic    =cif("file_exists(\"$_cfig[dir_upload]/full_$nnet_aid.jpg\") || file_exists(\"$_cfig[dir_upload]/full_$nnet_aid.gif\")","<img border=\"0\" src=\"$tpl_template_url/gfx/tinyicons/check.gif\">","");
			$tpl_feature=cif("$nnet_feature!=''","<a href=\"admin.php?mod=articles&opt=feature_unset&id=$nnet_aid\"><img border=\"0\" src=\"$tpl_template_url/gfx/tinyicons/check.gif\"></a>","<a href=\"admin.php?mod=articles&opt=feature_set&id=$nnet_aid\"><img border=\"0\" src=\"$tpl_template_url/gfx/tinyicons/set.gif\"></a>");
			$tpl_approve=cif("$nnet_approval!=1","<img border=\"0\" src=\"$tpl_template_url/gfx/tinyicons/_active.gif\">","<img border=\"0\" src=\"$tpl_template_url/gfx/tinyicons/_inactive.gif\">");
			$tpl_listings.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);
			$tpl_nav='';$tplc_feature='';
                }
                mysql_free_result($result);

                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_listings.html",0));
        }
	elseif($gbl_env["opt"]=='polls')
	{
		if($gbl_env["proceed"]!='true')
		{
			extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;"),MYSQL_ASSOC));
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_poll.html",0));
		}
		else
		{
			if($gbl_env["del"]!=1)
			{
				$input_cid      =mysql_fetch_row(mysql_query("SELECT `nnet_cid` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;"));
				$search_existing=mysql_fetch_row(mysql_query("SELECT count(*) FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='".$gbl_env["id"]."'"));
				if($search_existing[0]>0)
				{
					$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_polls` SET `nnet_ques`='".$gbl_mnv["nnet_ques"]."',`nnet_ans1`='".$gbl_mnv["nnet_ans1"]."',`nnet_ans2`='".$gbl_mnv["nnet_ans2"]."',`nnet_ans3`='".$gbl_mnv["nnet_ans3"]."',`nnet_ans4`='".$gbl_mnv["nnet_ans4"]."',`nnet_nans1`='".$gbl_mnv["nnet_nans1"]."',`nnet_nans2`='".$gbl_mnv["nnet_nans2"]."',`nnet_nans3`='".$gbl_mnv["nnet_nans3"]."',`nnet_nans4`='".$gbl_mnv["nnet_nans4"]."' WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;")
						or die("Error #".mysql_errno().": ".mysql_error());
					mysql_free_result($result);
					print "<script>alert('Poll ID: ".$gbl_mnv["id"]." is updated successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
				}
				else
				{
					$result=mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_polls` (`nnet_aid`,`nnet_cid`,`nnet_ques`,`nnet_ans1`,`nnet_ans2`,`nnet_ans3`,`nnet_ans4`,`nnet_nans1`,`nnet_nans2`,`nnet_nans3`,`nnet_nans4`) VALUES ('".$gbl_env['id']."','$input_cid[0]','".$gbl_mnv["nnet_ques"]."','".$gbl_mnv["nnet_ans1"]."','".$gbl_mnv["nnet_ans2"]."','".$gbl_mnv["nnet_ans3"]."','".$gbl_mnv["nnet_ans4"]."','".$gbl_mnv["nnet_nans1"]."','".$gbl_mnv["nnet_nans2"]."','".$gbl_mnv["nnet_nans3"]."','".$gbl_mnv["nnet_nans4"]."');")
						or die("Error #".mysql_errno().": ".mysql_error());
					mysql_free_result($result);
					print "<script>alert('Poll ID: ".$gbl_mnv["id"]." is added successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
				}
			}
			else
			{
				$result=mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;")
						or die("Error #".mysql_errno().": ".mysql_error());
				mysql_free_result($result);
				print "<script>alert('Poll ID: ".$gbl_mnv["id"]." is deleted successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
			}
		}
	}
	elseif($gbl_env["opt"]=='article_del')
	{
		// DELETE SPECIFIED ARTICLE
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='{$gbl_env["id"]}' LIMIT 1;")
		or die("Error #".mysql_errno().": ".mysql_error());

		// CLEAN POLL BELONG TO THIS ARTICLE
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='{$gbl_env["id"]}';")
		or die("Error #".mysql_errno().": ".mysql_error());
	
		// CLEAN REVIEWS THAT BELONG TO THIS ONE
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='{$gbl_env["id"]}';")
		or die("Error #".mysql_errno().": ".mysql_error());
	
		if(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.jpg"))
		{
			unlink(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.jpg"));
		}
		if(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.gif"))
		{
			unlink(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["id"]}.gif"));
		}
		if(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.gif"))
		{
			unlink(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.gif"));
		}
		if(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.jpg"))
		{
			unlink(file_exists("$_cfig[dir_upload]/full_{$gbl_env["id"]}.jpg"));
		}

		if($_cfig[static_pages]==1)
        	{
			if(file_exists("$_cfig[dir_static]/article{$gbl_env["id"]}.html"))
			{
				unlink("$_cfig[dir_static]/article{$gbl_env["id"]}.html");
			}
			
			print "<script>alert('Article: #`{$gbl_env["id"]}` is deleted successfully.\nYOU SHOULD UPDATE STATIC FILES.');document.location='admin.php?mod=articles&opt=view';</script>";
		}
		else
		{
			print "<script>alert('Article: #`{$gbl_env["id"]}` is deleted successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
		}
	}
	elseif($gbl_env["opt"]=='article_edit')
	{
		if($gbl_env["editor"]=='')
		{
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_edit_chose.html",0));
		}
		else
		{
			if($gbl_env["id"]==''){ _err("Puh-lease specify your id.");}
			extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."';")));
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
			$tpl_icon=cif("file_exists(\"$_cfig[dir_upload]/thumb_$nnet_aid.jpg\") || file_exists(\"$_cfig[dir_upload]/thumb_$nnet_aid.gif\")","<br><a href=\"admin.php?mod=articles&&opt=img_remove&type=thumb&id=$id\"><img border=0 src=\"$tpl_template_url/gfx/tinyicons/remove.gif\"></a> { File-uploaded } the thumbnail image file.","<br><br>Upload Thumb-nail Image<br><input type=\"file\" name=\"nnet_icon\" size=\"54\" maxlength=\"80\" style=\"border: 1px inset #000000; background-color: #CCCCFF; font-size:8pt; font-family:Verdana\"><br>");
			$tpl_pic=cif("file_exists(\"$_cfig[dir_upload]/full_$nnet_aid.jpg\") || file_exists(\"$_cfig[dir_upload]/full_$nnet_aid.gif\")","<br><a href=\"admin.php?mod=articles&&opt=img_remove&type=full&id=$id\"><img border=0 src=\"$tpl_template_url/gfx/tinyicons/remove.gif\"></a> { File-uploaded } the fullsize image file.<br>","<br><br>Upload Full-Size Image<br><input type=\"file\" name=\"nnet_pic\" size=\"54\" maxlength=\"80\" style=\"border: 1px inset #000000; background-color: #CCCCFF; font-size:8pt; font-family:Verdana\"><br>");
			if($gbl_env["editor"]=='x')
			{
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_edit_x.html",0));
			}
			else
			{
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_edit_y.html",0));
			}
		}
	}
	elseif($gbl_env["opt"]=='img_remove')
	{
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
		$input_app  =$gbl_env["approved"];
		$input_reset=$gbl_env["rreset"];

		// IMAGE SEARCH ////////////////////////////////////////////
		$gbl_env["nnet_data"]=stripslashes($gbl_env["nnet_data"]);
		
		$tmp_data=preg_replace("/\n/","",$gbl_env["nnet_data"]);	
		$tmp_data=preg_replace("/\r/","",$tmp_data);
		$tmp_data=preg_replace("/>/",">\n",$tmp_data);
		$tmp_hash=explode("\n",$tmp_data);
		$int_counter=0;
		
		while(list($key,$value)=each($tmp_hash))
		{
			$cint=time().$int_counter;
			preg_match("/<img.*src=.*\"(.*?)\".*>/i",$value, $matches);
			$matches[1]=stripslashes(addslashes($matches[1]));
			$key=$matches[1];
		
			$var_extention=substr($matches[1],strlen($matches[1])-3,strlen($matches[1]));
			
			if($matches[1]!='' && !preg_match("/http:\/\//i",$matches[1]))
			{
				$var_form.="<font size=\"1\" face=\"Verdana\">Set Path to: {$matches[1]}<br><input type=hidden name=\"{$int_counter}_name\" value=\"$cint\"> <input type=\"file\" name=\"{$int_counter}_file\" size=\"90\" maxlength=\"100\" value=\"\"/></font><br>";
				$gbl_env["nnet_data"]=str_replace($key,"{$_cfig[url_upload]}/$cint.$var_extention",$gbl_env["nnet_data"]);
				$int_counter++;
			}
		}
		$var_form.="<input type=hidden name='file_totals' value='$int_counter'>";
		$gbl_mnv["nnet_data"]=addslashes(stripslashes($gbl_env["nnet_data"]));
	  	///////////////////////////////////////////////////////////////////////////////////////

		$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_title`='".$gbl_mnv["nnet_title"]."',`nnet_desc`='".$gbl_mnv["nnet_desc"]."',`nnet_views`='".$gbl_mnv["nnet_views"]."',`nnet_approval`='".cif("$input_app==1",1,0)."',".cif("$input_reset==1","`nnet_trate`='0',`nnet_nrate`='0',`nnet_rrate`='0',",'')."`nnet_data`='".$gbl_mnv["nnet_data"]."' WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;")
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
				move_uploaded_file($HTTP_POST_FILES['nnet_icon']['tmp_name'],"$_cfig[dir_upload]/$file_icon");
                        }
                        elseif(getFileMimeType("nnet_icon") =="image/pjpeg")
                        {
                                $file_icon=$file_icon.".jpg";
				move_uploaded_file($HTTP_POST_FILES['nnet_icon']['tmp_name'],"$_cfig[dir_upload]/$file_icon");
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
				move_uploaded_file($HTTP_POST_FILES['nnet_pic']['tmp_name'],"$_cfig[dir_upload]/$file_pic");
                        }
                        elseif(getFileMimeType("nnet_pic") =="image/pjpeg")
                        {
                                $file_pic=$file_pic.".jpg";
				move_uploaded_file($HTTP_POST_FILES['nnet_pic']['tmp_name'],"$_cfig[dir_upload]/$file_pic");
                        }
                        else
                        {
                                _err("Unknown file type.");
                        }
              	}
		if($int_counter>0)
		{
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_upload.html",0));
		}
		else
		{
			print "<script>alert('Article: #".$gbl_env["id"]." is updated successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
		}
	}
	if($gbl_env["opt"]=='feature_set')
	{
		if($gbl_env["proceed"]!='true')
		{
			$nav_info=array();
			$result= mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1;")
	                                 or die("Error #".mysql_errno().": ".mysql_error());
	
	                while($data=mysql_fetch_row($result))
	                {
	                	$data[1]=preg_replace("/\//"," or ",$data[1]);
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
	                        	$tmp_nav.=" ".$nav_info[$tmp_array[$i]];
	                       	}
	                      	$tmp_nav.=" $data[1]";
	                       	$tmp_nav=preg_replace("/\/\//","/",$tmp_nav);
	                       	$catdisplay.="<option value=\"$data[0]\">$tmp_nav</option>\n";
	                       	$tmp_nav='';
	             	}
	                mysql_free_result($result);
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_feature.html",0));
		}
		else
		{
			if($gbl_env["type"]==1)
			{
				$result=mysql_query("SELECT `nnet_title` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_feature`='-1';")
					or die("Error #".mysql_errno().": ".mysql_error());
				$int_counter=0;
				While($data=mysql_fetch_row($result))
				{
					$int_counter++;
					$current_featured.="$int_counter. $data[0]<br>";
				}
				mysql_free_result($result);
				print "<br><br>Current Featured articles:<Br>".$current_featured;

				if($int_counter>=$_cfig[main_feature_limit])
				{
					_err("<br><br>Exceed the feature category limit for `main page` features. Please go to options menu and increase the limit allowance.");
				}

				$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_feature`='-1' WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;")
					or die("Error #".mysql_errno().": ".mysql_error());
				mysql_free_result($result);
				print "<script>alert('Article: ".$gbl_mnv["id"]." is set featured at frontpage successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
				
			}
			elseif($gbl_env["type"]==2)
			{
				if($gbl_env["nnet_cid"]=='')
				{
					_err("Category feature need to select category location.");
				}
				$nnet_fmax=mysql_fetch_row(mysql_query("SELECT `nnet_fmax` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$gbl_env["nnet_cid"]."' LIMIT 1;"));
				$result=mysql_query("SELECT `nnet_title` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_feature`='".$gbl_env["nnet_cid"]."';")
					or die("Error #".mysql_errno().": ".mysql_error());
				$int_counter=0;
				While($data=mysql_fetch_row($result))
				{
					$int_counter++;
					$current_featured.="$int_counter. $data[0]<br>";
				}
				mysql_free_result($result);

				print "<br><br>Current Featured articles:<Br>".$current_featured;
				if($int_counter>=$nnet_fmax[0])
				{
					if($nnet_fmax[0]==0)
					{
						_err("<br><br>Featured Article Option for this category is disabled. The Feature max is set to '0'.");
					}	
					else
					{
						_err("<br><br>Exceed the feature category limit for this categor #".$gbl_env["nnet_cid"]);
					}
				}
				$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_feature`='".$gbl_env["nnet_cid"]."' WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;")
					or die("Error #".mysql_errno().": ".mysql_error());
				mysql_free_result($result);

				print "<script>alert('Article: ".$gbl_mnv["id"]." is set featured at category ID # ".$gbl_env["nnet_cid"]." successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
			}
			else
			{
				_err("Please select feature type");
			}
		}
	}
	elseif($gbl_env["opt"]=='feature_unset')
	{
		$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_feature`='' WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;")
			or die("Error #".mysql_errno().": ".mysql_error());
		mysql_free_result($result);
		print "<script>alert('Article: ".$gbl_mnv["id"]." has been de-featured successfully.');document.location='admin.php?mod=articles&opt=view';</script>";
	}
	elseif($gbl_env["opt"]=='approval')
	{
		$result=mysql_query("SELECT `nnet_aid`,`nnet_title`,`nnet_uid`,`nnet_time` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`!='1';")
			or die("Error #".mysql_errno().": ".mysql_error());
		$int_counter=0;
		while($data=mysql_fetch_array($result,MYSQL_ASSOC))
		{
			extract($data);
			$nnet_time=_date($nnet_time);
			if($nnet_uid==0)
			{
				$nnet_name="by Administrator";
			}
			else
			{
				$nnet_name="by <a href='admin.php?mod=members&opt=edit&id=$nnet_uid'>Author</a>";
			}
			$tpl_articles.="<tr>
      					<td width=\"5%\" bgcolor=\"#F2EDED\" height=\"19\" align=\"center\">
      					<input type=\"checkbox\" name=\"C$int_counter\" value=\"$nnet_aid\"></td>
      					<td width=\"45%\" height=\"19\" bgcolor=\"#E8E9E9\">&nbsp;<a href='admin.php?mod=articles&opt=article_edit&id=$nnet_aid'>$nnet_title</a></td>
      					<td width=\"21%\" height=\"19\" bgcolor=\"#E8E9E9\">&nbsp;$nnet_name</td>
      					<td width=\"62%\" height=\"19\" bgcolor=\"#E8E9E9\">&nbsp;$nnet_time</td>
    					</tr>";
			$int_counter++;

		}		
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_approve.html",0));	
	}
	elseif($gbl_env["opt"]=='approval_go')
	{
		if($gbl_env["total"]<1)
		{
			_err("No articles need to be approved.");
		}
		print "<br><br>";
		if($gbl_env["action"]=='del')
		{
			for($i=0;$i<$gbl_env["total"];$i++)
			{
				if($gbl_env["C$i"]!='')
				{
					if(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["C$i"]}.jpg"))
					{
						unlink(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["C$i"]}.jpg"));
					}
					if(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["C$i"]}.gif"))
					{
						unlink(file_exists("$_cfig[dir_upload]/thumb_{$gbl_env["C$i"]}.gif"));
					}
					if(file_exists("$_cfig[dir_upload]/full_{$gbl_env["C$i"]}.gif"))
					{
						unlink(file_exists("$_cfig[dir_upload]/full_{$gbl_env["C$i"]}.gif"));
					}
					if(file_exists("$_cfig[dir_upload]/full_{$gbl_env["C$i"]}.jpg"))
					{
						unlink(file_exists("$_cfig[dir_upload]/full_{$gbl_env["C$i"]}.jpg"));
					}				
					// DELETE SPECIFIED ARTICLE
					mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='{$gbl_env["C$i"]}' LIMIT 1;")
					or die("Error #".mysql_errno().": ".mysql_error());
	
					// CLEAN POLL BELONG TO THIS ARTICLE
					mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='{$gbl_env["C$i"]}';")
					or die("Error #".mysql_errno().": ".mysql_error());
	
					// CLEAN REVIEWS THAT BELONG TO THIS ONE
					mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='{$gbl_env["C$i"]}';")
					or die("Error #".mysql_errno().": ".mysql_error());
					$msgbox.="Delete article #".$gbl_env["C$i"]." on "._date(time())."\\n";
				}
			}
		}
		elseif($gbl_env["action"]=='app')
		{
			for($i=0;$i<$gbl_env["total"];$i++)
			{
				if($gbl_env["C$i"]!='')
				{
					$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_approval`='1' WHERE `nnet_aid`='".$gbl_env["C$i"]."';");
					mysql_free_result($result);	
					$msgbox.="Approved article #".$gbl_env["C$i"]." on "._date(time())."\\n";
				}
			}
		}
		print "<script>alert('$msgbox\\n\\nAction is completed.');document.location='admin.php?mod=articles&opt=approval';</script>";
		
	}
	elseif($gbl_env["opt"]=='reviews')
	{
		if($gbl_env["action"]!='del')
		{
			if($gbl_env["id"]=="")
			{
				_err("Please specify the document id. Dependencies failed.");
			}
			$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='{$gbl_env["id"]}'")
                          	or die("Error #".mysql_errno().": ".mysql_error());
               	 	$html_docs=_html("$_cfig[dir_tpl]/html/admin_reviews.txt",0);
                	while ($line = mysql_fetch_array($result,MYSQL_ASSOC))
                	{
				extract($line);$nnet_date=_date($nnet_date);
				$nnet_isup= cif("$nnet_isup==1","<img src=\"$tpl_template_url/gfx/good.gif\">","<img src=\"$tpl_template_url/gfx/bad.gif\">");
                        	$reviews_output.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);
                	}
                	mysql_free_result($result);
                	print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/admin_reviews.html",0));
		}
		else
		{
			mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_rid`='{$gbl_env["id"]}'")
                        or die("Error #".mysql_errno().": ".mysql_error());
			
			Header("Location: ".getenv("HTTP_REFERER"));	
		}
	}
}
?>