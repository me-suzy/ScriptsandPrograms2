#!/bin/sh
sqlplus -s scott/tiger <<!
declare
 c1 cursor is
  select id
  from ebay_items_to_archive;
 itemid number;
begin
 open c1;
 loop
  fetch c1 into itemid;
  exit when c1%NOTFOUND;
  delete from ebay_bids
  where item_id = itemid and marketplace = 0;
  delete from ebay_item_desc
  where id = itemid and marketplace = 0;
  delete from ebay_item_info
  where id = itemid and marketplace = 0;
  delete from ebay_items
  where id = itemid and marketplace = 0;
  commit;
 end loop;
end;
/
!
