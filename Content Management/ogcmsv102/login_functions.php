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
//if not loaded yet:
require_once 'language.php';
//---------------------------

     function login_check() {
             //function to check that provided password matches registered password

             //make md5 hash from provided password
             $password = md5($_POST['password']);

             //query----------------------------------
             //match md5 hashes
             $query = "SELECT password FROM og_system WHERE password = '$password'";
             $query_result = mysql_query($query) or DIE (mysql_error());
             //------------------------------------------

             $login_check = mysql_num_rows($query_result);

             if ($login_check >= 1 ) {
                return true;
                     }
             else {
                  return false;
                  }
}

    function login_form () {
             //get language variables
             global $lang;
             //function to print login form
                   if ($_POST['ok'] == $lang[login_ok_button]) {
                      print "{$lang[login_error]}<br><br>";
                   }
                   print  "<div align=\"center\"><div class=\"post_view_heading\">$lang[login_prompt]
                   <br></div>
             <form action=\"admin.php\" name=\"login\" method=\"post\">
             <input type=\"password\" name=\"password\" size=\"40\" /><br>
             <input type=\"submit\" name=\"ok\" value=\"{$lang[login_ok_button]}\" />
             </form></div>";
    }

   function logout () {
            //get language variables
            global $lang;
            //log out function

            session_destroy();
            if(!session_is_registered('authorized')){
                print "{$lang[logout_success]}";
                login_form ();
                }
            else {
                 print "{$lang[logout_error]}";
                 }
}
?>