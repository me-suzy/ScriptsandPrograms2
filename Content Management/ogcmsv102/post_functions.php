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
//functions for adding, deleting and editing posts
//
//including functions for cleaning up and controlling user input

//if not loaded yet:
require_once 'language.php';
//------------------------------


    function popup () {
             //javascript popup functions
             /*******************************
             the popupWins array stores an object reference for
             each separate window that is called, based upon
             the name attribute that is supplied as an argument
             *******************************/
             print "<script language=\"JavaScript\">
                   var dialogArguments;
                       function getValueImage (field) {
                                dialogArguments = field;
                                window.open('upload_image.php', 'popup',
                                'width=250,height=100,scrollbars=1'); 
                                }
                       function getValueFile (field) {
                                dialogArguments = field;
                                window.open('upload_file.php', 'popup',
                                'width=250,height=100,scrollbars=1'); 
                                }
                   </script>";

    }


    function GetComments ($comment) {
             //get language variables
             global $lang;
             //function to make comments allowance
             //option list sticky

             $i = 0;
             while ($i < 2) {
                   print "<option value=\"$i\"";
                   if ($comment == $i) {
                          print " selected";
                   }
                   if ($i == 0) {
                      print ">{$lang[post_no]}</option>";
                   }
                   elseif ($i == 1) {
                          print ">{$lang[post_yes]}</option>";
                   }
             $i++;
             }
    }


    function GetZones ($id) {
             //function to get awailable zones, and make zones
             //drop down list sticky

             //query-----------------------------
             $query = "SELECT * FROM og_zones";
             $result = mysql_query($query) or die (mysql_error());
             //----------------------------------

             while ($row = mysql_fetch_array($result)) {
                   print ("<option value=\"{$row['id']}\"");
                   if ($id == $row['id']) {
                      print " selected>";
                      }
                   else {
                        print " >";
                        }
                   print $row['zone'];
                   print ("</option> /n");
             }
    }

    function validate ($fieldname, $maxlength, $minlength) {
             //function to  CHECK MAX AND MIN LENGHTS
             if (strlen($fieldname) < $minlength) {
                return false;
             }
             if (strlen($fieldname) > $maxlength) {
                return false;
             }
             return true;
    }

    function cleanup ($fieldname) {
             //function to clean up input

             //strip away tags
             $fieldname = strip_tags($fieldname);
             //replace linebreak /n with <br>
             $fieldname = nl2br($fieldname);
             $fieldname = AddSlashes($fieldname);
             return $fieldname;
             }



    function InsertLinks ( $Text )
             //function to recognice links and encode them with <a> tags
             {
             //  First match things beginning with http:// (or other protocols)
               $NotAnchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
               $Protocol = '(http|ftp|https):\/\/';
               $Domain = '[\w]+(.[\w]+)';
               $Subdir = '([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?';
               $Expr = '/' . $NotAnchor . $Protocol . $Domain . $Subdir . '/i';

               $Result = preg_replace( $Expr, "<a href=\"$0\" title=\"$0\" target=\"_blank\">$0</a>", $Text );

               //  Now match things beginning with www.
                $NotAnchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
                $NotHTTP = '(?<!:\/\/)';
                $Domain = 'www(.[\w]+)';
                $Subdir = '([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?';
                $Expr = '/' . $NotAnchor . $NotHTTP . $Domain . $Subdir . '/i';

   return preg_replace( $Expr, "<a href=\"http://$0\" title=\"http://$0\" target=\"_blank\">$0</a>", $Result );
}

   function add_form () {
            //get language variables
            global $lang;
            //function to display add_post form

            //load javascript popup script
            popup();

         print "<form action=\"{$_SERVER['PHP_SELF']}\" name=\"add_post\" method=\"post\">
               {$lang[post_title]}<br>
                   <input type=\"text\" name=\"title\" value=\"{$_POST['title']}\" size=\"60\" />
                   <br><br>

                {$lang[post_intro]}<br>
                    <textarea name=\"ingress\" rows=\"10\" cols=\"60\">{$_POST['ingress']}</textarea>
                    <br><br>

                {$lang[post_main_text_prompt]} <br>
                    <textarea name=\"main_text\" rows=\"20\" cols=\"60\">{$_POST['main_text']}</textarea>
                    <br><br>

                {$lang[post_file_name_prompt]}<br>
                    <input type=\"text\" name=\"file_name\" size=\"60\" value=\"{$_POST['file_name']}\" />
                           <INPUT TYPE=\"button\" VALUE=\"{$lang[post_upload_file]}\"
                                  ONCLICK=\"getValueFile(this.form.file_name)\">
                    <br><br>
                {$lang[post_image_name_prompt]} <br>
                    <input type=\"text\" name=\"image_file_name\" size=\"60\" value=\"{$_POST['image_file_name']}\" />
                           <INPUT TYPE=\"button\" VALUE=\"{$lang[post_upload_image]}\"
                                  ONCLICK=\"getValueImage(this.form.image_file_name)\">
                    <br><br>
                {$lang[post_zone]}<br>
                    <select name=\"zone\" size=\"1\">";
                    GetZones($_POST['zone']);
                    print "</select>
                    <br><br>
                {$lang[post_allow_comments]}<br>
                      <select name=\"comment\" size=\"1\">";
                      GetComments($_POST['comment']);
                      print "</select>
                      <br><br>

               <input type=\"hidden\" name=\"date\" value=\"";
               echo (strtotime("now"));
               print "\" />

            <input type=\"submit\" name=\"submit\" value=\"{$lang[post_submit]}\"/>
            <input type=\"reset\" name=\"reset\" value=\"{$lang[post_reset]}\" />
</form>";
}

      function addpostprocess () {
               //get language variables
               global $lang;
               //function to check input from add post form

      if ($_POST['submit'] == "{$lang[post_submit]}") {
        if (validate($_POST['title'], 50, 2)) {

         //check that an introduction is present, max 250 chars, min 5
         if (validate($_POST['ingress'], 250, 5)) {

               //then go ahead with the cleanup process
               //clean up the introdiction by removing html tags, and replacing \n linebreaks
               //with html <br> tags after that.
               $title = cleanup($_POST['title']);
               $ingress = cleanup($_POST['ingress']);
               //find links and encode them in html anchor tags
               $ingress = InsertLinks($ingress);

               //repeat with main text if present
               if (validate($_POST['main_text'], 99999, 3)) {
                     $main_text = cleanup($_POST['main_text']);
                     $main_text = InsertLinks($main_text);
                  }
                  else {
                  $main_text = null;
                  }


               //get file name if present
               if (validate($_POST['file_name'], 99999, 3)) {
               $file_url = $_POST['file_name'];
               $file_url = AddSlashes($file_url);
               $file_url = strtolower($file_url);
               }
               else {
               $file_url = null;
               }

               //get image name if present
               if (validate($_POST['image_file_name'], 99999, 3)) {
               $image_url = $_POST['image_file_name'];
               $image_url = AddSlashes($image_url);
               $image_url = strtolower($image_url);
               }
               else {
               $image_url = null;
               }

               //get category / zone
               if (isset($_POST['zone'])) {
               $zone = $_POST['zone'];
               }
               else {
               die("{$lang[post_error]}");
               }

               //get timestamp
               if (isset($_POST['date'])) {
               $date = $_POST['date'];
               }
               else {
               die("{$lang[post_error]}");
               }

               //get comment allowance:
                if (isset($_POST['comment'])) {
               $comment = $_POST['comment'];
               }
               else {
               die("{$lang[post_error]}");
               }


         //insert into database
         //query------------------------------------------
         $query_string = "INSERT INTO og_post(title, ingress, main_text, file_name,
                       image_name, date, zone, comment) VALUES('$title', '$ingress', '$main_text',
                       '$file_url', '$image_url', '$date', '$zone', '$comment')";
         //print "$query_string<br><br>";
         $query_result = mysql_query($query_string) or die (mysql_error());

         print "{$lang[post_add_success]}";

               }
               //close opened {}'s and add error messages
               else {
               print "{$lang[post_error_intro]}";
               }

               }
         else {
         print "{$lang[post_error_title]}";
         }
}
}

    function edit_post_form ($id) {
             //get language variables
             global $lang;
             //function to display form to edit posts

             //load javascript popup script
             popup();

         if (!$_POST['submit'] == "Edit Post") {
            $query = "SELECT * FROM og_post WHERE id = '$id'";
            $query_result = mysql_query($query) or die ("Horrible db fault, please evacuate");

            //get variables
            while ($row = mysql_fetch_array($query_result)) {
                  $title = $row['title'];
                  $ingress = $row['ingress'];
                  $main = $row['main_text'];
                  $file_name = $row['file_name'];
                  $image_name = $row['image_name'];
                  $zone = $row['zone'];
                  $comment = $row['comment'];
            }
            }
         else {
              $title = $_POST['title'];
                  $ingress = $_POST['ingress'];
                  $main = $_POST['main_text'];
                  $file_name = $_POST['file_name'];
                  $image_name = $_POST['image_file_name'];
                  $zone = $_POST['zone'];
                  $comment = $_POST['comment'];
                  }

         //print-out form
         print "<form action=\"{$_SERVER['PHP_SELF']}\" name=\"add_post\" method=\"post\">
                      {$lang[post_title]}<br>
                          <input type=\"text\" name=\"title\" size=\"60\" value=\"$title\"/>
                          <br><br>
                      {$lang[post_intro]}<br>
                          <textarea name=\"ingress\" rows=\"10\" cols=\"60\">$ingress</textarea>
                          <br><br>
                      {$lang[post_main_text_prompt]}
                          <br>
                          <textarea name=\"main_text\" rows=\"20\" cols=\"60\">$main</textarea>
                          <br><br>
                      {$lang[post_file_name_prompt]}<br>
                          <input type=\"text\" name=\"file_name\" size=\"60\" value=\"$file_name\" />
                                 <INPUT TYPE=\"button\" VALUE=\"{$lang[post_upload_file]}\"
                                        ONCLICK=\"getValueFile(this.form.file_name)\">
                          <br><br>
                      {$lang[post_image_name_prompt]} <br>
                             <input type=\"text\" name=\"image_file_name\" size=\"60\" value=\"$image_name\" />
                                    <INPUT TYPE=\"button\" VALUE=\"{$lang[post_upload_image]}\"
                                           ONCLICK=\"getValueImage(this.form.image_file_name)\">
                      <br><br>
                      {$lang[post_zone]}<br>
                              <select name=\"zone\" size=\"1\">";
                              GetZones($zone);
                              print "</select>
                              <br><br>
                      {$lang[post_allow_comments]}
                              <br>
                              <select name=\"comment\" size=\"1\">";
                              GetComments($comment);
                              print "</select>
                              <br><br>

            <input type=\"hidden\" name=\"id\" value=\"$id\" />
            <input type=\"submit\" name=\"submit\" value=\"{$lang[post_edit_button]}\"/>
            <input type=\"reset\" name=\"reset\" value=\"{$lang[post_reset]}\" />
</form>";
}

   function edit_post ($id) {
            //get language variables
            global $lang;
            //function to edit post info in database i.a.w. form

            if ($_POST['submit'] == "{$lang[post_edit_button]}") {
        if (validate($_POST['title'], 50, 2)) {

         //check that an introduction is present, max 250 chars, min 5
         if (validate($_POST['ingress'], 250, 5)) {

               //then go ahead with the cleanup process
               //clean up the introdiction by removing html tags, and replacing \n linebreaks
               //with html <br> tags after that.
               $title = cleanup($_POST['title']);
               $ingress = cleanup($_POST['ingress']);
               //find links and encode them in html anchor tags
               $ingress = InsertLinks($ingress);

               //repeat with main text if present
               if (validate($_POST['main_text'], 99999, 3)) {
                     $main_text = cleanup($_POST['main_text']);
                     $main_text = InsertLinks($main_text);
                  }
                  else {
                  $main_text = null;
                  }


               //get file name if present
               if (validate($_POST['file_name'], 99999, 3)) {
               $file_name = $_POST['file_name'];
               $file_name = AddSlashes($file_name);
               $file_name = strtolower($file_name);
               }
               else {
               $file_name = null;
               }

               //get image name if present
               if (validate($_POST['image_file_name'], 99999, 3)) {
               $image_name = $_POST['image_file_name'];
               $image_name = AddSlashes($image_name);
               $image_name = strtolower($image_name);
               }
               else {
               $image_name = null;
               }

               //get category / zone
               if (isset($_POST['zone'])) {
               $zone = $_POST['zone'];
               }
               else {
               die("{$lang[post_error]}");
               }

               //get comment allowance:
                if (isset($_POST['comment'])) {
               $comment = $_POST['comment'];
               }
               else {
               die("{$lang[post_error]}");
               }

              //insert into database
         $query_string = "UPDATE og_post SET title = '$title', ingress = '$ingress', main_text = '$main_text',
                         file_name = '$file_name', image_name = '$image_name', zone = '$zone',
                         comment = '$comment' WHERE id = '$id'";
         $query_result = mysql_query($query_string) or die (mysql_error());

         print ("{$lang[post_edit_success]}");

               }
               //close opened {}'s and add error messages
               else {
               print "{$lang[post_error_intro]}";
               }

               }
         else {
         print "{$lang[post_error_title]}";
         }
}
}

  function delete_confirm($id) {
           //get language variables
           global $lang;
           //function to get confirmation for deletion of post

           print "<form name=\"delete\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                 <input type=\"hidden\" name=\"id\" value=\"$id\"/>
                 <input type=\"submit\" name=\"delete_button\" value=\"{$lang[post_delete_confirm]}\"/>
                 <input type=\"reset\" name=\"reset\" value=\"$lang[post_delete_cancel]\" onClick=\"history.go(-1)\"/>
           </form>";
           }
           
  function delete_post($id) {
           //get language variables
           global $lang;
           //function to delete post from database

           //query-----------------------------------------
           $query = "DELETE FROM og_post WHERE id = '$id'";
           $query_result = mysql_query($query) or DIE ("terrible DB error, please evacuate");
           //-----------------------------------------------

           Print "{$lang[post_delete_success]}";

           print "<form action=\"post_list.php\" name=\"goon\" method=\"post\">
           <input type=\"submit\" name=\"go\" value=\"{$lang[post_continue_button]}\"/>
           </form>";
           }
           
?>