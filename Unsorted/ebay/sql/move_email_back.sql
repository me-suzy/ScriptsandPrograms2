/*	$Id: move_email_back.sql,v 1.3 1999/02/21 02:54:44 josh Exp $	*/
#!/bin/sh
sqlplus -s ebayqa/pipsky <<!
declare
 cursor c1 is
  select id,email
  from   ebay_users a
  where  exists(select 1
  from   ebay_user_info b
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
  update ebay_user_info
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
!

