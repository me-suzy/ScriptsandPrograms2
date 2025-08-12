<?php session_start();

if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

	// GET HEADER AND PAGE TEMPLATE FILE
    include("header.php");
    $tpl->assignInclude("content", "themes/$themes/tpl/multiple.tpl");

    // PREPARE THE TEMPLATE
    $tpl->prepare();

    // GET THE MENU AND LANGUAGE FILES
    include("./lang/$language/general.lang.php");
    include("./lang/$language/add.lang.php");
    include("menu.php");

   	// ASSIGN NEEDED TAMPLATE VALUES
    $tpl->assignGlobal("theme", $themes);
    $tpl->assign("onload", "");
    $tpl->assignGlobal("pmcurl", $siteurl);
    $tpl->assignGlobal("sitetitle", $sitetitle);
    $tpl->assignGlobal("imgfolder", "themes/$themes/img");
    $tpl->assign("version", $version);
    $tpl->assign("get_form", "function.php?cmd=addmulti");
    $tpl->assign("get_language", $_GET['s']);

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