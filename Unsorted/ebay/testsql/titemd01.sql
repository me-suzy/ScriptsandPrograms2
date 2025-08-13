/*	$Id: titemd01.sql,v 1.2 1999/02/21 02:57:17 josh Exp $	*/
/*
** itemd01.sql
**
** Create tablespace for item data
*/
create tablespace titemd01
	datafile '/oracle02/ebay/oradata/titemd01.dbf'
	size 80M
	autoextend off;
