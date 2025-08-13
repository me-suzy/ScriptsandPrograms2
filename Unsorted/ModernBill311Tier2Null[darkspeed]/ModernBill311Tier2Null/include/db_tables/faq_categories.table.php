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
      $title = FAQCATEGORIES;
      $children = array("faq_questions");
      $args = array(array("column"         => "cid",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "cname",
                           "required"      => 1,
                           "title"         => CATEGORY,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),
                    array("column"         => "ctype",
                           "required"      => 1,
                           "title"         => TYPE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => category_type_select_box($ctype)));

      $select_sql = "SELECT * FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (cid,
                                            cname,
                                            ctype) VALUES (NULL,
                                                          '$cname',
                                                          '$ctype')";

      $update_sql = "UPDATE $db_table SET cname='$cname', ctype='$ctype' WHERE cid='$cid'";

      $delete_sql = array("DELETE FROM $db_table WHERE cid='$cid'",
                          "DELETE FROM faq_questions WHERE cid='$cid'");
?>