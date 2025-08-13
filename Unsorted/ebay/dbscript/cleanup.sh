#!/bin/sh
TOOLS=/oracle04/export/home/oracle7
. $TOOLS/bkup_kit/backup.env
cd /export/home/dean
rm -f /export/home/oracle7/cleanup.out
sqlplus -s scott/haw98 <<!>/export/home/oracle7/cleanup.out
delete from ebay_rename_pending
where trunc(created) <= trunc(sysdate-30)
/
commit
/
declare
 cursor c1 is
  select id
  from   ebay_users a
  where  user_state = 2
  and    exists(
  select 1
  from   ebay_user_info b
  where  b.id = a.id
  and    trunc(b.creation) <= trunc(sysdate-30));
 uid number;
begin
 open c1;
 loop
  fetch c1 into uid;
  exit when c1%NOTFOUND;
  delete from ebay_user_info
  where id = uid;
  begin
   delete from ebay_user_attributes
   where user_id = uid;
  exception
   when NO_DATA_FOUND then
    null;
  end;
  delete from ebay_users
  where id = uid;
  commit;
 end loop;
 commit;
end;
/
!
