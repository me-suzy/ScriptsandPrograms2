# FishCart: an online catalog management / shopping system
# Copyright (C) 1997-2002  FishNet, Inc.

# Thanks to Glenda Snodgrass for contributing this grant list.
#
# If web server, secure server and database are all on the same box,
# you need only grant privileges to users '@localhost' in your db.??? file.
# If multiple servers will be involved, see the mysql manual for more info:
# (http://www.mysql.com/documentation/mysql/bychapter/manual_Privilege_system.html#Privilege_system

grant all privileges on DATABASENAME.* to ADMID@DATABASEHOST identified by 'ADMPW';

grant select,insert on MASTERTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant all on ORDERHEAD to USERID@DATABASEHOST identified by 'USERPW';
grant all on ORDERLINE to USERID@DATABASEHOST identified by 'USERPW';
grant all on ORDERLINEOPT to USERID@DATABASEHOST identified by 'USERPW';
grant select,insert,update on ACCTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select,insert,update on KEYTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on LANGTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on CATTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select,update on PRODTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on PRODLANG to USERID@DATABASEHOST identified by 'USERPW';
grant select,insert,update ON PRODCATTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on PRODOPT to USERID@DATABASEHOST identified by 'USERPW';
grant select on PRODOPTGRPNAME to USERID@DATABASEHOST identified by 'USERPW';
grant select on PRODREL to USERID@DATABASEHOST identified by 'USERPW';
grant select on NPRODTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on OPRODTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on ZONETABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on SUBZONETABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on VENDORTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select,insert,update on WEBTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on SHIPTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on SUBZSHIPTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on SHIPTHRESH to USERID@DATABASEHOST identified by 'USERPW';
grant select on WEIGHTTHRESH to USERID@DATABASEHOST identified by 'USERPW';
grant select on AUXLINKTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on AUXTXTTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select,insert,update on CUSTTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select,update on COUPONTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select,insert,update on PASSWORDTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select,insert,update on ESDTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on COUNTRYTABLE to USERID@DATABASEHOST identified by 'USERPW';
grant select on COUNTRYLANG to USERID@DATABASEHOST identified by 'USERPW';

grant all on INSTALLID_ccnums to DBDNAME@DATABASEHOST identified by 'DBDPASS';
