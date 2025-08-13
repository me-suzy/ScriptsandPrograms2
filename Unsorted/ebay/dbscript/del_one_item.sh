#!/bin/sh
# filename: del_one_item.sh
#  created: 25-FEB-98 By Dean Neufeld
#  purpose: given item id, delete all associated item and bid rows
#    usage: del_one_item.sh itemid
iid=$1
echo $iid
sqlplus -s scott/haw98 <<!
delete from ebay_item_info
where id = $iid
and marketplace = 0
/
delete from ebay_item_desc
where id = $iid
and marketplace = 0
/
delete from ebay_bids
where item_id = $iid
/
delete from ebay_items
where id = $iid
and marketplace = 0
/
commit
/
!
