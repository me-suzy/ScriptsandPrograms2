/*	$Id: rowcache_check.sql,v 1.2 1999/02/21 02:55:00 josh Exp $	*/
rem
rem  file:  rowcache_check.sql
rem  This script queries the dictionary cache to verify adequacy
rem  of settings.
rem
rem  parameter:  database link name
rem
ttitle 'RowCache Report for '&&1 center skip 2
column parameter format A25
set pagesize 60 linesize 132 newpage 0 verify off

select
   parameter,			/*cache name*/
   count,			/*current setting*/
   usage,			/*current usage*/
   gets,			/*accesses against cache*/
   getmisses,			/*misses*/
   round(decode(gets,0,0,100*getmisses/gets),2) pctmiss
from v$rowcache
where decode(count,0,0,usage/count) > .9
or decode(gets,0,0,getmisses/gets) > .1

spool &&1._rowcache_report.lst
/
spool off
undefine 1
