<?php
 // file: searchform.php
 // desc: search form for PhotoSeek
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";

$page_name = "Search Form";
include ("header.php");

echo "
 <!-- quick code to break to top of frame -->
 <SCRIPT LANGUAGE=\"JavaScript\">
 <!--
  if (top.location) {
    if (self != top) top.location = self.location;
  }
 //-->
 </SCRIPT>

 <TABLE WIDTH=100% CELLSPACING=0 CELLPADDING=3 BORDER=0
  BGCOLOR=\"#aaaaaa\" VALIGN=TOP ALIGN=CENTER>
 <TR><TD>
 <CENTER>
  <I>
  ".( $img_count = db_rec_count("images") )."
  ".( ($img_count != 1) ? "images" : "image" )." in
  ".( $rep_coumt = db_rec_count("repositories") )."
  ".( ($rep_count != 1) ? "repositories" : "repository" )."
  </I>
 </CENTER>
 </TD></TR></TABLE>
";

?>

<!-- begin form -->

<FORM ACTION="index.php" METHOD=POST>
<INPUT TYPE=HIDDEN NAME="action" VALUE="search">

<TABLE BORDER=0 VALIGN=MIDDLE ALIGN=CENTER CELLSPACING=0 CELLPADDING=5>
<TR BGCOLOR="#bbbbbb">
 <TD COLSPAN=4 ALIGN=CENTER VALIGN=MIDDLE>
  <B><FONT COLOR="#555555">Search</FONT></B>
 </TD>
</TR>

<?php
 $result = $sql->db_query (DB_NAME,
                         "SELECT * FROM repositories
                         ".(
                            (BASIC_AUTHENTICATION and ($ul = valid_user())) ?
                            ""                                              :
                            " WHERE rlevel <= '$ul'"
                          )."
                          ORDER BY rname,rdesc,rpath");

 if (($result) and ($sql->num_rows($result)>0)) {
  echo "
   <TR BGCOLOR=\"#cccccc\">
    <TD COLSPAN=1>&nbsp;</TD>
    <TD COLSPAN=2 ALIGN=RIGHT>Repository :
    ";
  echo "\n   <SELECT NAME=\"repository\">\n";
  echo "     <OPTION VALUE=\"0\">ALL REPOSITORIES\n";
  while ($r = $sql->fetch_array ($result))
   echo "
    <OPTION VALUE=\"$r[id]\">".htmlentities(stripslashes($r[rname]))."\n";
  echo "\n     </SELECT>\n";
 } else {
  echo "\n<INPUT TYPE=HIDDEN NAME=\"repository\" VALUE=\"0\">\n";
 }

 ?>
  <INPUT TYPE=SUBMIT NAME="action" VALUE="Tree View">
 </TD>
 <TD COLSPAN=1>&nbsp;</TD>
</TR>
 
<TR BGCOLOR="#cccccc">
 <TD COLSPAN=1 ALIGN=CENTER>
  <SELECT NAME="field">
   <OPTION VALUE="caption"    >Caption
   <OPTION VALUE="heading"    >Heading
   <OPTION VALUE="byline"     >Byline/Photographer
   <?php echo ( (DISABLE_CATEGORIES) ? "" : "
   <OPTION VALUE=\"categories\" >Categories\n" ); ?>
   <OPTION VALUE="keywords"   >Keywords
   <OPTION VALUE="city"       ><?php echo CITY_TEXT; ?>
  </SELECT>
 </TD><TD COLSPAN=1>
  <SELECT NAME="criteria_regular">
   <OPTION VALUE="contains">contains
   <OPTION VALUE="is">is
  </SELECT>
 </TD><TD COLSPAN=1>
  <INPUT TYPE=TEXT SIZE=20 MAXLENGTH=100 NAME="search_regular">
 </TD><TD COLSPAN=1 BGCOLOR="#aaaaaa" ALIGN=CENTER>
  <CENTER><INPUT TYPE=SUBMIT VALUE="  Search!  "></CENTER>
 </TD>
</TR>

