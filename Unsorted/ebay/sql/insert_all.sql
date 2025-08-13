/*	$Id: insert_all.sql,v 1.2 1999/02/21 02:54:35 josh Exp $	*/
rem  file:  ins_all.sql
rem  location:  /orasw/dba/CC1
rem  Used to perform all inserts into CC1 monitoring tables.
rem  This script is called from inserts.sql for each instance.
rem  For best results, name the database links after the
rem  instances they access.
rem
insert into files
   (db_nm,
   ts,
   check_date,
   file_nm,
   blocks)
select
   upper ('ebay'),      /*insert database link name as instance name*/
   tablespace_name,    /*taplespace name*/
   trunc(sysdate),     /*date query is being performed*/
   file_name,          /*full name of database file*/
   blocks              /*number of database blocks in file*/
from sys.dba_data_files
/
commit;
rem
insert into spaces
   (db_nm,
   check_date,
   ts,
   count_free_blocks,
   sum_free_blocks,
   max_free_blocks)
select
   upper('ebay'),      /*insert database link name as instance name*/
   trunc(sysdate),    /*date query is being performed*/
   tablespace_name,   /*tablespace name*/
   count(blocks),     /*num. of free space entries in the tablespace*/
   sum(blocks),       /*total free space in the tablespace*/
   max(blocks)       /*largest free extent in the tablespace*/
from sys.dba_free_space
group by tablespace_name
/
commit;
rem
insert into extents
   (db_nm,
   ts,
   seg_owner,
   seg_name,
   seg_type,
   extents,
   blocks,
   check_date)
select
   upper('ebay'),    /*insert database link name as instance name*/
   tablespace_name, /*tablespace name*/
   owner,           /*owner of the segment*/
   segment_name,    /*name of the segment*/
   segment_type,    /*type of segment (ex. TABLE, INDEX)*/
   extents,         /*number of extents in the segment*/
   blocks,          /*number of database blocks in the segment*/
   trunc(sysdate)   /*date the query is being performed*/
from sys.dba_segments
where extents>1          /*only record badly extended segments*/
or segment_type = 'ROLLBACK'   /*or rollback segments*/
/
commit;
rem
undefine 1
