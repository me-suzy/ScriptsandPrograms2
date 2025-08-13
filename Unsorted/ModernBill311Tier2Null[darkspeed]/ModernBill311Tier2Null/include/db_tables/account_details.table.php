<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- ACCOUNT_DETAILS ---------------------*/

      $title = ACCOUNTDETAILS;
      $parent = array(1=>"client_info",2=>"domain_names",3=>"client_package");
      $args = array(array("column"         => "details_id",
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
                           "required"      => 1,
                           "title"         => PACKAGE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => cp_select_box($client_id,$cp_id),
                           "link_to_parent"=> 3),
                    array("column"         => "domain_id",
                           "required"      => 0,
                           "title"         => DOMAINNAME,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => domain_select_box($domain_id,"domain_id",NULL,$client_id),
                           "link_to_parent"=> 2),
                    array("column"         => "ip",
                           "required"      => 0,
                           "title"         => IP,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 15,
                           "append"        => "(".IPFORMAT.")"),
                    array("column"         => "server_type",
                           "required"      => 0,
                           "title"         => SERVTYPE2,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => server_type_select_box($server_type)),
                    array("column"         => "username",
                           "required"      => 0,
                           "title"         => DOMUSER,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 255),
                    array("column"         => "password",
                           "required"      => 0,
                           "title"         => DOMPASS,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 15));

GLOBAL $enable_manual_server_names;
if ($enable_manual_server_names) {
  array_push ($args, array("column"        => "server",
                           "required"      => 0,
                           "title"         => SERVNAME,
                           "type"          => "TEXT",
                           "size"          => 30,
                           "maxlength"     => 255));
} else {
  array_push ($args, array("column"        => "server",
                           "required"      => 0,
                           "title"         => SERVNAME,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => server_name_select_box($server)));
}

      $select_sql = "SELECT details_id, cp_id, domain_id, ip, server, server_type, username, password FROM $db_table ";
   $recursive_sql = "SELECT details_id, cp_id, domain_id, ip, server, server_type, username, password FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (details_id, client_id, cp_id, domain_id, ip, server, server_type, username, password) VALUES (NULL, '$client_id', '$cp_id', '$domain_id', '$ip', '$server', '$server_type', '$username', '$password')";
      $update_sql = "UPDATE $db_table SET client_id='$client_id', cp_id='$cp_id', domain_id='$domain_id', ip='$ip', server='$server', server_type='$server_type', username='$username', password='$password' WHERE details_id='$details_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE details_id='$details_id'");
?>