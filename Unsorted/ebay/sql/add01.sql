/*	$Id: add01.sql,v 1.2 1999/02/21 02:52:11 josh Exp $	*/
/*
** add01.sql
**
** Create tablespace for ad data
*/
create tablespace add01
	datafile '/oracle03/ebay/oradata/add01.dbf'
	size 50M
	autoextend on next 10M;
