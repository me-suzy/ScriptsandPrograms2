/*	$Id: feedbacki01a.sql,v 1.2 1999/02/21 02:54:30 josh Exp $	*/
/*
 * feedbacki01a.sql
 *
 * Extend feedbacki01a 
** obsolete - see tablespaces.sql
**

alter tablespace feedbacki01
	add datafile '/oracle01/ebay/oradata/feedbacki01a.dbf'
	size 10M
	autoextend off;
*/
