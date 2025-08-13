<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- PACKAGE_TYPE ---------------------*/
      $title = PACKAGES;
      $parent = array(1=>"email_config");
      $children = array("package_feature");
      $args = array(array("column"         => "pack_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("type"           => "HEADERROW",
                           "title"         => "Package Info"),
                    array("column"         => "pack_name",
                           "required"      => 1,
                           "title"         => NAME,
                           "type"          => "TEXT",
                           "size"          => 20,
                           "maxlength"     => 50),
                    array("column"         => "pack_price",
                           "required"      => 1,
                           "title"         => PRICE,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>
                                               <b>FORMAT:</b> ".PRICEFORMAT." or Monthly|Quarterly|Semi-Annual|Annual|2years<br>
                                               <b>NOTE:</b> LIST AS <b>PRICE PER MONTH</b> PER BILLING CYCLE!<br><br>",
                           "default_value" => PRICEFORMAT),
                    array("column"         => "pack_setup",
                           "required"      => 0,
                           "title"         => SETUP,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>
                                               <b>FORMAT:</b> ".PRICEFORMAT." or Monthly|Quarterly|Semi-Annual|Annual|2years<br>
                                               <b>NOTE:</b> LIST AS <b>ONE-TIME SETUP PRICE</b> PER BILLING CYCLE!<br><br>",
                           "default_value" => PRICEFORMAT),
                    array("column"         => "pack_cost",
                           "required"      => 0,
                           "title"         => COST,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 255,
                           "append"        => "<br>
                                               <b>FORMAT:</b> Monthly Cost: ".PRICEFORMAT,
                           "default_value" => PRICEFORMAT),
                    array("column"         => "pack_comments",
                           "required"      => 0,
                           "title"         => COMMENTS,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "cuttext"       => 40),
                    array("column"         => "pack_status",
                           "required"      => 1,
                           "title"         => STATUS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => status_select_box($pack_status,"pack_status")),
                    array("type"           => "HEADERROW",
                           "admin_only"    => 1,
                           "title"         => "Vortech Signup Config"),
                    array("column"         => "email_override",
                           "required"      => 0,
                           "title"         => CUSTOMSIGNUPEMAIL,
                           "type"          => "FUNCTION_CALL",
                           "admin_only"    => 1,
                           "function_call" => true_false_radio("email_override",$email_override),
                           "append"        => "<br>Set to <b>YES</b> if you want this package to be displayed in the Vortech Signup Form.<br><br>"),
                    array("column"         => "pack_display",
                           "required"      => 0,
                           "title"         => SIGNUPDISPLAY,
                           "type"          => "FUNCTION_CALL",
                           "admin_only"    => 1,
                           "function_call" => pack_display_select_box($pack_display,"pack_display"),
                           "append"        => "<br>Set to \"Type1\" for the default Vortech Signup Form or if you created a second signup directory, you can set this to \"Type2\". This package can only be displayed in one form at a time.<br><br>"),
                    array("column"         => "email_id",
                           "required"      => 0,
                           "title"         => EMAILID,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => email_signup_select_box($email_id),
                           "link_to_parent"=> 1,
                           "admin_only"    => 1,
                           "append"        => "<br>This should be mapped ONLY to the Vortech Template(s)!<br>NO OTHER TEMPLATES WILL PARSE THE SPECIAL VARIABLES CORRECTLY!<br><br>"),
                    array("column"         => "pack_stamp",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "no_add"        => 1,
                           "admin_only"    => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($pack_stamp,NULL,NULL)));

      //$select_sql = "SELECT pack_id, pack_name, pack_price, pack_setup, pack_cost, pack_comments, pack_status, pack_display, email_override, email_id, pack_stamp FROM $db_table ";
      $select_sql = "SELECT pack_id, pack_name, pack_price, pack_status, pack_display, pack_stamp FROM $db_table ";

      $select_order = "pack_name";
      $insert_sql = "INSERT INTO $db_table (pack_id, pack_name, pack_price, pack_setup, pack_cost, pack_comments, pack_status, pack_display, email_override, email_id, pack_stamp) VALUES (NULL, '$pack_name', '$pack_price', '$pack_setup', '$pack_cost', '$pack_comments', '$pack_status', '$pack_display', '$email_override', '$email_id', '".mktime()."')";
      $update_sql = "UPDATE $db_table SET pack_name='$pack_name', pack_price='$pack_price', pack_setup='$pack_setup', pack_cost='$pack_cost', pack_comments='$pack_comments', pack_status='$pack_status', pack_display='$pack_display', email_override='$email_override', email_id='$email_id', pack_stamp='".mktime()."' WHERE pack_id='$pack_id'";
      $delete_sql = array ("DELETE FROM $db_table WHERE pack_id='$pack_id'",
                           "DELETE FROM package_feature WHERE pack_id='$pack_id'",
                           /*"DELETE FROM client_package WHERE pack_id='$pack_id'",*/
                           "DELETE FROM package_relationships WHERE parent_pack_id='$pack_id' || child_pack_id='$pack_id'");
?>