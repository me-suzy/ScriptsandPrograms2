set echo off;
set ver off;
set head off;
set pagesize 0;
select pid from ebay_inv_and_balaging_state where invoice_time= to_date('&1', 'yyyy-mm-dd-hh24:mi:ss') and start_id = &2 and end_id= &3 and program_type=&4;
exit
