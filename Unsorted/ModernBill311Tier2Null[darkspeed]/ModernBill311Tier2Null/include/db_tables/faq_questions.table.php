<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
      $title = FAQQUESTIONS;
      $parent = array(1=>"faq_categories");
      $args = array(array("column"         => "fid",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "cid",
                           "required"      => 1,
                           "title"         => CATEGORY,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => faq_category_select_box($cid),
                           "link_to_parent"=> 1),
                    array("column"         => "question",
                           "required"      => 1,
                           "title"         => QUESTION,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "cuttext"       => 20),
                    array("column"         => "answer",
                           "required"      => 1,
                           "title"         => ANSWER,
                           "type"          => "TEXTAREA",
                           "rows"          => $textarea_rows,
                           "cols"          => $textarea_cols,
                           "wrap"          => $textarea_wrap,
                           "cuttext"       => 50),
                    array("column"         => "timestamp",
                           "required"      => 0,
                           "no_add"        => 1,
                           "no_edit"       => 1,
                           "title"         => TIMESTAMP,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($timestamp,"timestamp")));

      $select_sql = "SELECT fid, cid, question, answer, timestamp FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (fid,
                                            cid,
                                            question,
                                            answer,
                                            timestamp) VALUES (NULL,
                                                               '$cid',
                                                               '$question',
                                                               '$answer',
                                                               '".mktime()."')";

      $update_sql = "UPDATE $db_table SET cid='$cid',
                                          question='$question',
                                          answer='$answer',
                                          timestamp='".mktime()."'
                                      WHERE fid='$fid'";

      $delete_sql = array("DELETE FROM $db_table WHERE fid='$fid'");

?>