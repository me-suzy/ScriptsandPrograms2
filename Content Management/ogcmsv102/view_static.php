<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                         |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
//inlcude required files------------
require_once 'header_footer.php';
//----------------------------------

function showStatic ($id) {
         //function to display static page

         //query-----------------------------------------------
         $query = "SELECT * FROM og_static WHERE id = '$id'";
         $query_result = mysql_query($query) or Die (mysql_error());
         //-----------------------------------------------------
         $row = mysql_fetch_array($query_result);
         $file = "static/" . $row['file_name'];
         include $file;
         }

//start
show_header();
print "<div id=\"static\">";
if (!isset($_GET['static'])) {
   Print "error";
   }
else
showStatic($_GET['static']);
print "</div>";
show_footer();
?>