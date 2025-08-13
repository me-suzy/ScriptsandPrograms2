/*	$Id: titemi01.sql,v 1.2 1999/02/21 02:57:18 josh Exp $	*/
/*
** itemi01.sql
**
** Create tablespace for item indicies
*/
create tablespace titemi01
	datafile '/oracle01/ebay/oradata/titemi01.dbf'
	size 10M
	autoextend off;
