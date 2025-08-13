/*	$Id: bizdevd01.sql,v 1.2 1999/02/21 02:52:34 josh Exp $	*/
/*
** bizdevd01.sql
**
** Create tablespace for bizdev data
*/
create tablespace bizdevd01
	datafile '/oracle03/ebay/oradata/bizdevd01.dbf'
	size 700M
	autoextend on next 100M;
