/*	$Id: itemi01.sql,v 1.2 1999/02/21 02:54:41 josh Exp $	*/
/*
** itemi01.sql
**
** Create tablespace for item indicies
*/
create tablespace itemi01
	datafile '/oracle05/ebay/oradata/itemi01.dbf'
	size 20M
	autoextend off;
