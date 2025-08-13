/*	$Id: buildall.sql,v 1.3 1999/02/21 02:52:37 josh Exp $	*/
spool C:\ORANT\DATABASE\billing\build.log
SET TERMOUT OFF
SET ECHO OFF
connect internal@2:orcl
@@build_db.sql

@@billing.sql
Rem connect internal@2:billing
Rem @@demo.sql

spool off

