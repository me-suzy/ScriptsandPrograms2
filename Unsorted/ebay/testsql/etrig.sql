/*	$Id: etrig.sql,v 1.3 1999/02/21 02:56:59 josh Exp $	*/
create or replace trigger ebay_email_synch
before update
on ebay_users
for each row
begin
 if :old.email != :new.email then
  update ebay_user_info
  set    email = :new.email
  where  id = :new.id;
 end if;
end;
/
