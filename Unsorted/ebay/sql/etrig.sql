/*	$Id: etrig.sql,v 1.3 1999/02/21 02:54:21 josh Exp $	*/
/* trigger to populate ebay_user_info field with
email from ebay_users for interim period until all
cgis have the emailmove dll */


create or replace trigger ebay_email_synch
before update
on ebay_users
for each row
begin
 if :old.email != :new.email or :old.email is null then
  update ebay_user_info
  set    email = :new.email
  where  id = :new.id;
 end if;
end;
/

