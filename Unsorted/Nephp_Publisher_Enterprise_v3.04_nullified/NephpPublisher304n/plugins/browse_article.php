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
function browse_article()
{

        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig,$HTTP_COOKIE_VARS;
        $tpl_template_url=$_cfig[url_tpl];

        $url_skins=$_cfig[url_skins];
        $url_upload=$_cfig[url_upload];
        $url_php=$_cfig[url_php];
        $url_tpl=$_cfig[url_tpl];unset($html);

	$id=$gbl_env["id"];
		
	if($_cfig[static_pages]!=1){ $url_php_home="$url_php/browse.php"; }
	else                       { $url_php_home="$_cfig[url_static]/index.html";}

        ///////////////////////////////////////////////////////////////////////////
        // POST-SPAN                                                             //
        ///////////////////////////////////////////////////////////////////////////
        if($gbl_env["page"] == '')
        {
                $gbl_env["page"]=1;
        }
        $gbl_env["page"]--;
        $startpoint=$_cfig[span_review]*$gbl_env["page"];

        $in_date=_date (time());
	
        if($gbl_env["opt"]=='view')
        {
                
		mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_views`=`nnet_views`+1 WHERE `nnet_aid`='".$gbl_env["id"]."';")
                or die("Error #".mysql_errno().": ".mysql_error());

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

                extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."' AND `nnet_approval`='1' LIMIT 1;"),MYSQL_ASSOC));
		$nnet_titlez=$nnet_title;
	
		// MULTIPAGE HACK \\\
                ////////////////////////////////////////////////////////////////////////
		$db_split=preg_split("/\[-split-\]/",$nnet_data);
		if(count($db_split)>1)
		{
			$var_page_span="Pages: ";
			for($i=0;$i<count($db_split);$i++)
			{
				$var_page_span.="[<a href='browse.php?mod=article&opt=view&id=".$gbl_env["id"]."&subpage=$i'>".($i+1)."</a>]&nbsp;";
			}
		}
		if($gbl_env['subpage']==''){$gbl_env['subpage']='0';}
		$nnet_data=$db_split[$gbl_env['subpage']];

		/////////////////////////////////////////////////////////////////////////

                $cid_nav =mysql_fetch_row(mysql_query("SELECT `nnet_nav` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='$nnet_cid';"));

                $tmp_array=explode("|",$cid_nav[0]);

                for($i=0;$i<count($tmp_array);$i++)
                {
                        if($_cfig[static_pages]!=1)
			{
				$tmp_nav.="<a href=\"browse.php?opt=browse&catid=".$tmp_array[$i]."\" class=\"link_nav\">".$nav_info[$tmp_array[$i]]."</a>  ";
			}
			else
			{
				$tmp_nav.="<a href=\"$_cfig[url_static]/cat_".$tmp_array[$i].".html\" class=\"link_nav\">".$nav_info[$tmp_array[$i]]."</a>  ";
			}
                        $tmp_cast.=$nav_info[$tmp_array[$i]];
                }
                if(strlen($tmp_cast)<2){ $tmp_nav="$info_cat[0]"; } else { $tmp_nav.="$info_cat[0]"; }
                $tpl_nav=preg_replace("/\/\//","",$tmp_nav);
	
		if($_cfig[static_pages]!=1)
		{
			$tpl_nav.="<a href=\"browse.php?opt=browse&catid=".$nnet_cid."\" class=\"link_nav\">".$nav_info[$nnet_cid]."</a>";unset($tmp_nav);
		}
		else
		{
			$tpl_nav.="<a href=\"$_cfig[url_static]/cat_".$nnet_cid.".html\" class=\"link_nav\">".$nav_info[$nnet_cid]."</a>";unset($tmp_nav);
		}

                if(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.jpg"))     { $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.jpg"; }
                elseif(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.gif")) { $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.gif"; }
                else                                                          { $var_fpic="$tpl_template_url/gfx/no_image.gif";     }

                if(file_exists("$_cfig[dir_upload]/full_$nnet_aid.jpg") || file_exists("$_cfig[dir_upload]/full_$nnet_aid.gif"))
                {
                        $var_enlarge.="<br><br><a href=\"show.php?id=$nnet_aid\"><img border=\"0\" src=\"$tpl_template_url/gfx/front/main_images.jpg\"></a>&nbsp;Enlarge</font></b>";
                }
                $nnet_time=_date($nnet_time);
                $gbl_microtime=microtime()-$gbl_microtime;

                // CHECK POLL
                extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_aid`='$nnet_aid' LIMIT 1")));
		
		$data=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_isup`='1' and `nnet_aid`='".$gbl_env["id"]."'"));
		$var_up=$data[0];

		$data=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_isup`='0' and `nnet_aid`='".$gbl_env["id"]."'"));
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
				if($_cfig[static_pages]!=1)
				{
					$var_relates.="+ <a href='$url_php/browse.php?mod=article&opt=view&id=$crelate[0]'>$crelate[1]</a><br>";
				}
				else
				{
					$var_relates.="+ <a href='$_cfig[url_static]/article$crelate[0].html'>$crelate[1]</a><br>";
				}
			}
		}
		
		$totalsize=mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='".$gbl_env["id"]."'"));
		$var_span=_span($_cfig[span_review],$gbl_env["page"]+1,$totalsize[0],"browse.php?mod=article&opt=view&id=".$gbl_env["id"]);

		$result=mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT $startpoint,$_cfig[span_review];")
			or die("Error #".mysql_errno().": ".mysql_error());
		//$tmp="$_cfig[dir_tpl]/html/browse_reviews_details.txt";
		//$html_docs=_html("$_cfig[dir_tpl]/html/browse_reviews_details.txt",0);

		$html_docs=_html("$_cfig[dir_tpl]/html/browse_reviews_details.txt",0);
		while($data=mysql_fetch_array($result,MYSQL_ASSOC))
		{
			extract($data);
			$nnet_isup= cif("$nnet_isup==1","<img src=\"$tpl_template_url/gfx/good.gif\">","<img src=\"$tpl_template_url/gfx/bad.gif\">");
			$review_tables.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);
		}
		mysql_free_result($result);
		
		if($review_tables=='') { $review_tables="<br><br>Be the first one to write a review";}

		$url_article_details="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid&style=details";
		$url_article_enlarge="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid&style=details";
		
		$data=mysql_fetch_row(mysql_query("SELECT `nnet_uname` FROM `$_cfig[sql_db]`.`nnet_users` WHERE `nnet_uid`='$nnet_uid' LIMIT 1;"));
		if($_cfig{"isvb"}!=1)
		{
			$nnet_author ="<a href=\"$url_php/browse.php?mod=members&opt=view&id=$nnet_uid\">$data[0]</a>";
		}
		else
		{
			$nnet_author ="<a href=\"{$_cfig{"vb_url"}}/member.php?action=getinfo&userid=$nnet_uid\">Author Info</a>";
		}
		
		if($nnet_uid==0)
		{
			$nnet_author="Administrator";
		}
                if($nnet_ques!='')
                {
                        $numbr1=round(($nnet_nans1*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg1=$numbr1*2;
                        $numbr2=round(($nnet_nans2*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg2=$numbr2*2;
                        $numbr3=round(($nnet_nans3*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg3=$numbr3*2;
                        $numbr4=round(($nnet_nans4*100)/($nnet_nans1+$nnet_nans2+$nnet_nans3+$nnet_nans4));$numbg4=$numbr4*2;
			if($gbl_env["style"]!='details')
			{
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_article_pview.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
			}
			else
			{
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_article_view_details.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
			}
                }
                else
                {
			if($gbl_env["style"]!='details')
			{
				
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_article_view.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
			}
			else
			{
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));				
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_article_view_details.html",0));
				print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
			}
                }
        }
	elseif($gbl_env["opt"]=='printable')
	{
		extract(mysql_fetch_array(mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_aid`='".$gbl_env["id"]."' LIMIT 1;"),MYSQL_ASSOC));
		$nnet_time=_date($nnet_time);
		if(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.jpg"))     { $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.jpg"; }
                elseif(file_exists("$_cfig[dir_upload]/thumb_$nnet_aid.gif")) { $var_fpic="$_cfig[url_upload]/thumb_$nnet_aid.gif"; }
                else                                                          { $var_fpic="$tpl_template_url/gfx/no_image.gif";     }
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/browse_printable.html",0));
	}
	elseif($gbl_env["opt"]=='rate')
	{
		if($gbl_env["rate_name"] < 0 &&  $gbl_env["rate_name"] > 10)
		{
			 _err("Invalid Rate input.");
		}
		if($gbl_env["id"]=='') { _err("Please specify your id."); }
		if((time()-$HTTP_COOKIE_VARS["nnet_grate_numb".$gbl_env["id"]])>3600) // 1 hours
                {
			mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_articles` SET `nnet_trate`=`nnet_trate`+'".$gbl_env["rate_name"]."', `nnet_nrate`=`nnet_nrate`+1,`nnet_rrate`=`nnet_trate`/`nnet_nrate` WHERE `nnet_aid`='".$gbl_env["id"]."'")
			or die("Error #".mysql_errno().": ".mysql_error());
			if($_cfig[auto_update]==1)
			{
				include("$_cfig[dir_library]/admin_built_static.php");
				gbl_build_document($gbl_env["id"]);
			}
			_cookie("nnet_grate_numb".$gbl_env["id"],time());
			if($_cfig[static_pages]!=1) { Header("Location: browse.php?mod=article&opt=view&id=".$gbl_env["id"]);}
			else                        { Header("Location: $_cfig[url_static]/article".$gbl_env["id"].".html"); }
		}
		else
		{
			print "<script>alert('You rated this article.');document.location='$_cfig[url_static]/article".$gbl_env["id"].".html';</script>";
		}
	}
        elseif($gbl_env["opt"]=='polls')
        {
               
		if($gbl_env["choice"] < 1 &&  $gbl_env["choice"] > 4)
		{
			 _err("Invalid poll input.");
		}		

		if((time()-$HTTP_COOKIE_VARS["nnet_gpoll_numb".$gbl_env["id"]])>3600) // 1 hours
                {
			//setcookie("nnet_gpoll_numb".$gbl_env["id"],1,time() + 1000);
			_cookie("nnet_gpoll_numb".$gbl_env["id"],time());

			mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_polls` SET `nnet_nans".$gbl_env["choice"]."`=`nnet_nans".$gbl_env["choice"]."`+1 WHERE `nnet_aid`='".$gbl_env["id"]."'")
			or die("Error #".mysql_errno().": ".mysql_error());

			if($_cfig[auto_update]==1)
			{
				include("$_cfig[dir_library]/admin_built_static.php");
				gbl_build_document($gbl_env["id"]);
			}
			if($_cfig[static_pages]!=1) { Header("Location: browse.php?mod=article&opt=view&id=".$gbl_env["id"]);}
			else                        { Header("Location: $_cfig[url_static]/article".$gbl_env["id"].".html"); }
                }
                else
                {
                        print "<script>alert('You just casted your poll this document.');document.location='$_cfig[url_static]/article".$gbl_env["id"].".html';</script>";
                }
        }
}
?>