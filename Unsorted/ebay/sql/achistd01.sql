/*	$Id: achistd01.sql,v 1.2 1999/02/21 02:52:10 josh Exp $	*/
/*
** achistoryd01.sql
**
** Create tablespace for statistics data
*/

create tablespace achistoryd01
	datafile '/oracle07/ebay/oradata/achistoryd01.dbf'
	size 700M 
	autoextend on next 50M;

/* move tablespace */
alter tablespace achistoryd01 offline normal;

/* copy file */
cp /oracle07/ebay/oradata/achistoryd01.dbf /oracle-items/ebay/oradata/achistoryd01.dbf

alter tablespace achistoryd01
  rename datafile '/oracle07/ebay/oradata/achistoryd01.dbf'
  to '/oracle-items/ebay/oradata/achistoryd01.dbf';


/* for Dean. Temporary table space */

create tablespace summary
	datafile '/oracle12/ebay/oradata/summary.dat' size 200M
	autoextend on next 50M;

create tablespace summaryi01
	datafile '/oracle05/ebay/oradata/summaryi01.dbf' size 100M
	autoextend on next 50M;
