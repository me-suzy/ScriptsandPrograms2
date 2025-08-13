<?php
 // file: admin.php
 // desc: admin menu for PhotoSeek
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";

openDatabase();

 // check for administrative access
 photoseek_authenticate_admin();

 // load repository table from data object
 if (!defined(REPOSITORY_DATA_OBJECT))
   include "class.repository_data_object.inc";
 $repository_table = new repository_data_object();

 $page_name = "Administration Menu";
 include "header.php";

 $result = $repository_table->find();
 
 if (($result) and ($sql->num_rows($result)>0)) {
  echo "
   <FORM ACTION=\"discoverRepository.php\" METHOD=POST>
   <CENTER>
   <TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5>

   <TR><TD BGCOLOR=\"#bbbbbb\">
    <CENTER><B>Administration Menu</B></CENTER>
   </TD></TR>
   
   <TR><TD BGCOLOR=\"#cccccc\">
    <INPUT TYPE=SUBMIT VALUE=\"  Discover  \">
    <SELECT NAME=\"repository\">
  ";
  while ($r = $sql->fetch_array ($result)) {
   echo "
     <OPTION VALUE=\"$r[id]\">".htmlentities(stripslashes($r[rname])).
    " (".htmlentities(stripslashes($r[rpath])).") 
   ";
  } // end of while
  echo "
    </SELECT>
   </TD></TR>
  ";
 } else {
   echo "
    <CENTER>
    <TABLE BORDER=0 CELLSPACING=2 CELLPADDING=5>

     <TR><TD BGCOLOR=\"#bbbbbb\">
      <CENTER><B>Administration Menu</B></CENTER>
     </TD></TR>
   "; 
 } // end of checking for result

 echo "
   <TR><TD BGCOLOR=\"#cccccc\">
    <IMG SRC=\"repository.gif\" WIDTH=15 HEIGHT=16 ALT=\"\" BORDER=0> &nbsp;
    <A HREF=\"repositorylist.php\"
    >Add/View/Modify Repositories</A>
   </TD></TR> 

   <TR><TD BGCOLOR=\"#cccccc\">
    <IMG SRC=\"user.gif\" WIDTH=15 HEIGHT=16 ALT=\"\" BORDER=0> &nbsp;
    <A HREF=\"userlist.php\"
    >Add/View/Modify Users</A>
   </TD></TR> 

   <TR><TD BGCOLOR=\"#cccccc\">
    <CENTER><A HREF=\"index.php\"
    >Return to ".PACKAGE_NAME."</A></CENTER>
   </TR>
  </TABLE>
  </CENTER>
  <P>
 ";
 if ($result) echo "\n  </FORM>\n";
 include "footer.php";

 closeDatabase ();
?>
