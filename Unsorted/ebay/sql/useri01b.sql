/*	$Id: useri01b.sql,v 1.2 1999/02/21 02:55:17 josh Exp $	*/
/*
 * Useri01a.sql
 *
 * Extend useri01
*/

alter tablespace useri01
	add datafile '/oracle01/ebay/oradata/useri01b.dbf'
	size 40M
	autoextend off;
