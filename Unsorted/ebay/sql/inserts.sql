/*	$Id: inserts.sql,v 1.2 1999/02/21 02:54:36 josh Exp $	*/
rem
rem  file:  inserts.sql
rem  location:  /orasw/dba/CC1
rem  Called from ins_cc1 shell script.
rem  New entries must be made here every time a new database
rem  is added to the system.
rem
set verify off
@insert_all
@space_watch
@extent_watcher
