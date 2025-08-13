<HTML>
<HEAD>
 <TITLE><?php echo PACKAGE_NAME." ".$page_name; ?></TITLE>
</HEAD>

<BODY BGCOLOR="<?php echo BGCOLOR; ?>" LINK="<?php echo LINKCOLOR ?>" VLINK="<?php echo VLINKCOLOR; ?>">

<!-- begin search form table -->
<TABLE WIDTH=100% BGCOLOR="#000000" CELLSPACING=0 CELLPADDING=3
 VALIGN=TOP ALIGN=CENTER BORDER=0>
 <TR><TD ALIGN=LEFT VALIGN=MIDDLE BGCOLOR="#000000">
 <FONT COLOR="#ffffff" SIZE=+1 FACE="Arial, Verdana"
 ><?php if (GRAPHICS) { echo 
    "<A HREF=\"index.php\"><IMG SRC=\"photoseek3.gif\"
    HEIGHT=34 WIDTH=223 BORDER=0
    ALT=\"".PACKAGE_NAME." $page_name\"></A>"; } else {
    echo PACKAGE_NAME." $page_name"; } ?></FONT>
 </TD><TD ALIGN=RIGHT BGCOLOR="#000000">
 <FONT COLOR=#cccccc FACE="Arial, Verdana"
 >version <?php echo VERSION; ?><BR>
  coded by <?php echo CODED_BY; ?></FONT>
 </TD></TR>
</TABLE>
<!-- end search form table -->

<!-- end of header -->
