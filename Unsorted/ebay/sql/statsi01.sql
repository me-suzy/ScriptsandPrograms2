/*	$Id: statsi01.sql,v 1.2 1999/02/21 02:55:06 josh Exp $	*/
/*
** statsi01.sql
**
** Create tablespace for statistics index
*/

create tablespace statsi01
	datafile '/oracle07/ebay/oradata/statsi01.dbf'
	size 20M 
	autoextend on next 10M;
