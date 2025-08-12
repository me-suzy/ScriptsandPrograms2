<?php session_start();

//Get the default templates objects, includes, db connections
include("header.php");

if($_GET['search'] == "simple")
   {
      $tpl->assignInclude("content", "themes/$themes/tpl/simple.tpl");

      // Prepare the template
      $tpl->prepare();

      // Get the menu items and links
      include("./lang/$language/general.lang.php");
	  include("./lang/$language/search.lang.php");
      include("menu.php");

      // Assign needed values
      $tpl->assignGlobal("theme", $themes);
      $tpl->assignGlobal("pmcurl", $siteurl);
      $tpl->assignGlobal("sitetitle", $sitetitle);
      $tpl->assignGlobal("imgfolder", "themes/$themes/img");
      $tpl->assign("version", $version);
	
	$Per_Page = 5;
	
	  if($_POST['search_check'] != "1")
	  
	  {
	  	
	  // Get total number of rows in result
	  $select_num = "SELECT * FROM pmc_artist WHERE name LIKE '%". mysql_real_escape_string($_POST['search_string']) ."%' ORDER BY '". $_POST['search_list'] ."'";
      $get_num = mysql_db_query($sql['data'], $select_num) or die("Select Search Failed!");
      $numrows = mysql_num_rows($get_num);            
      
      // The MySQL command to run
      $select = "SELECT * FROM pmc_artist WHERE name LIKE '%". mysql_real_escape_string($_POST['search_string']) ."%' ORDER BY '". $_POST['search_list'] ."'";     
	
		if (empty($_GET['Result_Set']))
			{
   				$Result_Set = 0;
   				$select.=" LIMIT $Result_Set, $Per_Page";
   			} else {
 				$Result_Set=$_GET['Result_Set'];
   				$select.=" LIMIT $Result_Set, $Per_Page";
  			}
  			
  		$data = mysql_db_query($sql['data'], $select) or die("Select Search Failed!");
  	
  	// This part creates the next and prev page links and the numbered page links
  	if ($numrows > 0)
			{
		   		if ($Result_Set < $numrows && $Result_Set > 0)
		      		{
		      			$Res1 = $Result_Set-$Per_Page;
		      			$tpl->assign("prev_page", "&laquo; <a href=\"result.php?search=simple&Result_Set=$Res1\">Previous Page</a>");
		      			//print("<a href=\"test_page.php?Result_Set=$Res1\">Previous Page</a>");
		      			//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\"> Previous Page</A>";
		      		}
				// Calculate and Display Page # Links
		   		$Pages = $numrows / $Per_Page;
		   		if ($Pages > 1)
		      		{
		      			for ($b=0,$c=1; $b < $Pages; $b++,$c++)
		          			{
		          				// NEED FUNCTION LIKE 
		          				$Res1=$Per_Page * $b;
		          				$get_pages[] = "<b><a href=\"result.php?search=simple&Result_Set=$Res1\">$c</a></b>";
		          				
		          				$all_pages = implode(', ', $get_pages);
		          				
		       					$tpl->assign("all_page", $all_pages);
		       					//print("<a href=\"test_page.php?Result_Set=$Res1\">$c</a> n");
		       					//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">$c</A> n";
		          			}
		      		}
		   		if ($Result_Set >= 0 && $Result_Set < $numrows)
		 			{
		      			$Res1 = $Result_Set+$Per_Page;
		      			if ($Res1 < $numrows)
		        			{
		         				$tpl->assign("next_page", "<a href=\"result.php?search=simple&Result_Set=$Res1\">Next Page</a> &raquo;");
		         				//print("<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">Next Page >></A>";
		         			}
		      		}
		   	}
		   	
      while ($row = mysql_fetch_array($data))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($type == 'Writer' or $type == 'Inker' or $type == 'Letterer' or $type == 'Colorist' or $type == 'Penciler' or $type == 'Coverartist')
              {
            
              // COUNT THE NUMBER OF ISSUES BY SELECTED ARTIST
      		$select_totalissues = mysql_query("SELECT * FROM pmc_link WHERE artist_id = '$uid'") or die("Select total artist issues");
			$totalissues = mysql_num_rows($select_totalissues);

            // Set the values to template variables
            $tpl->newBlock("artist_list");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("pmc_type", $type);
            $tpl->assign("pmc_uid", $uid);
            $tpl->assign("search_link", "list.php/$type/$uid");
            $tpl->assign("pmc_issues", $totalissues); 
            
              } elseif($type == 'Series') {
              
              // COUNT THE NUMBER OF ISSUES BY SELECTED ARTIST
      		$select_totalissues = mysql_query("SELECT * FROM pmc_comic WHERE title = '$uid'") or die("Select total issues");
      		$realname = mysql_fetch_assoc($select_totalissues);
          
      		$volume = $realname['volume'];
      		$comtype = $realname['type'];
      		
			$totalissues = mysql_num_rows($select_totalissues);

            // Set the values to template variables
            $tpl->newBlock("artist_list");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("pmc_type", $type);
            $tpl->assign("pmc_uid", $uid);
            $tpl->assign("search_link", "list.php/$type/$uid");
            $tpl->assign("pmc_issues", $totalissues);
            	                        
              } elseif($type == 'Publisher' or $type == 'Genre') {
              	
              	// COUNT THE NUMBER OF ISSUES BY SELECTED ARTIST
      		$select_totalissues = mysql_query("SELECT * FROM pmc_comic WHERE publisher = '$uid' or genre = '$uid'") or die("Select total issues");
			$totalissues = mysql_num_rows($select_totalissues);

            // Set the values to template variables
            $tpl->newBlock("artist_list");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("pmc_type", $type);
            $tpl->assign("pmc_uid", $uid);
            $tpl->assign("search_link", "list.php/$type/$uid");
            $tpl->assign("pmc_issues", $totalissues);
              	
              } else {
              	
              
              }
         }                
        
	  } else {
	  		
	  		// Get total number of rows in result
	  		$select_num = "SELECT * FROM pmc_artist WHERE name LIKE '%". mysql_real_escape_string($_POST['search_string']) ."%' ORDER BY '". $_POST['search_list'] ."'";
      		$get_num = mysql_db_query($sql['data'], $select_num) or die("Select Search Failed!");
      		$numrows = mysql_num_rows($get_num);

			$select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['search_string']) ."' ORDER BY name";
      		
      		if (empty($_GET['Result_Set']))
			{
   				$Result_Set = 0;
   				$select.=" LIMIT $Result_Set, $Per_Page";
   			} else {
 				$Result_Set=$_GET['Result_Set'];
   				$select.=" LIMIT $Result_Set, $Per_Page";
  			}
  			
      		$data = mysql_db_query($sql['data'], $select) or die("Select Search Failed!");
		
			while ($row = mysql_fetch_array($data))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($type == 'Writer' or $type == 'Inker' or $type == 'Letterer' or $type == 'Colorist' or $type == 'Penciler' or $type == 'Coverartist')
              {
            
              // COUNT THE NUMBER OF ISSUES BY SELECTED ARTIST
      		$select_totalissues = mysql_query("SELECT * FROM pmc_link WHERE artist_id = '$uid'") or die("Select total artist issues");
			$totalissues = mysql_num_rows($select_totalissues);

            // Set the values to template variables
            $tpl->newBlock("artist_list");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("pmc_type", $type);
            $tpl->assign("pmc_uid", $uid);
            $tpl->assign("search_link", "list.php/$type/$uid");
            $tpl->assign("pmc_issues", $totalissues); 
            
              } elseif($type == 'Series') {
              
              // COUNT THE NUMBER OF ISSUES BY SELECTED ARTIST
      		$select_totalissues = mysql_query("SELECT * FROM pmc_comic WHERE title = '$uid'") or die("Select total issues");
      		$realname = mysql_fetch_assoc($select_totalissues);
          
      		$volume = $realname['volume'];
      		$comtype = $realname['type'];
      		
			$totalissues = mysql_num_rows($select_totalissues);

            // Set the values to template variables
            $tpl->newBlock("artist_list");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("pmc_type", $type);
            $tpl->assign("pmc_uid", $uid);
            $tpl->assign("search_link", "serieslist.php/$uid/$volume/$comtype");
            $tpl->assign("pmc_issues", $totalissues);
            	                        
              } elseif($type == 'Publisher' or $type == 'Genre') {
              	
              	// COUNT THE NUMBER OF ISSUES BY SELECTED ARTIST
      		$select_totalissues = mysql_query("SELECT * FROM pmc_comic WHERE publisher = '$uid' or genre = '$uid'") or die("Select total issues");
			$totalissues = mysql_num_rows($select_totalissues);

            // Set the values to template variables
            $tpl->newBlock("artist_list");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("pmc_type", $type);
            $tpl->assign("pmc_uid", $uid);
            $tpl->assign("search_link", "list.php/$type/$uid");
            $tpl->assign("pmc_issues", $totalissues);
              	
              } else {
              	
              
              }
         }
         
         if ($numrows > 0)
			{
		   		if ($Result_Set < $numrows && $Result_Set > 0)
		      		{
		      			$Res1 = $Result_Set-$Per_Page;
		      			$tpl->assign("prev_page", "<a href=\"test_page.php?Result_Set=$Res1\">Previous Page</a>");
		      			//print("<a href=\"test_page.php?Result_Set=$Res1\">Previous Page</a>");
		      			//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\"> Previous Page</A>";
		      		}
				// Calculate and Display Page # Links
		   		$Pages = $numrows / $Per_Page;
		   		if ($Pages > 1)
		      		{
		      			for ($b=0,$c=1; $b < $Pages; $b++,$c++)
		          			{
		          				$Res1=$Per_Page * $b;
		       					$tpl->assign("all_page", "<a href=\"test_page.php?Result_Set=$Res1\">$c</a> n");
		       					//print("<a href=\"test_page.php?Result_Set=$Res1\">$c</a> n");
		       					//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">$c</A> n";
		          			}
		      		}
		   		if ($Result_Set >= 0 && $Result_Set < $numrows)
		 			{
		      			$Res1 = $Result_Set+$Per_Page;
		      			if ($Res1 < $numrows)
		        			{
		         				$tpl->assign("next_page", "<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//print("<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">Next Page >></A>";
		         			}
		      		}
		   	}
			  	
	  }
   }  

