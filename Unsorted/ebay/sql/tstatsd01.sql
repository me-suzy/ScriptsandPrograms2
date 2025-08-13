/*	$Id: tstatsd01.sql,v 1.2 1999/02/21 02:55:11 josh Exp $	*/
/*
** tstatsd01.sql
**
** Create tablespace for statistics data
*/

create tablespace tstatsd01
	datafile '/oracle01/ebay/oradata/oradata/test/tstatsd01.dbf'
	size 10M 
	autoextend off;
