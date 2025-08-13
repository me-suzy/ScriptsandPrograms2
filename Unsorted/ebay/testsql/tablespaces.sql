/*	$Id: tablespaces.sql,v 1.3 1999/02/21 02:57:04 josh Exp $	*/
/* tablespaces */

/* for partner and cobrand related tables */

create tablespace tpartd01
	datafile '/oracle01/ebay/oradata/oradata/test/tpartd01.dbf'
	size 10M
	autoextend off;

create tablespace tparti01
	datafile '/oracle01/ebay/oradata/oradata/test/tparti01.dbf'
	size 10M
	autoextend off;

vxmkcdev -h 2k -s 100m /oracle02/ebay/oradata/oradata/test/tnotesd01.dbf

create tablespace tnotesd01
	datafile '/oracle02/ebay/oradata/oradata/test/tnotesd01.dbf'
	size 100M
	autoextend off;

create tablespace titemd02
	datafile '/oracle02/ebay/oradata/oradata/test/titemd02.dbf'
	size 20M
	autoextend off;

create tablespace titemi02
	datafile '/oracle02/ebay/oradata/oradata/test/titemi02.dbf'
	size 20M
	autoextend off;
