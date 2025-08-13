/*	$Id: statsd01.sql,v 1.2 1999/02/21 02:55:05 josh Exp $	*/
/*
** statsd.sql
**
** Create tablespace for statistics data
*/

create tablespace statsd01
	datafile '/oracle07/ebay/oradata/statsd01.dbf'
	size 50M 
	autoextend on next 20M;
