<?php
 // file: advancedsearchform.php
 // desc: advanced search form for PhotoSeek
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";

$page_name = "Advanced Search Form";
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
<INPUT TYPE=HIDDEN NAME="action" VALUE="advancedsearch">

<TABLE BORDER=0 VALIGN=MIDDLE ALIGN=CENTER CELLSPACING=0 CELLPADDING=5>
<TR BGCOLOR="#bbbbbb">
 <TD COLSPAN=4 ALIGN=CENTER VALIGN=MIDDLE>
  <B><FONT COLOR="#555555">Advanced Search</FONT></B>
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
    <TD COLSPAN=3 ALIGN=CENTER>Repository :
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
</TR>
 
<TR BGCOLOR="#cccccc">
 <TD COLSPAN=1 ALIGN=CENTER>
  &nbsp;
 </TD>
 <TD COLSPAN=1 ALIGN=CENTER>
  <SELECT NAME="field_0">
   <OPTION VALUE="caption"    >Caption
   <OPTION VALUE="heading"    >Heading
   <OPTION VALUE="byline"     >Byline/Photographer
   <?php if (!DISABLE_CATEGORIES)
    echo "<OPTION VALUE=\"categories\" >Categories\n"; ?>
   <OPTION VALUE="keywords"   >Keywords
   <OPTION VALUE="city"       ><?php echo CITY_TEXT; ?>
  </SELECT>
 </TD><TD COLSPAN=1>
  <SELECT NAME="criteria_0">
   <OPTION VALUE="contains">contains
   <OPTION VALUE="is">is
  </SELECT>
 </TD><TD COLSPAN=1>
  <INPUT TYPE=TEXT SIZE=20 MAXLENGTH=100 NAME="search_0">
 </TD>
</TR>

<TR BGCOLOR="#cccccc">
 <TD COLSPAN=1 ALIGN=CENTER>
  <SELECT NAME="op_1"><?php echo "
   <OPTION VALUE=\"\" ".
    ( ($op_1=="") ? "SELECTED" : "" ).">--
   <OPTION VALUE=\"AND\" ".
    ( ($op_1=="and") ? "SELECTED" : "" ).">and
   <OPTION VALUE=\"or\" ".
    ( ($op_1=="or") ? "SELECTED" : "" ).">or
  "; ?></SELECT>
 </TD>
 <TD COLSPAN=1 ALIGN=CENTER>
  <SELECT NAME="field_1">
   <OPTION VALUE="caption"    >Caption
   <OPTION VALUE="heading"    >Heading
   <OPTION VALUE="byline"     >Byline/Photographer
   <?php if (!DISABLE_CATEGORIES)
    echo "<OPTION VALUE=\"categories\" >Categories\n"; ?>
   <OPTION VALUE="keywords"   >Keywords
   <OPTION VALUE="city"       ><?php echo CITY_TEXT; ?>
  </SELECT>
 </TD><TD COLSPAN=1>
  <SELECT NAME="criteria_1">
   <OPTION VALUE="contains">contains
   <OPTION VALUE="is">is
  </SELECT>
 </TD><TD COLSPAN=1>
  <INPUT TYPE=TEXT SIZE=20 MAXLENGTH=100 NAME="search_1">
 </TD>
</TR>

<TR BGCOLOR="#cccccc">
 <TD COLSPAN=1 ALIGN=CENTER>
  <SELECT NAME="op_2"><?php echo "
   <OPTION VALUE=\"\" ".
    ( ($op_2=="") ? "SELECTED" : "" ).">--
   <OPTION VALUE=\"AND\" ".
    ( ($op_2=="and") ? "SELECTED" : "" ).">and
   <OPTION VALUE=\"or\" ".
    ( ($op_2=="or") ? "SELECTED" : "" ).">or
  "; ?></SELECT>
 </TD>
 <TD COLSPAN=1 ALIGN=CENTER>
  <?php echo ( (!DISABLE_CATEGORIES) ? "Categories" : "Keywords" ); ?>
 </TD><TD COLSPAN=1>
  <SELECT NAME="criteria_2">
   <OPTION VALUE="contains">contains
   <OPTION VALUE="is">is
  </SELECT>
 </TD><TD COLSPAN=1>
  <?php if (PULLDOWNS) { ?>
  <SELECT NAME="search_2"><?php  
	$categories = commadelim_list( 
	  ( (!DISABLE_CATEGORIES) ? "categories" : "keywords" ) );
	if (count($categories)>0) {
  	  reset ($categories);
  	  while (list ($k, $v) = each ($categories)) {
    	    echo "  <OPTION VALUE=\"".prepare($v)."\">".prepare($v)."\n";
  	  }
	}
  ?></SELECT>
  <?php } else { // if there are no pulldowns ?>
  <INPUT TYPE=TEXT NAME="search_2" SIZE=20
   VALUE="<?php echo prepare($search_2); ?>">
  <?php } // end of checking for pulldowns ?>
 </TD>
</TR>

<TR BGCOLOR="#cccccc">
 <TD COLSPAN=1 ALIGN=CENTER>
  <SELECT NAME="op_3"><?php echo "
   <OPTION VALUE=\"\" ".
    ( ($op_3=="") ? "SELECTED" : "" ).">--
   <OPTION VALUE=\"AND\" ".
    ( ($op_3=="and") ? "SELECTED" : "" ).">and
   <OPTION VALUE=\"or\" ".
    ( ($op_3=="or") ? "SELECTED" : "" ).">or
  "; ?></SELECT>
 </TD>
 <TD COLSPAN=1 ALIGN=CENTER>
  Keywords
 </TD>
 <TD>
  <SELECT NAME="criteria_3">
   <OPTION VALUE="contains">contains
   <OPTION VALUE="is">is
  </SELECT>
 </TD>
 <TD>
  <?php if (PULLDOWNS) { ?>
  <SELECT NAME="search_3"><?php  
	$keywords = commadelim_list("keywords");
	if (count($keywords)>0) {
  	  reset ($keywords);
  	  while (list ($k, $v) = each ($keywords)) {
    	    echo "  <OPTION VALUE=\"".prepare($v)."\">".prepare($v)."\n";
  	  }
	}
  ?></SELECT>
  <?php } else { // if there are no pulldowns ?>
  <INPUT TYPE=TEXT NAME="search_3" SIZE=20
   VALUE="<?php echo prepare($search_3); ?>">
  <?php } // end of checking for pulldowns ?>
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
   <TABLE CELLSPACING=3 CELLPADDING=0 BORDER=0>
    <TR>
     <TD>
      &nbsp;&nbsp;
      <INPUT TYPE=SUBMIT NAME="action" VALUE="Simple Search">
      &nbsp;&nbsp;
     </TD>
     <TD BGCOLOR="#aaaaaa">
      &nbsp;&nbsp;
      <INPUT TYPE=SUBMIT VALUE=" Execute Advanced Search ">
      &nbsp;&nbsp;
     </TD>
    </TR>
   </TABLE>
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
