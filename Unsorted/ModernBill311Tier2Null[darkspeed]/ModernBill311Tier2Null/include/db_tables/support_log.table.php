<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
      $title = SUPPORTLOGS;
      $parent = array(1=>"support_desk");
      $args = array(array("column"         => "log_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "call_id",
                           "required"      => 0,
                           "title"         => SUBJECT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => support_select_box($call_id),
                           "link_to_parent"=> 1),
                    array("column"         => "log_event",
                           "required"      => 1,
                           "title"         => UPDATE,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "nl2br"         => 1),
                    array("column"         => "call_technician",
                           "required"      => 1,
                           "title"         => TECH,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => admin_select_box($call_technician,"call_technician")),
                    array("column"         => "log_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($log_stamp,"log_stamp")));

      $select_sql = "SELECT log_id,log_event,call_technician,log_stamp FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (log_id,
                                            call_id,
                                            log_event,
                                            call_technician,
                                            log_stamp) VALUES (NULL,
                                                              '$call_id',
                                                              '$log_event',
                                                              '$call_technician',
                                                              '".mktime()."')";

      $update_sql = "UPDATE $db_table SET call_id='$call_id',
                                          log_event='$log_event'
                                          call_technician='$call_technician',
                                          WHERE log_id='$log_id'";

      $delete_sql = array("DELETE FROM $db_table WHERE log_id='$log_id'");
?>