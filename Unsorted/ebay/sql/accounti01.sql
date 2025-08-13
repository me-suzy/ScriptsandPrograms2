/*	$Id: accounti01.sql,v 1.2 1999/02/21 02:52:09 josh Exp $	*/
/*
** accounti01.sql
**
** Create tablespace for account indicies
*/
create tablespace accounti01
	datafile '/oracle01/ebay/oradata/accounti01.dbf'
	size 100M
	autoextend off;
