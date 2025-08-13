/*	$Id: add01.sql,v 1.2 1999/02/21 02:55:36 josh Exp $	*/
/*
** add01.sql
**
** Create tablespace for ad data
*/
create tablespace tadd01
	datafile '/oracle01/ebay/oradata/oradata/test/tadd01.dbf'
	size 10M
	autoextend off;
