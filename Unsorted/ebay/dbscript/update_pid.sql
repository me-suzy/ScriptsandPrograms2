set echo off;
set ver off;
set feedback off;
update ebay_eoa_state set pid='0' where  from_time= to_date('&1', 'yyyy-mm-dd-hh24:mi:ss') and end_time = to_date('&2', 'yyyy-mm-dd-hh24:mi:ss');
exit