<?php if (!DISABLE_CATEGORIES) { ?>
<TR BGCOLOR="#cccccc">
 <TD COLSPAN=1 ALIGN=CENTER>
  Categories
 </TD><TD COLSPAN=1>
  <SELECT NAME="criteria_categories">
   <OPTION VALUE="contains">contains
   <OPTION VALUE="is">is
  </SELECT>
 </TD><TD COLSPAN=1>
  <?php if (PULLDOWNS) { ?>
  <SELECT NAME="search_categories"><?php  
	$categories = commadelim_list("categories");
	if (count($categories)>0) {
  	  reset ($categories);
  	  while (list ($k, $v) = each ($categories)) {
    	    echo "  <OPTION VALUE=\"".prepare($v)."\">".prepare($v)."\n";
  	  }
	}
  ?></SELECT>
  <?php } else { // if there are no pulldowns ?>
   <INPUT TYPE=TEXT NAME="search_categories" SIZE=20
    VALUE="<?php echo prepare($search_categories); ?>">
  <?php } // end of pulldowns check ?> 
 </TD><TD COLSPAN=1>
  <INPUT TYPE=SUBMIT NAME="action" VALUE="Search Categories"> 
 </TD>
</TR>
<?php } // end !DISABLE_CATEGORIES ?>

<TR BGCOLOR="#cccccc">
 <TD COLSPAN=1 ALIGN=CENTER>
  Keywords
 </TD>
 <TD>
  <SELECT NAME="criteria_keywords">
   <OPTION VALUE="contains">contains
   <OPTION VALUE="is">is
  </SELECT>
 </TD>
 <TD>
  <?php if (PULLDOWNS) { ?>
  <SELECT NAME="search_keywords"><?php  
	$keywords = commadelim_list("keywords");
	if (count($keywords)>0) {
  	  reset ($keywords);
  	  while (list ($k, $v) = each ($keywords)) {
    	    echo "  <OPTION VALUE=\"".prepare($v)."\">".prepare($v)."\n";
  	  }
	}
  ?></SELECT>
  <?php } else { // if there are no pulldowns ?>
   <INPUT TYPE=TEXT NAME="search_keywords" SIZE=20
    VALUE="<?php echo prepare($search_keywords); ?>">
  <?php } // end of pulldowns check ?> 
  </TD>
  <TD>
  <INPUT TYPE=SUBMIT NAME="action" VALUE="Search Keywords"> 
 </TD>
</TR>

<TR>
 <TD COLSPAN=4 ALIGN=CENTER BGCOLOR="#cccccc">
 <CENTER>View : <?php echo "
  <SELECT NAME=\"type\">
   <OPTION VALUE=\"list\" ".
    ( ($type=="list") ? "SELECTED" : "" ).">list
   <OPTION VALUE=\"thumbs\" ".
    ( ($type=="thumbs") ? "SELECTED" : "" ).">thumbnails
  </SELECT>
"; ?>  
 </CENTER>
 </TD>
</TR>

<TR>
 <TD COLSPAN=4 ALIGN=CENTER BGCOLOR="#cccccc">
  <CENTER>
   <INPUT TYPE=SUBMIT NAME="action" VALUE="Advanced Search">
  </CENTER>
 </TD>
 </FORM>
</TR>
<TR>
 <TD COLSPAN=4 ALIGN=CENTER BGCOLOR="#bbbbbb">
<?php

$this_valid = valid_user ();

if ((BASIC_AUTHENTICATION) and (!$this_valid)) {
 echo "
  <CENTER>
   <FORM ACTION=\"index.php\" METHOD=POST>
    <INPUT TYPE=HIDDEN NAME=\"action\" VALUE=\"auth\">
    <INPUT TYPE=SUBMIT VALUE=\"  Login as Registered User  \">
   </FORM>
  </CENTER> 
 ";
} elseif ($this_valid) {
 $this_user = $sql->fetch_array (
              $sql->db_query (DB_NAME,
               "SELECT * FROM users
                WHERE username='".addslashes($PHP_AUTH_USER)."'"
              ));
 echo "
   <CENTER>
   Logged in : <B>".htmlentities($this_user[userdesc])."</B>
   </CENTER>
 ";
} // end of the checking for validation

?>
 </TD>
</TR>
</TABLE>

<?php

if (SHOW_ADMIN_LINK) echo "
<BR><CENTER>
<A HREF=\"admin.php\">admin</A>
</CENTER><BR>
";
include ("footer.php");

?>
