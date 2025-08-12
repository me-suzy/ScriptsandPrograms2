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
//check login:--------------------
require_once 'header_footer.php';
if (!$_SESSION['authorized']) {
   include 'admin.php';
   die;
   }
//------------------------------

function pw_change($old, $new1, $new2) {
         //get language variables
         global $lang;
         //function to change password
         $oldmd5 = md5($old);
         //query----------------------------------
             //match md5 hashes
             $query = "SELECT password FROM og_system WHERE password = '$oldmd5'";
             $query_result = mysql_query($query) or DIE (mysql_error());
             //------------------------------------------

             $login_check = mysql_num_rows($query_result);

             if (!$login_check >= 1 ) {
                print "{$lang[system_old_pw_error]}<br><br>";
                     }
             elseif ($new1 != $new2) {
                     print "{$lang[system_new_pw_error]}<br><br>";
                  }
             elseif ($new1 == $new2) {
                         $new = md5($new2);
                          $query_upd = "UPDATE og_system SET password = '$new' WHERE password = '$oldmd5'";
                          $result = mysql_query($query_upd) or die(mysql_error());
                          print "{$lang[system_pw_update_success]}<br><BR>";
                  }
             else {
                  print "{$lang[system_error]}<br><br>";
                  }
}


function system_form () {
         //get language variables
         global $lang;
         //print out the form where the system variables can be edited

         //query to get system variables from the database-----------
         $query = "SELECT * FROM og_system WHERE id = '1'";
         $query_result = mysql_query($query) or DIE (mysql_error());
         //-----------------------------------------------------------

         $row = mysql_fetch_array($query_result);

         //print-out form
         print "<form action=\"{$_SERVER['PHP_SELF']}\" name=\"system\" method=\"post\">
               {$lang[system_pw_form_header]}<br><br>
               {$lang[system_pw_old]}<br> <input type=\"password\" size=\"40\" name=\"old\" /><br>
               {$lang[system_pw_new]}<br> <input type=\"password\" name=\"new1\" size=\"40\" /><br>
               {$lang[system_pw_repeat_new]} <br><input type=\"password\" name=\"new2\" size=\"40\" /><br>
               <br>
               <input type=\"submit\" name=\"passup\" value=\"{$lang[system_password_update_button]}\" /><hr/>
               {$lang[system_site_title]}<br>
                    <input type=\"text\" name=\"title\" size=\"40\" value=\"{$row['title']}\" />
                    <br><br>
               {$lang[system_webmaster_email]}<br>
                     <input type=\"text\" name=\"mail\" size=\"40\" value=\"{$row['mail']}\" />
                     <br><br>
               {$lang[system_footer]}<br>
                     <input type=\"text\" name=\"footer\" size=\"40\" value=\"{$row['footer']}\" />
                     <br><br>
               {$lang[system_t_img_prompt]}<br>
                   <input type=\"text\" name=\"top_image\" value=\"{$row['top_image']}\" size=\"40\" />
                   <br><br>
               {$lang[system_adm_t_img_prompt]}<br>
                   <input type=\"text\" name=\"admin_top_image\" value=\"{$row['admin_top_image']}\" size=\"40\" />
                   <br><br>
               {$lang[system_small_img_size_prompt]}<br>
                     {$lang[system_max_w]} <input type=\"text\" name=\"small_image_width\" value=\"{$row['small_image_width']}\" size=\"40\" />
                     <br><br>
                     {$lang[system_max_h]} <input type=\"text\" name=\"small_image_height\" value=\"{$row['small_image_height']}\" size=\"40\" />
                     <br><br>
               {$lang[system_img_size_prompt]}<br>
                     {$lang[system_max_w]} <input type=\"text\" name=\"image_width\" value=\"{$row['image_width']}\" size=\"40\" />
                     <br><br>
                     {$lang[system_max_h]} <input type=\"text\" name=\"image_height\" value=\"{$row['image_height']}\" size=\"40\" />
                     <br><br>
               {$lang[system_post_count]}<br>
                     <input type=\"text\" name=\"post_count\" value=\"{$row['post_count']}\" size=\"40\" />
                     <br><br>
               {$lang[system_rh_options]}
               <br>
               {$lang[system_rh_enable]} <input type=\"checkbox\" name=\"rh_enable\" value=\"on\"";
               if ($row['rh_enable'] == 'on') {
                  print " checked";
               }
         print "/>
               <br>
               {$lang[system_top_dl]} <input type=\"checkbox\" name=\"top_five_dl\" value=\"on\"";
               if ($row['top_five_dl'] == 'on') {
                  print " checked";
               }
         print "/>
               <br>
               {$lang[system_last_commented]} <input type=\"checkbox\" name=\"last_five_comm\" value=\"on\"";
               if ($row['last_five_comm'] == 'on') {
                  print " checked";
               }
        print "/>
               <br><br>
               <input type=\"submit\" name=\"ok\" value=\"{$lang[system_submit_button]}\" />
         </form>";
         //end of form print-out
         }

function update_sys() {
         //get language variables
         global $lang;
         //function to update system table in database i.a.w. form
         if ($_POST['passup'] == $lang[system_password_update_button]) {
            pw_change($_POST['old'], $_POST['new1'], $_POST['new2']);
            }
         //if form is posted

         if ($_POST['ok'] == "ok") {
            //get variables:
            $site_title = $_POST['title'];
            $mail = $_POST['mail'];
            $footer = $_POST['footer'];
            $rh_enable = $_POST['rh_enable'];
            $top_five_dl = $_POST['top_five_dl'];
            $last_five_comm = $_POST['last_five_comm'];
            $top_image = $_POST['top_image'];
            $admin_top_image = $_POST['admin_top_image'];
            $small_image_width = $_POST['small_image_width'];
            $small_image_height = $_POST['small_image_height'];
            $image_width = $_POST['image_width'];
            $image_height = $_POST['image_height'];
            $post_count = $_POST['post_count'];

            //update db
            //query----------------------------------------------------
            $query = "UPDATE og_system SET title = '$site_title', mail = '$mail', footer = '$footer',
                   top_image = '$top_image', admin_top_image = '$admin_top_image',
                   small_image_width = '$small_image_width', small_image_height = '$small_image_height',
                   image_width = '$image_width', image_height = '$image_height', post_count = '$post_count',
                   rh_enable = '$rh_enable', top_five_dl = '$top_five_dl', last_five_comm = '$last_five_comm'
                   WHERE id = '1'";
            $query_result = mysql_query($query) or DIE (mysql_error());
            //--------------------------------------------------------

            print "{$lang[system_update_success]}<br><br>";
         }
}

//display page
show_header();
print "<div id=\"view_container\">";
update_sys();
system_form();
print "</div>";
show_footer();
?>


