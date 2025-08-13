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
function admin_built_static()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
        if($gbl_env["offset"]==''){$gbl_env["offset"]=0;}
        $start_time=time();$int_counter=$gbl_env["offset"];

        if($gbl_env["header"]==1){gbl_build_main();}
        if($gbl_env["opt"]=='')
        {
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_options_build.html",0));
        }

        elseif ($gbl_env["opt"]=='built_cat')
        {
                if($gbl_env['fresh']==1){gbl_clean_files($_cfig[dir_static]);}
                $result=mysql_query("SELECT `nnet_cid` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1 LIMIT ".$gbl_env["offset"].",40");
                while($data=mysql_fetch_row($result))
                {
                        gbl_build_cat($data[0]);gbl_build_thumb($data[0]);$int_counter++;
                        $cnet=1;
                        if(time()-$start_time>($gbl_env["timeout"]*100))
                        {
                                break;
                        }
                }
                mysql_free_result($result);
                if($cnet==1)
                {
                        print "<html><head><META HTTP-EQUIV=\"Refresh\" CONTENT=\"1;URL=admin.php?mod=built_static&timeout=".$gbl_env["timeout"]."&opt=built_cat&offset=$int_counter\"></head><body><br><br>(Offset: <b>$int_counter</b>)- Building Category Files is proceeding. Please wait.....</body></html>";
                }
                else
                {
                        Header("Location: admin.php?mod=built_static&timeout=".$gbl_env["timeout"]."&opt=built_docs");
                }
        }
	
        elseif ($gbl_env["opt"]=='built_docs') //($gbl_env["opt"]!='done') // 
        {
                if($gbl_env['fresh']==1){gbl_clean_files($_cfig[dir_static]);gbl_build_main();}
                $result=mysql_query("SELECT `nnet_aid` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE 1 LIMIT ".$gbl_env["offset"].",40");
                while($data=mysql_fetch_row($result))
                {
                        gbl_build_document($data[0]);gbl_build_thumb($data[0]);$int_counter++;
                        $cnet=1;

                        if(time()-$start_time>10)
                        {
                                break;
                        }
                }
                mysql_free_result($result);
                if($cnet==1)
                {
                        print "<html><head><META HTTP-EQUIV=\"Refresh\" CONTENT=\"1;URL=admin.php?mod=built_static&timeout=".$gbl_env["timeout"]."&opt=built_docs&offset=$int_counter\"></head><body><br><br>(Offset: <b>$int_counter</b>)- Building Article Files is proceeding. Please wait.....</body></html>";
                }
                else
                {
                        Header("Location: admin.php?mod=built_static&opt=done");
                }
        }
        elseif ($gbl_env["opt"]=='done')
        {
                gbl_build_main();
                print "<br><br>The building process is finished successfully. <a href=\"$_cfig[url_static]/\" target=\"_blank\">Click here</a> to view the result.";
        }


}

