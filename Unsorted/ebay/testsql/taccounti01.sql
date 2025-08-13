/*	$Id: taccounti01.sql,v 1.2 1999/02/21 02:57:06 josh Exp $	*/
/*
** accounti01.sql
**
** Create tablespace for account indicies
*/
create tablespace taccounti01
	datafile '/oracle01/ebay/oradata/taccounti01.dbf'
	size 50M
	autoextend off;
