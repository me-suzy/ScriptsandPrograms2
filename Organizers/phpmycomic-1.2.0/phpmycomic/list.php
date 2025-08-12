<?php session_start();

// GETS VALUES FROM THE URL DATA
$urldata = explode("/",$_SERVER["PATH_INFO"]);

// GET HEADER AND PAGE TEMPLATE FILE
include("header.php");  
$tpl->assignInclude("content", "themes/$themes/tpl/comiclist.tpl");

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

// IF THE LIST IS OF GENRES OR PUBLISHER RUN THIS SECTION
if($urldata[1] == 'Genre' or $urldata[1] == 'Publisher' or $urldata[1] == 'Series')
	{
		// Get total number of rows in result
		$select_num = "SELECT * FROM pmc_comic WHERE title = '". mysql_real_escape_string($urldata[2]) ."' or genre = '". mysql_real_escape_string($urldata[2]) ."' or publisher = '". mysql_real_escape_string($urldata[2]) ."' or type = '". mysql_real_escape_string($urldata[2]) ."' ORDER BY title, volume, issue, issueltr";
		$get_num = mysql_db_query($sql['data'], $select_num) or die("Select Search Failed!");
		$numrows = mysql_num_rows($get_num); 

		// MYSQL QUERY TO SELECT ISSUES AFTER THE GENRE OR PUBLISHER YOU CHOSE
		$select = "SELECT * FROM pmc_comic WHERE title = '". mysql_real_escape_string($urldata[2]) ."' or genre = '". mysql_real_escape_string($urldata[2]) ."' or publisher = '". mysql_real_escape_string($urldata[2]) ."' or type = '". mysql_real_escape_string($urldata[2]) ."' ORDER BY title, volume, issue, issueltr";
		//$select_issues = mysql_query("SELECT * FROM pmc_comic WHERE title = '". mysql_real_escape_string($urldata[2]) ."' or genre = '". mysql_real_escape_string($urldata[2]) ."' or publisher = '". mysql_real_escape_string($urldata[2]) ."' or type = '". mysql_real_escape_string($urldata[2]) ."' ORDER BY title, volume, issue, issueltr") or die("Select issues after genre or publisher failed!");		
		
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
		      			$tpl->assign("prev_page", "&laquo; <a href=\"list.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_prev</a>");
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
		          				$get_pages[] = "<b><a href=\"list.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$c</a></b>";
		          				
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
		         				$tpl->assign("next_page", "<a href=\"list.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_next</a> &raquo;");
		         				//print("<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">Next Page >></A>";
		         			}
		      		}
		   	}
		   	
		while ($get_issues = mysql_fetch_array($data))
   			{
      			// GET VALUES FROM DB
      			$uid = $get_issues['uid'];
      			$name = $get_issues['title'];
      			$type = $get_issues['type'];
      			$story = stripslashes($get_issues['story']);
      			$vol = $get_issues['volume'];
      			$issue = $get_issues['issue'];
      			$issueltr = $get_issues['issueltr'];
      			$variation = $get_issues['variation'];

      			// OPEN A NEW TEMPLATE BLOCK :: COMIC_LIST
      			$tpl->newBlock("comic_list");

      			// GET THE SERIES REAL NAME
      			$select_issuename = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$name'") or die("Select series name failed!");      			
      			$get_issuename = mysql_fetch_assoc($select_issuename);
      			$seriesname = $get_issuename['name'];
      			$tpl->assign("pmc_name", $seriesname);      			

      			// GET THE COMIC TYPE
      			$select_issuetype = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$type'") or die("Select series type failed!");
				$get_issuetype = mysql_fetch_assoc($select_issuetype);
      			$seriestype = $get_issuetype['name'];
      			$tpl->assign("pmc_type", $seriestype);

      			// GET THE ISSUE VARIATION
      			$select_issuevariation = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$variation'") or die("Select series variation failed!");
      			$get_issuevariation = mysql_fetch_assoc($select_issuevariation);
      			$seriesvariation = $get_issuevariation['name'];
      			$tpl->assign("pmc_variation", $seriesvariation);

      			$tpl->assign("pmc_uid", $uid);
      			$tpl->assign("pmc_issue", $issue);
      			$tpl->assign("pmc_issueltr", $issueltr);
      			$tpl->assign("pmc_volume", $vol);
      			$tpl->assign("pmc_story", $story);
   			}
   			    
    // IF ANY OTHER LIST RUN THIS SECTION  	
	} else {
		
		// Get total number of rows in result
		$select_num = "SELECT * FROM pmc_link WHERE artist_id = '". mysql_real_escape_string($urldata[2]) ."' AND type = '". mysql_real_escape_string($urldata[1]) ."'";
		$get_num = mysql_db_query($sql['data'], $select_num) or die("Select Search Failed!");
		$numrows = mysql_num_rows($get_num); 
				
		// MYSQL QUERY TO SELECT ALL THE ARTISTS FROM PMC_LINK TABLE
		$select = "SELECT * FROM pmc_link WHERE artist_id = '". mysql_real_escape_string($urldata[2]) ."' AND type = '". mysql_real_escape_string($urldata[1]) ."'";
		//$select_artist = mysql_query("SELECT * FROM pmc_link WHERE artist_id = '". mysql_real_escape_string($urldata[2]) ."' AND type = '". mysql_real_escape_string($urldata[1]) ."'") or die("Select artist from pmc_link failed!");
		
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
		      			$tpl->assign("prev_page", "&laquo; <a href=\"list.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_prev</a>");
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
		          				$get_pages[] = "<b><a href=\"list.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$c</a></b>";
		          				
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
		         				$tpl->assign("next_page", "<a href=\"list.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_next</a> &raquo;");
		         				//print("<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">Next Page >></A>";
		         			}
		      		}
		   	}
		   		
		while ($get_artist = mysql_fetch_array($data))
   			{
   				// GET COMIC ID FROM TABLE
   				$comicid = $get_artist['comic_id'];   		   	
   		
   				// MYSQL QUERY :: GET ALL ISSUES AFTER A SELECTED ARTIST
   				$select_issues = mysql_query("SELECT * FROM pmc_comic WHERE uid = '$comicid' ORDER BY title, issue, issueltr") or die("Select issues after artist failed!");

				while ($get_issues = mysql_fetch_array($select_issues))
   					{   		
      					// GET VALUES FROM DB
      					$uid = $get_issues['uid'];
      					$name = $get_issues['title'];
      					$type = $get_issues['type'];
      					$story = stripslashes($get_issues['story']);
      					$vol = $get_issues['volume'];
      					$issue = $get_issues['issue'];
      					$issueltr = $get_issues['issueltr'];
      					$variation = $get_issues['variation'];
         
      					// OPEN A NEW TEMPLATE BLOCK :: COMIC_LIST
      					$tpl->newBlock("comic_list");

      					// GET THE SERIES REAL NAME
      					$select_issuename = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$name'") or die("Select series name failed!");      			
      					$get_issuename = mysql_fetch_assoc($select_issuename);
      					$seriesname = $get_issuename['name'];
      					$tpl->assign("pmc_name", $seriesname);      			

      					// GET THE COMIC TYPE
      					$select_issuetype = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$type'") or die("Select series type failed!");
						$get_issuetype = mysql_fetch_assoc($select_issuetype);
      					$seriestype = $get_issuetype['name'];
      					$tpl->assign("pmc_type", $seriestype);

      					// GET THE ISSUE VARIATION
      					$select_issuevariation = mysql_query("SELECT * FROM pmc_artist WHERE uid = '$variation'") or die("Select series variation failed!");
      					$get_issuevariation = mysql_fetch_assoc($select_issuevariation);
      					$seriesvariation = $get_issuevariation['name'];
      					$tpl->assign("pmc_variation", $seriesvariation);

      					$tpl->assign("pmc_uid", $uid);
      					$tpl->assign("pmc_issue", $issue);
      					$tpl->assign("pmc_issueltr", $issueltr);
      					$tpl->assign("pmc_volume", $vol);
      					$tpl->assign("pmc_story", $story);      
   					}
   			}
   
	}

// PRINT THE RESULT TO SCREEN
$tpl->printToScreen();

?>