<?php session_start();

// GETS VALUES FROM THE URL DATA
$urldata = explode("/",$_SERVER["PATH_INFO"]);

// GET HEADER AND PAGE TEMPLATE FILE
include("header.php");
$tpl->assignInclude("content", "themes/$themes/tpl/detail.tpl");

// PREPARE THE TEMPLATE
$tpl->prepare();

// GET THE MENU AND LANGUAGE FILES
include("./lang/$language/general.lang.php");
include("./lang/$language/comic.lang.php");
include("menu.php");

// ASSIGN NEEDED TAMPLATE VALUES
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);

// GET THE COMIC DETAILS FROM COMIC TABLE
$select = "SELECT * FROM pmc_comic WHERE uid = '". $urldata[1] ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
$row = mysql_fetch_array($data);

	// GET ALL NEEDED FIELDS
	$uid = $row['uid'];
  	$dat = $row['date'];
  	$name = $row['title'];
  	$issue = $row['issue'];
  	$issueltr = $row['issueltr'];
  	$publisher = $row['publisher'];
  	
  	// GET AND FORMAT THE IMAGE FIELD : IF NO IMAGE THEN USE DEFAULT
  	$i = $row['image'];
  	$image = "image/$i";
  	if (file_exists($image))
    	{
        	$img = $image;
     	} else {
        	$img = "image/noimage.jpg";
     	}
     	
  	$size = GetImageSize ("$img");
  	$width = $size['0'];
  	$height = $size['1'];  	
  	$price = $row['price'];
  	$value = $row['value'];
  	$story = stripslashes($row['story']);
  	$type = $row['type'];
  	$genre = $row['genre'];
  	$format = $row['format'];
  	$condition = $row['condition'];
  
  	// GET AND FORMAT THE PLOT FIELD : IF NO PLOT USE DEFAULT TEXT
  	$about = $row['plot'];
  	if($about ==  '')
  		{
    		$plot = $lang_no_plot;
    	} else {
      		$plot = $about;
    	}
      	
  	$volume = $row['volume'];
  	$part1 = $row['part1'];
  	$part2 = $row['part2'];  
  	$user1 = $row['user1'];
  	$user2 = $row['user2'];
	
	// GET AND FORMAT THE USER DEFINED FIELDS
	if($user1 == '')
      	{
      		$userdef1 = $lang_user_def1;
      	} else {
      		$userdef1 = $user1;
      	}
      	
	if($user2 == '')
      	{
      		$userdef2 = $lang_user_def2;
      	} else {
      		$userdef2 = $user2;
      	}
      	
  	$lang = $row['language'];
  	$translator = $row['translator'];
  	$variation = $row['variation'];
  	$currency = $row['currency'];
  	$loan = $row['loan'];
  	$fav = $row['fav'];
  	$ebay = $row['ebay'];
  	$ebaylink = $row['ebaylink'];
  	$pubdate = $row['pubdate'];
  	$qty = $row['qty'];
	
	// GET AND FORMAT THE LANGUAGE AND TRANSLATOR FIELDS
  	if ($lang == 'English' or $lang == '')
    	{
        	$language = "English";
     	} elseif ($translator == '') {
        	$language = "$lang";
     	} else {
        	$language = "$lang ($translator)";
     	}

  	// FORMAT THE DATE FIELDS AFTER USER OPTION
  	$dato = "SELECT date_format('$dat','$dateoption')";
  	$date = mysql_db_query($sql['data'], $dato) or die("Select Failed!");
  	$row = mysql_fetch_array($date);
  	$today = $row[0];

  	// SET TEMPLATE VALUES
  	$tpl->assign("comic_story", $story);
  	$tpl->assign("comic_cost", $price);
  	$tpl->assign("comic_value", $value);
  	$tpl->assign("comic_issue", $issue);
  	$tpl->assign("comic_issueltr", $issueltr);
  	$tpl->assign("comic_volume", $volume);
  	
  	// GET AND FORMAT THE PUBLICATION FIELD
  	if($pubdate == '' or $pubdate == '0000-00-00')
  		{
  			$tpl->assign("comic_pubdate", $lang_no_pubdate);
  		} else {
  			$tpl->assign("comic_pubdate", $pubdate);
  		}
  	
  	// GET AND FORMAT THE EBAY FIELD
  	if($ebay == 'yes')
  		{
  			$tpl->assign("comic_ebay", "<a href=\"$ebaylink\" class=\"defaultlink\" target=\"_blank\">$lang_ebay_click</a>");
  		} else {
  			$tpl->assign("comic_ebay", $lang_no_ebaylink);
  		}

  	// SET TEMPLATE VALUES
  	$tpl->assign("comic_useroption", $userdef1);
  	$tpl->assign("comic_useranswer", $userdef2);
  	$tpl->assign("comic_language", $language);  
  	$tpl->assign("comic_image", $img);
  	$tpl->assign("comic_qty", $qty);
  	$tpl->assign("comic_about", $plot);
  	$tpl->assign("comic_width", $width);
  	$tpl->assign("comic_height", $height);
  	$tpl->assign("comic_modified", $today);  
  	
  	//---------------------------------------------------------
  	// ARTISTS :: WRITERS
  	//---------------------------------------------------------
  	
  	$select_writer = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Writer'";
  	$data = mysql_db_query($sql['data'], $select_writer) or die("Select Writers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Writers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$writers[] = $realname['name'];
   		}

		if($writers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $writers);		
				$tpl->assign('comic_writer', $art);
		
			} 
  
  	//---------------------------------------------------------
  	// ARTISTS :: INKERS
  	//---------------------------------------------------------
  	
  	$select_inker = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Inker'";
  	$data = mysql_db_query($sql['data'], $select_inker) or die("Select Inkers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Inkers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$inkers[] = $realname['name'];
   		}

		if($inkers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $inkers);		
				$tpl->assign('comic_inker', $art);
			
			}
  	
	//---------------------------------------------------------
  	// ARTISTS :: PENCILERS
  	//---------------------------------------------------------
  	
  	$select_penciler = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Penciler'";
  	$data = mysql_db_query($sql['data'], $select_penciler) or die("Select Pencilers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Pencilers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$pencilers[] = $realname['name'];
   		}

		if($pencilers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $pencilers);		
				$tpl->assign('comic_penciler', $art);
				
			}
  	
  	//---------------------------------------------------------
  	// ARTISTS :: LETTERERS
  	//---------------------------------------------------------
  	
  	$select_letterer = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Letterer'";
  	$data = mysql_db_query($sql['data'], $select_letterer) or die("Select Letterers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Letterers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$letterers[] = $realname['name'];
   		}

		if($letterers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $letterers);		
				$tpl->assign('comic_letterer', $art);
				
			}
  	
  	//---------------------------------------------------------
  	// ARTISTS :: COLORIST
  	//---------------------------------------------------------
  	
  	$select_colorist = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Colorist'";
  	$data = mysql_db_query($sql['data'], $select_colorist) or die("Select Colorists Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Colorists Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$colorists[] = $realname['name'];
   		}

		if($colorists == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $colorists);		
				$tpl->assign('comic_colorist', $art);
				
			}
  	
  	//---------------------------------------------------------
  	// ARTISTS :: COVER ARTISTS
  	//---------------------------------------------------------
  	
  	$select_cover = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Coverartist'";
  	$data = mysql_db_query($sql['data'], $select_cover) or die("Select Covers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Covers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$covers[] = $realname['name'];
   		}

		if($covers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $covers);		
				$tpl->assign('comic_coverartist', $art);
				
			}
  	
  	  
  	// Get the series name
  	$select = "SELECT * FROM pmc_artist WHERE uid = '$name'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$name_uid = $row['name'];
  	$link_uid = $row['link'];
  	// Assign series link values
  	if($row['link'] == '') { $showlink_comic = $lang_comic_comurl; } else { $showlink_comic = "<a href=\"$link_uid\" class=\"listlink\" target=\"_blank\">$lang_comic_comurl</a>"; }
  	$tpl->assign("comic_title", $name_uid);

  	// Get the comic type
  	$select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$type_uid = $row['name'];
  	$tpl->assign("comic_type", $type_uid);

  	// Get the issue format
  	$select = "SELECT * FROM pmc_artist WHERE uid = '$format'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$format_uid = $row['name'];
  	$tpl->assign("comic_format", $format_uid);

  	// Get the publisher name
  	$select = "SELECT * FROM pmc_artist WHERE uid = '$publisher'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$publisher_uid = $row['name'];
  	$link_uid = $row['link'];
  	// Assign publisher link values
  	if($row['link'] == '') { $showlink_publisher = $lang_comic_puburl; } else { $showlink_publisher = "<a href=\"$link_uid\" class=\"listlink\" target=\"_blank\">$lang_comic_puburl</a>"; }
  	$tpl->assign("comic_publisher", $publisher_uid);

  	// Get the issue condition
  	$select = "SELECT * FROM pmc_artist WHERE uid = '$condition'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$condition_uid = $row['name'];
  	$tpl->assign("comic_condition", $condition_uid);

  	// Get the comic genre
  	$select = "SELECT * FROM pmc_artist WHERE uid = '$genre'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$genre_uid = $row['name'];
  	$tpl->assign("comic_genre", $genre_uid);

  	// Get the issue variation
  	$select = "SELECT * FROM pmc_artist WHERE uid = '$variation'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$variation_uid = $row['name'];
  	$tpl->assign("comic_variation", $variation_uid);

  	// Get the currency
  	$select = "SELECT name FROM pmc_artist WHERE uid = '$currency'";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
  	$row = mysql_fetch_array($data);
  	$currency_uid = $row['name'];
  	$tpl->assign("comic_currency", $currency_uid);
  	
  	// Format the comic part options
  	if($type_uid == 'Mini Series')
    	{
        	$tpl->assign("comic_part", " $lang_part_miniseries <b>$part2</b>");
     	} else {
        	$tpl->assign("comic_part", "");
     	}
	
	// PDF :: Enable or Disable
  	if($pdfenable == "Yes")
     	{
        	$menupdf = "| <a href=\"makepdf.php?id=$uid\" class=\"listlink\" target=\"_blank\">$lang_comic_pdf</a>";
     	} else {
        	$menupdf = "";
     	}
	
	// PRINT :: Enable or Disable
  	if($printenable == "Yes")
     	{
        	$menuprint = "| <a href=\"print.php?id=$uid\" class=\"listlink\" target=\"_blank\">$lang_comic_print</a>";
     	} else {
        	$menuprint = "";
     	}
    
    // LOAN MANAGER :: Enable or Disable 
  	if($loanenable == "Yes")
  	 	{
  	 		if($loan == "yes")
  	 			{
  	 				$menuloan = "| <font class=\"error\">$lang_comic_borrowed</font>";
  	 			} else {
  	 				$menuloan = "| <a href=\"loan.php?id=$uid\" class=\"listlink\">$lang_comic_loan</a>";
  	 			}
  	 	} else {
  	 		$menuloan = "";
  	 	}
  	 
  	if($favenable == "Yes")
  		{
  			if($fav == "yes")
  				{
  					$menufav = "";
  				} else {
  					$menufav = "| <a href=\"function.php?cmd=favcomic&id=$uid\" class=\"listlink\">$lang_comic_fav</a>";
  				}
  		} else {
  			$menufav = "";
  		}

  	// Assign the comic menu options
  	if($_SESSION['loggedin'] == 'yes')
     	{
        	$tpl->assign("options", "<a href=\"editcomic.php?uid=$uid\" class=\"listlink\">$lang_comic_edit</a> | <a href=\"function.php?cmd=delete_series&del=delcomic&uid=$uid&type=Comic\" class=\"listlink\">$lang_comic_delete</a> | $showlink_comic | $showlink_publisher $menupdf $menuprint | <a href=\"export.php?id=$uid\" class=\"listlink\">$lang_comic_export</a> $menuloan $menufav");
     	} else {
        	$tpl->assign("options", "$showlink_comic | $showlink_publisher $menupdf $menuprint | <a href=\"export.php?id=$uid\" class=\"listlink\">$lang_comic_export</a>");
     	}
     
  	// Get all issues in this series
  	$select = "SELECT * FROM pmc_comic WHERE title = '". mysql_real_escape_string($name) ."' AND uid = $uid";
  	$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

	$row = mysql_fetch_array($data);
	
	// Get the uid field
	$issue = $row['issue'];
	$issue_ltr = $row['issueltr'];
	$volume = $row['volume'];
		

	// Get the next issue in the series
	$number = "SELECT uid FROM pmc_comic WHERE title = '". mysql_real_escape_string($name) ."' AND volume = '". $volume ."' AND issue = '". $issue ."'";
	$num = mysql_db_query($sql['data'], $number) or die("Select Failed!");
	$issues = mysql_num_rows($num);
	
	if($issues > 1)
		{
			$select2 = "SELECT uid FROM pmc_comic WHERE issue = '". $issue ."' AND issueltr > '". $issueltr ."' ORDER BY issue desc LIMIT 1";
			$data2 = mysql_db_query($sql['data'], $select2) or die("Select Failed!");
			$row2 = mysql_fetch_array($data2);
			$uid2 = $row2['uid'];
			
			if($uid2 == '')
				{
					$selectb = "SELECT uid FROM pmc_comic WHERE title = '". mysql_real_escape_string($name) ."' AND volume = '". $volume ."' AND issue > '". $issue ."' ORDER BY issue asc LIMIT 1";
					$datab = mysql_db_query($sql['data'], $selectb) or die("Select Failed!");		
					$rowb = mysql_fetch_array($datab);
					$nextlink = $rowb['uid'];

					if($nextlink == '')
						{
							$tpl->assign("nlink", "");
						} else {			
							$tpl->assign("nlink", "<a href=\"comic.php/$nextlink\" class=\"defaultlink\">$lang_next_issue</a> &raquo;");
						}
		
				} else {
					$tpl->assign("nlink", "<a href=\"comic.php/$uid2\" class=\"defaultlink\">$lang_next_issue</a> &raquo;");
				}
			
		} else {
	
			$selectb = "SELECT uid FROM pmc_comic WHERE title = '". mysql_real_escape_string($name) ."' AND volume = '". $volume ."' AND issue > '". $issue ."' ORDER BY issue, issueltr asc LIMIT 1";
			$datab = mysql_db_query($sql['data'], $selectb) or die("Select Failed!");		
			$rowb = mysql_fetch_array($datab);
			$nextlink = $rowb['uid'];

			if($nextlink == '')
				{
					$tpl->assign("nlink", "");
				} else {			
					$tpl->assign("nlink", "<a href=\"comic.php/$nextlink\" class=\"defaultlink\">$lang_next_issue</a> &raquo;");
				}
		}

	// Get the previous issue in the series
	$number = "SELECT uid FROM pmc_comic WHERE title = '". mysql_real_escape_string($name) ."' AND volume = '". $volume ."' AND issue = '". $issue ."'";
	$num = mysql_db_query($sql['data'], $number) or die("Select Failed!");
	$issues = mysql_num_rows($num);
	
	if($issues > 1)
		{
			$select2 = "SELECT uid FROM pmc_comic WHERE issue = '". $issue ."' AND issueltr < '". $issueltr ."' ORDER BY issue desc LIMIT 1";
			$data2 = mysql_db_query($sql['data'], $select2) or die("Select Failed!");
			$row2 = mysql_fetch_array($data2);
			$uid2 = $row2['uid'];
			
			if($uid2 == '')
				{
					$selectc = "SELECT uid FROM pmc_comic WHERE title = '". mysql_real_escape_string($name) ."' AND volume = '". $volume ."' AND issue < '". $issue ."' ORDER BY issue desc LIMIT 1";
					$datac = mysql_db_query($sql['data'], $selectc) or die("Select Failed!");
					$rowc = mysql_fetch_array($datac);
					$prevlink = $rowc['uid'];

					if($prevlink == '')
						{
							$tpl->assign("plink", "");
						} else {
							$tpl->assign("plink", "&laquo; <a href=\"comic.php/$prevlink\" class=\"defaultlink\">$lang_prev_issue</a>");
						}
		
				} else {
					$tpl->assign("plink", "&laquo; <a href=\"comic.php/$uid2\" class=\"defaultlink\">$lang_prev_issue</a>");
				}
			
		} else {
	
			$selectc = "SELECT uid FROM pmc_comic WHERE title = '". mysql_real_escape_string($name) ."' AND volume = '". $volume ."' AND issue < '". $issue ."' ORDER BY issue desc LIMIT 1";
			$datac = mysql_db_query($sql['data'], $selectc) or die("Select Failed!");
			$rowc = mysql_fetch_array($datac);
			$prevlink = $rowc['uid'];

			if($prevlink == '')
				{
					$tpl->assign("plink", "");
				} else {
					$tpl->assign("plink", "&laquo; <a href=\"comic.php/$prevlink\" class=\"defaultlink\">$lang_prev_issue</a>");
				}
		}	


// PRINT THE RESULT TO SCREEN
$tpl->printToScreen();

?>