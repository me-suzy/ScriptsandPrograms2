<?php session_start();

// GETS VALUES FROM THE URL DATA
$urldata = explode("/",$_SERVER["PATH_INFO"]);

// GET HEADER AND PAGE TEMPLATE FILE
include("header.php");
$tpl->assignInclude("content", "themes/$themes/tpl/artistlist.tpl");

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
$tpl->assign("type", $urldata[1]);

// Assign som language bits
if($urldata[1] == 'Inker') { $tpl->assign("lang_browse_type", $lang_type_inker); }
if($urldata[1] == 'Writer') { $tpl->assign("lang_browse_type", $lang_type_writer); }
if($urldata[1] == 'Penciler') { $tpl->assign("lang_browse_type", $lang_type_penciler); }
if($urldata[1] == 'Colorist') { $tpl->assign("lang_browse_type", $lang_type_colorist); }
if($urldata[1] == 'Letterer') { $tpl->assign("lang_browse_type", $lang_type_letterer); }
if($urldata[1] == 'Coverartist') { $tpl->assign("lang_browse_type", $lang_type_cover); }
if($urldata[1] == 'Genre') { $tpl->assign("lang_browse_type", $lang_type_genre); }
if($urldata[1] == 'Publisher') { $tpl->assign("lang_browse_type", $lang_type_publisher); }

$Per_Page = $paginate;

