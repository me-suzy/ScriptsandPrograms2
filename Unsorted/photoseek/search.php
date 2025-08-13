<?php
 // file: search.php
 // desc: search through PhotoSeek repository
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";

openDatabase();

 $page_name = "Search Results";

 if (!empty ($s))
  die ("search :: illegal variable used");

 // add spaces if padding is specified
 $s = addslashes ($search) ;   // sanitize search variable

 switch ($action) {
   case "search":
   case "simplesearch":


   /*
    switch ($criteria) {
     case "is":
      switch ($field) {
        // check smooshed fields
	case "keywords":
	case "categories":
         $INTERNAL_SEARCH_CRITERIA = " (
              (".addslashes($field)." LIKE '$s'  ) OR
              (".addslashes($field)." LIKE '$s,%') OR
              (".addslashes($field)." LIKE '%,$s') OR
              (".addslashes($field)." LIKE '%,$s,%') )
         ";
	 break; // end checking smooshed fields
      
        // check all regular fields
	default:
         $INTERNAL_SEARCH_CRITERIA = "
              (".addslashes($field)." = '$s'  )
	 ";
         break; // end regular fields

      } // end interior field switch
      break;

     case "contains":
     default:
      $INTERNAL_SEARCH_CRITERIA = "
                (".addslashes($field)." LIKE '%$s%')
                ";
      break;
    } // end criteria check
    */
    $INTERNAL_SEARCH_CRITERIA = sql_search_for ($field,
    	( ($criteria=="is") ? CRITERIA_IS : CRITERIA_CONTAINS ),
	$search);
    $search_criteria = $search;
    break; // simplesearch

   case "advancedsearch":
     // DEFINE NUMBER OF ADVANCED SEARCH OPTIONS
     define (NUM_ADVANCED_SEARCH_OPTIONS, 3);

     // first, create initial portion
     $INTERNAL_SEARCH_CRITERIA =
       sql_search_for ($field_0,
         ( ($criteria_0=="is") ? CRITERIA_IS : CRITERIA_CONTAINS ),
	 $search_0);

     // now loop to NUM_ADVANCED_SEARCH_OPTIONS
     for ($iscloop=1;$iscloop<=NUM_ADVANCED_SEARCH_OPTIONS;$iscloop++) {
       $INTERNAL_SEARCH_CRITERIA .= ( (empty(${"op_".$iscloop})) ? "" :
         " ".${"op_".$iscloop}." ".
	 sql_search_for (${"field_".$iscloop},
	   ( (${"criteria_".$iscloop}=="is") ? CRITERIA_IS :
	     CRITERIA_CONTAINS ),
	   ${"search_".$iscloop}) 
       );
     } // end for iscloop
     break; // advancedsearch

   case "catsearch":
    $INTERNAL_SEARCH_CRITERIA = "
              (categories   LIKE '$s'  ) OR
              (categories   LIKE '$s,%') OR
              (categories   LIKE '%,$s') OR
              (categories   LIKE '%,$s,%')
      ";
    $search_criteria = $search;
    break; // simplesearch

   case "keysearch":
    $INTERNAL_SEARCH_CRITERIA = "
              (keywords     LIKE '$s'  ) OR
              (keywords     LIKE '$s,%') OR
              (keywords     LIKE '%,$s') OR
              (keywords     LIKE '%,$s,%')
      ";
    $search_criteria = $search;
    break; // simplesearch
    
   case "locationsearch":
    $r_result = $sql->db_query (DB_NAME,
                "SELECT * FROM repositories
		 WHERE id='".addslashes($repository)."'");
    $r_r = $sql->fetch_array ($r_result);
     // get rid of duplicate slashes
    str_replace ("//", "/", $s);
    str_replace ("//", "/", $s);
     // remove beginning slash, if there is one
    if (substr ($s, 0, 1) == "/") $s = substr ($s, -(strlen($s)-1)); 
     // make sure the ending has a slash
    if (substr ($s, -1) != "/") $s .= "/"; 
    $INTERNAL_SEARCH_CRITERIA = "fullfilename ".(
                          !empty($s) ?
                          "REGEXP \"^".addslashes($r_r[rpath]).
			    addslashes(( ($s!="/") ? $s : "" ))."[^/]+\$\"" :
			  "LIKE \"".addslashes($r_r[rpath])."%\""
				 );
    $search_criteria = ( !empty($s) ? prepare($s) :
      "Root Level of Repository");
    $search = stripslashes ($s); // return s to search for later use  
    break;
 } // end of internal search formation
 if (BASIC_AUTHENTICATION) {
   $query = "SELECT a.* from images       AS a,
                             repositories AS b
             WHERE (
               ( $INTERNAL_SEARCH_CRITERIA ) and
               (a.repository = b.id) 
               and (b.rlevel ".
          ( (BASIC_AUTHENTICATION and ($this_valid = valid_user())) ?
            " <= '".addslashes($this_valid)."'" :
            " = '0'" )
               .") ".
          ( ($repository > 0) ?
            "and (a.repository = '".addslashes($repository)."') " :
            ""
          )." 
             ) ORDER BY lastmodified DESC";
 } else {
   $query = "SELECT * FROM images
             WHERE (
               ( $INTERNAL_SEARCH_CRITERIA )
               ".(
                 ($repository > 0) ?
                 "and (repository = '".addslashes($repository)."') " :
                 ""
               )." 
             ) ORDER BY lastmodified DESC";
 } // end forming query from basic authentication

 $result   = $sql->db_query (DB_NAME, $query);
 $num_rows = $sql->num_rows ($result); 

 // check if there are results
 if (($result==0) or ($num_rows<1)) {
   if ($style != "nobar") { include "header.php"; }
    else {
    echo "
     <HTML>
     <TITLE>".PACKAGENAME." ".prepare($page_name)."</TITLE>
     <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
     "VLINK=\"".VLINKCOLOR."\">
    "; }
   if (VERBOSE) echo "(query = \"$query\") <BR>\n"; 
   echo "
    <P>
    <CENTER>
     <B>Search found no results.</B>
    </CENTER>
    <P>
    <CENTER>
     <A HREF=\"index.php\"
     >Search Again</A>
    </CENTER>
    <P>
   ";
   if ($style != "nobar") include "footer.php";
 } else { // if there *are* results
  if ($style != "nobar") { include "header.php"; }
   else {
   echo "
    <HTML>
    <TITLE>".PACKAGENAME." ".prepare($page_name)."</TITLE>
    <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
    "VLINK=\"".VLINKCOLOR."\">
   "; }

  // set count to first picture
  $count = 1;

  // validate the starting result number (avoid divide by zero
  if (empty($start) or ($start>$num_rows)) $start = 1;

  // determine how many results per page by view type
  switch ($type) {
    case "thumbs":
      $max_num_res = MAX_NUM_RES_THUMBS;
      $columns     = THUMBS_COLUMNS;
      break;
    case "list":
    default: // to avoid divide by 0 problems upon lack of passing...
      $max_num_res = MAX_NUM_RES_LIST;
      $columns     = 3;
      break;
  } // end switch

  // determine how many links there need to be
  $total_num_res_pages = ceil ($num_rows / $max_num_res);
  if ($total_num_res_pages>MAX_PAGES) $total_num_res_pages = MAX_PAGES;

  // determine current page number
  if ($start!=0)  { $this_page = ceil ($start / $max_num_res); }
   else           { $this_page = 1;                            }

  // determine if there is a previous/next button (as well as displaying)
  if (($total_num_res_pages>1) and ($this_page>1)) { $prev = true;  }
   else                                            { $prev = false; }
  if (($total_num_res_pages>1) and ($this_page<$total_num_res_pages))
                                                   { $next = true;  }
   else                                            { $next = false; }
  if ($total_num_res_pages>1)                      { $disp = true;  }
   else                                            { $disp = false; }

  // calculate last displayed image
  $last_displayed = ($start + $max_num_res) - 1;
  if ($last_displayed > $num_rows) $last_displayed = $num_rows;

  echo "
   <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 VALIGN=MIDDLE
    ALIGN=CENTER WIDTH=\"100%\">
   <TR BGCOLOR=\"#999999\">
    <FORM ACTION=\"index.php\" METHOD=POST>
    <TD COLSPAN=".($columns)." ALIGN=CENTER>
     <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2>
     <TR><TD>
     <CENTER>
      <FONT SIZE=+1><B>Search Results for 
        \"".htmlentities($search_criteria)."\"</B></FONT><BR>
      <I>$num_rows result(s) [$start - $last_displayed displayed]</I>
     </TD>
     <TD>
     ".pass_form(
     	array (
	  "type" => ""
	)
     )."
     <SELECT NAME=\"type\">
      <OPTION VALUE=\"list\" ".
       ( ($type != "list") ? "SELECTED" : "" ).">list
      <OPTION VALUE=\"thumbs\" ".
       ( ($type != "thumbs") ? "SELECTED" : "" ).">thumbs
     </SELECT>
     <INPUT TYPE=SUBMIT VALUE=\"Change View\">
     </CENTER>
     </TD></TR></TABLE>
    </TD>
    </FORM>
   </TR>
  ";

  // list view needs bar
  if ($type=="list") echo "
   <TR BGCOLOR=\"#aaaaaa\">
    <TD WIDTH=150><B>Preview</B></TD>
    <TD><B>Information</B></TD>
    <TD ALIGN=CENTER><B>Action</B></TD>
   </TR>
  ";

  // seek (if need be) and you shall find...
  if ($start>1) $sql->data_seek ($result, ($start - 1) );

  while (($r = $sql->fetch_array ($result)) and ($count<=$max_num_res)) {
   // construct file name for download...
   $this_fileparts = explode ("/", $r[fullfilename]);
   $filename       = $this_fileparts [(count ($this_fileparts) - 1)];

   switch ($type) {
    case "thumbs":
     if (($count % $columns) == 1) {
       if ($count != $start) 
         echo "\n   </TR>"; // row end
       echo "\n   <TR>"; // row begin
     }
     echo "
     <TD WIDTH=150><CENTER>
      ";
     break; // end thumbs view

    case "list": default:
     echo "
    <TR>
     <TD WIDTH=150><CENTER>
     ";
     break; // end list view 
   }   
   switch (IMAGE_CLICK_ACTION) {
     case "download":
      echo "<A HREF=\"get_image.php/id=$r[id]/mime=image/".
            urlencode($filename)."\">";
      break;
     case "info":
      echo "<A HREF=\"index.php?".
           pass_url (
	   	array (
			"action" => "info",
			"last_action" => $action,
			"id" => $r[id]
		)
	   )."\">";
      break;
     case "false":
     default:
      // do nothing
      break;
   } // end switch IMAGE_CLICK_ACTION    
   echo "<IMG BORDER=0 SRC=\"get_thumbnail.php?id=$r[id]\" ALT=\"\">";
   switch (IMAGE_CLICK_ACTION) {
     case "info":
     case "download":
       echo "</A>"; // display end of anchor tag
       break;
     case "false":
     default:
       // do nothing
       break;
   } // end switching for IMAGE_CLICK_ACTION 
   echo "<BR>
      <TT>".hilite(htmlentities($filename), $search)."</TT>
     </CENTER></TD>
   ";

   switch ($type) {
    case "thumbs":
     echo "   </TD>\n";
     break; // end of thumbs
   
    case "list":
     echo "
     <TD><B>Caption</B> : <I>".
         hilite(htmlentities(stripslashes($r[caption])), $search)."</I>
     ";
   // categories -- linkify
     if (!DISABLE_CATEGORIES) {
       echo "
         <BR>
           <B>Categories</B> :
       ";
       if (!empty($r[categories])) {
         $categories = explode (",", $r[categories]);
         reset ($categories);
         for ($i=0;$i<count($categories);$i++) {
           $categories[$i] = "<A HREF=\"index.php?".
	 		pass_url (
				array (
					"action" => "catsearch",
					"search" => $categories[$i]
				)
			)."\"><I>".
                        htmlentities($categories[$i])."</I></A>";
         } // end looping
         echo join (", ", $categories);
       } else {
         echo "NONE";
       }
     } // end check for DISABLE_CATEGORIES

     // keywords -- linkify
     echo "
         <BR>
           <B>Keywords</B> :
     ";
     if (!empty($r[keywords])) {
       $keywords = explode (",", $r[keywords]);
       reset ($keywords);
       for ($i=0;$i<count($keywords);$i++) {
         $keywords[$i] = "<A HREF=\"index.php?".
	 		pass_url (
				array (
					"action" => "keysearch",
					"search" => $keywords[$i]
				)
			)."\"><I>".
                        htmlentities($keywords[$i])."</I></A>";
       } // end looping
         echo join (", ", $keywords);
       } else {
         echo "NONE";
       }

       echo "
         <BR>
         <B>File type</B> : $r[imagetype]
        </TD>
        <TD ALIGN=CENTER>
        <CENTER>
        <A HREF=\"get_image.php/id=$r[id]/".urlencode($filename)."\"
        >".( (GRAPHICS) ?
         "<IMG SRC=\"download.gif\" HEIGHT=32 WIDTH=32
         ALT=\"[Download]\" BORDER=0>" :
         "Download" )."</A><BR>
        <A HREF=\"index.php?".
	pass_url (
		array (
			"action" => "info",
			"last_action" => $action,
			"id" => $r[id]
		)
	)."\">".( (GRAPHICS) ?
         "<IMG SRC=\"information.gif\" HEIGHT=32 WIDTH=32
         ALT=\"[Info]\" BORDER=0>" :
         "Info" )."</A><BR>
        ".(
          ((valid_user() > 0) and ($r[imagetype]=="JPEG")) ?
	  "<A HREF=\"iptc_edit.php?".
	   pass_url (
	   	array (
			"id" => $r[id],
		)
	   )."\">IPTC</A>" :
  	  ""
        )."
        </CENTER>
       </TD>
      </TR>
     ";
     break;
   } // end of switch for type
   $count++;
  } // end of while

  // fill up the rest of everything, end TR tag, etc
  if ($type=="thumbs") {
    if ((!$next) and ( ($columns - ($count % $columns)) > 0 ))
      echo "\n   <TD COLSPAN=".( $columns - ($count % $columns) ).
        ">&nbsp;</TD>";
    echo "\n   </TR>\n";	
  } // end checking for thumbs
  
  if ($disp) {
   echo "
    <TD COLSPAN=".$columns." ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"#aaaaaa\">
    <CENTER>
   ";

   if ($prev) {
    echo "
     &nbsp;
     <A HREF=\"index.php?".
     pass_url (
     	array (
		"start" => abs($start-($max_num_res)),
		"search" => $s
	)
     )."\"
     >prev</A>&nbsp;
    ";
   } else {
    echo "&nbsp;prev&nbsp;\n";
   } // endif $prev

   for ($i=1;$i<=$total_num_res_pages;$i++) {
    if ($i!=$this_page) {
     echo "
     &nbsp;
     <A HREF=\"index.php?".
     pass_url (
     	array (
		"start" => abs(($i - 1) * $max_num_res)+1,
		"search" => $s
	)
     )."\"><B>$i</B></A>&nbsp;
     ";
    } else {
     echo "&nbsp;<B>$this_page</B>&nbsp;\n";
    } // end checking if it is this page or not 
   } // end of for loop

   if ($next) {
    echo "
     &nbsp;
     <A HREF=\"index.php?".
     pass_url (
     	array (
		"search" => $s,
		"start" => abs ($start + ($max_num_res))
	)
     )."\"
     >next</A>&nbsp;
    ";
   } else {
    echo "&nbsp;next&nbsp;";
   } // endif $next

   echo "
    </FONT>
    </CENTER>
    </TD></TR>
   ";  
  } // end if display...
  echo "
   <TR>
    <TD COLSPAN=".$columns." ALIGN=CENTER VALIGN=TOP BGCOLOR=\"#aaaaaa\">
    <CENTER><A HREF=\"index.php\"
    ><FONT COLOR=\"#ffffff\">Search Again</FONT></A>
    </CENTER>
    </TD></TR>
   </TABLE>
  ";
  if ($style != "nobar") include "footer.php";
 } // end if there are/aren't results

 //closeDatabase ();
?>