if($_GET['search'] == "story")
   {
      $tpl->assignInclude("content", "themes/$themes/tpl/result.tpl");

      // Prepare the template
      $tpl->prepare();

      // Get the menu items and links
      include("./lang/$language/general.lang.php");
	  include("./lang/$language/search.lang.php");
      include("menu.php");

      // Assign needed values
      $tpl->assignGlobal("theme", $themes);
      $tpl->assignGlobal("imgfolder", "themes/$themes/img");
      $tpl->assign("version", $version);

      // The MySQL command to run
      $select = "SELECT * FROM pmc_comic WHERE story LIKE '%". mysql_real_escape_string($_POST['search_string']) ."%' ORDER BY title, issue";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

      while ($row = mysql_fetch_array($data))
         {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['title'];
            $story = stripslashes($row['story']);
            $type = $row['type'];
            $issue = $row['issue'];
            $volume = $row['volume'];
            $variation = $row['variation'];

            // Set the values to template variables
            $tpl->newBlock("search_list");

            // Get Series name
            $select = "SELECT * FROM pmc_artist WHERE uid = '$name'";
            $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($dat);
            $name_uid = $row['name'];
            $tpl->assign("pmc_name", $name_uid);

            // Get comic types
            $select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
            $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($dat);
            $type_uid = $row['name'];
            $tpl->assign("pmc_type", $type_uid);

            // Get issue variations
            $select = "SELECT * FROM pmc_artist WHERE uid = '$variation'";
            $dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($dat);
            $variation_uid = $row['name'];
            $tpl->assign("pmc_variation", $variation_uid);

            // Set the values to template variables
            $tpl->assign("pmc_story", $story);
            $tpl->assign("pmc_uid", $uid);
            $tpl->assign("pmc_issue", $issue);
            $tpl->assign("pmc_volume", $volume);
         }
   }

// Print the result
$tpl->printToScreen();

?>