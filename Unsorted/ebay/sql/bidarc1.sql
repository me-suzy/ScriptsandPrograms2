/*	$Id: bidarc1.sql,v 1.2 1999/02/21 02:52:30 josh Exp $	*/
create tablespace bidarc1
	datafile '/oracle12/ebay/oradata/bidarc1.dbf'
	size 100M 
	autoextend on next 20M;

/* not created yet!!! Decide on which disk to put this */

create tablespace bidarci1
	datafile '/oracle09/ebay/oradata/bidarci1.dbf'
	size 300M
	autoextend on next 20M;
