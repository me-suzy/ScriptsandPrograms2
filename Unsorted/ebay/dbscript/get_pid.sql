set echo off;
set ver off;
set head off;
set pagesize 0;
select pid from ebay_eoa_state where from_time= to_date('&1', 'yyyy-mm-dd-hh24:mi:ss') and end_time = to_date('&2', 'yyyy-mm-dd-hh24:mi:ss');
exit
