<?php session_start();

// GETS VALUES FROM THE URL DATA
$urldata = explode("/",$_SERVER["PATH_INFO"]);

// GET HEADER AND PAGE TEMPLATE FILE
include("header.php");
$tpl->assignInclude("content", "themes/$themes/tpl/series.tpl");

// PREPARE THE TEMPLATE
$tpl->prepare();

// GET THE MENU AND LANGUAGE FILES
include("./lang/$language/general.lang.php");
include("./lang/$language/browse.lang.php");
include("menu.php");

// ASSIGN NEEDED TAMPLATE VALUES
$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);

$Per_Page = $paginate;

// Get total number of rows in result
$select_num = "SELECT * FROM pmc_artist WHERE type = 'Series' AND name LIKE '". mysql_real_escape_string($urldata[1]) ."%' ORDER BY name";
$get_num = mysql_db_query($sql['data'], $select_num) or die("Select Search Failed!");
$numrows = mysql_num_rows($get_num); 

// MYSQL QUERY :: SELECT ALL ISSUES BY LETTER
$select = "SELECT * FROM pmc_artist WHERE type = 'Series' AND name LIKE '". mysql_real_escape_string($urldata[1]) ."%' ORDER BY name";
//$select_series = mysql_query("SELECT * FROM pmc_artist WHERE type = 'Series' AND name LIKE '". mysql_real_escape_string($urldata[1]) ."%' ORDER BY name") or die("Select all series failed!");

if (empty($_GET['Result_Set']))
			{
   				$Result_Set = 0;
   				$select.=" LIMIT $Result_Set, $Per_Page";
   			} else {
 				$Result_Set=$_GET['Result_Set'];
   				$select.=" LIMIT $Result_Set, $Per_Page";
  			}

$data = mysql_db_query($sql['data'], $select) or die("Select Search Failed!");

if ($numrows > 0)
			{
		   		if ($Result_Set < $numrows && $Result_Set > 0)
		      		{
		      			$Res1 = $Result_Set-$Per_Page;
		      			$tpl->assign("prev_page", "&laquo; <a href=\"series.php/$urldata[1]?Result_Set=$Res1\">$lang_page_prev</a>");
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
		          				$get_pages[] = "<b><a href=\"series.php/$urldata[1]?Result_Set=$Res1\">$c</a></b>";
		          				
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
		         				$tpl->assign("next_page", "<a href=\"series.php/$urldata[1]?Result_Set=$Res1\">$lang_page_next</a> &raquo;");
		         				//print("<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">Next Page >></A>";
		         			}
		      		}
		   	}
		   	
