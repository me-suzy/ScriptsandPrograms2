<?php session_start();

if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
   {
      // Get the default templates, Includes and Database connections
      include("header.php");

      $tpl->assignInclude("content", "themes/$themes/tpl/edit.tpl");

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

      // Get the artist data from database
      $select = "SELECT * FROM pmc_artist WHERE uid = '". $_GET['uid'] ."'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);

      // Query result
      $uid = $row['uid'];
      $name = $row['name'];
      $types = $row['type'];
      $link = $row['link'];
      $year = $row['year'];

      // Get all the form values from url
      $tpl->assign("get_name", $name);
      $tpl->assign("get_type", $types);
      $tpl->assign("get_form", "function.php?cmd=editartist&uid=$uid&a=". $_GET['a'] ."");

      if($types == "Series")
         {
            $tpl->assign("edit_link", "&nbsp;<INPUT TYPE=\"text\" name=\"art_link\" class=\"formfield\" value=\"$link\">&nbsp;<INPUT TYPE=\"text\" name=\"art_year\" class=\"formfieldsmall\" value=\"$year\">");
         } elseif ($types == "Publisher") {
         	$tpl->assign("edit_link", "&nbsp;<INPUT TYPE=\"text\" name=\"art_link\" class=\"formfield\" value=\"$link\">");         
         } else {
            $tpl->assign("edit_link", "");
         }

      // Print the result
      $tpl->printToScreen();

   } else {

   // Login failed
   header("Location: error.php?error=01");
   exit;

}

?>