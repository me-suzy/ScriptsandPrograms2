/* splitting the arc tables into monthly tables with their own
tablespaces and indices */
     2> where tablespace_name like '%ARC%';
SUBSTR(SEGMENT_NAME,1,30)      TABLESPACE_NAME                BYTES      EXTENTS   
------------------------------ ------------------------------ ---------- ----------
EBAY_BIDS_MAYJUN               BIDSARC                         209715200          2
EBAY_ITEMS_ARC_TAPE            ARCTOTAPED01                    314572800          1
EBAY_ITEM_INFO_ARC_TAPE        ARCTOTAPED01                     41943040          1
EBAY_BIDS_ARC_TAPE             ARCTOTAPED01                    209715200          1
EBAY_ITEM_INFO_ARC             ITEMARC2                       1615790080         22
EBAY_ITEMS_ARC                 ITEMARC3                       6594560000         32
TEMP_BIDS_ARC                  BIDSARC                          22128640         18
EBAY_BIDS_OCT98                BIDSARC                         209715200          2
EBAY_BIDS_ARC                  BIDSARC                        4718592000          8
EBAY_ITEMARC_ID_INDEX          ITEMARCI1                       760217600         10
EBAY_ITEM_INFO_ARC_I1          ITEMARCI2                       838860800         12
TEMP_BIDS_USER_INDEX           BIDSARCI                         22128640         18
BIDS_ARC_ID                    BIDSARCI                       2202009600         17
13 rows selected.

create tablespace ITEMARCD01 datafile
'/oracle/rdata10/ebay/oradata/itemarcd01a.dbf' size 2000M autoextend off;

create tablespace ITEMARCI01 datafile
'/oracle/rdata05/ebay/oradata/itemarci01a.dbf' size 1000M autoextend off;

create tablespace BIDARCD01 datafile
'/oracle/rdata10/ebay/oradata/bidarcd01a.dbf' size 2000m autoextend off;

create tablespace BIDARCI01 datafile
'/oracle/rdata05/ebay/oradata/bidarci01a.dbf' size 2000m autoextend off;

create tablespace ITEMARCD02 datafile
'/oracle/rdata10/ebay/oradata/itemarcd02a.dbf' size 500M autoextend off;

create tablespace ITEMARCI02 datafile
'/oracle/rdata05/ebay/oradata/itemarci02a.dbf' size 300M autoextend off;

create tablespace ARCD97 datafile
'/oracle/rdata10/ebay/oradata/arcd97.dbf' size 1024M autoextend off;

create tablespace ARCD0198 datafile
'/oracle/rdata10/ebay/oradata/arcd0198.dbf' size 1024M autoextend off;

create tablespace ARCD0298 datafile
'/oracle/rdata10/ebay/oradata/arcd0298.dbf' size 1024M autoextend off;

create tablespace ARCD0398 datafile
'/oracle/rdata10/ebay/oradata/arcd0398.dbf' size 1024M autoextend off;

create tablespace ARCD0498 datafile
'/oracle/rdata10/ebay/oradata/arcd0498.dbf' size 1024M autoextend off;

create tablespace ARCD0598 datafile
'/oracle/rdata10/ebay/oradata/arcd0598.dbf' size 1024M autoextend off;

create tablespace ARCD0698 datafile
'/oracle/rdata10/ebay/oradata/arcd0698.dbf' size 1024M autoextend off;

create tablespace ARCD0798 datafile
'/oracle/rdata10/ebay/oradata/arcd0798.dbf' size 1024M autoextend off;

create tablespace ARCD0898 datafile
'/oracle/rdata10/ebay/oradata/arcd0898.dbf' size 1024M autoextend off;

create tablespace ARCD0998 datafile
'/oracle/rdata10/ebay/oradata/arcd0998.dbf' size 1024M autoextend off;

create tablespace ARCD1098 datafile
'/oracle/rdata10/ebay/oradata/arcd1098.dbf' size 1024M autoextend off;

alter tablespace ARCD0698
add datafile '/oracle/rdata10/ebay/oradata/arcd0698a.dbf'
size 101M;

alter tablespace ARCD0798
add datafile '/oracle/rdata10/ebay/oradata/arcd0798a.dbf'
size 151M;

