#!/bin/sh
###################################################################
# filename: arcitems.sh
#  purpose: to move rows from item related tables to archive tables
#  created: 22-JAN-98 Dean Neufeld
###################################################################
TOOLS=/oracle04/export/home/oracle7
. $TOOLS/bkup_kit/backup.env
##########################################################
# clear and rebuild archive list
##########################################################
sqlplus -s scott/haw98 <<!
truncate table ebay_items_to_archive
/
truncate table ebay_failed_copy_items
/
insert into ebay_items_to_archive
select id
from   ebay_items
where  sale_end < (sysdate - 45)
/
!
##########################################################
# run program to archive to ebay_item_desc_arc
##########################################################
/export/home/tini/ebay/ArchiveDescription/ArchiveDesc
##########################################################
# archive/validate/delete items in list
##########################################################
sqlplus -s scott/haw98 <<!
declare
 cursor c1 is
 select id
 from   ebay_items_to_archive;
 iid number;
 valid_count number;
 temp_count number;
 bid_count number;
 desc_count number;
begin
 open c1;
 loop
  fetch c1 into iid;
  exit when c1%NOTFOUND;
--------------------------------------------
-- Archive
--------------------------------------------
  insert into ebay_item_info_arc
  select * from ebay_item_info
  where id = iid
  and marketplace = 0;
  insert into ebay_bids_arc
  select * from ebay_bids
  where item_id = iid
  and marketplace = 0;
  insert into ebay_items_arc
  select * from ebay_items
  where id = iid
  and marketplace = 0;
--------------------------------------------
-- Validate
--------------------------------------------
  valid_count := 0;
  select count(1)
  into   temp_count
  from   ebay_item_info_arc
  where  id = iid
  and    marketplace = 0;
  valid_count := valid_count + temp_count;
  select count(1)
  into   temp_count
  from   ebay_bids_arc
  where  item_id = iid
  and    marketplace = 0;
  select count(1)
  into   bid_count
  from   ebay_bids
  where  item_id = iid
  and    marketplace = 0;
  if temp_count = bid_count then
   valid_count := valid_count + 1;
  end if;
  select count(1)
  into   temp_count
  from   ebay_items_arc
  where  id = iid
  and    marketplace = 0;
  valid_count := valid_count + temp_count;
  desc_count := 0;
  select count(1)
  into desc_count
  from ebay_item_desc
  where id = iid
  and marketplace = 0;
  if desc_count = 0 then
   valid_count := valid_count + 1;
  else
   select count(1)
   into   temp_count
   from ebay_item_desc_arc
   where id = iid
   and marketplace = 0;
   valid_count := valid_count + temp_count;
  end if;
  if valid_count = 4 then
--------------------------------------------
-- All tables successfully archived, delete
--------------------------------------------
   delete from ebay_item_info
   where id = iid
   and marketplace = 0;
   delete from ebay_bids
   where item_id = iid
   and marketplace = 0;
   if desc_count = 1 then
    delete from ebay_item_desc
    where id = iid
    and marketplace = 0;
   end if;
   delete from ebay_items
   where id = iid
   and marketplace = 0;
  else
--------------------------------------------
-- Unarchive unsuccessful archives
--------------------------------------------
   begin
    delete from ebay_item_info_arc
    where id = iid
    and marketplace = 0;
   exception
    when NO_DATA_FOUND then
     null;
   end;
   begin
    delete from ebay_bids_arc
    where item_id = iid
    and marketplace = 0;
   exception
    when NO_DATA_FOUND then
     null;
   end;
   begin
    delete from ebay_items_arc
    where id = iid
    and marketplace = 0;
   exception
    when NO_DATA_FOUND then
     null;
   end;
   begin
    delete from ebay_item_desc_arc
    where id = iid
    and marketplace = 0;
   exception
    when NO_DATA_FOUND then
     null;
   end;
--------------------------------------------
-- Save unarchived id for report
--------------------------------------------
   insert into ebay_failed_copy_items(id)
   values(iid);
  end if;
  commit;
 end loop;
 commit;
end;
/
!
##########################################################
# check failure count and run failure report if count > 0
##########################################################
failcount=`sqlplus -s scott/haw98 <<!
set embedded on
set heading off
set tab off
select to_char(count(1))
from ebay_failed_copy_items
/
!`
if [ $failcount -gt 0 ]
then
sqlplus -s scott/haw98 <<! > /oracle04/export/home/oracle7/archivescripts/failedcopy.txt
ttitle center "ITEMS WHICH FAILED ARCHIVING" skip 2
column id format 9999999999 heading "ITEM ID"
select id
from ebay_failed_copy_items
/
!
mail -s "Item archive failure" dean@ebay.com < /oracle04/export/home/oracle7/archivescripts/failedcopy.txt
mail -s "Item archive failure" tini@ebay.com < /oracle04/export/home/oracle7/archivescripts/failedcopy.txt
else
mail -s "Archive succeeded" dean@ebay.com <<!
All items archived successfully.
!
mail -s "Archive succeeded" tini@ebay.com <<!
All items archived successfully.
!
fi
