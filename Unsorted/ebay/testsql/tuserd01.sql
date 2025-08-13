/*	$Id: tuserd01.sql,v 1.3 1999/02/21 02:57:19 josh Exp $	*/
/*
** userd01.sql
**
** Create tablespace for user data
*/
create tablespace tuserd01
	datafile '/oracle02/ebay/oradata/tuserd01.dbf'
	size 60M
	autoextend off;


