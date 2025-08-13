/*	$Id: tbidd01.sql,v 1.2 1999/02/21 02:57:08 josh Exp $	*/
/*
** bidd01.sql
**
** Create tablespace for bid data
*/
create tablespace tbidd01
	datafile '/oracle02/ebay/oradata/tbidd01.dbf'
	size 10M
	autoextend off;
