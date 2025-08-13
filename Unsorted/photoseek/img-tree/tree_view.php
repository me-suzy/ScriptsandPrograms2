<?php
 // file: tree_view.php
 // desc: expandable tree view 
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";
include "class.tree.php";
openDatabase();

$page_name="Tree View";

if (($repository+0)<1) {
  include "header.php";
  echo "<P> <CENTER> You must specify a repository! </CENTER> <P>\n";
  include "footer.php";
  DIE("");
} // end checking for repository

// get repository path
$full_r = $sql->fetch_array ( $sql->db_query ( DB_NAME,
  "SELECT rname,rpath FROM repositories WHERE id='".addslashes($repository)."'"
) );
$r_path = stripslashes($full_r["rpath"]);
$r_name = stripslashes($full_r["rname"]);

function map_tree_path ($this_path) {
  global $nodes, $tree, $root, $repository;

  // bail if nothing sent
  if (empty($this_path)) return false;
  if ($this_path=="/") return false;

  // get parts of the path
  $path = explode ("/", $this_path);

  // loop through the path parts
  for ($i=0;$i<count($path);$i++) {
    // form temporary path
    unset($temp_path);
    for ($j=0;$j<=$i;$j++) {
      if ($j>0) $temp_path .= "/" .$path[$j];
       else $temp_path .= $path[$j];
    } // end forming temporary path

    // remove trailing slash, if there is one
    if (substr ($temp_path, -1)=="/")
      $temp_path = substr ($temp_path, 0, strlen($temp_path)-1);

    // now determine if we have this already
    if (!isset ($nodes["$temp_path"])) {
      if ($i==0) { // if this is a root level folder...
        $nodes["$temp_path"] = $tree->add_folder (
	  $root, $path[$i],
	  "index.php?".
	  pass_url (
	  	array (
			"action" => "locationsearch",
			"style" => "nobar",
			"type" => "thumbs",
			"search" => $temp_path
		)
	  ));
      } else { // or else, if this isn't...
        // get previous path
        unset($prev_path);
        for ($j=0;$j<=($i-1);$j++) {
          if ($j>0) $prev_path .= "/" .$path[$j];
            else $prev_path .= $path[$j];
         }  

        // recurse if neccesary
        if (!isset ($nodes["$prev_path"])) map_tree_path ($prev_path);

	// link current directory to there
	$nodes["$temp_path"] = $tree->add_folder (
	  $nodes["$prev_path"], $path[$i],
	  "index.php?".
	  pass_url (
	  	array (
			"action" => "locationsearch",
			"style" => "nobar",
			"type" => "thumbs",
			"search" => $temp_path
		)
	  ));
      } // end checking if not a root level folder
    } // end checking if we have this already
  } // end of looping through path parts
} // end function map_tree_path

// MASTER FRAME SWITCH
switch ($frame) {
 case "topbar": // title/top bar
  include "header.php";
  echo "</BODY></HTML>\n";
  break; // end title/top bar

 case "bottombar": // title/top bar
  echo "
   <HTML>
    <TITLE>".PACKAGE_NAME." ".prepare($page_name)."</TITLE>
   <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
   "VLINK=\"".VLINKCOLOR."\">
  ";
  include "footer.php";
  break; // end title/top bar

 case "tree": // tree sidebar
  echo "
   <HTML>
    <TITLE>".PACKAGE_NAME." ".prepare($page_name)."</TITLE>
   <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
   "VLINK=\"".VLINKCOLOR."\">
  ";
   // create new browsing tree
  $tree = new Tree();
   // create root tree node
  $root = $tree->open_tree ($r_name, 
	  "index.php?".
	  pass_url (
	  	array (
			"action" => "locationsearch",
			"style" => "nobar",
			"type" => "thumbs",
			"search" => $temp_path
		)
	  ));

   // get all from database
  $result = $sql->db_query (DB_NAME, "SELECT fullfilename FROM images
    WHERE repository='".addslashes($repository)."' ORDER BY fullfilename");
  unset ($nodes);
  while ($r = $sql->fetch_array ($result)) {
    $this_path = str_replace ($r_path, "", dirname($r[fullfilename])."/");
    if (substr ($this_path, strlen($this_path), 1)=="/")
      $this_path = substr ($this_path, 0, strlen($this_path) - 1);
    map_tree_path ($this_path);
  } // end of looping through results
   // display tree
  $tree->close_tree();
  break; // end tree sidebar

 case "blank":
  echo "
   <HTML>
    <TITLE>".PACKAGE_NAME." ".prepare($page_name)."</TITLE>
   <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
   "VLINK=\"".VLINKCOLOR."\">
   &nbsp; 
   </BODY></HTML>
  ";
  break; // blank

 default: // default: frameset
  // form links for page
  list($this_page, $garbage) = explode ("?", basename($GLOBALS["REQUEST_URI"]));
  
  // display
  echo "
   <HTML>
    <TITLE>".PACKAGE_NAME." ".prepare($page_name)."</TITLE>
   <FRAMESET ROWS=\"60,*,40\" BORDER=0>
    <FRAME SRC=\"".$this_page."?repository=".urlencode($repository).
     "&frame=topbar\" SCROLLING=NO NORESIZE>
    <FRAMESET COLS=\"20%,*\" BORDER=1>
     <FRAME SRC=\"".$this_page."?repository=".urlencode($repository).
      "&frame=tree\" NAME=\"_tree\">
     <FRAME SRC=\"".$this_page."?repository=".urlencode($repository).
      "&frame=blank\" NAME=\"_results\">
    </FRAMESET>
    <FRAME SRC=\"".$this_page."?repository=".urlencode($repository).
     "&frame=bottombar\" SCROLLING=NO NORESIZE>
   </FRAMESET>
   </HTML>
  ";
  break; // end default frameset
} // end MASTER FRAME SWITCH

?>