alter tablespace ARCD0798
add datafile '/oracle/rdata10/ebay/oradata/arcd0798b.dbf'
size 60M;

alter tablespace ARCD0898
add datafile '/oracle/rdata10/ebay/oradata/arcd0898a.dbf'
size 151M;

alter tablespace ARCD0898
add datafile '/oracle/rdata10/ebay/oradata/arcd0898b.dbf'
size 151M;

alter tablespace ARCD0898
add datafile '/oracle/rdata10/ebay/oradata/arcd0898c.dbf'
size 151M;

alter tablespace ARCD0998
add datafile '/oracle/rdata10/ebay/oradata/arcd0998a.dbf'
size 151M;

alter tablespace ARCD0998
add datafile '/oracle/rdata10/ebay/oradata/arcd0998b.dbf'
size 501M;

alter tablespace ARCD1098
add datafile '/oracle/rdata10/ebay/oradata/arcd1098a.dbf'
size 151M;

alter tablespace ARCD1098
add datafile '/oracle/rdata10/ebay/oradata/arcd1098b.dbf'
size 801M;

create tablespace ARCI97 datafile
'/oracle/rdata05/ebay/oradata/arci97.dbf' size 150M autoextend off;

create tablespace ARCI0198 datafile
'/oracle/rdata05/ebay/oradata/arci0198.dbf' size 250M autoextend off;

create tablespace ARCI0298 datafile
'/oracle/rdata05/ebay/oradata/arci0298.dbf' size 250M autoextend off;

create tablespace ARCI0398 datafile
'/oracle/rdata05/ebay/oradata/arci0398.dbf' size 250M autoextend off;

create tablespace ARCI0498 datafile
'/oracle/rdata05/ebay/oradata/arci0498.dbf' size 250M autoextend off;

create tablespace ARCI0598 datafile
'/oracle/rdata05/ebay/oradata/arci0598.dbf' size 250M autoextend off;

create tablespace ARCI0698 datafile
'/oracle/rdata05/ebay/oradata/arci0698.dbf' size 250M autoextend off;

create tablespace ARCI0798 datafile
'/oracle/rdata05/ebay/oradata/arci0798.dbf' size 250M autoextend off;

create tablespace ARCI0898 datafile
'/oracle/rdata05/ebay/oradata/arci0898.dbf' size 250M autoextend off;

create tablespace ARCI0998 datafile
'/oracle/rdata05/ebay/oradata/arci0998.dbf' size 250M autoextend off;

create tablespace ARCI1098 datafile
'/oracle/rdata05/ebay/oradata/arci1098.dbf' size 250M autoextend off;

/* ebay_items_arc */

rename ebay_items_arc to ebay_items_arc_old;

