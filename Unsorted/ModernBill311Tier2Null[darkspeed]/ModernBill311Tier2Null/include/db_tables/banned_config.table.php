<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- banned_config ---------------------*/
      $title = BANNEDCONFIG;
      $args = array(array("column"         => "ban_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "ban_type",
                           "required"      => 1,
                           "title"         => TYPE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => ban_type_select_box($ban_type)),
                    array("column"         => "ban_string",
                           "required"      => 1,
                           "title"         => BANNED,
                           "type"          => "TEXT",
                           "size"          => 20,
                           "maxlength"     => 255,
                           "append"        => "(".IPOREMAIL.")"),
                    array("column"         => "ban_message",
                           "required"      => 0,
                           "title"         => MESSAGE,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "cuttext"       => 50,
                           "append"        => "<br>This is the override text. You can configure the default text in the vortech config.<br><br>"),
                    array("column"         => "ban_count",
                           "required"      => 0,
                           "title"         => COUNT,
                           "type"          => "TEXT",
                           "size"          => 3,
                           "maxlength"     => 255),
                    array("column"         => "ban_status",
                           "required"      => 1,
                           "title"         => STATUS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => status_select_box($ban_status,"ban_status")),
                    array("column"         => "ban_last_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($ban_last_stamp,"ban_last_stamp")));

      $select_sql = "SELECT * FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (ban_id,
                                            ban_type,
                                            ban_string,
                                            ban_message,
                                            ban_count,
                                            ban_status,
                                            ban_last_stamp) VALUES (NULL,
                                                                    '$ban_type',
                                                                    '$ban_string',
                                                                    '$ban_message',
                                                                    '$ban_count',
                                                                    '$ban_status',
                                                                    '".mktime()."')";

      $update_sql = "UPDATE $db_table SET ban_type='$ban_type',
                                          ban_string='$ban_string',
                                          ban_message='$ban_message',
                                          ban_count='$ban_count',
                                          ban_status='$ban_status',
                                          ban_last_Stamp='".mktime()."' WHERE ban_id='$ban_id'";

      $delete_sql = array("DELETE FROM $db_table WHERE ban_id='$ban_id'");
/*
sscanf($ip, "%d.%d.%d.%d", &$quad1, &$quad2, &$quad3, &$quad4);

    switch($quad1)
    {
      case 57:
      case 61:
      case 62:
      case 80:
      case 151:
      case 193:
      case 194:
      case 195:
      case 202:
      case 203:
      case 210:
      case 211:
      case 212:
      case 213:
      case 217:
      case 218:
      case 219:
        return true;

      case 24:
        if($quad2 > 132 && $quad2 < 136) return true;

      case 130:
        if($quad2 == 237 ||
           $quad2 == 242 ||
           $quad2 == 243) return true;

      case 134:
        if($quad2 == 75) return true;

      case 141:
        if($quad2 < 86) return true;

      case 165:
        if($quad2 == 21) return true;

      case 169:
        if($quad2 > 207 && $quad2 < 224) return true;

      case 170:
        if($quad2 == 60) return true;

      case 192:
        if($quad2 == 36 ||
           $quad2 == 164 ||
           $quad2 == 165 ||
           $quad2 == 166 ||
           $quad2 == 167) return true;
    }

    return false;
  }
*/
?>