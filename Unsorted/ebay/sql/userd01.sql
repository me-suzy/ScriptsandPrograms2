/*	$Id: userd01.sql,v 1.2 1999/02/21 02:55:14 josh Exp $	*/
/*
** userd01.sql
**
** Create tablespace for user data
*/
create tablespace userd01
	datafile '/oracle02/ebay/oradata/userd01.dbf'
	size 60M
	autoextend off;