create table ebay_items_arc tablespace ITEMARCD01 storage
(initial 500m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-NOV-98' ;

/* make sure indices are there */

create index ebay_itemarc_id_idx
   on ebay_items_arc(id)
   tablespace itemarci01
   storage(initial 50m next 50M pctincrease 0) unrecoverable;
commit;

--- bids arc switcharoo

rename ebay_bids_arc to ebay_bids_arc_old;

create table ebay_bids_arc tablespace BIDARCD01 storage
(initial 400m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc);


 create index ebay_bidsarc_item_idx
	on ebay_bids_arc(item_id)
	tablespace bidarci01
	storage(initial 50M next 50M pctincrease 0) unrecoverable;

--- item info arc switcharoo

rename ebay_item_info_arc to ebay_item_info_arc_old;

create table ebay_item_info_arc tablespace ITEMARCD02 storage
(initial 200m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc);

create index ebay_iteminfoarc_id_idx
   on ebay_item_info_arc(id)
   tablespace itemarci02
   storage(initial 50m next 50M pctincrease 0) unrecoverable;
commit;

---- start doing older tables - cgi can be up at this point.

create table ebay_items_arc_97 tablespace ARCD97 storage (initial
100m next 50m pctincrease 0)   unrecoverable as
  select * from ebay_items_arc_old where sale_end < '01-JAN-98';

create index ebay_itemarc_id_97
   on ebay_items_arc_97(id)
   tablespace arci97
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0198 tablespace ARCD0198 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-JAN-98' and
sale_end<'01-FEB-98' ;

create index ebay_itemarc_id_0198
   on ebay_items_arc_0198(id)
   tablespace arci0198
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0298 tablespace ARCD0298 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-FEB-98' and
sale_end<'01-MAR-98';

create index ebay_itemarc_id_0298
   on ebay_items_arc_0298(id)
   tablespace arci0298
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0398 tablespace ARCD0398 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-MAR-98' and
sale_end<'01-APR-98';

create index ebay_itemarc_id_0398
   on ebay_items_arc_0398(id)
   tablespace arci0398
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0498 tablespace ARCD0498 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-APR-98' and
sale_end<'01-MAY-98';

create index ebay_itemarc_id_0498
   on ebay_items_arc_0498(id)
   tablespace arci0498
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0598 tablespace ARCD0598 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-MAY-98' and
sale_end<'01-JUN-98' ;

create index ebay_itemarc_id_0598
   on ebay_items_arc_0598(id)
   tablespace arci0598
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0698 tablespace ARCD0698 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-JUN-98' and
sale_end<'01-JUL-98' ;

create index ebay_itemarc_id_0698
   on ebay_items_arc_0698(id)
   tablespace arci0698
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0798 tablespace ARCD0798 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-JUL-98' and
sale_end<'01-AUG-98' ;

create index ebay_itemarc_id_0798
   on ebay_items_arc_0798(id)
   tablespace arci0798
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0898 tablespace ARCD0898 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-AUG-98' and
sale_end<'01-SEP-98' ;

create index ebay_itemarc_id_0898
   on ebay_items_arc_0898(id)
   tablespace arci0898
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_0998 tablespace ARCD0998 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-SEP-98' and
sale_end<'01-OCT-98' ;

create index ebay_itemarc_id_0998
   on ebay_items_arc_0998(id)
   tablespace arci0998
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_items_arc_1098 tablespace ARCD1098 storage
(initial 100m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-OCT-98' and
sale_end<'01-NOV-98';

create index ebay_itemarc_id_1098
   on ebay_items_arc_1098(id)
   tablespace arci1098
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

----- bids_arc split

create table ebay_bids_arc_97 tablespace ARCD97 storage (initial
50m next 50m pctincrease 0)   unrecoverable as
	 select * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_97);

create table ebay_bids_arc_0198 tablespace ARCD0198 storage (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0198);

create table ebay_bids_arc_0298 tablespace ARCD0298 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0298);


create table ebay_bids_arc_0398 tablespace ARCD0398 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0398);


create table ebay_bids_arc_0498 tablespace ARCD0498 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0498);


create table ebay_bids_arc_0598 tablespace ARCD0598 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0598);


create table ebay_bids_arc_0698 tablespace ARCD0698 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0698);


create table ebay_bids_arc_0798 tablespace ARCD0798 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0798);


create table ebay_bids_arc_0898 tablespace ARCD0898 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0898);


create table ebay_bids_arc_0998 tablespace ARCD0998 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_0998);


create table ebay_bids_arc_1098 tablespace ARCD1098 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc_1098);

/* ebay_item_info doesn't need to be split, but we'll do it anyway */

create table ebay_item_info_arc_97 tablespace ARCD97 storage
(initial 100m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_97);

create table ebay_item_info_arc_0198 tablespace ARCD0198 storage
(initial 100m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0198);

create table ebay_item_info_arc_0298 tablespace ARCD0298 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0298);

create table ebay_item_info_arc_0398 tablespace ARCD0398 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0398);

create table ebay_item_info_arc_0498 tablespace ARCD0498 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0498);

create table ebay_item_info_arc_0598 tablespace ARCD0598 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0598);

create table ebay_item_info_arc_0698 tablespace ARCD0698 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0698);

create table ebay_item_info_arc_0798 tablespace ARCD0798 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0798);

create table ebay_item_info_arc_0898 tablespace ARCD0898 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0898);

create table ebay_item_info_arc_0998 tablespace ARCD0998 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_0998);

create table ebay_item_info_arc_1098 tablespace ARCD1098 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc_old where 
  id in (select id from ebay_items_arc_1098);


