<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
GLOBAL $feature_name;
 /* ----------------- PACKAGE_FEATURE ---------------------*/
     $title = FEATURES;
    $parent = array(2=>"package_type");
      $args = array(array("column"         => "feature_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "pack_id",
                           "required"      => 1,
                           "title"         => PACKAGE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => package_select_box($pack_id),
                           "link_to_parent"=> 2),
                    array("column"         => "feature_name",
                           "required"      => 1,
                           "title"         => FEATURE,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 255,
                           "default"       => $feature_name),
                    array("column"         => "feature_comments",
                           "required"      => 0,
                           "title"         => COMMENTS,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "cuttext"       => 40),
                    array("column"         => "feature_stamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => "TimeStamp",
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($feature_stamp,"feature_stamp")));
      $select_sql = "SELECT feature_id, pack_id, feature_name, feature_comments FROM $db_table ";
    $select_order = "feature_name";
   $recursive_sql = "SELECT feature_id, feature_name, feature_comments FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (feature_id, pack_id, feature_name, feature_comments, feature_stamp) VALUES (NULL, '$pack_id', '$feature_name', '$feature_comments', '".mktime()."')";
      $update_sql = "UPDATE $db_table SET pack_id='$pack_id', feature_name='$feature_name', feature_comments='$feature_comments', feature_stamp='".mktime()."' WHERE feature_id='$feature_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE feature_id='$feature_id'");
?>