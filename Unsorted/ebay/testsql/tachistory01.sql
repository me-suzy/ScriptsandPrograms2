/*	$Id: tachistory01.sql,v 1.2 1999/02/21 02:57:07 josh Exp $	*/
/*
** tachistory01.sql
**
** Create tablespace for historical data
*/
create tablespace tachist01
	datafile '/oracle01/ebay/oradata/oradata/test/tachist01.dbf'
	size 10M
	autoextend off;