while ($get_seriesrow = mysql_fetch_array($data))
	{
		// GET THE VALUES FROM DB
        $name = $get_seriesrow['name'];
        $uid = $get_seriesrow['uid'];

        // GET DISTINCT TYPE FROM SELECTED SERIES
        $select_seriestype = mysql_query("SELECT DISTINCT type FROM pmc_comic WHERE title = '$uid'") or die("Get distinct type failed!");

        while ($get_seriestype = mysql_fetch_array($select_seriestype))
			{
				// GET DB VALUES
         		$type = $get_seriestype['type'];
		
				// GET ISSUE REAL NAME
				$select_issuename = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$uid'") or die("Select issue name failed!");
         		$get_issuename = mysql_fetch_array($select_issuename);
         		$name_uid = $get_issuename['name'];

         		// GET ISSUE TYPE
         		$select_issuetype = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$type'") or die("Select issue type failed!");
         		$get_issuetype = mysql_fetch_array($select_issuetype);
         		$type_uid = $get_issuetype['name'];

         		// GET SERIES PUBLISHER AND SERIES VOLUME
         		$select_comic = mysql_query("SELECT * FROM pmc_comic WHERE title = '$uid'") or die("Select comic failed!");
         		$get_comic = mysql_fetch_array($select_comic);
         		$com_pub = $get_comic['publisher'];         
         		$volume = $get_comic['volume'];
         
         		// GET SERIES YEAR FROM PMC_ARTIST
         		$select_year = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$uid' AND type = 'Series'") or die("Select series year failed!");
         		$get_year = mysql_fetch_array($select_year);
         		$series_year = $get_year['year'];

         		// IF SERIES IS ONGOING SERIES RUN THIS SECTION
         		if($type_uid == 'Ongoing Series')
         			{
            			// SELECT DISTINCT VALUE OF SELECTED SERIES
            			$select_seriesvolume = mysql_query("SELECT DISTINCT volume FROM pmc_comic WHERE title = '$uid' AND type = '$type'") or die("Select series volume failed!");            			

            			while ($get_seriesvolume = mysql_fetch_array($select_seriesvolume))
            				{
            					// GET DB VALUES
            					$volume = $get_seriesvolume['volume'];
            	
            					// MYSQL QUERY :: SELECT ALL FROM SELECTED SERIES AND VOLUME
            					$select_allseries = mysql_query("SELECT * FROM pmc_comic WHERE title = '$uid' AND volume = '$volume'") or die("Select issues from series and volume failed!");            
         						$get_allseries = mysql_fetch_array($select_allseries);
         						$publisher_id = $get_allseries['publisher'];
         						// $year = $row['year']; This is if year is added to the main table :: pmc_comic
         	
            					// MYSQL QUERY :: GET PUBLISHER NAME
            					$select_publisher = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$publisher_id'") or die("Select publisher from series failed");
         						$get_publisher = mysql_fetch_array($select_publisher);
         						$publisher = $get_publisher['name'];                                               

								// SET TEMPLATE VALUES
               					$tpl->newBlock("artist_list");
               					$tpl->assign("pmc_name", $name_uid);
               					$tpl->assign("pmc_type", $type_uid);
               					$tpl->assign("pmc_volume", $volume);
               					$tpl->assign("pmc_publisher", $publisher);
               					$tpl->assign("pmc_year", $series_year);
               					$tpl->assign("pmc_link", "serieslist.php/$uid/$volume/$type");
               
               					// CHECK IF USER LOGGED IN :: SET OPTION VALUES
      		   					if($_SESSION['loggedin'] == 'yes')
         							{
            							$tpl->assign("pmc_option", "<a href=\"editartist.php?uid=$uid&a=".$_GET['ltr']."\" class=\"listlink\">$lang_option_edit</a> - <a href=\"function.php?cmd=delete&del=delartist&uid=$uid&a=Series&b=".$_GET['ltr']."&type=Artist\" class=\"listlink\">$lang_option_delete</a>");
         							} else {
            							$tpl->assign("pmc_option", "$lang_option_none");
         							}               			
            				}
         			}
         		elseif ($type_uid == 'One Shot')
         			{
            			// SELECT SERIES :: ONE SHOT
            			$select_oneshot = mysql_query("SELECT * FROM pmc_comic WHERE title = '$uid' AND type = '$type'") or die("Select oneshot series failed!");
            			$get_oneshot = mysql_fetch_array($select_oneshot);
            			$com_uid = $get_oneshot['uid'];
            			$volumes = $get_oneshot['volume'];
            			$publisher_id = $get_oneshot['publisher'];

            			// GET PUBLISHER NAME
            			$select_publisher = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$publisher_id'") or die("Select oneshot publisher failed!");
            			$get_publisher = mysql_fetch_array($select_publisher);
            			$publisher_name = $get_publisher['name'];

            			// SET TEMPLATE VALUES
            			$tpl->newBlock("artist_list");
            			$tpl->assign("pmc_name", $name_uid);
            			$tpl->assign("pmc_type", $type_uid);
            			$tpl->assign("pmc_volume", $volumes);
            			$tpl->assign("pmc_publisher", $publisher_name);
            			$tpl->assign("pmc_link", "comic.php/$com_uid");
            
            			// CHECK IF USER LOGGED IN :: SET OPTION VALUES
      		   			if($_SESSION['loggedin'] == 'yes')
         					{
            					$tpl->assign("pmc_option", "<a href=\"editartist.php?uid=$uid&a=".$_GET['ltr']."\" class=\"listlink\">$lang_option_edit</a> - <a href=\"function.php?cmd=delete&del=delartist&uid=$uid&a=Series&b=".$_GET['ltr']."&type=Artist\" class=\"listlink\">$lang_option_delete</a>");
         					} else {
            					$tpl->assign("pmc_option", "$lang_option_none");
         					}         					 
         			}
         		else
         			{
            			// SET TEMPLATE VALUES
            			$tpl->newBlock("artist_list");
            			$tpl->assign("pmc_name", $name_uid);
            			$tpl->assign("pmc_type", $type_uid);
            			$tpl->assign("pmc_volume", $volume);
            			$tpl->assign("pmc_publisher", $publisher);
            			$tpl->assign("pmc_link", "serieslist.php/$uid/$volume/$type");
            
            			// CHECK IF USER LOGGED IN :: SET OPTION VALUES
      		   			if($_SESSION['loggedin'] == 'yes')
         					{
            					$tpl->assign("pmc_option", "<a href=\"editartist.php?uid=$uid&a=".$_GET['ltr']."\" class=\"listlink\">$lang_option_edit</a> - <a href=\"function.php?cmd=delete&del=delartist&uid=$uid&a=Series&b=".$_GET['ltr']."&type=Artist\" class=\"listlink\">$lang_option_delete</a>");
         					} else {
            					$tpl->assign("pmc_option", "$lang_option_none");
         					}            
         			}
         	}
	}

// PRINT RESULT TO SCREEN
$tpl->printToScreen();

?>