<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
      $title = PACKAGERELATIONSHIPS;
      $parent = array(1=>"package_type");
      $args = array(array("column"         => "pr_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "parent_pack_id",
                           "required"      => 1,
                           "title"         => PARENTPACKAGE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => package_select_box($parent_pack_id,$cp_billing_cycle,"parent_pack_id"),
                           "link_to_parent"=> 1),
                    array("column"         => "child_pack_id",
                           "required"      => 1,
                           "title"         => CHILDPACKAGE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => package_select_box($child_pack_id,$cp_billing_cycle,"child_pack_id"),
                           "link_to_parent"=> 1),
                    array("column"         => "pr_status",
                           "required"      => 1,
                           "title"         => STATUS,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => status_select_box($pr_status,"pr_status")));

      $select_sql = "SELECT pr_id, parent_pack_id, child_pack_id, pr_status FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (pr_id, parent_pack_id, child_pack_id, pr_status) VALUES (NULL, '$parent_pack_id', '$child_pack_id', '$pr_status')";
      $update_sql = "UPDATE $db_table SET parent_pack_id='$parent_pack_id', child_pack_id='$child_pack_id', pr_status='$pr_status' WHERE pr_id='$pr_id'";
      $delete_sql = array ("DELETE FROM $db_table WHERE pr_id='$pr_id'");
?>