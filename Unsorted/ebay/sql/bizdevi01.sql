/*	$Id: bizdevi01.sql,v 1.2 1999/02/21 02:52:35 josh Exp $	*/
/*
** bizdevi01.sql
**
** Create tablespace for bizdev indicies
*/
create tablespace bizdevi01
	datafile '/oracle05/ebay/oradata/bizdevi01.dbf'
	size 100M
	autoextend on next 50M;
