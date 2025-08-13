/*	$Id: space_watch.sql,v 1.2 1999/02/21 02:55:03 josh Exp $	*/
rem
rem  file:  space_watcher.sql
rem  location:  /orasw/dba/CC1
rem  Called from inserts.sql
rem
rem  ...like some watcher of the skies
rem  when a new planet swims into his ken (keats)
rem
column db_nm format A8
column ts format A20
column week4 format 999 heading "4Wks|Ago"
column week3 format 999 heading "3Wks|Ago"
column week2 format 999 heading "2Wks|Ago"
column week1 format 999 heading "1Wk|Ago"
column today format 999
column change format 999

set pagesize 60
break on db_nm skip 2
ttitle center 'Tablespaces whose PercentFree values have -
decreased 5 pct this month' skip 2

select
   spaces.db_nm,
   spaces.ts,
   max(decode(spaces.check_date, trunc(sysdate-28),
         round(100*sum_free_blocks/sum_file_blocks),0)) week1,
   max(decode(spaces.check_date, trunc(sysdate-21),
         round(100*sum_free_blocks/sum_file_blocks),0)) week2,
   max(decode(spaces.check_date, trunc(sysdate-14),
         round(100*sum_free_blocks/sum_file_blocks),0)) week3,
   max(decode(spaces.check_date, trunc(sysdate-7),
         round(100*sum_free_blocks/sum_file_blocks),0)) week4,
   max(decode(spaces.check_date, trunc(sysdate),
         round(100*sum_free_blocks/sum_file_blocks),0)) today,
   max(decode(spaces.check_date, trunc(sysdate),
         round(100*sum_free_blocks/sum_file_blocks),0)) -
   max(decode(spaces.check_date, trunc(sysdate-28),
         round(100*sum_free_blocks/sum_file_blocks),0)) change
from spaces, files_ts_view ftv
where spaces.db_nm = ftv.db_nm          /*same database name*/
and spaces.ts = ftv.ts                  /*same tablespace name*/
and spaces.check_date = ftv.check_date  /*same check date*/
and exists                              /*does ts still exist?*/
   (select 'x' from spaces x
   where x.db_nm = spaces.db_nm
   and x.ts = spaces.ts
   and x.check_date = trunc(sysdate))
group by
   spaces.db_nm,
   spaces.ts
having               /*has percentfree dropped 5 pct in 4 weeks?*/
(  max(decode(spaces.check_date, trunc(sysdate),
         round(100*sum_free_blocks/sum_file_blocks),0)) -
   max(decode(spaces.check_date, trunc(sysdate-28),
         round(100*sum_free_blocks/sum_file_blocks),0))
   >5    )
or                   /*is percentfree less than 10?*/
( max(decode(spaces.check_date, trunc(sysdate),
                round(100*sum_free_blocks/sum_file_blocks),0)) <10)
order by spaces.db_nm, decode(
   max(decode(spaces.check_date,trunc(sysdate),
         round(100*sum_free_blocks/sum_file_blocks),0)) -
   max(decode(spaces.check_date, trunc(sysdate-28),
         round(100*sum_free_blocks/sum_file_blocks),0)),0,9999,
   max(decode(spaces.check_date,trunc(sysdate),
         round(100*sum_free_blocks/sum_file_blocks),0)) -
   max(decode(spaces.check_date, trunc(sysdate-28),
         round(100*sum_free_blocks/sum_file_blocks),0))),
   max(decode(spaces.check_date,trunc(sysdate),
         round(100*sum_free_blocks/sum_file_blocks),0))

spool space_watcher.lst
/
