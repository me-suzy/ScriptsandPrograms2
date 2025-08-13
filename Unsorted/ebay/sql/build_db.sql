/*	$Id: build_db.sql,v 1.4 1999/04/17 20:22:55 wwen Exp $	*/
--
-- $Header: /home/src/ebay/sql/build_db.sql,v 1.4 1999/04/17 20:22:55 wwen Exp $ Copyr (c) 1994 Oracle
--
-- This file must be run out of the directory containing the
-- initialization file.

-- USER_DATA: Create user sets this as the default tablespace
-- TEMPORARY_DATA: Create user sets this as the temporary tablespace
-- ROLLBACK_DATA: For rollback segments

create tablespace billingd01
    datafile 'C:\ORANT\DATABASE\billing\billingd01.dbf' size 100M reuse autoextend on
      next 5M maxsize 250M;
create tablespace billingi01
    datafile 'C:\ORANT\DATABASE\billing\billingi01.dbf' size 50m reuse autoextend on
      next 5M maxsize 100m;
alter tablespace rollback_data
    add datafile 'C:\ORANT\DATABASE\billing\rbs01.dbf' size 20M reuse autoextend on
      next 5M maxsize 150M;
alter tablespace temporary_data
    add datafile 'C:\ORANT\DATABASE\billing\temp01.dbf' size 20M reuse autoextend on
      next 5M maxsize 150M;
