#!/bin/sh
TOOLS=/oracle04/export/home/oracle7
. $TOOLS/bkup_kit/backup.env
cd /export/home/oracle7
sqlplus -s scott/tiger <<! > /export/home/oracle7/early.out
declare
 cursor c1 is
  select userid,regdate
  from   ebay_reg_dates;
 uid number;
 rdate date;
 pstart date;
begin
 open c1;
 loop
  fetch c1 into uid,rdate;
  exit when c1%NOTFOUND;
  pstart := rdate;
  begin
   select min(period_start)
   into   pstart
   from   ebay_historical_data
   where  user_id = uid;
  exception
   when NO_DATA_FOUND then
    pstart := rdate;
  end;
  update ebay_user_info
  set    creation = least(nvl(pstart,rdate),rdate)
  where  id = uid;
  commit;
 end loop;
 commit;
end;
/
!
