/*	$Id: billing.sql,v 1.3 1999/02/21 02:52:33 josh Exp $	*/
rem Create USer
SET TERMOUT OFF
SET ECHO OFF
create user billing identified by billing;
GRANT CONNECT,RESOURCE,UNLIMITED TABLESPACE TO billing IDENTIFIED BY billing;
ALTER USER billing DEFAULT TABLESPACE billingd01;
ALTER USER billing TEMPORARY TABLESPACE temporary_data;
CONNECT billing/billing@2:orcl
