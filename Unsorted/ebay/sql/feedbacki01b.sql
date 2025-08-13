/*	$Id: feedbacki01b.sql,v 1.2 1999/02/21 02:54:31 josh Exp $	*/
/*
 * feedbacki01a.sql
 *
 * Extend feedbacki01a 
** obsolete - see tablespaces.sql
**
alter tablespace feedbacki01
	add datafile '/oracle01/ebay/oradata/feedbacki01b.dbf'
	size 10M
	autoextend off;
*/
