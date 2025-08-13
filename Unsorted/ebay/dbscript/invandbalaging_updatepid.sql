set echo off;
set ver off;
set feedback off;
update ebay_inv_and_balaging_state set pid='0' where  invoice_time= to_date('&1', 'yyyy-mm-dd-hh24:mi:ss') and start_id= &2 and end_id= &3 and program_type= &4;
exit

 