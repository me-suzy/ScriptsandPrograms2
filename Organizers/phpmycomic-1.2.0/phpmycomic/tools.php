<?php session_start();

if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

	// GET HEADER AND PAGE TEMPLATE FILE
  	include("header.php");
  	$tpl->assignInclude("content", "themes/$themes/tpl/tools.tpl");

  	// PREPARE THE TEMPLATE
  	$tpl->prepare();

  	// GET THE MENU AND LANGUAGE FILES
  	include("./lang/$language/general.lang.php");
  	include("./lang/$language/tools.lang.php");
  	include("menu.php");

  	// ASSIGN NEEDED TAMPLATE VALUES
  	$tpl->assignGlobal("theme", $themes);
  	$tpl->assignGlobal("pmcurl", $siteurl);
  	$tpl->assignGlobal("sitetitle", $sitetitle);
  	$tpl->assignGlobal("imgfolder", "themes/$themes/img");
  	$tpl->assign("version", $version);
  	$tpl->assignGlobal("tools", "_act");
  
  	// GET SERIES FOR ISSUE CHECKLIST
    $get = "SELECT * FROM pmc_artist WHERE type = 'Series' ORDER BY name";
    $com = mysql_db_query($sql['data'], $get) or die("Select Failed!");

    while ($row = mysql_fetch_array($com))
    	{
        	// GET FIELDS FROM RESULT
            $uid = $row['uid'];
            $name = $row['name'];
            $type = $row['type'];

            if($_GET['a'] == $name) { $sel = ' selected'; } else { $sel = ''; }

            // SET TEMPLATE VALUES
            $tpl->newBlock("name_series");
            $tpl->assign("pmc_name", $name);
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