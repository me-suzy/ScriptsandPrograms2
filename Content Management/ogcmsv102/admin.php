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
//start looking for a session
session_start();

//includes-------------------------
//get connection to db statement
require_once 'connect.php';
//include functions for logging in
require_once 'login_functions.php';
//----------------------------------


//if a password has been passed, check it, if ok include post_list.php
if (login_check()) {
   $_SESSION['admin'] = true;
   include 'post_list.php';
}
//if not, show the login form
else {
print "<link rel=\"StyleSheet\" href=\"style.css\" type=\"text/css\">
      <div id=\"login\">";
login_form();
print "</div>";
}
?>