function gbl_build_main()
{

        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
        $in_date=_date(time());
        $tpl_template_url=$_cfig[url_tpl];
        ////////////////////////////////////////////////
        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];

                $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_parent`='';")
                        or die("Error #".mysql_errno().": ".mysql_error());
                $int_ccounter=0;

                $tpl_cat=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_category.txt",0);
                $tpl_sub=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_subcat.txt",0);
		$tpl_news=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_news.txt",0);	
		$tpl_news_child=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_news_child.txt",0);
                $tpl_feature=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_feature.txt",0);

                while(extract(mysql_fetch_array($result,MYSQL_ASSOC)))
                {
                        $info_ctotal=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_parent`='$nnet_cid'"));
                        $info_atotal=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_parent`='$nnet_cid' AND `nnet_approval`='1'"));
                        $sub_result=mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_child`='$nnet_cid' LIMIT 0,$_cfig[num_sub_cat];")
                                    or die("Error #".mysql_errno().": ".mysql_error());

                        $var_ctotal=$info_ctotal[0];
                        $var_atotal=$info_atotal[0];
			if($_cfig[news_by_cat]==1){ $news_cats[]=$nnet_cid;$news_cname[$nnet_cid]=$nnet_name; }
                        $var_category_url="$_cfig[url_static]/cat_$nnet_cid.html";
                        while($data=mysql_fetch_row($sub_result))
                        {
                                $var_sub_url="$_cfig[url_static]/cat_$data[0].html";
				$cmp_id=$data[0];
				$cmp_name=$data[1]; 
				$subcat.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_sub);
                        }
                        //if($subcat!=''){$subcat="<tr><td><font size=\"2\" face=\"Arial\">$subcat...</font></td></tr>";}
                        if($nnet_icon==''){$nnet_icon='default.gif';}
                        if($nnet_desc!='')
                        {
                                $nnet_desc="<tr><td><font size=\"1\" face=\"Arial\">$nnet_desc</font></td></tr>";
                        }
                        if($int_ccounter%2==0)
                        {
                                $column_two.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_cat);
                        }
                        else
                        {
                                $column_one.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_cat);
                        }
                        $int_ccounter++;
                        $subcat='';
                }
                mysql_free_result($result);

                $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_feature`<'0' AND `nnet_approval`='1'")
                        or die("Error #".mysql_errno().": ".mysql_error());
                $int_fcounter=0;$open_node=0;
                $var_features='<table width=100%>';
               	while($data=mysql_fetch_array($result,MYSQL_ASSOC))
              	{
                    	extract($data);
			$nnet_desc=substr($nnet_desc,0,200).'...';
			if(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.jpg"))
	             	{
	                 	$var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.jpg";
	             	}
	             	elseif(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.gif"))
	            	{
	                       	$var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.gif";
	             	}
	               	else
	            	{
	                      	$var_fpic="$tpl_template_url/gfx/no_image.gif";
	             	}
	
	              	if(file_exists("$_cfig[dir_upload]/full_$nnet_aid.jpg"))
	            	{
	                        	$nnet_title.="<br><br><font size=\"2\" face=\"Arial\"><a href=\"show.php?id=$nnet_aid\"><img border=\"0\" src=\"$tpl_template_url/gfx/front/main_images.jpg\"></a>&nbsp;Enlarge</font></b>";
	              	}
	          	elseif(file_exists("$_cfig[dir_upload]/full_$nnet_aid.gif"))
	              	{
				$nnet_title.="<br><br><font size=\"2\" face=\"Arial\"><a href=\"show.php?id=$nnet_aid\"><img border=\"0\" src=\"$tpl_template_url/gfx/front/main_images.jpg\"></a>&nbsp;Enlarge</font></b>";
	             	}

                 	$open_node=0;
                      	if($int_fcounter==$_cfig[main_feature_row_limit])
                    	{
                           	$var_features.='</tr>';
                        	$int_fcounter=0;
                           	$open_node=1;
                    	}
                   	if($int_fcounter==0) { $var_features.='<tr>'; }
                 	$int_fcounter++;
			$var_features_url="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid";
                    	$var_features.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_feature);
               	}
             	mysql_free_result($result);
               	// Check End Node
             	if($open_node!=1){$var_features.='</tr>';}
       		$var_features.='</table>';
		if($_cfig[news_by_cat]==1)
		{
			$int_ncounter=0;
			while(list($ckey,$cvalue)=each($news_cats))
			{
				$result=mysql_query("SELECT `nnet_aid`,`nnet_title` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_parent`='$cvalue' AND `nnet_approval`='1' ORDER BY `nnet_aid` DESC LIMIT 0,$_cfig[news_by_cat_numb];")
                        		or die("Error #".mysql_errno().": ".mysql_error());
				while($data=mysql_fetch_array($result,MYSQL_ASSOC))
				{
					extract($data);
					$nnet_title=substr($nnet_title,0,22)."...";
					$var_news_child.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_news_child);
					
				}
				mysql_free_result($result);
				if($var_news_child!='')
				{
					$nnet_cname2002=$news_cname[$cvalue];
					if($int_ncounter%2==0) { $var_news01.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_news); }
					else                   { $var_news02.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_news); }
				}
				$int_ncounter++;$var_news_child='';
									
			}
		}
                else
		{
			$result=mysql_query("SELECT `nnet_aid`,`nnet_title`,`nnet_desc` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' ORDER BY `nnet_aid` DESC LIMIT 0,$_cfig[num_news_display];")
                        	or die("Error #".mysql_errno().": ".mysql_error());
			while($data=mysql_fetch_array($result,MYSQL_ASSOC))
                	{
                        	extract($data);
				$var_news.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_news);
                	}
                	mysql_free_result($result);
		}
                $result=mysql_query("SELECT `nnet_aid`,`nnet_title`,`nnet_desc` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' ORDER BY `nnet_aid` DESC LIMIT 0,$_cfig[num_news_display];")
                        or die("Error #".mysql_errno().": ".mysql_error());
                while($data=mysql_fetch_row($result))
                {
                        $var_news.="<b><a href=\"$_cfig[url_static]/article$data[0].html\"><font face=\"Arial\" size=\"2\" color=\"#000080\">$data[1]</font></a><font face=\"Arial\" size=\"2\" color=\"#808080\"></b><br>
                                  </font><font face=\"Arial\" size=\"1\" color=\"#808080\">&quot;$data[2]&quot;</font><br><br>";
                }
                mysql_free_result($result);

                $result=mysql_query("SELECT `nnet_aid`,`nnet_title` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' ORDER BY `nnet_rrate` DESC LIMIT 0,$_cfig[num_toprate_display]")
                        or die("Error #".mysql_errno().": ".mysql_error());
                while($data=mysql_fetch_row($result))
                {
                        if(strlen($data[1])>20){$data[1]=substr($data[1],0,20)."...";}
                        $var_toprate.="&nbsp;<img border=0 src=\"$tpl_template_url/gfx/front/_inactive.gif\">&nbsp;<a href=\"$_cfig[url_static]/article$data[0].html\"><font face=\"Arial\" size=\"2\" color=\"#000080\">$data[1]</font></a><font face=\"Arial\" size=\"2\" color=\"#808080\"></font><br>";
                }
                mysql_free_result($result);
                $gbl_microtime=microtime()-$gbl_microtime;

                sys01_write("$_cfig[dir_static]/index.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)).preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_main.html",0)).preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));

}

