/*	$Id: itemarc.sql,v 1.2 1999/02/21 02:54:37 josh Exp $	*/
create tablespace itemarc1
	datafile '/oracle12/ebay/oradata/itemarc1.dbf'
	size 150M 
	autoextend on next 20M;

create tablespace itemarc2
	datafile '/oracle12/ebay/oradata/itemarc2.dbf'
	size 50M 
	autoextend on next 10M;

create tablespace itemarc3
	datafile '/oracle12/ebay/oradata/itemarc3.dbf'
	size 150M 
	autoextend on next 50M;

create tablespace itemarci1
    datafile '/oracle05/ebay/oradata/itemarci1.dbf'
	size 100M
	autoextend on next 20M;

create tablespace itemarci2
	datafile '/oracle10/ebay/oradata/itemarci2.dbf'
	size 100M 
	autoextend on next 20M;
