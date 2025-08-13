<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/

      $title = ACCOUNTREGISTER;
      $parent = array(1=>"client_info",2=>"client_invoice");
      $args = array(array("column"         => "reg_id",
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
                    array("column"         => "reg_date",
                           "required"      => 1,
                           "title"         => DATE,
                           "type"          => "FUNCTION_CALL",
                           "function_call" => date_input_generator($reg_date,"reg_date")),
                    array("column"         => "reg_desc",
                           "required"      => 1,
                           "title"         => DESCRIPTION,
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),
                    array("column"         => "invoice_id",
                           "required"      => 0,
                           "title"         => INVOICE,
                           "type"          => "TEXT",
                           "link_to_parent"=> 2,
                           "parent_op"     => "client_invoice",                           
                           "size"          => 5,
                           "maxlength"     => 255),
                    array("column"         => "reg_bill",
                           "required"      => 0,
                           "title"         => DEBIT,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 255,
                           "append"        => "(0.00)",
                           "default"       => "0.00"),
                    array("column"         => "reg_payment",
                           "required"      => 0,
                           "title"         => CREDIT,
                           "type"          => "TEXT",
                           "size"          => 10,
                           "maxlength"     => 255,
                           "append"        => "(0.00)",
                           "default"       => "0.00"));

      if ($submit||$do=="add"||$do=="edit"){
         if (!date_check($reg_date))
         {
            $oops .= "[".ERROR."] ".REGDATEINVALID."<br>";
         }
         $reg_date = (date_check($reg_date)) ? date_to_stamp($reg_date) : 0 ;
      }

      $select_sql = "SELECT reg_id,
                            client_id,
                            reg_date,
                            reg_desc,
                            invoice_id,
                            reg_bill,
                            reg_payment FROM $db_table ";

      $insert_sql = "INSERT INTO $db_table (reg_id,
                                            client_id,
                                            reg_date,
                                            reg_desc,
                                            invoice_id,
                                            reg_bill,
                                            reg_payment) VALUES (NULL,
                                                                 '$client_id',
                                                                 '$reg_date',
                                                                 '$reg_desc',
                                                                 '$invoice_id',
                                                                 '$reg_bill',
                                                                 '$reg_payment')";
      $update_sql = "UPDATE $db_table SET client_id='$client_id',
                                          reg_date='$reg_date',
                                          reg_desc='$reg_desc',
                                          invoice_id='$invoice_id',
                                          reg_bill='$reg_bill',
                                          reg_payment='$reg_payment' WHERE reg_id='$reg_id'";

      $delete_sql = array("DELETE FROM $db_table WHERE reg_id='$reg_id'");
?>