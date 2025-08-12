<?php session_start();

if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
   {
      // Get the default templates, Includes and Database connections
      include("header.php");

      $tpl->assignInclude("content", "themes/$themes/tpl/editcomic.tpl");

      // Prepare the template
      $tpl->prepare();

      // Get the menu items and links
      include("./lang/$language/general.lang.php");
	  include("./lang/$language/edit.lang.php");
      include("menu.php");

      // Assign needed values
      $tpl->assignGlobal("theme", $themes);           
      $tpl->assignGlobal("pmcurl", $siteurl);
      $tpl->assignGlobal("sitetitle", $sitetitle);
      $tpl->assignGlobal("imgfolder", "themes/$themes/img");      
      $tpl->assign("version", $version);     

      // Get the comic data from database
      $select = "SELECT * FROM pmc_comic WHERE uid = '". $_GET['uid'] ."'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);

      // Query results
      $uid = $row['uid'];
      $dat = $row['date'];
      $issue = $row['issue'];
      $issueltr = $row['issueltr'];
      $image = $row['image'];
      $cost = $row['price'];
      $val = $row['value'];
      $story = stripslashes($row['story']);
      $comment = $row['plot'];
      $volume = $row['volume'];
      $language = $row['language'];
      $translator = $row['translator'];
      $part1 = $row['part1'];
      $part2 = $row['part2'];
      $usr1 = $row['user1'];
      $usr2 = $row['user2'];
      $ebay = $row['ebay'];
      $ebaylink = $row['ebaylink'];
      $pubdate = $row['pubdate'];    
      $qty = $row['qty']; 

      $title = $row['title'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$title' AND type = 'Series'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $name_uid = $rowb['name'];

      $publisher = $row['publisher'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$publisher' AND type = 'Publisher'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $publisher_uid = $rowb['name'];

      $writer = $row['writer'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$writer' AND type = 'Writer'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $writer_uid = $rowb['name'];

      $inker = $row['inker'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$inker' AND type = 'Inker'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $inker_uid = $rowb['name'];

      $letterer = $row['letterer'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$letterer' AND type = 'Letterer'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $letterer_uid = $rowb['name'];

      $penciler = $row['penciler'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$penciler' AND type = 'Penciler'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $penciler_uid = $rowb['name'];

      $colorist = $row['colorist'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$colorist' AND type = 'Colorist'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $colorist_uid = $rowb['name'];

      $cover = $row['coverartist'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$cover' AND type = 'Coverartist'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $cover_uid = $rowb['name'];

      $types = $row['type'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$types' AND type = 'Type'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $types_uid = $rowb['name'];

      $genre = $row['genre'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$genre' AND type = 'Genre'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $genre_uid = $rowb['name'];

      $format = $row['format'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$format' AND type = 'Format'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $format_uid = $rowb['name'];

      $con = $row['condition'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$con' AND type = 'Condition'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $con_uid = $rowb['name'];

      $variation = $row['variation'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$variation' AND type = 'Variation'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $variation_uid = $rowb['name'];

      $curr = $row['currency'];
      $select = "SELECT * FROM pmc_artist WHERE uid = '$curr' AND type = 'Currency'";
      $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $rowb = mysql_fetch_array($dat);
      // Query result
      $cur_uid = $rowb['name'];

      // Get all the form values from url
      $tpl->assign("get_issue", $issue);
      $tpl->assign("get_issueltr", $issueltr);
      $tpl->assign("get_volume", $volume);
      $tpl->assign("get_part1", $part1);
      $tpl->assign("get_part2", $part2);
      $tpl->assign("get_price", $cost);
      $tpl->assign("get_value", $val);;
      $tpl->assign("get_image", $image);
      $tpl->assign("get_linkpub", $link1);
      $tpl->assign("get_linkcom", $link2);
      $tpl->assign("get_story", $story);
      $tpl->assign("get_user1", $usr1);
      $tpl->assign("get_user2", $usr2);
      $tpl->assign("get_ebay", $ebay);
      $tpl->assign("get_ebaylink", $ebaylink);
      $tpl->assign("get_pubdate", $pubdate);
      $tpl->assign("get_plot", $comment);
      $tpl->assign("get_language", $language);
      $tpl->assign("get_translator", $translator);
      $tpl->assign("ebayyes", $ebayyes);
      $tpl->assign("ebayno", $ebayno);
      $tpl->assign("get_qty", $qty);
      $tpl->assign("get_form", "function.php?cmd=editcomic&uid=$uid");
      
      if($ebay == 'yes')
      	{
      		if($types_uid == 'Mini Series')
      			{
      				$tpl->assign("onload", "onload=\"document.getElementById('showhide').style.display = ''; document.getElementById('ebayshowhide').style.display = '';\"");
      				$tpl->assign("ebayyes", "checked");
      			} else {
      				$tpl->assign("onload", "onload=\"document.getElementById('showhide').style.display = 'none'; document.getElementById('ebayshowhide').style.display = '';\"");
      				$tpl->assign("ebayyes", "checked");
      			}      		      		      	
      	} else {
      		if($types_uid == 'Mini Series')
      			{
      				$tpl->assign("onload", "onload=\"document.getElementById('showhide').style.display = ''; document.getElementById('ebayshowhide').style.display = 'none';\"");
      				$tpl->assign("ebayno", "checked");
      			} else {
      				$tpl->assign("onload", "onload=\"document.getElementById('showhide').style.display = 'none'; document.getElementById('ebayshowhide').style.display = 'none';\"");
      				$tpl->assign("ebayno", "checked");
      			}      		      		
      	}

      
      // GET WRITERS
      $select_writer = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Writer'";
  	  $data = mysql_db_query($sql['data'], $select_writer) or die("Select Writers Failed!");
 
	  while ($row = mysql_fetch_array($data))
   	  	{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Writers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$writers[] = "".$realname['name']." [<a href=\"function.php?cmd=delartlink&id=".$realname['uid']."&comicid=$uid&type=Writer\">delete</a>]";
   		}

		if($writers == '')
			{
				
				$tpl->assign('writer_option', '[<a href="addartlink.php?type=Writer&id='.$uid.'&title='.$title.'">add new writer</a>]');
				
			} else {
				
				$art = implode('<br />', $writers);		
				$tpl->assign('comic_writer', $art); 
				$tpl->assign('writer_option', '<br />[<a href="addartlink.php?type=Writer&id='.$uid.'&title='.$title.'">add new writer</a>]');
				
			}
		
      // GET PENCILERS
      $select_penciler = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Penciler'";
  	  $data = mysql_db_query($sql['data'], $select_penciler) or die("Select Penciler Failed!");
 
	  while ($row = mysql_fetch_array($data))
   	  	{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Penciler Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$pencilers[] = "".$realname['name']." [<a href=\"function.php?cmd=delartlink&id=".$realname['uid']."&comicid=$uid&type=Penciler\">delete</a>]";
   		}

		if($pencilers == '')
			{
				
				$tpl->assign('penciler_option', '[<a href="addartlink.php?type=Penciler&id='.$uid.'&title='.$title.'">add new penciler</a>]');
				
			} else {
				
				$art = implode('<br />', $pencilers);		
				$tpl->assign('comic_penciler', $art); 
				$tpl->assign('penciler_option', '<br />[<a href="addartlink.php?type=Penciler&id='.$uid.'&title='.$title.'">add new penciler</a>]');
				
			}
      
      // GET INKERS
      $select_inker = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Inker'";
  	  $data = mysql_db_query($sql['data'], $select_inker) or die("Select Inker Failed!");
 
	  while ($row = mysql_fetch_array($data))
   	  	{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Inker Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$inkers[] = "".$realname['name']." [<a href=\"function.php?cmd=delartlink&id=".$realname['uid']."&comicid=$uid&type=Inker\">delete</a>]";
   		}

		if($inkers == '')
			{
				
				$tpl->assign('inker_option', '[<a href="addartlink.php?type=Inker&id='.$uid.'&title='.$title.'">add new inker</a>]');
				
			} else {
				
				$art = implode('<br />', $inkers);		
				$tpl->assign('comic_inker', $art); 
				$tpl->assign('inker_option', '<br />[<a href="addartlink.php?type=Inker&id='.$uid.'&title='.$title.'">add new inker</a>]');
				
			}
		
      // GET COVER ARTISTS
      $select_cover = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Coverartist'";
  	  $data = mysql_db_query($sql['data'], $select_cover) or die("Select Cover Failed!");
 
	  while ($row = mysql_fetch_array($data))
   	  	{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Cover Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$covers[] = "".$realname['name']." [<a href=\"function.php?cmd=delartlink&id=".$realname['uid']."&comicid=$uid&type=Coverartist\">delete</a>]";
   		}

		if($covers == '')
			{
				
				$tpl->assign('cover_option', '[<a href="addartlink.php?type=Coverartist&id='.$uid.'&title='.$title.'">add new coverartist</a>]');
				
			} else {
				
				$art = implode('<br />', $covers);		
				$tpl->assign('comic_cover', $art); 
				$tpl->assign('cover_option', '<br />[<a href="addartlink.php?type=Coverartist&id='.$uid.'&title='.$title.'">add new coverartist</a>]');
				
			}
		
      // GET LETTERER
      $select_letterer = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Letterer'";
  	  $data = mysql_db_query($sql['data'], $select_letterer) or die("Select Letterer Failed!");
 
	  while ($row = mysql_fetch_array($data))
   	  	{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Letterer Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$letterers[] = "".$realname['name']." [<a href=\"function.php?cmd=delartlink&id=".$realname['uid']."&comicid=$uid&type=Letterer\">delete</a>]";
   		}

		if($letterers == '')
			{
				
				$tpl->assign('letterer_option', '[<a href="addartlink.php?type=Letterer&id='.$uid.'&title='.$title.'">add new letterer</a>]');
				
			} else {
				
				$art = implode('<br />', $letterers);		
				$tpl->assign('comic_letterer', $art); 
				$tpl->assign('letterer_option', '<br />[<a href="addartlink.php?type=Letterer&id='.$uid.'&title='.$title.'">add new letterer</a>]');
				
			}
		
      // GET COLORIST
      $select_colorist = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Colorist'";
  	  $data = mysql_db_query($sql['data'], $select_colorist) or die("Select Colorist Failed!");
 
	  while ($row = mysql_fetch_array($data))
   	  	{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Colorist Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$colorists[] = "".$realname['name']." [<a href=\"function.php?cmd=delartlink&id=".$realname['uid']."&comicid=$uid&type=Colorist\">delete</a>]";
   		}

		if($colorists == '')
			{
				
				$tpl->assign('colorist_option', '[<a href="addartlink.php?type=Colorist&id='.$uid.'&title='.$title.'">add new colorist</a>]');
				
			} else {
				
				$art = implode('<br />', $colorists);		
				$tpl->assign('comic_colorist', $art); 
				$tpl->assign('colorist_option', '<br />[<a href="addartlink.php?type=Colorist&id='.$uid.'&title='.$title.'">add new colorist</a>]');
				
			}
		
      // Get series
      $get = "SELECT * FROM pmc_artist WHERE type = 'Series' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($name_uid == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_series");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("selected", $sel);
         }

      // Get publishers
      $get = "SELECT * FROM pmc_artist WHERE type = 'Publisher' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($publisher_uid == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_publisher");
            $tpl->assign("pmc_publisher", $name);
            $tpl->assign("selected", $sel);
         }

      // Get genres
      $get = "SELECT * FROM pmc_artist WHERE type = 'Genre' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($genre_uid == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_genre");
            $tpl->assign("pmc_genre", $name);
            $tpl->assign("selected", $sel);
         }        

      // Get the variations
      $get = "SELECT * FROM pmc_artist WHERE type = 'Variation' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($variation_uid == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_variation");
            $tpl->assign("pmc_variation", $name);
            $tpl->assign("selected", $sel);
         }

      // Get the variations
      $get = "SELECT * FROM pmc_artist WHERE type = 'Condition' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($con_uid == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_condition");
            $tpl->assign("pmc_condition", $name);
            $tpl->assign("selected", $sel);
         }

      // Get the variations
      $get = "SELECT * FROM pmc_artist WHERE type = 'Format' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($format_uid == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_format");
            $tpl->assign("pmc_format", $name);
            $tpl->assign("selected", $sel);
         }

      // Get the types
      $get = "SELECT * FROM pmc_artist WHERE type = 'Type' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($types_uid == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_type");
            $tpl->assign("pmc_type", $name);
            $tpl->assign("selected", $sel);
         }

      // Get the currency
      $get = "SELECT * FROM pmc_artist WHERE type = 'Currency' ORDER BY name";
      $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

      while ($row = mysql_fetch_array($com))
         {
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if ($curr == '')
               {
                  if($cur_uid == $name) { $sel = ' selected'; } else { $sel = ''; }
               } else {
                  if($cur_uid == $name) { $sel = ' selected'; } else { $sel = ''; }
               }

            $tpl->newBlock("addc_currency");
            $tpl->assign("pmc_currency", $name);
            $tpl->assign("selected", $sel);
         }

      // Print the result
      $tpl->printToScreen();

   } else {

   // Login failed
   header("Location: error.php?error=01");
   exit;

}

?>