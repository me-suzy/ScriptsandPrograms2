/*	$Id: tuseri01.sql,v 1.2 1999/02/21 02:57:20 josh Exp $	*/
/*
** userd01.sql
**
** Create tablespace for user indicies
*/
create tablespace tuseri01
	datafile '/oracle01/ebay/oradata/tuseri01.dbf'
	size 60M
	autoextend off;

alter tablespace tuseri01
   add datafile '/oracle01/ebay/oradata/oradata/test/tuseri01a.dbf'
   size 10M autoextend on next 10M;
