<?
/*
** ModernBill [TM] (Copyright::2002)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- ACCOUNT_POPS ---------------------*/
      $title = ACCOUNTPOPS;
      $parent = array(1=>"client_info",2=>"client_package");
      $args = array(array("column"         => "pop_id",
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
                           "title"         => PACKGE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => cp_select_box($client_id,$cp_id),
                           "link_to_parent"=> 2),
                    array("column"         => "pop_real_name",
                           "required"      => 0,
                           "title"         => POPREAL,
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 255),
                    array("column"         => "pop_username",
                           "required"      => 0,
                           "title"         => POPUSER,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 255),
                    array("column"         => "pop_password",
                           "required"      => 0,
                           "title"         => POPPASS,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 255),
                    array("column"         => "pop_space",
                           "required"      => 0,
                           "title"         => POPSPACE,
                           "type"          => "TEXT",
                           "size"          => 3,
                           "maxlength"     => 10,
                           "append"        => "(MBs)"),
                    array("column"         => "pop_ftp",
                           "required"      => 0,
                           "title"         => POPFTP,
                           "type"          => "TEXT",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("pop_ftp",$pop_ftp)),
                    array("column"         => "pop_telnet",
                           "required"      => 0,
                           "title"         => POPTELNET,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => true_false_radio("pop_telnet",$pop_telnet)),
                    array("column"         => "pop_stamp",
                           "required"      => 0,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "no_edit"       => 1,
                           "function_call" => date_input_generator($pop_stamp,"pop_stamp")));

      $select_sql = "SELECT * FROM $db_table ";
   $recursive_sql = "SELECT pop_id, cp_id, pop_real_name, pop_username, pop_password, pop_space, pop_ftp, pop_telnet FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (pop_id, client_id, cp_id, pop_real_name, pop_username, pop_password, pop_space, pop_ftp, pop_telnet, pop_stamp) VALUES (NULL, '$client_id', '$cp_id', '$pop_real_name', '$pop_username', '$pop_password', '$pop_space', '$pop_ftp', '$pop_telnet', '".mktime()."')";
      $update_sql = "UPDATE $db_table SET client_id='$client_id', cp_id='$cp_id', pop_real_name='$pop_real_name', pop_username='$pop_username', pop_password='$pop_password', pop_space='$pop_space', pop_ftp='$pop_ftp', pop_telnet='$pop_telnet', pop_stamp='".mktime()."' WHERE pop_id='$pop_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE pop_id='$pop_id'");
?>