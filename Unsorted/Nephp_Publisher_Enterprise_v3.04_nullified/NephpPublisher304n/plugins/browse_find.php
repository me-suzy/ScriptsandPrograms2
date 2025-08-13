<?php
/*
 ####   ###        ###                  ##						  
  ####   #          ##                  ##						  
  #####  #          ##									  
  # ###  #   ####   ##    ###    #########    ###					  
  #  ### #   #  ##  ##   #   #  ##  ##  ##   #  ##					  
  #  #####  ######  ##  ##   ## ##  ##  ##  ##  ##					  
  #   ####  ##      ##  ##   ## ##  ##  ##  ##						  
  #    ###  ##   #  ##  ##   ##   ###   ##  ##						  
  #     ##  ###  #  ##   #   #  ##      ##  ##	 #
 ###     #   ####  ####   ###   ###### ####  ####
                                 ######		
                                ##   ##						  
                                 #####							  
  ########              ###                      ###                  ##		  
  #  ##  #               ##                       ##                  ##		  
     ##                  ##                       ##					  
     ##    ####    ###   ## ### ### ###    ###    ##    ###    #########   ####    ####  
     ##    #  ##  #  ##  ###  ## ###  ##  #   #   ##   #   #  ##  ##  ##   #  ##  ##  #  
     ##   ###### ##  ##  ##   ## ##   ## ##   ##  ##  ##   ## ##  ##  ##  ######  ####	 
     ##   ##     ##      ##   ## ##   ## ##   ##  ##  ##   ## ##  ##  ##  ##       ####  
     ##   ##   # ##      ##   ## ##   ## ##   ##  ##  ##   ##   ###   ##  ##   #  #  ##  
     ##   ###  # ###     ##   ## ##   ##  #   #   ##   #   #  ##      ##  ###  #  #  ##  
    ####   ####   ##### ###   #####   ###  ###   ####   ###   ###### ####  ####   ####	 
                                                               ######			 
                                                              ##   ##			 
                                                               ######			 
 Program name: NePublisher Server ( PHP EDITION )
 =======================================================================================
                                                                                        
 DECLAIMER										  
 =========										  
 This program is protected by the US pattern services. Any illegal possession will	  
 persecuted under the law. The owner of this program may modify the coding to fit their  
 needs. Please keep in mind that if we find out that you share this program to others    
 without our permission, your license might be terminated. Thank you. */
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