--- we can make arcd97, arcdxx98, arci97, arcixx98 readonly.
--- remove ebay_items_arc_old, ebay_bids_arc_old, ebay_item_info_arc_old
--- drop the tablespaces

-- Verifying counts

select count(*) from ebay_items_arc_0198;
--- 1086387
select count(*) from ebay_items_arc_0298;
---	1242141
select count(*) from ebay_items_arc_0398;
--- 1703675
select count(*) from ebay_items_arc_0498;
--- 1844346
select count(*) from ebay_items_arc_0598;
--- 2202705
select count(*) from ebay_items_arc_0698;
--- 2401338
select count(*) from ebay_items_arc_0798;
--- 2661403
select count(*) from ebay_items_arc_0898;
--- 3052629
select count(*) from ebay_items_arc_0998;
--- 3284020
select count(*) from ebay_items_arc_1098;
--- 3942231

--- total: 23,420,875.

select count(*) from ebay_items_arc_old where sale_end < '01-NOV-98';
--- 23420875

select count(*) from ebay_bids_arc_0198;
---  3794275
select count(*) from ebay_bids_arc_0298;
---  5313647
select count(*) from ebay_bids_arc_0398;
---  7169804
select count(*) from ebay_bids_arc_0498;
---  7183740
select count(*) from ebay_bids_arc_0598;
---  7714124
select count(*) from ebay_bids_arc_0698;
---  8374482
select count(*) from ebay_bids_arc_0798;
---  9495851
select count(*) from ebay_bids_arc_0898;
--- 10900114
select count(*) from ebay_bids_arc_0998;
--- 11579653
select count(*) from ebay_bids_arc_1098;
--- 13835893
--- total: 85,361,583.

select count(*) from ebay_bids_arc_old where item_id in
(select id from ebay_items_arc_old where sale_end < '01-NOV-98');
--- 


--- once verified, drop the old tables
drop table ebay_bids_arc_old;
drop table ebay_item_info_arc_old;
drop table ebay_items_arc_old;

--- other unused tables?
drop table ebay_bids_mayjun;
drop table temp_bids_arc;
drop table ebay_bids_oct98;

--- verify there's nothing in the tablespaces
select substr(segment_name,1,30), tablespace_name, bytes from
dba_segments where tablespace_name = 'ITEMARC2';

select substr(segment_name,1,30), tablespace_name, bytes from
dba_segments where tablespace_name = 'ITEMARC3';

select substr(segment_name,1,30), tablespace_name, bytes from
dba_segments where tablespace_name = 'ITEMARCI1';

select substr(segment_name,1,30), tablespace_name, bytes from
dba_segments where tablespace_name = 'ITEMARCI2';

select substr(segment_name,1,30), tablespace_name, bytes from
dba_segments where tablespace_name = 'BIDSARC';

select substr(segment_name,1,30), tablespace_name, bytes from
dba_segments where tablespace_name = 'BIDSARCI';

--- find out the datafiles associated with the arcs
select substr(file_name,1,50) from dba_data_files
where tablespace_name = 'ITEMARC2';
------------------------------------------------------------
/oracle/rdata07/ebay/oradata/itemarc2d.dbf
/oracle/rdata07/ebay/oradata/itemarc2c.dbf
/oracle/rdata07/ebay/oradata/itemarc2b.dbf
/oracle/rdata07/ebay/oradata/itemarc2.dbf
/oracle/rdata07/ebay/oradata/itemarc2a.dbf
/oracle/rdata07/ebay/oradata/itemarc2e.dbf

6 rows selected.

select substr(file_name,1,50) from dba_data_files
where tablespace_name = 'ITEMARC3';
SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
/oracle/rdata06/ebay/oradata/itemarc3j.dbf
/oracle/rdata06/ebay/oradata/itemarc3i.dbf
/oracle/rdata09/ebay/oradata/itemarc3h.dbf
/oracle/rdata06/ebay/oradata/itemarc3g.dbf
/oracle/rdata11/ebay/oradata/itemarc3e.dbf
/oracle/rdata06/ebay/oradata/itemarc3c.dbf
/oracle/rdata06/ebay/oradata/itemarc3b.dbf
/oracle/rdata06/ebay/oradata/itemarc3d.dbf
/oracle/rdata06/ebay/oradata/itemarc3f.dbf
/oracle/rdata06/ebay/oradata/itemarc3.dbf
/oracle/rdata06/ebay/oradata/itemarc3a.dbf

SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
/oracle/rdata06/ebay/oradata/itemarc3k.dbf
/oracle/rdata06/ebay/oradata/itemarc3l.dbf
/oracle/rdata06/ebay/oradata/itemarc3m.dbf

14 rows selected.

select substr(file_name,1,50) from dba_data_files
where tablespace_name = 'ITEMARCI1';
SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
/oracle/rdata07/ebay/oradata/itemarci1b.dbf
/oracle/rdata07/ebay/oradata/itemarci1a.dbf
/oracle/rdata07/ebay/oradata/itemarci1.dbf
/oracle/rdata09/ebay/oradata/itemarci1c.dbf

select substr(file_name,1,50) from dba_data_files
where tablespace_name = 'ITEMARCI2';
SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
/oracle/rdata07/ebay/oradata/itemarci2b.dbf
/oracle/rdata06/ebay/oradata/itemarci2a.dbf
/oracle/rdata06/ebay/oradata/itemarci2.dbf
/oracle/rdata07/ebay/oradata/itemarci2c.dbf

select substr(file_name,1,50) from dba_data_files
where tablespace_name = 'BIDSARC';
--------------------------------------------------
/oracle/rdata09/ebay/oradata/bidsarc004.dbf
/oracle/rdata09/ebay/oradata/bidsarc003.dbf
/oracle/rdata09/ebay/oradata/bidsarc002.dbf
/oracle/rdata09/ebay/oradata/bidsarc001.dbf

select substr(file_name,1,50) from dba_data_files
where tablespace_name = 'BIDSARCI';
SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
/oracle/rdata09/ebay/oradata/bidsarci01_q.dbf
/oracle/rdata09/ebay/oradata/bidsarci.dbf

--- drop the tablespaces
drop tablespace itemarc2 including contents;
drop tablespace itemarc3 including contents;
drop tablespace itemarci1 including contents;
drop tablespace itemarci2 including contents;
drop tablespace bidsarc including contents;
drop tablespace bidsarci including contents;

--- rm the datafiles

rm /oracle/rdata07/ebay/oradata/itemarc2d.dbf
rm /oracle/rdata07/ebay/oradata/itemarc2c.dbf
rm /oracle/rdata07/ebay/oradata/itemarc2b.dbf
rm /oracle/rdata07/ebay/oradata/itemarc2.dbf
rm /oracle/rdata07/ebay/oradata/itemarc2a.dbf
rm /oracle/rdata07/ebay/oradata/itemarc2e.dbf
rm /oracle/rdata07/ebay/oradata/.itemarc2e.dbf

rm /oracle/rdata06/ebay/oradata/itemarc3j.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3i.dbf
rm /oracle/rdata09/ebay/oradata/itemarc3h.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3g.dbf
rm /oracle/rdata11/ebay/oradata/itemarc3e.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3c.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3b.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3d.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3f.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3a.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3k.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3l.dbf
rm /oracle/rdata06/ebay/oradata/itemarc3m.dbf
rm /oracle/rdata06/ebay/oradata/.itemarc3j.dbf
rm /oracle/rdata06/ebay/oradata/.itemarc3k.dbf

rm /oracle/rdata07/ebay/oradata/itemarci1b.dbf
rm /oracle/rdata07/ebay/oradata/itemarci1a.dbf
rm /oracle/rdata07/ebay/oradata/itemarci1.dbf
rm /oracle/rdata09/ebay/oradata/itemarci1c.dbf
rm /oracle/rdata09/ebay/oradata/.itemarci1c.dbf

rm /oracle/rdata07/ebay/oradata/itemarci2b.dbf
rm /oracle/rdata06/ebay/oradata/itemarci2a.dbf
rm /oracle/rdata06/ebay/oradata/itemarci2.dbf
rm /oracle/rdata07/ebay/oradata/itemarci2c.dbf

