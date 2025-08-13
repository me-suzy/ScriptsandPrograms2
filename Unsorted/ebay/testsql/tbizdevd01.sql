/*	$Id: tbizdevd01.sql,v 1.2 1999/02/21 02:57:11 josh Exp $	*/
/*
** bizdevd01.sql
**
** Create tablespace for bizdev data
*/
create tablespace tbizdevd01
	datafile '/oracle01/ebay/oradata/oradata/test/tbizdevd01.dbf'
	size 10M
	autoextend off;