function browse_find()
{
        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;
	global $gbl_sid,$gbl_id,$gbl_type, $gbl_name,$cperf;

        $tpl_template_url=$_cfig[url_tpl];
	
	$url_skins=$_cfig[url_skins];
	$url_upload=$_cfig[url_upload];
	$url_php=$_cfig[url_php];
	$url_tpl=$_cfig[url_tpl];
	$in_date=_date(time());

	
	$id=$gbl_env["id"];

	///////////////////////////////////////////////////////////////////////////
        // POST-SPAN                                                             //
        ///////////////////////////////////////////////////////////////////////////
        if($gbl_env["page"] == '')     { $gbl_env["page"]=1;}
	$gbl_env["page"]--;$startpoint=$_cfig[span]*$gbl_env["page"];
        ///////////////////////////////////////////////////////////////////////////
	
	if(preg_match("/\.cookie/i",$gbl_env["keywords"]))
	{
		_err("Malicious cookie stealing code has found. Action terminated.");
	}

	if($gbl_env["opt"] == "")
	{
		if(strlen($gbl_env["keywords"]) < 3) { _err("Your query is too short."); }
		$keywords=$gbl_env["keywords"];

		$line = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE (`nnet_title` REGEXP '$keywords' OR `nnet_desc` REGEXP '$keywords' OR `nnet_data` REGEXP '$keywords') AND `nnet_approval`='1';"));
                $totalsize=$line[0];

                $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE (`nnet_title` REGEXP '$keywords' OR `nnet_desc` REGEXP '$keywords' OR `nnet_data` REGEXP '$keywords') AND `nnet_approval`='1' ORDER BY `nnet_aid` DESC LIMIT $startpoint,$_cfig[span];")
                          or die("Error #".mysql_errno().": ".mysql_error());

                $html_docs=_html("$_cfig[dir_tpl]/html/browse_listings_default.txt",0);

                while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
                {
	                extract($line);
			$datdes=preg_replace("/$keywords/i", "<font color=\"#71AE1A\"><b>$keywords</b></font>",$datdes);
			if(strlen($datdes)>160)
			{
				$datdes=substr($datdes,0,160)."...";
			}			
                        if($nnet_feature  != 0)
                        {
                                $isfeatured=" <sup><b><font face=\"Verdana\" size=\"2\" color=\"#FF0000\">featured</font></b></sup>";
                        }
                        else
                        {
                                $isfeatured="";
                        }
			if($_cfig[static_pages]!=1){$var_listings_view_url="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid";}
                        else                       {$var_listings_view_url="$_cfig[url_static]/article$nnet_aid.html";}

			if($_cfig{"isvb"}!=1)
			{
				$var_listings_author_url ="$url_php/browse.php?mod=members&id=$nnet_uid";
			}
			else
			{
				$var_listings_author_url ="{$_cfig{"vb_url"}}/member.php?action=getinfo&userid=$nnet_uid";
			}

                        $article_listing.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);
                }
                mysql_free_result($result);

		if($article_listing == "")
		{
			$article_listing="<h1 align=\"center\"><font face=\"Verdana\" color=\"#008080\" size=\"3\">No documents are found in this category.</font></h1>";
		}

                $span_display=_span($_cfig[span],$gbl_env["page"]+1,$totalsize,"browse.php?mod=find&keywords=$keywords");
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_search.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
	}
	elseif ($gbl_env["opt"]=="author")
	{
                if($id == "")
		{
			_err("Please specify author ID.");
		}
		$keywords="posts by author.";

		$line = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_articles` WHERE (`nnet_uid`='$id') AND `nnet_approval`='1';"));
                $totalsize=$line[0];

                $result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_articles` WHERE (`nnet_uid`='$id') AND `nnet_approval`='1' ORDER BY `nnet_aid` DESC LIMIT $startpoint,$_cfig[span];")
                          or die("Error #".mysql_errno().": ".mysql_error());

                $html_docs=_html("$_cfig[dir_tpl]/html/browse_listings_default.txt",0);

                while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
                {
	                extract($line);
			if(strlen($datdes)>160)
			{
				$datdes=substr($datdes,0,160)."...";
			}			
                        if($nnet_feature  != 0)
                        {
                                $isfeatured=" <sup><b><font face=\"Verdana\" size=\"2\" color=\"#FF0000\">featured</font></b></sup>";
                        }
                        else
                        {
                                $isfeatured="";
                        }
			if($_cfig[static_pages]!=1){$var_listings_view_url="$url_php/browse.php?mod=article&opt=view&id=$nnet_aid";}
                        else                       {$var_listings_view_url="$_cfig[url_static]/article$nnet_aid.html";}

			if($_cfig{"isvb"}!=1)
			{
				$var_listings_author_url ="$url_php/browse.php?mod=members&id=$nnet_uid";
			}
			else
			{
				$var_listings_author_url ="{$_cfig{"vb_url"}}/member.php?action=getinfo&userid=$nnet_uid";
			}

                        $article_listing.=preg_replace("/{%(\w+)%}/ee", "$\\1",$html_docs);
                }
                mysql_free_result($result);

		if($article_listing == "")
		{
			$article_listing="<h1 align=\"center\"><font face=\"Verdana\" color=\"#008080\" size=\"3\">No documents are found in this category.</font></h1>";
		}

                $span_display=_span($_cfig[span],$gbl_env["page"]+1,$totalsize,"browse.php?mod=find&opt=author&id=$id");
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_header.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_search.html",0));
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_tpl]/html/browse_footer.html",0));
	}
	else {}	
}
?>