function gbl_build_cat($in_cid)
{
        $catid=$in_cid;
	$id=$in_cid;
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
        $in_date=_date(time());
        $tpl_template_url=$_cfig[url_tpl];
        ////////////////////////////////////////////////
        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];

         // LOAD CATEGORIES INTO ARRAY()
                $nav_info=array();
                $var_browse_url="index.html";
                $var_browse_normal="cat_$catid.html";
                $var_browse_complete="cat_$catid"."_details.html";
                $result= mysql_query("SELECT `nnet_cid`,`nnet_name`,`nnet_child` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1;")
                         or die("Error #".mysql_errno().": ".mysql_error());

                while($data=mysql_fetch_row($result))
                {
                        $data[1]=preg_replace("/\//"," or ",$data[1]);
                        $nav_info[$data[0]]=$data[1];
                               if($data[2]==$in_cid)
                        {
                              $simple_sub[$data[0]]=$data[1];
                        }
                }
                mysql_free_result($result);

                // Navigation SETUP
                $info_cat=mysql_fetch_row(mysql_query("SELECT `nnet_name`,`nnet_categories`,`nnet_listings`,`nnet_features`,`nnet_flimit`,`nnet_fmax`,`nnet_nav` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$in_cid."'"));
                $tmp_array=explode("|",$info_cat[6]);
                for($i=0;$i<count($tmp_array);$i++)
                {
                        $tmp_nav.="<a href=\"cat_".$tmp_array[$i].".html\" class=\"link_nav\">".$nav_info[$tmp_array[$i]]."</a>  ";
                        $tmp_cast.=$nav_info[$tmp_array[$i]];
                }
                if(strlen($tmp_cast)<2){ $tmp_nav="$info_cat[0]"; } else { $tmp_nav.="$info_cat[0]"; }
                $tpl_nav=preg_replace("/\/\//","",$tmp_nav);unset($tmp_nav);


                //$totalsize=mysql_fetch_row(mysql_query("SELECT count(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_cid`='".$in_cid."'"));
                //$var_span=_span($_cfig[span],$gbl_env["page"]+1,$totalsize[0],"browse.php?mod=articles&opt=browse&catid=$catid&complete=".$gbl_env["complete"]);


                $file_cat_template     =cif("$info_cat[1]!=''","customs/categories/$info_cat[1]","browse_cat.html");
                $file_listing_template =cif("$info_cat[2]!=''","customs/listings/$info_cat[2]","browse_listings_default.txt");
                $file_features_template=cif("$info_cat[3]!=''","customs/features/$info_cat[3]","browse_features_default.txt");
		$file_cnet             =_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_subcat_list.txt",0);

                ////////////////////////////////////////////////////////////////
                // PULL THE CORRECT FEATURES TABLES & SETTINGS                //
                ////////////////////////////////////////////////////////////////

                if($info_cat[4]>0)
                {
                        $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_feature`='$catid';")
                                or die("Error #".mysql_errno().": ".mysql_error());

                        $html_features=_html("$_cfig[dir_skins]/$_cfig[template]/html/$file_features_template",0);
                        $int_fcounter=0;$open_node=0;
                        $var_features='<table width=100%>';
                        while($data=mysql_fetch_array($result,MYSQL_ASSOC))
                        {
                                extract($data);
                                $nnet_desc=substr($nnet_desc,0,200).'...';
                                if(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.jpg"))
                                {
                                        $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.jpg";
                                }
                                elseif(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.gif"))
                                {
                                        $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.gif";
                                }
                                else
                                {
                                        $var_fpic="$tpl_template_url/gfx/no_image.gif";
                                }

                                if(file_exists("$_cfig[dir_upload]/full_$nnet_aid.jpg") || file_exists("$_cfig[dir_upload]/full_$nnet_aid.gif"))
                                {
                                        $nnet_title.="<br><br><font size=\"2\" face=\"Arial\"><a href=\"$_cfig[url_static]/fullsize_$nnet_aid.html\"><img border=\"0\" src=\"$tpl_template_url/gfx/front/main_images.jpg\"></a>&nbsp;Enlarge</font></b>";
                                }
                                $open_node=0;
                                if($int_fcounter==$info_cat[4])
                                {
                                        $var_features.='</tr>';
                                        $int_fcounter=0;
                                        $open_node=1;
                                }
                                if($int_fcounter==0) { $var_features.='<tr>'; }
                                $int_fcounter++;
                                $var_features_url="$_cfig[url_static]/article$nnet_aid.html";
                                $var_features.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_features);
                        }
                        mysql_free_result($result);
                        // Check End Node
                        if($open_node!=1){$var_features.='</tr>';}
                        $var_features.='</table>';
                }
                //////////////////////////////////////////////////////////////////
                // MAKE SUB DIRECTORIES                                         //
                //////////////////////////////////////////////////////////////////
                $int_subcount=0;
                        while(list($keys,$values)=each($simple_sub))
                        {
                                if($int_subcount>9) { break; }
                                $var_cats.="&nbsp;<img border=0 src='$tpl_template_url/gfx/front/xlink.gif'>&nbsp<a href='cat_$keys.html'>$values</a><br>";
                                $int_subcount++;
                        }
                        $result=mysql_query("SELECT `nnet_cid`,`nnet_name`,`nnet_icon` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_child`='$in_cid';")
                                or die("Error #".mysql_errno().": ".mysql_error());
                        $int_rowcounter=0;
                        while($data=mysql_fetch_row($result))
                        {
                                $sub_query=mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_child`='$data[0]' LIMIT 0,3;")
                                           or die("Error #".mysql_errno().": ".mysql_error());
                                while($sub_data=mysql_fetch_row($sub_query))
                                {
                                        $tpl_subcat.="<a href='browse.php?opt=browse&catid=$sub_data[0]'>$sub_data[1]</a>, ";
                                }
                                mysql_free_result($sub_query);
                                if($data[2]==''){$data[2]='default.gif';}
				if($_cfig[show_sub_icons]==1)
				{
					$show_icons="<img border=0 src=\"$tpl_template_url/icons/$data[2]\">";
				}

				$var_subcat_url="$_cfig[url_static]/cat_$data[0].html";
				$var_subcat_name=$data[1];
                                if($int_rowcounter%2==0)
                                {
                                        $var_cats_one.=preg_replace("/{%(\w+)%}/ee", "$\\1",$file_cnet);
                                }
                                else
                                {
                                        $var_cats_two.=preg_replace("/{%(\w+)%}/ee", "$\\1",$file_cnet);
                                }
                                $tpl_subcat='';$int_rowcounter++;
                        }
                        mysql_free_result($result);

                // BACK UP TEMPLATE
                if($info_cat[1]==''){$hhh_tmp.=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0);}
                $hhh_tmp.=_html("$_cfig[dir_skins]/$_cfig[template]/html/$file_cat_template",0);
                if($info_cat[1]==''){$hhh_tmp.=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0);}
                $backtpl_cat        =$hhh_tmp; unset($hhh_tmp);
                $backtpl_cat_details=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_cat_details.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0);

                ///////////////////////////////////////////////////////////////////////////////////////
                // GENERATE DATA ENTRY LISTINGS                                                      //
                ///////////////////////////////////////////////////////////////////////////////////////


                $totalsize=mysql_fetch_row(mysql_query("SELECT count(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_cid`='".$in_cid."' AND `nnet_feature`='0'"));

                $nPages=floor($totalsize[0]/$_cfig[span]);
                if($totalsize[0]%$_cfig[span]>0){$nPages++;}

                if($nPages<1){$nPages=1;}
                //print $nPages;
                $html_listings=_html("$_cfig[dir_skins]/$_cfig[template]/html/$file_listing_template",0);

                for($i=0;$i<$nPages;$i++)
                {
                        $startpoint=$_cfig[span]*($i);

                        $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' AND (`nnet_cid`='".$in_cid."' AND `nnet_feature`='0') ORDER BY `nnet_aid` DESC LIMIT $startpoint,$_cfig[span];")
                                or die("Error #".mysql_errno().": ".mysql_error());

                        while($data=mysql_fetch_array($result,MYSQL_ASSOC))
                        {
                                extract($data);
                                if(strlen($nnet_desc)>200){$nnet_desc=substr($nnet_desc,0,200)."...";}
                                if(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.jpg"))
                                {
                                        $ficon="<img border=0 src='$_cfig[url_upload]/thumb_$nnet_aid.jpg' width=60 height=45>";
                                }
                                elseif(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.gif"))
                                {
                                        $ficon="<img border=0 src='$_cfig[url_upload]/thumb_$nnet_aid.gif' width=60 height=45>";
                                }
                                else
                                {
                                        $ficon="<img border=0 src='$tpl_template_url/gfx/no_image.gif' width=60 height=45>";
                                }
                                $var_listings_view_url="article$nnet_aid.html";
                                $var_listings_author_url="$url_php/browse.php?mod=members&id=$nnet_uid";

                                $var_listings.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_listings);
                                $id_counter++;
                        }
                               mysql_free_result($result);
                        if($i+1<=$nPages){$nnext="_".($i+1);}
                        else            {$nnext='';}

                        if($i-1>0)      {$pprev="_".($i-1);}
                        else            {$pprev='';}

                        $var_next="<a href=\"cat_$in_cid$nnext.html\">Next</a> &gt;&gt;";
                        $var_next_detail="<a href=\"cat_$in_cid$nnext"."_details.html\">Next</a> &gt;&gt;";

                        $var_prev="&lt;&lt; <a href=\"cat_$in_cid$pprev.html\">Prev</a>";
                        $var_prev_detail="&lt;&lt; <a href=\"cat_$in_cid$pprev"."_details.html\">Prev</a>";

                        if($i+1>=$nPages){$var_next='';$var_next_detail='';}

                        if($i==0){
                                $var_span="<p align=\"center\"><b><font face=\"Arial\" size=\"2\" color=\"#808080\">$var_next</font></b></p>";
                                sys01_write("$_cfig[dir_static]/cat_$in_cid.html",preg_replace("/{%(\w+)%}/ee", "$\\1",$backtpl_cat));
                                $var_next=$var_next_detail;$var_prev=$var_prev_detail;
                                $var_span="<p align=\"center\"><b><font face=\"Arial\" size=\"2\" color=\"#808080\">$var_next_detail</font></b></p>";
                                sys01_write("$_cfig[dir_static]/cat_$in_cid"."_details.html",preg_replace("/{%(\w+)%}/ee", "$\\1",$backtpl_cat_details));
                        }
                        else {
                                $var_span="<p align=\"center\"><b><font face=\"Arial\" size=\"2\" color=\"#808080\">".$var_prev."&nbsp;&nbsp;&nbsp;&nbsp;".$var_next."</font></b></p>";
                                sys01_write("$_cfig[dir_static]/cat_$in_cid"."_$i.html",preg_replace("/{%(\w+)%}/ee", "$\\1",$backtpl_cat));
                                $var_next=$var_next_detail;$var_prev=$var_prev_detail;
                                $var_span="<p align=\"center\"><b><font face=\"Arial\" size=\"2\" color=\"#808080\">".$var_prev_detail."&nbsp;&nbsp;&nbsp;&nbsp;".$var_next_detail."</font></b></p>";
                                sys01_write("$_cfig[dir_static]/cat_$in_cid"."_$i"."_details.html",preg_replace("/{%(\w+)%}/ee", "$\\1",$backtpl_cat_details));
                        }

                        $var_listings='';

                }

}

function gbl_build_document ($in_id)
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
        $in_date=_date(time());
        $tpl_template_url=$_cfig[url_tpl];
        ////////////////////////////////////////////////
        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];

        $url_php_home="index.html";

        ///////////////////////////////////////////////////////////////////////////
        // POST-SPAN                                                             //
        ///////////////////////////////////////////////////////////////////////////
        if($gbl_env["page"] == '')
        {
                $gbl_env["page"]=1;
        }
        $gbl_env["page"]--;
        $startpoint=$_cfig[span]*$gbl_env["page"];
        ///////////////////////////////////////////////////////////////////////////
        $ad=localtime();$ad[4]++;
        $in_date=date ("M d Y H:i:s", mktime ($ad[2],$ad[1],$ad[0],$ad[4],$ad[3],$ad[5]));
	$id=$in_id;

        if($in_id!='')
        {
                // NAVIGATION DETAILS
                $result= mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1;")
                         or die("Error #".mysql_errno().": ".mysql_error());
                $nav_info=array();
                while($data=mysql_fetch_row($result))
                {
                        $data[1]=preg_replace("/\//"," or ",$data[1]);
                        $nav_info[$data[0]]=$data[1];
                }
                mysql_free_result($result);

                extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$in_id."' AND `nnet_approval`='1' LIMIT 1;"),MYSQL_ASSOC));
                $nnet_titlez=$nnet_title;

                $cid_nav =mysql_fetch_row(mysql_query("SELECT `nnet_nav` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='$nnet_cid';"));

                $tmp_array=explode("|",$cid_nav[0]);

                for($i=0;$i<count($tmp_array);$i++)
                {
                        $tmp_nav.="<a href=\"cat_$tmp_array[$i].html\"  class=\"link_nav\">".$nav_info[$tmp_array[$i]]."</a>  ";
                        $tmp_cast.=$nav_info[$tmp_array[$i]];
                }
                if(strlen($tmp_cast)<2){ $tmp_nav="$info_cat[0]"; } else { $tmp_nav.="$info_cat[0]"; }
                $tpl_nav=preg_replace("/\/\//","",$tmp_nav);$tpl_nav.="<a href=\"cat_$nnet_cid.html\" class=\"link_nav\">".$nav_info[$nnet_cid]."</a>";unset($tmp_nav);

                if(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.jpg"))     { $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.jpg"; }
                elseif(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.gif")) { $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.gif"; }
                else                                                          { $var_fpic="$tpl_template_url/gfx/no_image.gif";     }

                if(file_exists("$_cfig[dir_upload]/full_$nnet_aid.jpg") || file_exists("$_cfig[dir_upload]/full_$nnet_aid.gif"))
                {
                        $var_enlarge.="<br><br><a href=\"fullsize_$nnet_aid.html\"><img border=\"0\" src=\"$tpl_template_url/gfx/front/main_images.jpg\"></a>&nbsp;Enlarge</font></b>";
                }
                $nnet_time=_date($nnet_time);
                $gbl_microtime=microtime()-$gbl_microtime;

                // CHECK POLL
                extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='$nnet_aid' LIMIT 1")));

                $data=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_isup`='1' and `nnet_aid`='".$in_id."'"));
                $var_up=$data[0];

                $data=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_isup`='0' and `nnet_aid`='".$in_id."'"));
                $var_down=$data[0];

                if(($var_up+$var_down)==0)
                {
                        $var_up  =0;
                        $var_down=0;
                }
                else
                {
                        $var_down=round(($var_down*100)/($var_down+$var_up));
                        $var_up  =round(($var_up  *100)/($data[0] +$var_up));
                }
                unset($data);

		if($_cfig[show_relate_articles]==1)
		{
			$result=mysql_query("SELECT `nnet_aid`,`nnet_title` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_cid`='$nnet_cid' ORDER BY `nnet_aid` LIMIT 0, $_cfig[show_relate_numb];")
				or die("Error #".mysql_errno().": ".mysql_error());
			while($crelate=mysql_fetch_row($result))
			{
				$var_relates.="+ <a href='$_cfig[url_static]/article$crelate[0].html'>$crelate[1]</a><br>";
			}
			mysql_free_result($result);
		}

                $totalsize=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='".$in_id."'"));
                //$var_span=_span($_cfig[span],$gbl_env["page"]+1,$totalsize[0],"browse.php?mod=article&opt=view&id=".$in_id);

                $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='".$in_id."' LIMIT $startpoint,$_cfig[span_review];")
                        or die("Error #".mysql_errno().": ".mysql_error());
                $html_docs=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_reviews_details.txt",0);
                while($data=mysql_fetch_array($result,MYSQL_ASSOC))
                {
                        extract($data);
                        $nnet_isup= cif("$nnet_isup==1","<img src=\"$tpl_template_url/gfx/good.gif\">","<img src=\"$tpl_template_url/gfx/bad.gif\">");
                        $review_tables.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);
                }
                mysql_free_result($result);

                $url_article_details="article$nnet_aid"."_details.html";
                $url_article_enlarge="fullsize_$nnet_aid.html";

                $data=mysql_fetch_row(mysql_fetch_array("SELECT `nnet_uname` FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_uid`='$nnet_uid' LIMIT 1;"));
                $nnet_author ="<a href=\"$url_php/browse.php?mod=members&opt=view&id=$nnet_uid\">$data[0]</a>";
                $var_span="<a href=\"$url_php/browse.php?mod=article&opt=view&id=$in_id\">More Reviews</a>";
                if($nnet_uid==0)
                {
                        $nnet_author="Administrator";
                }
		
		////////////////////////////////////////////////////////////////////////
                if($nnet_ques!='')
                {
                        $numbr1=round(($nnet_nans1*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg1=$numbr1*2;
                        $numbr2=round(($nnet_nans2*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg2=$numbr2*2;
                        $numbr3=round(($nnet_nans3*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg3=$numbr3*2;
                        $numbr4=round(($nnet_nans4*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg4=$numbr4*2;
			if(count($db_split)>1)
			{
				
				$var_page_span="Pages: ";
				for($i=0;$i<count($db_split);$i++)
				{
      					if($i==''){$nav='';}
					else      {$nav="_$i";}

					$var_page_span.="[<a href='$_cfig[url_static]/article{$in_id}{$nav}.html'>".($i+1)."</a>]&nbsp;";
				}				

				for($i=0;$i<count($db_split);$i++)
				{
					$nnet_data=$db_split[$i];

      					if($i==''){$nav='';}
					else      {$nav="_$i";}

					sys01_write("$_cfig[dir_static]/article{$in_id}{$nav}.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_pview.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));
                        		sys01_write("$_cfig[dir_static]/article{$in_id}{$nav}_details.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_view_details.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));
				}
			}
			else
			{
				sys01_write("$_cfig[dir_static]/article$in_id.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_pview.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));
                        	sys01_write("$_cfig[dir_static]/article$in_id"."_details.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_view_details.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));
			}
			
                }
                else
                {
			if(count($db_split)>1)
			{

				$var_page_span="Pages: ";
				for($i=0;$i<count($db_split);$i++)
				{
      					if($i==''){$nav='';}
					else      {$nav="_$i";}

					$var_page_span.="[<a href='$_cfig[url_static]/article{$in_id}{$nav}.html'>".($i+1)."</a>]&nbsp;";
				}				

				for($i=0;$i<count($db_split);$i++)
				{
					$nnet_data=$db_split[$i];
      					if($i==''){$nav='';}
					else      {$nav="_$i";}
					sys01_write("$_cfig[dir_static]/article{$in_id}{$nav}.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_view.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));
                        		sys01_write("$_cfig[dir_static]/article{$in_id}{$nav}"."_details.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_view_details.html",0). _html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));
				}
			}
			else
			{
				
                        	sys01_write("$_cfig[dir_static]/article$in_id.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_view.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));
                       		sys01_write("$_cfig[dir_static]/article$in_id"."_details.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0)._html("$_cfig[dir_skins]/$_cfig[template]/html/browse_article_view_details.html",0). _html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0)));	
			}
                }
        }


}
function gbl_build_thumb($in_id)
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
        $in_date=_date(time());
        $tpl_template_url=$_cfig[url_tpl];
        ////////////////////////////////////////////////
        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];

        if(file_exists("$_cfig[dir_upload]/full_$in_id.jpg"))
        {
                $filename="$_cfig[url_upload]/full_$in_id.jpg";
        }
        elseif(file_exists("$_cfig[dir_upload]/full_$in_id.gif"))
        {
                $filename="$_cfig[url_upload]/full_$in_id.gif";
        }

        if($filename=='')
        {
                $var_enlarge='No image to enlarge';
        }
        else
        {
                $var_enlarge="<img src=\"$filename\" border=0>";
        }
        $url_article="article$in_id.html";
        extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='$in_id';"),MYSQL_ASSOC));
        $nnet_time=_date($nnet_time);
        $rate_bar="<img border=0 src=\"$tpl_template_url/gfx/stars/$nnet_rrate.gif\">";
        $gbl_microtime=microtime()-$gbl_microtime;

        sys01_write("$_cfig[dir_static]/fullsize_$in_id.html",preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_articles_enlarge.html",0)));

}
function gbl_clean_files($in_dir)
{
        if ($dir = @opendir($in_dir))
        {
                while (($file = readdir($dir)) !== false)
                {
                        if(strlen($file)>2 && !is_dir("$in_dir/$file"))
                        {
                                unlink("$in_dir/$file");
                        }
                }
                closedir($dir);
                return 1;
        }
        else
        {
                return 0;
        }
}
?>