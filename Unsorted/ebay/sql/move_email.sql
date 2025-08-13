/*	$Id: move_email.sql,v 1.3 1999/02/21 02:54:43 josh Exp $	*/
#!/bin/sh
sqlplus -s ebayqa/pipsky <<!
/
declare
 cursor c1 is
  select id,email
  from   ebay_user_info a
  where  exists(select 1
  from   ebay_users b
  where  b.id = a.id
  and    b.email is null);
 usid number;
 ucount number;
 eadd varchar2(64);
begin
 open c1;
 ucount := 0;
 loop
  fetch c1 into usid,eadd;
  exit when c1%NOTFOUND;
  update ebay_users
  set    email = eadd
  where  id = usid;
  ucount := ucount + 1;
  if ucount >= 1000 then
   commit;
   ucount := 0;
  end if;
 end loop;
 commit;
end;
/
declare
 cursor c1 is
  select id
  from ebay_users
  minus
  select id
  from ebay_user_info;
 usid number;
begin
 open c1;
 loop
  fetch c1 into usid;
  exit when c1%NOTFOUND;
  update ebay_users
  set    email = userid
  where  id = usid;
  commit;
 end loop;
 commit;
end;
/
!
