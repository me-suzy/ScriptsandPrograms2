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
| Licence Type   : FREE                                                         |
+------------------------------------------------------------------------------+ */
//check login:---------------------------
require_once 'header_footer.php';
if (!$_SESSION['authorized']) {
   include 'admin.php';
   die;
   }
//-------------------------------------

//inlcude necessary functions
require_once 'post_functions.php';
//-----------------------------------

//start
show_header ();
print "<div id=\"view_container\">";
addpostprocess ();
add_form ();
print "</div>";
show_footer ();

?>




