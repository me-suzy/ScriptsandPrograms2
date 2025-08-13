set echo off;
set ver off;
set head off;
set pagesize 0;
select to_char(max(invoice_time), 'YYYY-MM-DD HH24:MI:SS') from ebay_inv_and_balaging_state where program_type=1;
exit
