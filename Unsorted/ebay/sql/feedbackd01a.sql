/*	$Id: feedbackd01a.sql,v 1.2 1999/02/21 02:54:27 josh Exp $	*/
/*
 * feedbackd01a.sql
 *
 * Extend feedbackd01a 
** obsolete - see tablespaces.sql
**
alter tablespace feedbackd01
	add datafile '/oracle02/ebay/oradata/feedbackd01a.dbf'
	size 20M
	autoextend off;
*/
