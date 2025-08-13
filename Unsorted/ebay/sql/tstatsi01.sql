/*	$Id: tstatsi01.sql,v 1.2 1999/02/21 02:55:12 josh Exp $	*/
/*
** tstatsi01.sql
**
** Create tablespace for statistics index
*/

create tablespace tstatsi01
	datafile '/oracle01/ebay/oradata/oradata/test/tstatsi01.dbf'
	size 10M 
	autoextend off;