rm /oracle/rdata09/ebay/oradata/bidsarc004.dbf
rm /oracle/rdata09/ebay/oradata/bidsarc003.dbf
rm /oracle/rdata09/ebay/oradata/bidsarc002.dbf
rm /oracle/rdata09/ebay/oradata/bidsarc001.dbf
rm /oracle/rdata09/ebay/oradata/.bidsarc004.dbf
rm /oracle/rdata09/ebay/oradata/.bidsarc003.dbf
rm /oracle/rdata09/ebay/oradata/.bidsarc002.dbf
rm /oracle/rdata09/ebay/oradata/.bidsarc001.dbf

rm /oracle/rdata09/ebay/oradata/bidsarci01_q.dbf
rm /oracle/rdata09/ebay/oradata/bidsarci.dbf
rm /oracle/rdata09/ebay/oradata/.bidsarci01_q.dbf
rm /oracle/rdata09/ebay/oradata/.bidsarci.dbf

-- feb 2, 1999

create tablespace ARCD1198 datafile
'/oracle/rdata10/ebay/oradata/arcd1198.dbf' 
size 2001M autoextend off;

create tablespace ARCI1198 datafile
'/oracle/rdata05/ebay/oradata/arci1198.dbf' 
size 250M autoextend off;

create tablespace ARCD1298 datafile
'/oracle/rdata10/ebay/oradata/arcd1298.dbf' 
size 2001M autoextend off;

alter tablespace ARCD1298 add datafile
'/oracle/rdata10/ebay/oradata/arcd1298a.dbf'
size 501M;

create tablespace ARCI1298 datafile
'/oracle/rdata05/ebay/oradata/arci1298.dbf' 
size 250M autoextend off;

