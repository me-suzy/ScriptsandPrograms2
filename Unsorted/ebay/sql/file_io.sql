/*	$Id: file_io.sql,v 1.2 1999/02/21 02:54:32 josh Exp $	*/
rem  Oracle7  VERSION
rem
rem  file:  file_io.sql
rem  This is an ad hoc report against current statistics, based
rem  on the queries used for UTLBSTAT/UTLESTAT.
rem
rem  There are two queries:  the first sums the IOs at a drive level,
rem  the second at a file level.
rem
rem  NOTE:  This assumes that the drive designation is the first 5
rem      letters of the file name (ex:  /db01).  If your drive
rem      designation is a different length, change the script
rem      where noted***
rem
column drive format A5
column filename format a30
column physrds format 99999999
column physwrt format 99999999
column blk_rds format 99999999
column blk_wrt format 99999999
column total   format 99999999
set linesize 80 pagesize 60 newpage 0 feedback off
ttitle skip center "Database File Information" skip center -
"Ordered by IO per Drive" skip 2

break on report
compute sum of physrds on report
compute sum of physwrt on report
compute sum of blk_rds on report
compute sum of blk_wrt on report
compute sum of total on report

select
   substr(i.name,1,5)"DRIVE",  /*assumes a 5-letter drive name*/
   sum(x.phyrds) +
   sum(x.phywrts) "total",            /*Total IO*/
   sum(x.phyrds) "PHYSRDS",	      /*Physical Reads*/
   sum(x.phywrts) "PHYSWRT",          /*Physical Writes*/
   sum(x.phyblkrd) "BLK_RDS",         /*Block Reads*/
   sum(x.phyplkwrt) "BLK_WRT"         /*Block Writes*/
from v$filestat x, ts$ ts, v$datafile i,file$ f
where i.file#=f.file#
and ts.ts#=f.ts#
and x.file#=f.file#
group by
   substr(i.name,1,5)           /*assumes a 5-letter drive name*/
order by 2 desc

spool file_io_by_drive.lis
/
spool off

set linesize 132 pagesize 60
ttitle skip center "Database File IO Information" skip 2
clear breaks

break on drive skip 1 on report
compute sum of total on drive
compute sum of physrds on drive
compute sum of physwrt on drive
compute sum of blk_rds on drive
compute sum of blk_wrt on drive

select
   substr(i.name,1,5) "DRIVE",   /*assumes a 5-letter drive name*/
   i.name filename,
   x.phyrds +
   x.phywrts "total",                 /*Total IO*/
   x.phyrds "PHYSRDS",		      /*Physical Reads*/
   x.phywrts "PHYSWRT",		      /*Physical Writes*/
   x.phyblkrd "BLK_RDS",	      /*Block Reads*/
   x.phyblkwrt "BLK_WRT"	      /*Block Writes*/
from v$filestat x, ts$ ts, v$datafile i,file$ f
where i.file#=f.file#
and ts.ts#=f.ts#
order by 1,2

spool file_io_by_file.lis
/
spool off
