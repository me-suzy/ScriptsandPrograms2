/*	$Id: taccountd01.sql,v 1.2 1999/02/21 02:57:05 josh Exp $	*/
/*
** accountd01.sql
**
** Create tablespace for account data
*/
create tablespace taccountd01
	datafile '/oracle02/ebay/oradata/taccountd01.dbf'
	size 100M
	autoextend off;
