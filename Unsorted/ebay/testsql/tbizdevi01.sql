/*	$Id: tbizdevi01.sql,v 1.2 1999/02/21 02:57:12 josh Exp $	*/
/*
** bizdevi01.sql
**
** Create tablespace for bizdev indicies
*/
create tablespace tbizdevi01
	datafile '/oracle01/ebay/oradata/oradata/test/tbizdevi01.dbf'
	size 5M
	autoextend off;
