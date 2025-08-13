/*	$Id: discoverd01.sql,v 1.2 1999/02/21 02:52:55 josh Exp $	*/
/*
** discoverd01.sql
**
** Create tablespace for user data
*/
create tablespace discover01
	datafile '/oracle02/ebay/oradata/discover01.dbf'
	size 10M
	autoextend off;
