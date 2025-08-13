<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
      $title = WHOISSTATS;
      $args = array(array("column"         => "ws_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "ws_domain",
                           "required"      => 0,
                           "title"         => DOMAINNAME,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "whois_lookup"  => 1),
                    array("column"         => "ws_qty",
                           "required"      => 0,
                           "title"         => QTY,
                           "type"          => "TEXT",
                           "size"          => 4,
                           "maxlength"     => 5),
                    array("column"         => "ws_from",
                           "required"      => 0,
                           "title"         => IP,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),
                    array("column"         => "ws_stamp",
                           "required"      => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($ws_stamp,"ws_stamp")));

      $select_sql = "SELECT * FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (ws_id, ws_domain, ws_qty, ws_from, ws_stamp) VALUES (NULL, '$ws_domain', '$ws_qty', '$ws_from', '".mktime()."')";
      $update_sql = NULL;
      $delete_sql = array("DELETE FROM $db_table WHERE ws_id='$ws_id'");
?>