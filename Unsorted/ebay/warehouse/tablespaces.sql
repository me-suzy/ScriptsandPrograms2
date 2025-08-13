/*	$Id: tablespaces.sql,v 1.2 1999/02/21 02:57:50 josh Exp $	*/
create tablespace ITEMSD01
datafile '/oracle04/ebay/oradata/test/itemsd01.dbf' size 751M
autoextend on next 100M
/
create tablespace ITEMSI01
datafile '/oracle05/ebay/oradata/test/itemsi01.dbf' size 201M
autoextend on next 10M
/
create tablespace CATEGORYD01
datafile '/oracle05/ebay/oradata/test/categoryd01.dbf' size 6M
autoextend on next 1M
/
create tablespace CATEGORYI01
datafile '/oracle06/ebay/oradata/test/categoryi01.dbf' size 6M
autoextend on next 1M
/
create tablespace ACCOUNTSD01
datafile '/oracle05/ebay/oradata/test/accountsd01.dbf' size 701M
autoextend on next 100M
/
create tablespace ACCOUNTSI01
datafile '/oracle04/ebay/oradata/test/accountsi01.dbf' size 201M
autoextend on next 100M
/
create tablespace USERSD01
datafile '/oracle06/ebay/oradata/test/usersd01.dbf' size 101M
autoextend on next 10M
/
create tablespace USERSI01
datafile '/oracle04/ebay/oradata/test/usersi01.dbf' size 51M
autoextend on next 10M
/
create tablespace USERSD02
datafile '/oracle05/ebay/oradata/test/usersd02.dbf' size 6M
autoextend on next 2M
/
create tablespace USERSI02
datafile '/oracle06/ebay/oradata/test/usersi02.dbf' size 6M
autoextend on next 2M
/
create tablespace HISTORYD01
datafile '/oracle05/ebay/oradata/test/historyd01.dbf' size 501M
autoextend on next 100M
/
create tablespace HISTORYI01
datafile '/oracle06/ebay/oradata/test/historyi01.dbf' size 101M
autoextend on next 50M
/
create tablespace REGDATESD01
datafile '/oracle04/ebay/oradata/test/regdatesd01.dbf' size 11M
autoextend on next 1M
/
create tablespace REGDATESI01
datafile '/oracle05/ebay/oradata/test/regdatesi01.dbf' size 11M
autoextend on next 1M
/
create tablespace CUSTOMERD01
datafile '/oracle06/ebay/oradata/test/customerd01.dbf' size 21M
autoextend on next 1M
/