// BROWSE GENRES AND PUBLISHERS
if($urldata[1] == 'Genre' or $urldata[1] == 'Publisher')
	{
		// Get total number of rows in result
		$select_num = "SELECT * FROM pmc_artist WHERE type = '". mysql_real_escape_string($urldata[1]) ."' AND name LIKE '". mysql_real_escape_string($urldata[2]) ."%' ORDER BY name";
		$get_num = mysql_db_query($sql['data'], $select_num) or die("Select Search Failed!");
		$numrows = mysql_num_rows($get_num); 

		// SQL QUERY :: SELECT GENRE OR PUBLISHER AFTER LETTER
		$select = "SELECT * FROM pmc_artist WHERE type = '". mysql_real_escape_string($urldata[1]) ."' AND name LIKE '". mysql_real_escape_string($urldata[2]) ."%' ORDER BY name";
		//$select_type = mysql_query("SELECT * FROM pmc_artist WHERE type = '". mysql_real_escape_string($urldata[1]) ."' AND name LIKE '". mysql_real_escape_string($urldata[2]) ."%' ORDER BY name") or die("Select genre or publisher failed!");
		
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
		      			$tpl->assign("prev_page", "&laquo; <a href=\"browse.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_prev</a>");
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
		          				$get_pages[] = "<b><a href=\"browse.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$c</a></b>";
		          				
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
		         				$tpl->assign("next_page", "<a href=\"browse.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_next</a> &raquo;");
		         				//print("<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">Next Page >></A>";
		         			}
		      		}
		   	}
		   	
		while ($get_typerow = mysql_fetch_array($data))
   			{
      			// GET FIELDS FROM DB
      			$uid = $get_typerow['uid'];
      			$name = $get_typerow['name'];
      			$type = $get_typerow['type'];      			      			
      			

      			// COUNT THE NUMBER OF ISSUES BY SELECTED ARTIST
      			$select_totalissues = mysql_query("SELECT * FROM pmc_comic WHERE title = '$uid' or publisher = '$uid' or genre = '$uid'") or die("Select total issues");
				$totalissues = mysql_num_rows($select_totalissues);

      			// SET THE TEMPLATE VALUES
      			$tpl->newBlock("artist_list");
      			$tpl->assign("pmc_name", $name);      			
      			$tpl->assign("pmc_uid", $uid);
      			$tpl->assign("pmc_type_id", $type);
      			$tpl->assign("pmc_issues", $totalissues);
      			
      			if($type == 'Genre') { $tpl->assign("pmc_type", $lang_type_genre); }
				if($type == 'Publisher') { $tpl->assign("pmc_type", $lang_type_publisher); }

      			// CHECK IF USER LOGGED IN :: SET OPTION VALUES
      			if($_SESSION['loggedin'] == 'yes')
         			{
            			$tpl->assign("options", "<a href=\"editartist.php?uid=$uid&a=". $urldata[2] ."\" class=\"listlink\">$lang_option_edit</a> - <a href=\"function.php?cmd=delete&del=delartist&uid=$uid&a=". $urldata[1] ."&b=". $urldata[2] ."&type=Artist\" class=\"listlink\">$lang_option_delete</a>");
         			} else {
            			$tpl->assign("options", "$lang_option_none");
         			}
   			}
   
   	// BROWSE THE DIFFERENT ARTISTS :: WRITER, INKER, PENCILER, COLORIST, LETTERER, COVER ARTIST
	} else {
		
		// Get total number of rows in result
		$select_num = "SELECT * FROM pmc_artist WHERE type = '". mysql_real_escape_string($urldata[1]) ."' AND name LIKE '". mysql_real_escape_string($urldata[2]) ."%' ORDER BY name";
		$get_num = mysql_db_query($sql['data'], $select_num) or die("Select Search Failed!");
		$numrows = mysql_num_rows($get_num); 
		
		// SQL QUERY :: SELECT ARTISTS AFTER LETTER
		$select = "SELECT * FROM pmc_artist WHERE type = '". mysql_real_escape_string($urldata[1]) ."' AND name LIKE '". mysql_real_escape_string($urldata[2]) ."%' ORDER BY name";
		//$select_artist = mysql_query("SELECT * FROM pmc_artist WHERE type = '". mysql_real_escape_string($urldata[1]) ."' AND name LIKE '". mysql_real_escape_string($urldata[2]) ."%' ORDER BY name") or die("Select artists by letter failed!");
		
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
		      			$tpl->assign("prev_page", "&laquo; <a href=\"browse.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_prev</a>");
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
		          				$get_pages[] = "<b><a href=\"browse.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$c</a></b>";
		          				
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
		         				$tpl->assign("next_page", "<a href=\"browse.php/$urldata[1]/$urldata[2]?Result_Set=$Res1\">$lang_page_next</a> &raquo;");
		         				//print("<a href=\"test_page.php?Result_Set=$Res1\">Next Page</a>");
		         				//echo "<A HREF=\"test.php?Result_Set=$Res1&Keyword=\".$_REQUEST['Keyword'].\"\">Next Page >></A>";
		         			}
		      		}
		   	}
		   	
		while ($get_artistrow = mysql_fetch_array($data))
   			{
      			// GET THE VALUES FROM DB
      			$uid = $get_artistrow['uid'];
      			$name = $get_artistrow['name'];
      			$type = $get_artistrow['type'];

      			// COUNT THE TOTAL NUMBER OF ISSUES BY ARTIST
      			$select_totalissues = mysql_query("SELECT * FROM pmc_link WHERE artist_id = '$uid' AND type = '$type'") or die("Get total issues by artist failed!");
      			$totalissues = mysql_num_rows($select_totalissues);

      			// SET THE TEMPLATE VALUES
      			$tpl->newBlock("artist_list");
      			$tpl->assign("pmc_name", $name);
      			$tpl->assign("pmc_uid", $uid);
      			$tpl->assign("pmc_type_id", $type);
      			$tpl->assign("pmc_issues", $totalissues);
      			
      			if($type == 'Inker') { $tpl->assign("pmc_type", $lang_type_inker); }
				if($type == 'Writer') { $tpl->assign("pmc_type", $lang_type_writer); }
				if($type == 'Penciler') { $tpl->assign("pmc_type", $lang_type_penciler); }
				if($type == 'Colorist') { $tpl->assign("pmc_type", $lang_type_colorist); }
				if($type == 'Letterer') { $tpl->assign("pmc_type", $lang_type_letterer); }
				if($type == 'Coverartist') { $tpl->assign("pmc_type", $lang_type_cover); }

      			// CHECK IF USER LOGGED IN :: SET OPTION VALUES
     			if($_SESSION['loggedin'] == 'yes')
         			{
            			$tpl->assign("options", "<a href=\"editartist.php?uid=$uid&a=". $urldata[2] ."\" class=\"listlink\">$lang_option_edit</a> - <a href=\"function.php?cmd=delete&del=delartist&uid=$uid&a=". $urldata[1] ."&b=". $urldata[2] ."&type=Artist\" class=\"listlink\">$lang_option_delete</a>");
         			} else {
            			$tpl->assign("options", "$lang_option_none");
         			}
   			}
   
	}

// PRINT THE RESULT TO PAGE
$tpl->printToScreen();

?>