<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- EMAIL_CONFIG SETTINGS ---------------------*/
      $title = EMAILCONFIG;
      $include_bottom = "include/html/email_shortcuts.inc.php";
      $args = array(array("column"         => "email_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "email_title",
                           "required"      => 1,
                           "title"         => TITLE,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),
                    array("column"         => "email_heading",
                           "required"      => 0,
                           "title"         => HEADING,
                           "type"          => "TEXTAREA",
                           "rows"          => 20,
                           "cols"          => 65,
                           "wrap"          => $textarea_wrap,
                           "nl2br"         => 1,
                           "cuttext"       => 15),
                    array("column"         => "email_body",
                           "required"      => 0,
                           "title"         => BODY,
                           "type"          => "TEXTAREA",
                           "rows"          => 20,
                           "cols"          => 65,
                           "wrap"          => $textarea_wrap,
                           "nl2br"         => 1,
                           "cuttext"       => 15),
                    array("column"         => "email_footer",
                           "required"      => 0,
                           "title"         => FOOTER,
                           "type"          => "TEXTAREA",
                           "rows"          => 20,
                           "cols"          => 65,
                           "wrap"          => $textarea_wrap,
                           "nl2br"         => 1,
                           "cuttext"       => 15),
                    array("column"         => "email_signature",
                           "required"      => 0,
                           "title"         => SIGNATURE,
                           "type"          => "TEXTAREA",
                           "rows"          => 20,
                           "cols"          => 65,
                           "wrap"          => $textarea_wrap,
                           "nl2br"         => 1,
                           "cuttext"       => 15),
                    array("column"         => "email_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($email_stamp,"email_stamp")));
      $select_sql = "SELECT email_id, email_title FROM $db_table ";
      $select_order = "email_title";
      $insert_sql = "INSERT INTO $db_table (email_id, email_title, email_heading, email_body, email_footer, email_signature, email_stamp) VALUES (NULL, '$email_title', '$email_heading', '$email_body', '$email_footer', '$email_signature', '".mktime()."')";
      $update_sql = "UPDATE $db_table SET email_title='$email_title', email_heading='$email_heading', email_body='$email_body', email_footer='$email_footer', email_signature='$email_signature', email_stamp='".mktime()."' WHERE email_id='$email_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE email_id='$email_id'");
?>