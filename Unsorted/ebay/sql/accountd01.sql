/*	$Id: accountd01.sql,v 1.2 1999/02/21 02:52:07 josh Exp $	*/
/*
** accountd01.sql
**
** Create tablespace for account data
*/
create tablespace accountd01
	datafile '/oracle02/ebay/oradata/accountd01.dbf'
	size 800M
	autoextend off;
