#!/bin/sh
sqlplus -s scott/haw98 <<!
declare
 ouser varchar2(50);
 nuser varchar2(50);
 uid number;
 eru_count number;
 cursor c1 is
  select id,alias
  from   ebay_user_past_aliases
  where  aliasflag = '1';
begin
 open c1;
 loop
  fetch c1 into uid,ouser;
  exit when c1%NOTFOUND;
  select userid
  into   nuser
  from   ebay_users
  where  id = uid;
  eru_count := 0;
  begin
   select count(1)
   into   eru_count
   from   ebay_renamed_users
   where  fromuserid = ouser
   and    touserid = nuser;
  exception
   when NO_DATA_FOUND then
    eru_count := 0;
  end;
  if eru_count = 0 then
   insert into ebay_renamed_users(touserid,fromuserid)
   values(nuser,ouser);
  end if;
  commit;
 end loop;
end;
/
!
