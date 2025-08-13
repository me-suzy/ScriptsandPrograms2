/*	$Id: tbidi01.sql,v 1.2 1999/02/21 02:57:10 josh Exp $	*/
/*
** bidi01.sql
**
** Create tablespace for bid indicies
*/
create tablespace tbidi01
	datafile '/oracle01/ebay/oradata/tbidi01.dbf'
	size 5M
	autoextend off;
