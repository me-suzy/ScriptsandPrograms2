/*	$Id: extent_watcher.sql,v 1.2 1999/02/21 02:54:23 josh Exp $	*/
rem
rem  file: ext_watcher.sql
rem  location:  /orasw/dba/CC1
rem  Called from inserts.sql
rem
rem  ...like some watcher of the skies
rem  when a new planet swims into his ken (Keats)
rem
column	db_nm		format A8
column	ts			format A18
column	seg_owner	format a14
column	seg_name	format a32
column	seg_type	format a8
column	blocks		format 99999999
column	week4		format 999 heading "4Wks|Ago"
column	week3		format 999 heading "3Wks|Ago"
column	week2		format 999 heading "2Wks|Ago"
column	week1		format 999 heading "1Wk|Ago"
column	today		format 999
column	change		format 999

set pagesize 60 linesize 132
break on db_nm skip 2 on ts skip 1 on seg_owner
ttitle center 'Segments whose extent count is over 10' skip 2

select
   extents.db_nm,
   extents.ts,
   extents.seg_owner,
   extents.seg_name,
   extents.seg_type,
   max(decode(extents.check_date, trunc(sysdate),
         blocks,0)) blocks,
   max(decode(extents.check_date, trunc(sysdate-28),
         extents,0)) week1,
   max(decode(extents.check_date, trunc(sysdate-21),
         extents,0)) week2,
   max(decode(extents.check_date, trunc(sysdate-14),
         extents,0)) week3,
   max(decode(extents.check_date, trunc(sysdate-7),
         extents,0)) week4,
   max(decode(extents.check_date, trunc(sysdate),
         extents,0)) today,
   max(decode(extents.check_date, trunc(sysdate),
         extents,0)) -
   max(decode(extents.check_date, trunc(sysdate-28),
         extents,0)) change
from extents
where exists /*did this segment show up today during the inserts?*/
   (select 'x' from extents x
   where x.db_nm = extents.db_nm
   and x.ts = extents.ts
   and x.seg_owner = extents.seg_owner
   and x.seg_name = extents.seg_name
   and x.seg_type = extents.seg_type
   and x.check_date = trunc(sysdate))
group by
   extents.db_nm,
   extents.ts,
   extents.seg_owner,
   extents.seg_name,
   extents.seg_type
order by extents.db_nm, extents.ts, decode (
   max(decode(extents.check_date,trunc(sysdate),
         extents,0)) -
   max(decode(extents.check_date, trunc(sysdate-28),
        extents,0)),0,-9999,
   max(decode(extents.check_date,trunc(sysdate),
         extents,0)) -
   max(decode(extents.check_date, trunc(sysdate-28),
         extents,0))) desc,
   max(decode(extents.check_date,trunc(sysdate),
         extents,0)) desc

spool extent_watcher.lst
/
spool off
