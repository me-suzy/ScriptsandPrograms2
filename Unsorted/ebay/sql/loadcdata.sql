/*	$Id: loadcdata.sql,v 1.2 1999/02/21 02:54:42 josh Exp $	*/
declare
 cursor c1 is
 select distinct user_id
 from   ebay_historical_data
 order by user_id;
 bucketno number;
 ldone number;
 prevbucketno number;
 ccount number;
 uname varchar2(100);
 usrid number;
 it_bought number;
 it_sold number;
 dol_bought number;
 dol_sold number;
 rdate date;
 pstart date;
 lpstart date;
 nper date;
begin
 open c1;
 loop
  fetch c1 into usrid;
  exit when c1%NOTFOUND;
  begin
   select userid
   into   uname
   from   ebay_users
   where id = usrid;
  exception
   when NO_DATA_FOUND then
    uname := 'NOTFOUND';
  end;
  if uname != 'NOTFOUND' then
   begin
    begin
     select min(regdate)
     into   rdate
     from   ebay_reg_dates
     where  custname = uname;
    exception when NO_DATA_FOUND then
     select min(period_start)
     into   rdate
     from   ebay_historical_data
     where  user_id = usrid;
    end;
    if rdate is null then
     select min(period_start)
     into   rdate
     from   ebay_historical_data
     where  user_id = usrid;
    end if;
    declare
     cursor c2 is
     select period_start,
            nvl(floor(months_between(period_start,rdate)),-999),
	    items_bought,
            dollars_bought,
	    items_sold,
            dollars_sold
     from   ebay_historical_data
     where  user_id = usrid
     order by period_start;
    begin
     open c2;
     prevbucketno := -9999;
     loop
      fetch c2 into pstart,bucketno,it_bought,dol_bought,it_sold,dol_sold;
      exit when c2%NOTFOUND;
      if bucketno = -999 then
       insert into ebay_period_summary_audit(userid,regdate,period_start)
       values (usrid,rdate,pstart);
      end if;
-- set customer count variable
      if prevbucketno = bucketno then
       ccount := 0;
      else
       ccount := 1;
      end if;
      prevbucketno := bucketno;
      update ebay_period_summary
      set dollars_bought = nvl(dollars_bought,0) + dol_bought,
          dollars_sold = nvl(dollars_sold,0) + dol_sold,
          items_bought = nvl(items_bought,0) + it_bought,
          items_sold = nvl(items_sold,0) + it_sold,
	  custcount = nvl(custcount,0) + ccount
      where bucket = bucketno;
      if SQL%NOTFOUND then
       insert into ebay_period_summary(bucket,
                                       dollars_bought,
                                       dollars_sold,
                                       items_bought,
                                       items_sold,
                                       custcount)
       values(bucketno,dol_bought,dol_sold,it_bought,it_sold,1);
      end if;
      commit;
      lpstart := pstart;
     end loop;
     close c2;
     if bucketno > 0 then
-- create user buckets out past end of individual history to end of Aug 97
      ldone := 0;
      nper := lpstart;
      bucketno := prevbucketno;
      loop
       exit when ldone = 1;
       nper := add_months(nper,1);
       bucketno := bucketno + 1;
       if nper >= to_date('31-AUG-97') then
        ldone := 1;
       else
        update ebay_period_summary
        set custcount = nvl(custcount,0) + 1
        where bucket = bucketno;
        if SQL%NOTFOUND then
         insert into ebay_period_summary(bucket,
                                         dollars_bought,
                                         dollars_sold,
                                         items_bought,
                                         items_sold,
                                         custcount)
         values(bucketno,0,0,0,0,1);
        end if;
       end if;
      end loop;
      commit;
     end if;
    end;
   exception
    when NO_DATA_FOUND then
     null;
   end;
  end if;
 end loop;
end;
/
