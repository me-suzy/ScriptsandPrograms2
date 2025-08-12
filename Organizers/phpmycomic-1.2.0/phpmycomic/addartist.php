<?php session_start();

// CHECK IF USER IS LOGGED IN OR NOT
if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

	// GET HEADER AND PAGE TEMPLATE FILE
    include("header.php");
    $tpl->assignInclude("content", "themes/$themes/tpl/artist.tpl");

    // PREPARE THE TEMPLATE
    $tpl->prepare();

    // GET THE MENU AND LANGUAGE FILES
    include("./lang/$language/general.lang.php");
    include("./lang/$language/add.lang.php");
    include("menu.php");

    // ASSIGN NEEDED TAMPLATE VALUES
    $tpl->assignGlobal("theme", $themes);
    $tpl->assignGlobal("pmcurl", $siteurl);
    $tpl->assignGlobal("sitetitle", $sitetitle);
    $tpl->assignGlobal("imgfolder", "themes/$themes/img");
    $tpl->assign("version", $version);
    $tpl->assignGlobal("addartist", "_act");
    $tpl->assign("form_artist", 'function.php?cmd=addartist');
    $tpl->assign("form_series", 'function.php?cmd=addseries');
    $tpl->assign("form_publisher", 'function.php?cmd=addpublisher');
    $tpl->assign("form_genre", 'function.php?cmd=addgenre');

    // SET TEMPLATE VALUES
    $tpl->assign("get_name", "");
    $tpl->assign("get_form", "function.php?cmd=addartist");

    // PRINT RESULT TO SCREEN
    $tpl->printToScreen();

} else {

   	// LOGIN FAILED
   	header("Location: error.php?error=01");
   	exit;

}

?>