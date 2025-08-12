<?php session_start();

if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {
      
	// GET HEADER AND PAGE TEMPLATE FILE
    include("header.php");
    $tpl->assignInclude("content", "themes/$themes/tpl/comic.tpl");

    // PREPARE THE TEMPLATE
    $tpl->prepare();

    // GET THE MENU AND LANGUAGE FILES
    include("./lang/$language/general.lang.php");
    include("./lang/$language/add.lang.php");
    include("menu.php");

    // ASSIGN NEEDED TAMPLATE VALUES
    $tpl->assignGlobal("theme", $themes);
    $tpl->assign("onload", "onload=\"document.getElementById('showhide').style.display = 'none'; document.getElementById('ebayshowhide').style.display = 'none';\"");
    $tpl->assignGlobal("pmcurl", $siteurl);
    $tpl->assignGlobal("sitetitle", $sitetitle);
    $tpl->assignGlobal("imgfolder", "themes/$themes/img");
    $tpl->assign("version", $version);

    // GET TEMPLATE VALUES FROM THE URL
    $tpl->assign("get_issue", $_GET['b']);
    $tpl->assign("get_volume", $_GET['c']);
    $tpl->assign("get_part1", $_GET['d']);
    $tpl->assign("get_part2", $_GET['e']);
    $tpl->assign("get_price", $_GET['k']);
    $tpl->assign("get_value", $_GET['l']);
    $tpl->assign("get_image", $_GET['r']);
    $tpl->assign("get_story", $_GET['u']);
    $tpl->assign("get_user1", $_GET['w']);
    $tpl->assign("get_user2", $_GET['x']);
    $tpl->assign("get_plot", $_GET['y']);
    $tpl->assign("ebayyes", "");
    $tpl->assign("ebayno", "checked");
    $tpl->assign("get_pubdate", "");
    $tpl->assign("get_language", $_GET['s']);
    $tpl->assign("get_translator", $_GET['t']);
    $tpl->assign("get_form", "function.php?cmd=addcomic");

    //--------------------------------------------------------------------------
    // GET SERIES FROM ARTIST TABLE
    //--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Series' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
    	{
        	// GET FIELDS FROM QUERY
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['a'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // SET TEMPLATE VALUES
            $tpl->newBlock("addc_series");
            $tpl->assign("pmc_name", $name);
            $tpl->assign("selected", $sel);
     	}

	//--------------------------------------------------------------------------
	// GET PUBLISHERS FROM ARTIST TABLE
	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Publisher' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
      	{
        	// GET FIELDS FROM QUERY
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['f'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // SET TEMPLATE VALUES
            $tpl->newBlock("addc_publisher");
            $tpl->assign("pmc_publisher", $name);
            $tpl->assign("selected", $sel);
    	}

  	//--------------------------------------------------------------------------
  	// GET GENRES FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Genre' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
     	{
     		// GET FIELDS FROM QUERY
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['h'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // SET TEMPLATE VALUES
            $tpl->newBlock("addc_genre");
            $tpl->assign("pmc_genre", $name);
            $tpl->assign("selected", $sel);
     	}   

  	//--------------------------------------------------------------------------
  	// GET WRITERS FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Writer' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
     	{
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['m'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_writer");
            $tpl->assign("pmc_writer", $name);
            $tpl->assign("selected", $sel);
       	}

  	//--------------------------------------------------------------------------
  	// GET PENCILERS FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Penciler' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
        {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['o'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_penciler");
            $tpl->assign("pmc_penciler", $name);
            $tpl->assign("selected", $sel);
        }

 	//--------------------------------------------------------------------------
  	// GET INKERS FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Inker' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
        {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['n'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_inker");
            $tpl->assign("pmc_inker", $name);
            $tpl->assign("selected", $sel);
        }

   	//--------------------------------------------------------------------------
  	// GET COLORISTS FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Colorist' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
        {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['q'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_colorist");
            $tpl->assign("pmc_colorist", $name);
            $tpl->assign("selected", $sel);
        }

    //--------------------------------------------------------------------------
  	// GET LETTERERS FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Letterer' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
        {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['z'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_letterer");
            $tpl->assign("pmc_letterer", $name);
            $tpl->assign("selected", $sel);
        }

    //--------------------------------------------------------------------------
  	// GET COVERARTISTS FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Coverartist' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
        {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['p'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_coverartist");
            $tpl->assign("pmc_coverartist", $name);
            $tpl->assign("selected", $sel);
        }

    //--------------------------------------------------------------------------
  	// GET VARIATION FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Variation' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
        {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['v'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_variation");
            $tpl->assign("pmc_variation", $name);
            $tpl->assign("selected", $sel);
        }

    //--------------------------------------------------------------------------
  	// GET CONDITION FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Condition' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
        {
            // Get all the fields
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['j'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // Set the values to template variables
            $tpl->newBlock("addc_condition");
            $tpl->assign("pmc_condition", $name);
            $tpl->assign("selected", $sel);
        }

    //--------------------------------------------------------------------------
  	// GET FORMATS FROM ARTIST TABLE
  	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Format' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
     	{
            // GET FIELDS FROM QUERY
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['i'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // SET TEMPLATE VALUES
            $tpl->newBlock("addc_format");
            $tpl->assign("pmc_format", $name);
            $tpl->assign("selected", $sel);
     	}

 	//--------------------------------------------------------------------------
 	// GET TYPES FROM ARTIST TABLE
 	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Type' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
     	{
            // GET FIELDS FROM QUERY
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['g'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // SET TEMPLATE VALUES
            $tpl->newBlock("addc_type");
            $tpl->assign("pmc_type", $name);
            $tpl->assign("selected", $sel);
   		}

	//--------------------------------------------------------------------------
   	// GET CURRENCIES FROM ARTIST TABLE
   	//--------------------------------------------------------------------------
    $get = "SELECT * FROM pmc_artist WHERE type = 'Currency' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
     	{
            // GET VALUES FROM QUERY
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['cur'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // SET TEMPLATE VALUES
            $tpl->newBlock("addc_currency");
            $tpl->assign("pmc_currency", $name);
            $tpl->assign("selected", $sel);
         }

  	// PRINT RESULT TO SCREEN
	$tpl->printToScreen();

} else {

    // LOGIN FAILED
    header("Location: error.php?error=01");
    exit;

}

?>