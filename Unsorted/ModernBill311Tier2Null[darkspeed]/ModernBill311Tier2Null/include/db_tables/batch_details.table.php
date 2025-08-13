<?
/*
** ModernBill [TM] (Copyright::2002)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
 /* ----------------- BATCH_DETAILS ---------------------*/
      $title = BATCHDETAILS;
      $table_no_edit = 1;
      $args = array(array("column"         => "batch_id",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "batch_stamp",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => "Batch Date",
                           "type"          => "TEXT"),
                    array("column"         => "batch_sum_approved",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => TTLAPPROVED,
                           "type"          => "TEXT"),
                    array("column"         => "batch_sum_declined",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => TTLDECLINED,
                           "type"          => "TEXT"),
                    array("column"         => "batch_sum_error",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => TTLERROR,
                           "type"          => "TEXT"),
                    array("column"         => "batch_num_approved",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => NUMAPPROVED,
                           "type"          => "TEXT"),
                    array("column"         => "batch_num_declined",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => NUMDECLINED,
                           "type"          => "TEXT"),
                    array("column"         => "batch_num_error",
                           "required"      => 0,
                           "no_edit"       => 1,
                           "title"         => NUMERROR,
                           "type"          => "TEXT"));
      if($submit||$do=="edit"){
         $batch_stamp = (date_check($batch_stamp)) ? date_to_stamp($batch_stamp) : 0 ;
      }
      $select_sql = "SELECT batch_id, batch_sum_approved, batch_sum_declined, batch_sum_error, batch_num_approved, batch_num_declined, batch_num_error, batch_stamp FROM $db_table ";
      $insert_sql = "INSERT INTO $db_table (batch_id, batch_stamp, batch_sum_approved, batch_sum_declined, batch_sum_error, batch_num_approved, batch_num_declined, batch_num_error) VALUES (NULL, '".mktime()."', '$batch_sum_approved', '$batch_sum_declined', '$batch_sum_error', '$batch_num_approved', '$batch_num_declined', '$batch_num_error')";
      $update_sql = "UPDATE $db_table SET batch_stamp='$batch_stamp', batch_sum_approved='$batch_sum_approved', batch_sum_declined='$batch_sum_declined', batch_sum_error='$batch_sum_error', batch_num_approved='$batch_num_approved', batch_num_declined='$batch_num_declined', batch_num_error='$batch_num_error' WHERE batch_id='$batch_id'";
      $delete_sql = array("DELETE FROM $db_table WHERE batch_id='$batch_id'");
?>