create table ebay_items_arc_1198 tablespace ARCD1198 storage
(initial 300m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc where sale_end >='01-NOV-98' and
sale_end<'01-DEC-98';

create index ebay_itemarc_id_1198
   on ebay_items_arc_1198(id)
   tablespace arci1198
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_bids_arc_1198 tablespace ARCD1198 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc where item_id in
	 (select id from ebay_items_arc_1198);

create table ebay_item_info_arc_1198 tablespace ARCD1198 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc where 
  id in (select id from ebay_items_arc_1198);

---- clean uup ebay_items_arc and ebay_bids_arc

rename ebay_items_arc to ebay_items_arc_old;

create table ebay_items_arc tablespace ITEMARCD01 storage
(initial 500m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-DEC-98' ;

/* make sure indices are there */

create index ebay_itemsarc_id_idx
   on ebay_items_arc(id)
   tablespace itemarci01
   storage(initial 50m next 50M pctincrease 0) unrecoverable;
commit;

--- bids arc switcharoo

rename ebay_bids_arc to ebay_bids_arc_old;

create table ebay_bids_arc tablespace BIDARCD01 storage
(initial 500m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc);


 create index ebay_bidarc_item_idx
	on ebay_bids_arc(item_id)
	tablespace bidarci01
	storage(initial 50M next 50M pctincrease 0) unrecoverable;

--- do fill it with December data

create table ebay_items_arc_1298 tablespace ARCD1298 storage
(initial 300m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc where sale_end >='01-DEC-98' and
sale_end<'01-JAN-99';

create index ebay_itemarc_id_1298
   on ebay_items_arc_1298(id)
   tablespace arci1298
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

create table ebay_bids_arc_1298 tablespace ARCD1298 storage   (initial
50m next 50m pctincrease 0)   unrecoverable as
  select * from ebay_bids_arc where item_id in
  (select id from ebay_items_arc_1298);
/* 1.55 - 2:53 */

create table ebay_item_info_arc_1298 tablespace ARCD1298 storage
(initial 50m next 20m pctincrease 0)   unrecoverable as
  select  * from ebay_item_info_arc where 
  id in (select id from ebay_items_arc_1298);

--- DONE TILL HERE ---

--- clean out ebay_items_arc TO DO END OF FEBRUARY

rename ebay_items_arc to ebay_items_arc_old;

create table ebay_items_arc tablespace ITEMARCD01 storage
(initial 500m next 100m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='20-DEC-98' ;

/* make sure indices are there */

create index ebay_itemsarc_id_index
   on ebay_items_arc(id)
   tablespace itemarci01
   storage(initial 50m next 50M pctincrease 0) unrecoverable;
commit;

--- bids arc switcharoo

rename ebay_bids_arc to ebay_bids_arc_old;

create table ebay_bids_arc tablespace BIDARCD01 storage
(initial 500m next 100m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc);

 create index ebay_bidsarc_item_index
	on ebay_bids_arc(item_id)
	tablespace bidarci01
	storage(initial 50M next 50M pctincrease 0) unrecoverable;


-- or delete from ebay_items_arc, ebay_bids_arc, ebay_item_info_arc
-- but by day!
-- modify slowarc to do this.
delete from ebay_bids_arc
where item_id in
  (select id from ebay_items_arc_1198);
commit;

delete from ebay_item_info_arc
where id in
(select id from ebay_items_arc_1198);
commit;

delete from ebay_items_arc
where id in
(select id from ebay_items_arc_1198);
commit;

--- took too long - 
-- create tablespaces ITEMSARCD01, ITEMSARCI01, BIDSARCD01, BIDSARCI01
create tablespace ITEMSARCD01 datafile
'/oracle/rdata11/ebay/oradata/itemsarcd01a.dbf' size 2001M,
'/oracle/rdata11/ebay/oradata/itemsarcd01b.dbf' size 2001m,
'/oracle/rdata11/ebay/oradata/itemsarcd01c.dbf' size 2001m;

create tablespace ITEMSARCI01 datafile
'/oracle/rdata05/ebay/oradata/itemsarci01a.dbf' size 1001M autoextend off;

create tablespace BIDSARCD01 datafile
'/oracle/rdata11/ebay/oradata/bidsarcd01a.dbf' size 2001m,
'/oracle/rdata11/ebay/oradata/bidsarcd01b.dbf' size 2001m;

create tablespace BIDSARCI01 datafile
'/oracle/rdata05/ebay/oradata/bidsarci01a.dbf' size 2001m autoextend off;

-- clean up ebay_items_arc_0199

alter tablespace ARCD0199 read write;
alter tablespace ARCI0199 read write;
alter tablespace ITEMARCD03 read only;

--- items_arc switcharoo

rename ebay_items_arc to ebay_items_arc_old;

create table ebay_items_arc tablespace ITEMSARCD01 storage
(initial 500m next 100m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-FEB-99' ;

/* make sure indices are there */

create index ebay_itemsarc_id_index
   on ebay_items_arc(id)
   tablespace itemsarci01
   storage(initial 50m next 50M pctincrease 0) unrecoverable;
commit;

--- bids arc switcharoo

rename ebay_bids_arc to ebay_bids_arc_old;

create table ebay_bids_arc tablespace BIDSARCD01 storage
(initial 500m next 100m pctincrease 0)   unrecoverable as
  select  * from ebay_bids_arc_old where item_id in
	 (select id from ebay_items_arc);

 create index ebay_bidsarc_item_index
	on ebay_bids_arc(item_id)
	tablespace bidsarci01
	storage(initial 100M next 50M pctincrease 0) unrecoverable;

----
drop table ebay_items_arc_0199;

create table ebay_items_arc_0199 tablespace ARCD0199 storage
(initial 300m next 50m pctincrease 0)   unrecoverable as
  select  * from ebay_items_arc_old where sale_end >='01-JAN-99' and
sale_end<'01-FEB-99';

create index ebay_itemarc_id_0199
   on ebay_items_arc_0199(id)
   tablespace arci0199
   storage(initial 50m next 10M pctincrease 0) unrecoverable;
commit;

---
alter tablespace ARCD0199 read only;
alter tablespace ARCI0199 read only;

-- verify and drop old tables - after all is saved
alter tablespace ITEMARCI03 read only;

drop table ebay_items_arc_old;
drop table ebay_bids_arc_old;

--- verify no segments in the tablespaces
select substr(segment_name,1,40), tablespace_name, bytes
from dba_segments where tablespace_name = 'ITEMARCD01';

drop tablespace itemarcd01;

rm /oracle/rdata10/ebay/oradata/itemarcd01b.dbf
rm /oracle/rdata10/ebay/oradata/itemarcd01c.dbf
rm /oracle/rdata10/ebay/oradata/itemarcd01a.dbf
rm /oracle/rdata03/ebay/oradata/itemarcd01d.dbf

select substr(file_name,1,50), tablespace_name, bytes from 
dba_data_files where tablespace_name = 'ITEMARCD01';

SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
TABLESPACE_NAME                     BYTES
------------------------------ ----------
/oracle/rdata10/ebay/oradata/itemarcd01b.dbf
ITEMARCD01                     1049624576

/oracle/rdata10/ebay/oradata/itemarcd01c.dbf
ITEMARCD01                     2098200576

/oracle/rdata10/ebay/oradata/itemarcd01a.dbf
ITEMARCD01                     2097152000


SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
TABLESPACE_NAME                     BYTES
------------------------------ ----------
/oracle/rdata03/ebay/oradata/itemarcd01d.dbf
ITEMARCD01                     2098200576


select substr(segment_name,1,40), tablespace_name, bytes
from dba_segments where tablespace_name = 'BIDARCD01';

drop tablespace bidarcd01;

rm /oracle/rdata10/ebay/oradata/bidarcd01b.dbf
rm /oracle/rdata10/ebay/oradata/bidarcd01a.dbf
rm /oracle/rdata10/ebay/oradata/bidarcd01c.dbf


select substr(file_name,1,50), tablespace_name, bytes from 
dba_data_files where tablespace_name = 'BIDARCD01';

SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
TABLESPACE_NAME                     BYTES
------------------------------ ----------
/oracle/rdata10/ebay/oradata/bidarcd01b.dbf
BIDARCD01                      1049624576

/oracle/rdata10/ebay/oradata/bidarcd01c.dbf
BIDARCD01                      2098200576

/oracle/rdata10/ebay/oradata/bidarcd01a.dbf
BIDARCD01                      2097152000

select substr(segment_name,1,40), tablespace_name, bytes
from dba_segments where tablespace_name = 'BIDARCI01';

drop tablespace bidarci01;

rm /oracle/rdata05/ebay/oradata/bidarci01a.dbf


SQL> select substr(file_name,1,50), tablespace_name, bytes from 
  2   dba_data_files where tablespace_name = 'BIDARCI01';

SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
TABLESPACE_NAME                     BYTES
------------------------------ ----------
/oracle/rdata05/ebay/oradata/bidarci01a.dbf
BIDARCI01                      2097152000

select substr(segment_name,1,40), tablespace_name, bytes
from dba_segments where tablespace_name = 'ITEMARCI01';

drop tablespace itemarci01;

rm /oracle/rdata05/ebay/oradata/itemarci01a.dbf

SQL> select substr(file_name,1,50), tablespace_name, bytes from 
  2  dba_data_files where tablespace_name = 'ITEMARCI01';

SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
TABLESPACE_NAME                     BYTES
------------------------------ ----------
/oracle/rdata05/ebay/oradata/itemarci01a.dbf
ITEMARCI01                     1048576000


-------------------------------------------------
---- reindex ebay_items sale end index.
-------------------------------------------------
!qiomkfile -h -s 701m /oracle/rdata14/ebay/oradata/itemi06a.dbf
create tablespace itemi06 datafile
'/oracle/rdata14/ebay/oradata/itemi06a.dbf' size 701m;

drop index EBAY_ITEMS_ENDING_INDEX ;
CREATE INDEX EBAY_ITEMS_ENDING_INDEX ON EBAY_ITEMS (SALE_END )
PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL 400m NEXT 50m
MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1)
TABLESPACE itemi06 unrecoverable;

---- verify nothing is in this tablespace
select substr(segment_name,1,40), tablespace_name, bytes
from dba_segments where tablespace_name = 'ITEMSI06';

drop tablespace itemsi06 including contents;

SVRMGR> select substr(file_name,1,50), tablespace_name from dba_data_files
     2> where tablespace_name = 'ITEMI06';
SUBSTR(FILE_NAME,1,50)                             TABLESPACE_NAME               
-------------------------------------------------- ------------------------------
/oracle/rdata14/ebay/oradata/itemsi06a.dbf          ITEMI06                       

rm /oracle/rdata14/ebay/oradata/itemsi06a.dbf

