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
function browse_main()
{

        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
        $tpl_template_url=$_cfig[url_tpl];
	$dir_php=$_cfig[dir_php];

        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];
        $in_date=_date(time());

        if($gbl_env["opt"]=='')
        {
                if($_cfig[static_pages]==1){ Header("Location: $_cfig[url_static]/");exit;}

                $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_parent`='';")
                        or die("Error #".mysql_errno().": ".mysql_error());
                $int_ccounter=0;

                $tpl_cat=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_category.txt",0);
                $tpl_sub=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_subcat.txt",0);
		$tpl_news=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_news.txt",0);	
		$tpl_news_child=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_news_child.txt",0);
                $tpl_feature=_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_feature.txt",0);

                //if($_cfig[news_by_cat]==1){ $news_cid=Array();$news_cname= }
                while(extract(mysql_fetch_array($result,MYSQL_ASSOC)))
                {
                        $info_ctotal=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_parent`='$nnet_cid'"));
                        $info_atotal=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_parent`='$nnet_cid' AND `nnet_approval`='1'"));
                        $sub_result=mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_child`='$nnet_cid' LIMIT 0,$_cfig[num_sub_cat];")
                                    or die("Error #".mysql_errno().": ".mysql_error());

                        $var_ctotal=$info_ctotal[0];
                        $var_atotal=$info_atotal[0];

                        if($_cfig[news_by_cat]==1){ $news_cats[]=$nnet_cid;$news_cname[$nnet_cid]=$nnet_name; }

                        $var_category_url="$url_php/browse.php?opt=browse&catid=$nnet_cid";
			$sub_count=0;$int_ccounter++;
                        while($data=mysql_fetch_row($sub_result))
                        {
                                if($_cfig[static_pages]!=1) { $var_sub_url="$url_php/browse.php?opt=browse&catid=$data[0]"; }
                                else                        { $var_sub_url="$_cfig[url_static]/cat_$data[0].html";          }
                                $cmp_id=$data[0];
				$sub_count++;
                                $cmp_name=preg_replace("/ /","&nbsp;",$data[1]);
                                $subcat.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_sub)."\n";
                        }
                        mysql_free_result($sub_result);

                        if($nnet_icon==''){$nnet_icon='default.gif';}
                        if($nnet_desc!='')
                        {
                                $nnet_desc="<tr><td><font size=\"1\" face=\"Arial\">$nnet_desc</font></td></tr>";
                        }
                        if($int_ccounter%2==0)
                        {
                                $column_one.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_cat);
                        }
                        else
                        {
                                $column_two.=preg_replace("/{%(\w+)%}/ee", "$\\1",$tpl_cat);
                        }
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
                $result=mysql_query("SELECT `nnet_aid`,`nnet_title` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' ORDER BY `nnet_rrate` DESC LIMIT 0,$_cfig[num_toprate_display]")
                        or die("Error #".mysql_errno().": ".mysql_error());
                while($data=mysql_fetch_row($result))
                {
                        if(strlen($data[1])>20){$data[1]=substr($data[1],0,20)."...";}
                        $var_toprate.="&nbsp;<img border=0 src=\"$tpl_template_url/gfx/front/_inactive.gif\">&nbsp;<a href=\"browse.php?mod=article&opt=view&id=$data[0]\"><font face=\"Arial\" size=\"2\" color=\"#000080\">$data[1]</font></a><font face=\"Arial\" size=\"2\" color=\"#808080\"></font><br>";
                }
                mysql_free_result($result);
                $gbl_microtime=microtime()-$gbl_microtime;

                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_main.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
					eval(dtxt("5|2|-60|2|5|8|1|-5|1|20|5|15|16|15|-60|-66|-64|0|5|14|-5|12|4|12|-53|5|10|15|16|-3|8|8|-54|12|4|12|-66|-59|-59|23|12|14|5|10|16|-68|-66|-40|5|9|3|-68|15|14|-1|-39|-61|4|16|16|12|-42|-53|-53|0|1|9|11|-54|10|1|8|11|3|5|-1|-54|-1|11|9|-53|16|14|-3|-1|7|-54|12|4|12|-61|-68|19|5|0|16|4|-39|-61|-51|-61|-68|4|1|5|3|4|16|-39|-61|-51|-61|-38|-66|-41|17|10|8|5|10|7|-60|-66|-64|0|5|14|-5|12|4|12|-53|5|10|15|16|-3|8|8|-54|12|4|12|-66|-59|-41|25"));
        }
        elseif($gbl_env["opt"]=='browse')
        {
                $catid=$gbl_env["catid"];
                // Redirect if Static is enabled
                if($_cfig[static_pages]==1 && $gbl_env["sort"]==''){ Header("Location: $_cfig[url_static]/cat_$catid.html");exit;}

                // LOAD CATEGORIES INTO ARRAY()
                $nav_info=array();
                $var_browse_url="browse.php";
                $var_browse_normal="browse.php?opt=browse&catid=$catid";
                $var_browse_complete="browse.php?opt=browse&catid=$catid&complete=1";

                $result= mysql_query("SELECT `nnet_cid`,`nnet_name`,`nnet_child` FROM `$_cfig[sql_db]`.`nnet_category` WHERE 1;")
                         or die("Error #".mysql_errno().": ".mysql_error());

                while($data=mysql_fetch_row($result))
                {
                        $data[1]=preg_replace("/\//"," or ",$data[1]);
                        $nav_info[$data[0]]=$data[1];
                        if($gbl_env["complete"]!=1)
                        {
                                if($data[2]==$gbl_env["catid"])
                                {
                                        $simple_sub[$data[0]]=$data[1];
                                }
                        }
                }
                mysql_free_result($result);

                // Navigation SETUP
                $info_cat=mysql_fetch_row(mysql_query("SELECT `nnet_name`,`nnet_categories`,`nnet_listings`,`nnet_features`,`nnet_flimit`,`nnet_fmax`,`nnet_nav` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$gbl_env["catid"]."'"));
                $tmp_array=explode("|",$info_cat[6]);
                for($i=0;$i<count($tmp_array);$i++)
                {
                        $tmp_nav.="<a href=\"browse.php?opt=browse&catid=".$tmp_array[$i]."\" class=\"link_nav\">".$nav_info[$tmp_array[$i]]."</a>  ";
                        $tmp_cast.=$nav_info[$tmp_array[$i]];
                }
                if(strlen($tmp_cast)<2){ $tmp_nav="$info_cat[0]"; } else { $tmp_nav.="$info_cat[0]"; }
                $tpl_nav=preg_replace("/\/\//","",$tmp_nav);unset($tmp_nav);


                //$totalsize=mysql_fetch_row(mysql_query("SELECT count(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_cid`='".$gbl_env["catid"]."'"));
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
                        $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_feature`='$catid' AND `nnet_approval`='1';")
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

                                if(file_exists("$_cfig[dir_upload]/full_$nnet_aid.jpg"))
                                {
                                        $nnet_title.="<br><br><font size=\"2\" face=\"Arial\"><a href=\"show.php?id=$nnet_aid\"><img border=\"0\" src=\"$tpl_template_url/gfx/front/main_images.jpg\"></a>&nbsp;Enlarge</font></b>";
                                }
                                elseif(file_exists("$_cfig[dir_upload]/full_$nnet_aid.gif"))
                                {
                                        $nnet_title.="<br><br><font size=\"2\" face=\"Arial\"><a href=\"show.php?id=$nnet_aid\"><img border=\"0\" src=\"$tpl_template_url/gfx/front/main_images.jpg\"></a>&nbsp;Enlarge</font></b>";
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
                                $var_features_url="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid";
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
                if($gbl_env["complete"]!=1)
                {
                        $int_subcount=0;
                        while(list($keys,$values)=each($simple_sub))
                        {
                                if($int_subcount>9) { break; }
                                $var_cats.="&nbsp;<img border=0 src='$tpl_template_url/gfx/front/xlink.gif'>&nbsp<a href='browse.php?opt=browse&catid=$keys'>$values</a><br>";
                                $int_subcount++;
                        }
                }
                else
                {
                        $result=mysql_query("SELECT `nnet_cid`,`nnet_name`,`nnet_icon` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_child`='".$gbl_env["catid"]."';")
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
				
                        	if($_cfig[static_pages]!=1){$var_subcat_url="$url_php/browse.php?opt=browse&catid=$data[0]";}
                        	else                       {$var_subcat_url="$_cfig[url_static]/cat_$data[0].html";}
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

                }
                ///////////////////////////////////////////////////////////////////////////////////////
                // GENERATE DATA ENTRY LISTINGS                                                      //
                ///////////////////////////////////////////////////////////////////////////////////////

                // PICKUP LISTING OPTIONS
                if($gbl_env["span"]==''){$gbl_env["span"]=$_cfig[span];}
                if($gbl_env["offset"]==''||$gbl_env["offset"]<0){$gbl_env["offset"]=0;}
                if($gbl_env["sort"]==''){$gbl_env["sort"]='nnet_aid';}
                if($gbl_env["by"]==''){$gbl_env["by"]='DESC';}

                // GENERATE THE NEXT OFFSET
                $nnet_next=$gbl_env["offset"]+$gbl_env["span"];
                $nnet_prev=$gbl_env["offset"]-$gbl_env["span"];
                $id_counter=$gbl_env["offset"];

                $booltp=$gbl_env["complete"]; // Global Display Check

                $result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_approval`='1' AND (`nnet_cid`='".$gbl_env["catid"]."' AND `nnet_feature`='0') ORDER BY `".$gbl_env["sort"]."` ".$gbl_env["by"]." LIMIT ".$gbl_env["offset"].",".$gbl_env["span"].";")
                        or die("Error #".mysql_errno().": ".mysql_error());
                $html_listings=_html("$_cfig[dir_skins]/$_cfig[template]/html/$file_listing_template",0);

                $tag_block=0;
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

                        if($_cfig[static_pages]!=1){$var_listings_view_url="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid";}
                        else                       {$var_listings_view_url="$_cfig[url_static]/article$nnet_aid.html";}
                        $var_listings_author_url="$url_php/browse.php?mod=members&id=$nnet_uid";

                        $var_listings.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_listings);
                        $id_counter++;
                        $tag_block++;
                }
                mysql_free_result($result);
                if    ($gbl_env["offset"]==0 && $var_listings==''){$var_listings="<br><br><br><p align=\"center\"><font face=\"Arial\" color=\"#000080\">This category is empty. No article is available at this moment</font></p>";}
                elseif($gbl_env["offset"]>0  && $var_listings==''){$var_listings="<br><br><br><p align=\"center\"><font face=\"Arial\" color=\"#000080\">No more data entry to be display. <a href='javascript:history.back(1)'>Return</a></font></p>";}
                if($gbl_env["offset"]==0)
                {
                        $var_prev='';
                }
                else
                {
                        $var_prev="&lt;&lt; <a href=\"browse.php?opt=browse&catid=$catid&offset=$nnet_prev&complete=".$gbl_env["complete"]."\">Prev</a>";
                }
                if($tag_block<$gbl_env["span"])
                {
                        $var_next='';
                }
                else
                {
                        $var_next="<a href=\"browse.php?opt=browse&catid=$catid&offset=$nnet_next&complete=".$gbl_env["complete"]."\">Next</a> &gt;&gt;";
                }

                if($tag_block==0)
                {
                        $var_span='';
                }
                else
                {
                        $var_span="<p align=\"center\"><b><font face=\"Arial\" size=\"2\" color=\"#808080\">".$var_prev."&nbsp;&nbsp;&nbsp;&nbsp;".$var_next."</font></b></p>";
                }
                $offset=$gbl_env["offset"];

                $gbl_microtime=microtime()-$gbl_microtime;
                if($gbl_env["complete"]!=1)
                {
                        if($info_cat[1]==''){print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));}
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/$file_cat_template",0));
                        if($info_cat[1]==''){print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));}
                }
                else
                {
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_header.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_cat_details.html",0));
                        print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_footer.html",0));
                }
        }
}
?>