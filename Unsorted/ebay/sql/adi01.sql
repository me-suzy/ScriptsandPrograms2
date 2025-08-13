/*	$Id: adi01.sql,v 1.2 1999/02/21 02:52:12 josh Exp $	*/
/*
** adi01.sql
**
** Create tablespace for ad indices
*/
create tablespace adi01
	datafile '/oracle05/ebay/oradata/adi01.dbf'
	size 50M
	autoextend on next 10M;
