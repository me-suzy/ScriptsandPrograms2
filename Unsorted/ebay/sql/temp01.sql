/*	$Id: temp01.sql,v 1.2 1999/02/21 02:55:08 josh Exp $	*/
/*
 * Useri01a.sql
 *
 * Extend useri01
*/

alter tablespace temp
	add datafile '/oracle02/ebay/oradata/tempebay01.dbf'
	size 50M
	autoextend off;
