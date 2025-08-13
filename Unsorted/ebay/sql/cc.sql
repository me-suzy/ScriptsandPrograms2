/*	$Id: cc.sql,v 1.3 1999/02/21 02:52:40 josh Exp $	*/
/*
** cc.sql
**
** Create tablespace for monitoring data
*/
create tablespace cc
	datafile '/oracle01/ebay/oradata/cc.dbf'
	size 10M
	autoextend off;

alter tablespace cc
add datafile '/oracle/rdata07/ebay/oradata/cca.dbf'
size 10M;
