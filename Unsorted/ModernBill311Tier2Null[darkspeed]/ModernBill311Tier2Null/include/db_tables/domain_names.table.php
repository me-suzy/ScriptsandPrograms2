<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- DOMAIN_NAMES ---------------------*/
      $title = DOMAINS;
      $parent = array(1=>"client_info");
      $args = array(array("column"         => "domain_id",
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
                    array("column"         => "domain_name",
                           "required"      => 1,
                           "title"         => DOMAINNAME,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "whois_lookup"  => 1),
                    array("column"         => "domain_created",
                           "required"      => 1,
                           "title"         => CREATEDON,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($domain_created,"domain_created",stamp_to_date(mktime())),
                           "append"        => "(".DATEFORMAT2.")"),
                    array("column"         => "domain_expires",
                           "required"      => 1,
                           "title"         => EXPIRES,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($domain_expires,"domain_expires",stamp_to_date(mktime())),
                           "append"        => "(".DATEFORMAT2.")"),
                    array("column"         => "registrar_id",
                           "required"      => 1,
                           "title"         => REGISTRAR,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => registrar_select_box($registrar_id)),
                    array("column"         => "monitor",
                           "required"      => 1,
                           "title"         => MONITOR,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => monitor_select_box($monitor)));
      if ($submit&&($do=="add"||$do="edit")){
         if (!date_check($domain_created)) $oops .= "[".ERROR."] ".CREATEDINVALID."<br>";
         if (!date_check($domain_expires)) $oops .= "[".ERROR."] ".EXPIRESINVALID."<br>";
         $domain_created = (date_check($domain_created)) ? date_to_stamp($domain_created) : 0 ;
         $domain_expires = (date_check($domain_expires)) ? date_to_stamp($domain_expires) : 0 ;
      }
      $select_sql = "SELECT * FROM $db_table ";
   $recursive_sql = "SELECT domain_id, domain_name, domain_created, domain_expires, registrar_id, monitor FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (domain_id, domain_name, client_id, domain_created, domain_expires, registrar_id, monitor) VALUES (NULL, '$domain_name', '$client_id', '$domain_created', '$domain_expires', '$registrar_id', '$monitor')";
      $update_sql = "UPDATE $db_table SET domain_name='$domain_name', client_id='$client_id', domain_created='$domain_created', domain_expires='$domain_expires', registrar_id='$registrar_id', monitor='$monitor' WHERE domain_id='$domain_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE domain_id='$domain_id'");
?>