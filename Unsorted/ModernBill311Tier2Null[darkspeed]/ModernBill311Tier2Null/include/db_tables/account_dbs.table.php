<?
/*
** ModernBill [TM] (Copyright::2002)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- ACCOUNT_DBS ---------------------*/
      $title = ACCOUNTDBS;
      $parent = array(1=>"client_info",2=>"client_package");
      $args = array(array("column"         => "db_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "client_id",
                           "required"      => 1,
                           "title"         => CLIENT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => client_select_box($client_id),
                           "link_to_parent"=> 1,
                           "parent_op"     => "client_details"),
                    array("column"         => "cp_id",
                           "required"      => 0,
                           "title"         => PACKAGE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => cp_select_box($client_id,$cp_id),
                           "link_to_parent"=> 2),
                    array("column"         => "db_type",
                           "required"      => 0,
                           "title"         => DBT,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => db_type_select_box($db_type)),
                    array("column"         => "db_name",
                           "required"      => 0,
                           "title"         => DBN,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 25),
                    array("column"         => "db_user",
                           "required"      => 0,
                           "title"         => DBU,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 25),
                    array("column"         => "db_pass",
                           "required"      => 0,
                           "title"         => DBP,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 25),
                    array("column"         => "db_stamp",
                           "required"      => 0,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "no_edit"       => 1,
                           "no_add"        => 1,
                           "function_call" => date_input_generator($db_stamp,"db_stamp")));

      $select_sql = "SELECT * FROM $db_table ";
   $recursive_sql = "SELECT db_id, cp_id, db_type, db_name, db_user, db_pass FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (db_id, client_id, cp_id, db_type, db_name, db_user, db_pass, db_stamp) VALUES (NULL, '$client_id', '$cp_id', '$db_type', '$db_name', '$db_user', '$db_pass', '".mktime()."')";
      $update_sql = "UPDATE $db_table SET client_id='$client_id', cp_id='$cp_id', db_type='$db_type', db_name='$db_name', db_user='$db_user', db_pass='$db_pass', db_stamp='".mktime()."' WHERE db_id='$db_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE db_id='$db_id'");
?>