<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
      /* ----------------- TODO_LIST ---------------------*/
      $title = TODOLIST;
      $args = array(array("column"         => "todo_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "todo_title",
                           "required"      => 1,
                           "title"         => TITLE,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 40,
                           "cuttext"       => 15),
                    array("column"         => "todo_desc",
                           "required"      => 1,
                           "title"         => DESCRIPTION,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "cuttext"       => 30),
                    array("column"         => "admin_id",
                           "required"      => 1,
                           "title"         => ADMIN,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => admin_select_box($admin_id)),
                    array("column"         => "todo_status",
                           "required"      => 1,
                           "title"         => STATUS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => todo_status_select_box($todo_status)),
                    array("column"         => "todo_due",
                           "required"      => 1,
                           "title"         => DUEDATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($todo_due,"todo_due",stamp_to_date(mktime())),
                           "append"        => "(".DATEFORMAT2.")"),
                    array("column"         => "todo_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($todo_stamp,"todo_stamp")));
      if(($submit||$do=="add"||$do=="edit")){
          $todo_due = (date_check($todo_due)) ? date_to_stamp($todo_due) : 0 ;
      }
      $select_sql = "SELECT todo_id, todo_title, todo_desc, admin_id, todo_status, todo_due, todo_stamp FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (todo_id, todo_title, todo_desc, admin_id, todo_status, todo_due, todo_stamp) VALUES (NULL, '$todo_title', '$todo_desc', '$admin_id', '$todo_status', '$todo_due', '".mktime()."')";
      $update_sql = "UPDATE $db_table SET todo_title='$todo_title',
                                          todo_desc='$todo_desc',
                                          admin_id='$admin_id',
                                          todo_status='$todo_status',
                                          todo_due='$todo_due' WHERE todo_id='$todo_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE todo_id='$todo_id'");
?>