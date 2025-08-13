<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- CLIENT_CREDIT ---------------------*/
      $title = CREDITS;
      $parent = array(1=>"client_info");
      $args = array(array("column"         => "credit_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "client_id",
                           "required"      => 1,
                           "title"         => CLIENT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => client_select_box($client_id,"client_id"),
                           "link_to_parent"=> 1,
                           "parent_op"     => "client_details"),
                    array("column"         => "credit_amount",
                           "required"      => 1,
                           "title"         => AMOUNT,
                           "type"          => "TEXT",
                           "size"          => 7,
                           "maxlength"     => 25,
                           "append"        => "(".PRICEFORMAT.")",
                           "default_value" => PRICEFORMAT),
                    array("column"         => "credit_comments",
                           "required"      => 1,
                           "title"         => COMMENTS,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "cuttext"       => 50),
                    array("column"         => "credit_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($credit_stamp,"credit_stamp")));
      $select_sql = "SELECT credit_id, client_id, credit_amount, credit_comments, credit_stamp FROM $db_table ";
   $recursive_sql = "SELECT credit_id, credit_amount, credit_comments, credit_stamp FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (credit_id, client_id, credit_amount, credit_comments, credit_stamp) VALUES (NULL, '$client_id', '$credit_amount', '$credit_comments', '$credit_stamp')";
      $update_sql = "UPDATE $db_table SET client_id='$client_id', credit_amount='$credit_amount', credit_comments='$credit_comments', credit_stamp='$credit_stamp' WHERE credit_id='$credit_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE credit_id='$credit_id'");
?>