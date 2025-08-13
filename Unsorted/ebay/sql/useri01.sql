/*	$Id: useri01.sql,v 1.2 1999/02/21 02:55:15 josh Exp $	*/
/*
** userd01.sql
**
** Create tablespace for user indicies
*/
create tablespace useri01
	datafile '/oracle01/ebay/oradata/useri01.dbf'
	size 60M
	autoextend off;
