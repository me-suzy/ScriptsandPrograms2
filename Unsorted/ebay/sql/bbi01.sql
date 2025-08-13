/*	$Id: bbi01.sql,v 1.2 1999/02/21 02:52:25 josh Exp $	*/
/*
** bbi01.sql
**
** Create tablespace for bb indicies
*/
create tablespace bbi01
	datafile '/oracle01/ebay/oradata/bbi01.dbf'
	size 1M
	autoextend off;
