#!/bin/sh
# mail list of bad eoa runs for sysdate -1
rm -f badeoa
sqlplus -s ebayqa/pipsky <<! >badeoa
set hea off
set pagesize 0
column col1 format a21 
column col2 format a21
select 'end-of-auction.sh' , to_char(from_time,'yyyy-mm-dd-hh24:mi:ss') col1, to_char(end_time,'yyyy-mm-dd-hh24:mi:ss') col2 from ebay_eoa_state
where started < sysdate-.5 and pid <> 'YES'
/
select 'today date is:' from dual 
/
select sysdate from dual
/
!
if [ -s badeoa ]
then
/usr/ucb/mail -s "EOA to ReRun" inna@ebay.com tini@ebay.com <badeoa
fi
