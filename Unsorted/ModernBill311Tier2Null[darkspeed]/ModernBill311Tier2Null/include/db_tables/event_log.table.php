<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- EVENT_LOG ---------------------*/
      $title = CLIENTNOTES;
      $parent = array(1=>"client_info");
      $args = array(array("column"         => "log_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN",
                           "parent_op"     => "details"),
                    array("column"         => "client_id",
                           "required"      => 1,
                           "title"         => CLIENT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => client_select_box($client_id),
                           "link_to_parent"=> 1,
                           "parent_op"     => "client_details"),
                    array("column"         => "log_type",
                           "required"      => 1,
                           "title"         => TYPE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => log_type_select_box($log_type)),
                    array("column"         => "log_comments",
                           "required"      => 0,
                           "title"         => COMMENTS,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "cuttext"       => 35),
                    array("column"         => "log_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($log_stamp,"log_stamp")));
      $select_sql = "SELECT log_id, client_id, log_type, log_comments, log_stamp FROM $db_table ";
   $recursive_sql = "SELECT log_id, log_type, log_comments, log_stamp FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (log_id, client_id, log_type, log_comments, log_stamp) VALUES (NULL, '$client_id', '$log_type', '$log_comments', '".mktime()."')";
      $update_sql = "UPDATE $db_table SET client_id='$client_id', log_type='$log_type', log_comments='$log_comments', log_stamp='".mktime()."' WHERE log_id='$log_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE log_id='$log_id'");
?>