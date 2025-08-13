/*	$Id: adi01.sql,v 1.2 1999/02/21 02:55:37 josh Exp $	*/
/*
** adi01.sql
**
** Create tablespace for ad indices
*/
create tablespace tadi01
	datafile '/oracle01/ebay/oradata/oradata/test/tadi01.dbf'
	size 10M
	autoextend off;
