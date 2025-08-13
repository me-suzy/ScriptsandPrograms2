/*	$Id: bbd01.sql,v 1.2 1999/02/21 02:52:24 josh Exp $	*/
/*
** bbd01.sql
**
** Create tablespace for bulletin boards
*/
create tablespace bbd01
	datafile '/oracle02/ebay/oradata/bbd01.dbf'
	size 10M
	autoextend off;
