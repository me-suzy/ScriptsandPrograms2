/*	$Id: raid_move2.sql,v 1.6 1999/02/21 02:54:51 josh Exp $	*/
/* scripts for moving tables and tablespaces to A5000 */

-------------
ebay_feedback_detail
-------------
1. create tablespace for ebay_feedback
vxmkcdev -o oracle_file -s 50m /oracle/rdata01/ebay/oradata/feedbackd01.dbf

	create tablespace feedbackd01
		datafile '/oracle/rdata01/ebay/oradata/feedbackd01.dbf'
		size 50M 
		autoextend off;

	create tablespace feedbacki01
	    datafile '/oracle18/ebay/oradata/feedbacki01.dbf'
		size 15M autoextend on next 5M;


2. cd to /oracle-items/ebay (for space)
exp scott/haw98 tables=ebay_feedback direct=Y indexes=N grants=Y constraints=N file=fb.dmp

3. drop existing table
drop table ebay_feedback;

4. create new table with new tablespace

 create table ebay_feedback
 (
	id				int
		constraint	feedback_id_nn
		not null,
	created		date
		constraint	feedback_date_nn
		not null,
	last_update	date
		constraint	feedback_last_update_nn
		not null,
	score			int
		constraint	feedback_score_nn
		not null,
	flags			int
		constraint	feedback_flags_nn
		not null
 )
 tablespace feedbackd01
 storage (initial 15M next 15M pctincrease 0);

5. import new table
imp scott/haw98 file = fb.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880

/* time taken: 1 min  */

6. reinstate constraints and indices
alter table ebay_feedback
   add constraint		feedback_pk
			primary key (id)
			using index	tablespace feedbacki01
			storage(initial 10M next 5m pctincrease 0) unrecoverable;
commit;
/* time taken: 1 min */
						
alter table ebay_feedback
	add	constraint		feedback_fk
			foreign key (id)
			references	ebay_users(id);
commit;
/* time taken: 1 min */

7. remove feedback tablespaces:
verify no tables in tablespaces:
select substr(segment_name,1,40), tablespace_name from dba_segments
where tablespace_name = 'RFEEDBACKD01' or tablespace_name = 'RFEEDBACKI01';

drop tablespace rfeedbackd01 including contents;
drop tablespace rfeedbacki01 including contents;

/* manually delete files for feedbackd01 and feedbacki01 */

/* create tablespace for itemi01 */
	create tablespace itemi01
		datafile '/oracle/rdata01/ebay/oradata/itemi01.dbf'
		size 2047M 
		autoextend off;

/* recreate indices except item_pk */

 create index ebay_items_seller_index
	on ebay_items(seller)
	tablespace itemi01
	storage(initial 70m next 30M) unrecoverable parallel (degree 3);
	commit;
/* 7:31 - 7:44 */

 create index ebay_items_high_bidder_index
   on ebay_items(high_bidder)
   tablespace itemi01
   storage(initial 70m next 30M) unrecoverable parallel (degree 3);
	commit;
/* 7:44 - 7:53 */

 create index ebay_items_starting_index
   on ebay_items(sale_start)
   tablespace itemi01
   storage(initial 70M next 30M) unrecoverable parallel (degree 3);
	commit;
/* 7:53 -  8:04*/

 create index ebay_items_ending_index
   on ebay_items(sale_end)
   tablespace itemi01
   storage(initial 70M next 30M) unrecoverable parallel (degree 6);
	commit;
/* 8:04 - 8:16 */

 create index ebay_items_category_index 
	on ebay_items(category)
	tablespace itemi01
	storage(initial 70m next 30m) unrecoverable parallel (degree 6);
	commit;
/* 8:16 - 8:24 */

 create index ebay_items_last_modified_index
   on ebay_items(last_modified)
   tablespace itemi01
   storage(initial 100m next 30M) unrecoverable parallel (degree 6);
	commit;
/* 8:24 - 8:36 */

/* rebuild items_pk */
alter index items_pk
	rebuild unrecoverable parallel (degree 3) tablespace itemi01;


/* scripts for copying files from /oracle-items to /oracle/rdata01 */
TO BE MOVED IN ORDER OF IMPORTANCE:

/* these should use: alter database rename datafile 
   when oracle is down 

1. shutdown database
> shutdown immediate;
2. copy datafiles 
> cp ...
3. rename datafiles via alter database
> alter database rename ...
4. start the instance:
> alter database open
5. verify everything works

6. if not finished, copy other tablespaces by alter tablespace commands

7. verify all tablespaces are online
select tablespace_name, status from dba_tablespaces;



10. rm old datafiles after verifying it is no longer used
(via dba_data_files query)

*/

(system tables)
/export/home/oracle7/dbs/systebay.dbf              SYSTEM
/oracle04/export/home/oracle7/dbs/systebay01.dbf   SYSTEM

cp /export/home/oracle7/dbs/systebay.dbf /oracle/rdata01/ebay/oradata/systebay.dbf
cp /oracle04/export/home/oracle7/dbs/systebay01.dbf /oracle/rdata01/ebay/oradata/systebay01.dbf

alter database rename file '/export/home/oracle7/dbs/systebay.dbf'
to '/oracle/rdata01/ebay/oradata/systebay.dbf';
alter database rename file '/oracle04/export/home/oracle7/dbs/systebay01.dbf'
to '/oracle/rdata01/ebay/oradata/systebay01.dbf';


(ebay_items index already moved)
/oracle-items/ebay/oradata/ritemd01.dbf            RITEMD01

cp /oracle-items/ebay/oradata/ritemd01.dbf /oracle/rdata01/ebay/oradata/ritemd01.dbf

alter database rename file '/oracle-items/ebay/oradata/ritemd01.dbf'
to '/oracle/rdata01/ebay/oradata/ritemd01.dbf';


(ebay_bids and indices)
/oracle-items/ebay/oradata/rbidd01.dbf             RBIDD01
/oracle21/ebay/oradata/rbidi01.dbf                 RBIDI01
/oracle-items/ebay/oradata/rbidi02.dbf             RBIDI02
/oracle-items/ebay/oradata/rbidi03.dbf             RBIDI03

cp /oracle-items/ebay/oradata/rbidd01.dbf /oracle/rdata01/ebay/oradata/rbidd01.dbf
cp /oracle21/ebay/oradata/rbidi01.dbf /oracle/rdata01/ebay/oradata/rbidi01.dbf
cp /oracle-items/ebay/oradata/rbidi02.dbf /oracle/rdata01/ebay/oradata/rbidi02.dbf
cp /oracle-items/ebay/oradata/rbidi03.dbf /oracle/rdata01/ebay/oradata/rbidi03.dbf

alter database rename file '/oracle-items/ebay/oradata/rbidd01.dbf' 
to '/oracle/rdata01/ebay/oradata/rbidd01.dbf';
alter database rename file '/oracle21/ebay/oradata/rbidi01.dbf'
to '/oracle/rdata01/ebay/oradata/rbidi01.dbf';
alter database rename file '/oracle-items/ebay/oradata/rbidi02.dbf'
to '/oracle/rdata01/ebay/oradata/rbidi02.dbf';
alter database rename file '/oracle-items/ebay/oradata/rbidi03.dbf'
to '/oracle/rdata01/ebay/oradata/rbidi03.dbf';


(ebay_item_info)
/oracle-items/ebay/oradata/ritemd03.dbf            RITEMD03
/oracle18/ebay/oradata/ritemi03.dbf                RITEMI03

cp /oracle-items/ebay/oradata/ritemd03.dbf /oracle/rdata01/ebay/oradata/ritemd03.dbf
cp /oracle18/ebay/oradata/ritemi03.dbf /oracle/rdata01/ebay/oradata/ritemi03.dbf

alter database rename file '/oracle-items/ebay/oradata/ritemd03.dbf'
to '/oracle/rdata01/ebay/oradata/ritemd03.dbf';
alter database rename file '/oracle18/ebay/oradata/ritemi03.dbf' 
to '/oracle/rdata01/ebay/oradata/ritemi03.dbf';


(ebay_users)
/oracle-items/ebay/oradata/ruserd01.dbf            RUSERD01
/oracle18/ebay/oradata/ruseri01.dbf                RUSERI01

cp /oracle-items/ebay/oradata/ruserd01.dbf /oracle/rdata01/ebay/oradata/ruserd01.dbf
cp /oracle18/ebay/oradata/ruseri01.dbf /oracle/rdata01/ebay/oradata/ruseri01.dbf

alter database rename file '/oracle-items/ebay/oradata/ruserd01.dbf'
to '/oracle/rdata01/ebay/oradata/ruserd01.dbf';
alter database rename file '/oracle18/ebay/oradata/ruseri01.dbf'
to '/oracle/rdata01/ebay/oradata/ruseri01.dbf';


(ebay_user_info)
/oracle-items/ebay/oradata/ruserd02.dbf            RUSERD02
/oracle18/ebay/oradata/ruseri02.dbf                RUSERI02

cp /oracle-items/ebay/oradata/ruserd02.dbf /oracle/rdata01/ebay/oradata/ruserd02.dbf
cp /oracle18/ebay/oradata/ruseri02.dbf /oracle/rdata01/ebay/oradata/ruseri02.dbf

alter database rename file '/oracle-items/ebay/oradata/ruserd02.dbf'
to '/oracle/rdata01/ebay/oradata/ruserd02.dbf';
alter database rename file '/oracle18/ebay/oradata/ruseri02.dbf'
to '/oracle/rdata01/ebay/oradata/ruseri02.dbf';


(ebay_user_code, ebay_categories)
/oracle-items/ebay/oradata/statmiscd.dbf           STATMISCD
/oracle18/ebay/oradata/statmisci.dbf               STATMISCI

cp /oracle-items/ebay/oradata/statmiscd.dbf /oracle/rdata01/ebay/oradata/statmiscd.dbf
cp /oracle18/ebay/oradata/statmisci.dbf /oracle/rdata01/ebay/oradata/statmisci.dbf

alter database rename file '/oracle-items/ebay/oradata/statmiscd.dbf'
to '/oracle/rdata01/ebay/oradata/statmiscd.dbf';
alter database rename file '/oracle18/ebay/oradata/statmisci.dbf'
to '/oracle/rdata01/ebay/oradata/statmisci.dbf';


*** from here on, it could be by tablespace ***
(ebay_item_desc)
/oracle-items/ebay/oradata/ritemd02a.dbf           RITEMD02                                         
/oracle-items/ebay/oradata/ritemd02b.dbf           RITEMD02                                         
/oracle-items/ebay/oradata/ritemd02.dbf            RITEMD02                                         
/oracle-items/ebay/oradata/ritemd02d.dbf           RITEMD02                                         
/oracle-items/ebay/oradata/ritemd02e.dbf           RITEMD02                                         
/oracle-items/ebay/oradata/ritemd02c.dbf           RITEMD02                                         
/oracle18/ebay/oradata/ritemi02.dbf                RITEMI02                                         

alter tablespace ritemd02 offline;
cp /oracle-items/ebay/oradata/ritemd02*.dbf /oracle/rdata01/ebay/oradata/.
alter tablespace ritemd02 rename datafile
'/oracle-items/ebay/oradata/ritemd02.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02.dbf';
alter tablespace ritemd02 rename datafile
'/oracle-items/ebay/oradata/ritemd02a.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02a.dbf';
alter tablespace ritemd02 rename datafile
'/oracle-items/ebay/oradata/ritemd02b.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02b.dbf';
alter tablespace ritemd02 rename datafile
'/oracle-items/ebay/oradata/ritemd02c.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02c.dbf';
alter tablespace ritemd02 rename datafile
'/oracle-items/ebay/oradata/ritemd02d.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02d.dbf';
alter tablespace ritemd02 rename datafile
'/oracle-items/ebay/oradata/ritemd02e.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02e.dbf';
alter tablespace ritemd02 online;
OR
alter database rename file
'/oracle-items/ebay/oradata/ritemd02.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02.dbf';
alter database rename file
'/oracle-items/ebay/oradata/ritemd02a.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02a.dbf';
alter database rename file
'/oracle-items/ebay/oradata/ritemd02b.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02b.dbf';
alter database rename file
'/oracle-items/ebay/oradata/ritemd02c.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02c.dbf';
alter database rename file
'/oracle-items/ebay/oradata/ritemd02d.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02d.dbf';
alter database rename file
'/oracle-items/ebay/oradata/ritemd02e.dbf' to
'/oracle/rdata01/ebay/oradata/ritemd02e.dbf';


alter tablespace ritemi02 offline;
cp /oracle18/ebay/oradata/ritemi02.dbf /oracle/rdata01/ebay/oradata/ritemi02.dbf
alter tablespace ritemi02 rename datafile
'/oracle18/ebay/oradata/ritemi02.dbf' to
'/oracle/rdata01/ebay/oradata/ritemi02.dbf';
alter tablespace ritemi02 online;

alter database rename file
'/oracle18/ebay/oradata/ritemi02.dbf' to
'/oracle/rdata01/ebay/oradata/ritemi02.dbf';


(ebay_feedback_detail and index, index for ebay_feedback)
/oracle06/ebay/oradata/feedbackd02.dbf             FEEDBACKD02                                      
/oracle18/ebay/oradata/feedbacki01.dbf             FEEDBACKI01                                      
/oracle18/ebay/oradata/feedbacki02.dbf             FEEDBACKI02                                      

alter tablespace feedbackd02 offline;
cp /oracle06/ebay/oradata/feedbackd02.dbf /oracle/rdata01/ebay/oradata/feedbackd02.dbf
alter tablespace feedbackd02 rename datafile
'/oracle06/ebay/oradata/feedbackd02.dbf' to
'/oracle/rdata01/ebay/oradata/feedbackd02.dbf';
alter tablespace feedbackd02 online;

alter tablespace feedbacki01 offline;
cp /oracle18/ebay/oradata/feedbacki01.dbf /oracle/rdata01/ebay/oradata/feedbacki01.dbf
alter tablespace feedbacki01 rename datafile
'/oracle18/ebay/oradata/feedbacki01.dbf' to
'/oracle/rdata01/ebay/oradata/feedbacki01.dbf';

alter tablespace feedbacki02 offline;
cp /oracle18/ebay/oradata/feedbacki02.dbf /oracle/rdata01/ebay/oradata/feedbacki02.dbf
alter tablespace feedbacki02 rename datafile
'/oracle18/ebay/oradata/feedbacki02.dbf' to
'/oracle/rdata01/ebay/oradata/feedbacki02.dbf';

alter database rename file
'/oracle06/ebay/oradata/feedbackd02.dbf' to
'/oracle/rdata01/ebay/oradata/feedbackd02.dbf';

alter database rename file
'/oracle18/ebay/oradata/feedbacki01.dbf' to
'/oracle/rdata01/ebay/oradata/feedbacki01.dbf';

alter database rename file
'/oracle18/ebay/oradata/feedbacki02.dbf' to
'/oracle/rdata01/ebay/oradata/feedbacki02.dbf';


(ebay_seller_item_lists)
/oracle-items/ebay/oradata/ruserd06.dbf            RUSERD06                                         
/oracle18/ebay/oradata/ruseri06.dbf                RUSERI06                                         

alter tablespace ruserd06 offline;
cp /oracle-items/ebay/oradata/ruserd06.dbf /oracle/rdata01/ebay/oradata/ruserd06.dbf
alter tablespace ruserd06 rename datafile
'/oracle-items/ebay/oradata/ruserd06.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd06.dbf';
alter tablespace ruserd06 online;

alter tablespace ruseri06 offline;
cp /oracle18/ebay/oradata/ruseri06.dbf /oracle/rdata01/ebay/oradata/ruseri06.dbf
alter tablespace ruseri06 rename datafile
'/oracle18/ebay/oradata/ruseri06.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri06.dbf';
alter tablespace ruseri06 online;
OR
alter database rename file
'/oracle-items/ebay/oradata/ruserd06.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd06.dbf';
alter database rename file
'/oracle18/ebay/oradata/ruseri06.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri06.dbf';


(ebay_bidder_item_lists)
/oracle-items/ebay/oradata/ruserd07.dbf            RUSERD07
/oracle18/ebay/oradata/ruseri07.dbf                RUSERI07

alter tablespace ruserd07 offline;
cp /oracle-items/ebay/oradata/ruserd07.dbf /oracle/rdata01/ebay/oradata/ruserd07.dbf
alter tablespace ruserd07 rename datafile
'/oracle-items/ebay/oradata/ruserd07.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd07.dbf';
alter tablespace ruserd07 online;

alter tablespace ruseri07 offline;
cp /oracle18/ebay/oradata/ruseri07.dbf /oracle/rdata01/ebay/oradata/ruseri07.dbf
alter tablespace ruseri07 rename datafile
'/oracle18/ebay/oradata/ruseri07.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri07.dbf';
alter tablespace ruseri07 online;
OR
alter database rename file
'/oracle-items/ebay/oradata/ruserd07.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd07.dbf';
alter database rename file
'/oracle18/ebay/oradata/ruseri07.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri07.dbf';


(ebay_accounts and indices)
/oracle02/ebay/oradata/accountd01.dbf              ACCOUNTD01
/oracle02/ebay/oradata/accountd01a.dbf             ACCOUNTD01
/oracle01/ebay/oradata/accountd01b.dbf             ACCOUNTD01
/oracle05/ebay/oradata/accounti02d.dbf             ACCOUNTI02
/oracle05/ebay/oradata/accounti02a.dbf             ACCOUNTI02
/oracle05/ebay/oradata/accounti02e.dbf             ACCOUNTI02
/oracle05/ebay/oradata/accounti02b.dbf             ACCOUNTI02
/oracle05/ebay/oradata/accounti02.dbf              ACCOUNTI02
/oracle05/ebay/oradata/accounti02c.dbf             ACCOUNTI02

alter tablespace accountd01 offline;
cp /oracle02/ebay/oradata/accountd01* /oracle/rdata01/ebay/oradata/.
cp /oracle01/ebay/oradata/accountd01b.dbf /oracle/rdata01/ebay/oradata/.
alter tablespace accountd01 rename datafile
'/oracle02/ebay/oradata/accountd01.dbf' to
'/oracle/rdata01/ebay/oradata/accountd01.dbf';
alter tablespace accountd01 rename datafile
'/oracle02/ebay/oradata/accountd01a.dbf' to
'/oracle/rdata01/ebay/oradata/accountd01a.dbf';
alter tablespace accountd01 rename datafile
'/oracle01/ebay/oradata/accountd01b.dbf' to
'/oracle/rdata01/ebay/oradata/accountd01b.dbf';
alter tablespace accountd01 online;

alter database rename file
'/oracle02/ebay/oradata/accountd01.dbf' to
'/oracle/rdata01/ebay/oradata/accountd01.dbf';
alter database rename file
'/oracle02/ebay/oradata/accountd01a.dbf' to
'/oracle/rdata01/ebay/oradata/accountd01a.dbf';
alter database rename file
'/oracle01/ebay/oradata/accountd01b.dbf' to
'/oracle/rdata01/ebay/oradata/accountd01b.dbf';

alter tablespace accounti02 offline;
cp /oracle05/ebay/oradata/accounti02* /oracle/rdata01/ebay/oradata/.
alter tablespace accounti02 rename datafile
'/oracle05/ebay/oradata/accounti02.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02.dbf';
alter tablespace accounti02 rename datafile
'/oracle05/ebay/oradata/accounti02a.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02a.dbf';
alter tablespace accounti02 rename datafile
'/oracle05/ebay/oradata/accounti02b.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02b.dbf';
alter tablespace accounti02 rename datafile
'/oracle05/ebay/oradata/accounti02c.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02c.dbf';
alter tablespace accounti02 rename datafile
'/oracle05/ebay/oradata/accounti02d.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02d.dbf';
alter tablespace accounti02 rename datafile
'/oracle05/ebay/oradata/accounti02e.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02e.dbf';
alter tablespace accounti02 online;

alter database rename file
'/oracle05/ebay/oradata/accounti02.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02.dbf';
alter database rename file
'/oracle05/ebay/oradata/accounti02a.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02a.dbf';
alter database rename file
'/oracle05/ebay/oradata/accounti02b.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02b.dbf';
alter database rename file
'/oracle05/ebay/oradata/accounti02c.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02c.dbf';
alter database rename file
'/oracle05/ebay/oradata/accounti02d.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02d.dbf';
alter database rename file
'/oracle05/ebay/oradata/accounti02e.dbf' to
'/oracle/rdata01/ebay/oradata/accounti02e.dbf';


(ebay_renamed_users)
/oracle-items/ebay/oradata/ruserd03.dbf            RUSERD03
/oracle18/ebay/oradata/ruseri03.dbf                RUSERI03

alter tablespace ruserd03 offline;
cp /oracle-items/ebay/oradata/ruserd03.dbf /oracle/rdata01/ebay/oradata/ruserd03.dbf
alter tablespace ruserd03 rename datafile
'/oracle-items/ebay/oradata/ruserd03.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd03.dbf';
alter tablespace ruserd03 online;
OR
alter database rename file
'/oracle-items/ebay/oradata/ruserd03.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd03.dbf';

alter tablespace ruseri03 offline;
cp /oracle18/ebay/oradata/ruseri03.dbf /oracle/rdata01/ebay/oradata/ruseri03.dbf
alter tablespace ruseri03 rename datafile
'/oracle18/ebay/oradata/ruseri03.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri03.dbf';
OR
alter database rename file
'/oracle18/ebay/oradata/ruseri03.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri03.dbf';


(ebay_user_attributes)
/oracle-items/ebay/oradata/ruserd04.dbf            RUSERD04
/oracle18/ebay/oradata/ruseri04.dbf                RUSERI04

alter tablespace ruserd04 offline;
cp /oracle-items/ebay/oradata/ruserd04.dbf /oracle/rdata01/ebay/oradata/ruserd04.dbf
alter tablespace ruserd04 rename datafile
'/oracle-items/ebay/oradata/ruserd04.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd04.dbf';
alter tablespace ruserd04 online;

alter database rename file
'/oracle-items/ebay/oradata/ruserd04.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd04.dbf';
alter database rename file
'/oracle18/ebay/oradata/ruseri04.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri04.dbf';


alter tablespace ruseri04 offline;
cp /oracle18/ebay/oradata/ruseri04.dbf /oracle/rdata01/ebay/oradata/ruseri04.dbf
alter tablespace ruseri04 rename datafile
'/oracle18/ebay/oradata/ruseri04.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri04.dbf';
alter tablespace ruseri04 online;


/oracle-items/ebay/oradata/ruserd05.dbf            RUSERD05                                         
/oracle18/ebay/oradata/ruseri05.dbf                RUSERI05                                         
(ebay_aw_credit_status, ebay_admin, ebay_user_survey_responses,
 ebay_marketplaces_info)

alter tablespace ruserd05 offline;
cp /oracle-items/ebay/oradata/ruserd05.dbf /oracle/rdata01/ebay/oradata/ruserd05.dbf
alter tablespace ruserd05 rename datafile
'/oracle-items/ebay/oradata/ruserd05.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd05.dbf';
alter tablespace ruserd05 online;

alter tablespace ruseri05 offline;
cp /oracle18/ebay/oradata/ruseri05.dbf /oracle/rdata01/ebay/oradata/ruseri05.dbf
alter tablespace ruseri05 rename datafile
'/oracle18/ebay/oradata/ruseri05.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri05.dbf';
alter tablespace ruseri05 online;

alter database rename file
'/oracle-items/ebay/oradata/ruserd05.dbf' to
'/oracle/rdata01/ebay/oradata/ruserd05.dbf';
alter database rename file
'/oracle18/ebay/oradata/ruseri05.dbf' to
'/oracle/rdata01/ebay/oradata/ruseri05.dbf';



/oracle-items/ebay/oradata/dynmiscd.dbf            DYNMISCD
/oracle18/ebay/oradata/dynmisci.dbf                DYNMISCI
(ebay_rename_pending, ebay_announce, ebay_special_users,
 ebay_special_items)

alter tablespace dynmiscd offline;
cp /oracle-items/ebay/oradata/dynmiscd.dbf /oracle/rdata01/ebay/oradata/dynmiscd.dbf
alter tablespace dynmiscd rename datafile
'/oracle-items/ebay/oradata/dynmiscd.dbf' to
'/oracle/rdata01/ebay/oradata/dynmiscd.dbf';
alter tablespace dynmiscd online;

alter tablespace dynmisci offline;
cp /oracle18/ebay/oradata/dynmisci.dbf /oracle/rdata01/ebay/oradata/dynmisci.dbf
alter tablespace dynmisci rename datafile
'/oracle18/ebay/oradata/dynmisci.dbf' to
'/oracle/rdata01/ebay/oradata/dynmisci.dbf';
alter tablespace dynmisci online;

alter database rename file
'/oracle-items/ebay/oradata/dynmiscd.dbf' to
'/oracle/rdata01/ebay/oradata/dynmiscd.dbf';
alter database rename file
'/oracle18/ebay/oradata/dynmisci.dbf' to
'/oracle/rdata01/ebay/oradata/dynmisci.dbf';

(ebay_bulletin_boards)                                            
/oracle-items/ebay/oradata/bbd01.dbf               BBD01                                            
/oracle01/ebay/oradata/bbi01.dbf                   BBI01

alter tablespace bbd01 offline;
cp /oracle-items/ebay/oradata/bbd01.dbf /oracle/rdata01/ebay/oradata/bbd01.dbf
alter tablespace bbd01 rename datafile
'/oracle-items/ebay/oradata/bbd01.dbf' to
'/oracle/rdata01/ebay/oradata/bbd01.dbf';
alter tablespace bbd01 online;

alter tablespace bbi01 offline;
cp /oracle01/ebay/oradata/bbi01.dbf /oracle/rdata01/ebay/oradata/bbi01.dbf
alter tablespace bbi01 rename datafile
'/oracle01/ebay/oradata/bbi01.dbf' to
'/oracle/rdata01/ebay/oradata/bbi01.dbf';
alter tablespace bbi01 online;

alter database rename file
'/oracle-items/ebay/oradata/bbd01.dbf' to
'/oracle/rdata01/ebay/oradata/bbd01.dbf';
alter database rename file
'/oracle01/ebay/oradata/bbi01.dbf' to
'/oracle/rdata01/ebay/oradata/bbi01.dbf';

--- copy here -----
TO BE DROPPED - or used when ebay_categories are moved to a5000:
/oracle/rdata01/ebay/oradata/categoryd01.dbf       CATEGORYD01                                      
/oracle/rdata01/ebay/oradata/categoryi01.dbf       CATEGORYI01                                      


MOVED LATER:
/oracle03/ebay/oradata/add01.dbf                   ADD01 
alter tablespace add01 offline;
cp /oracle03/ebay/oradata/add01.dbf /oracle/rdata01/ebay/oradata/add01.dbf
alter tablespace add01 rename datafile
'/oracle03/ebay/oradata/add01.dbf' to
'/oracle/rdata01/ebay/oradata/add01.dbf';
alter tablespace add01 online;
                                           
/oracle21/ebay/oradata/bizdevd01.dbf               BIZDEVD01
alter tablespace bizdevd01 offline;
cp /oracle21/ebay/oradata/bizdevd01.dbf /oracle/rdata01/ebay/oradata/bizdevd01.dbf
alter tablespace bizdevd01 rename datafile
'/oracle21/ebay/oradata/bizdevd01.dbf' to
'/oracle/rdata01/ebay/oradata/bizdevd01.dbf';
alter tablespace bizdevd01 online;

                                        
/oracle06/ebay/oradata/partd01.dbf                 PARTD01
alter tablespace partd01 offline;
cp /oracle06/ebay/oradata/partd01.dbf /oracle/rdata01/ebay/oradata/partd01.dbf
alter tablespace partd01 rename datafile
'/oracle06/ebay/oradata/partd01.dbf' to
'/oracle/rdata01/ebay/oradata/partd01.dbf';
alter tablespace partd01 online;
                                 
/oracle18/ebay/oradata/parti01.dbf                 PARTI01
alter tablespace parti01 offline;
cp /oracle18/ebay/oradata/parti01.dbf /oracle/rdata01/ebay/oradata/parti01.dbf
alter tablespace parti01 rename datafile
'/oracle18/ebay/oradata/parti01.dbf' to
'/oracle/rdata01/ebay/oradata/parti01.dbf';
alter tablespace parti01 online;

/oracle06/ebay/oradata/statsd01.dbf                STATSD01
alter tablespace STATSD01 offline;
cp /oracle06/ebay/oradata/statsd01.dbf /oracle/rdata01/ebay/oradata/statsd01.dbf
alter tablespace statsd01 rename datafile
'/oracle06/ebay/oradata/statsd01.dbf' to
'/oracle/rdata01/ebay/oradata/statsd01.dbf';
alter tablespace statsd01 online;
                                         
/oracle07/ebay/oradata/statsi01.dbf                STATSI01                                         
alter tablespace STATSi01 offline;
cp /oracle07/ebay/oradata/statsi01.dbf /oracle/rdata01/ebay/oradata/statsi01.dbf
alter tablespace statsi01 rename datafile
'/oracle07/ebay/oradata/statsi01.dbf' to
'/oracle/rdata01/ebay/oradata/statsi01.dbf';
alter tablespace statsi01 online;

/oracle12/ebay/oradata/summary.dat                 SUMMARY 
alter tablespace SUMMARY offline;
cp /oracle12/ebay/oradata/summary.dat /oracle/rdata01/ebay/oradata/summary.dbf
alter tablespace SUMMARY rename datafile
'/oracle12/ebay/oradata/summary.dat' to
'/oracle/rdata01/ebay/oradata/summary.dbf';
alter tablespace SUMMARY online;
                                         
/oracle01/ebay/oradata/cc.dbf                      CC
alter tablespace CC offline;
cp /oracle01/ebay/oradata/cc.dbf /oracle/rdata01/ebay/oradata/cc.dbf
alter tablespace CC rename datafile
'/oracle01/ebay/oradata/cc.dbf' to
'/oracle/rdata01/ebay/oradata/cc.dbf';
alter tablespace CC online;
                                               
/oracle03/ebay/oradata/discover01.dbf              DISCOVER01                                       
alter tablespace DISCOVER01  offline;
cp /oracle03/ebay/oradata/discover01.dbf /oracle/rdata01/ebay/oradata/discover01.dbf
alter tablespace DISCOVER01 rename datafile
'/oracle03/ebay/oradata/discover01.dbf' to
'/oracle/rdata01/ebay/oradata/discover01.dbf';
alter tablespace DISCOVER01 online;

/oracle-items/ebay/oradata/achistoryd01.dbf        ACHISTORYD01
alter tablespace ACHISTORYD01  offline;
cp /oracle-items/ebay/oradata/achistoryd01.dbf /oracle/rdata01/ebay/oradata/achistoryd01.dbf
alter tablespace ACHISTORYD01 rename datafile
'/oracle-items/ebay/oradata/achistoryd01.dbf' to
'/oracle/rdata01/ebay/oradata/achistoryd01.dbf';
alter tablespace ACHISTORYD01 online;

                                     
/oracle12/ebay/oradata/bidarc1.dbf                 BIDARC1
/oracle03/ebay/oradata/bidarc1a.dbf                BIDARC1                                          
alter tablespace BIDARC1  offline;
cp /oracle12/ebay/oradata/bidarc1.dbf /oracle/rdata01/ebay/oradata/bidarc1.dbf
cp /oracle03/ebay/oradata/bidarc1a.dbf /oracle/rdata01/ebay/oradata/bidarc1a.dbf
alter tablespace BIDARC1 rename datafile
'/oracle12/ebay/oradata/bidarc1.dbf' to
'/oracle/rdata01/ebay/oradata/bidarc1.dbf';
alter tablespace BIDARC1 rename datafile
'/oracle03/ebay/oradata/bidarc1a.dbf' to
'/oracle/rdata01/ebay/oradata/bidarc1a.dbf';
alter tablespace BIDARC1 online;

                                          
/oracle09/ebay/oradata/bidarci1.dbf                BIDARCI1
alter tablespace BIDARCI1  offline;
cp /oracle09/ebay/oradata/bidarci1.dbf /oracle/rdata01/ebay/oradata/bidarci1.dbf
alter tablespace BIDARCI1 rename datafile
'/oracle09/ebay/oradata/bidarci1.dbf' to
'/oracle/rdata01/ebay/oradata/bidarci1.dbf';
alter tablespace BIDARCI1 online;
                                        
/oracle-items/ebay/oradata/bidd02.dbf              BIDD02 (bid archive)
alter tablespace BIDD02  offline;
cp /oracle-items/ebay/oradata/bidd02.dbf /oracle/rdata01/ebay/oradata/bidd02.dbf
alter tablespace BIDD02 rename datafile
'/oracle-items/ebay/oradata/bidd02.dbf' to
'/oracle/rdata01/ebay/oradata/bidd02.dbf';
alter tablespace BIDD02 online;


/oracle03/ebay/oradata/itemarc2a.dbf               ITEMARC2                                         
/oracle03/ebay/oradata/itemarc2.dbf                ITEMARC2                                         
alter tablespace ITEMARC2  offline;
cp /oracle03/ebay/oradata/itemarc2.dbf /oracle/rdata01/ebay/oradata/itemarc2.dbf
cp /oracle03/ebay/oradata/itemarc2a.dbf /oracle/rdata01/ebay/oradata/itemarc2a.dbf
alter tablespace ITEMARC2 rename datafile
'/oracle03/ebay/oradata/itemarc2.dbf' to
'/oracle/rdata01/ebay/oradata/itemarc2.dbf';
alter tablespace ITEMARC2 rename datafile
'/oracle03/ebay/oradata/itemarc2a.dbf' to
'/oracle/rdata01/ebay/oradata/itemarc2a.dbf';
alter tablespace ITEMARC2 online;

/oracle01/ebay/oradata/itemarc3.dbf                ITEMARC3                                         
/oracle06/ebay/oradata/itemarc3a.dbf               ITEMARC3                                         
alter tablespace ITEMARC3  offline;
cp /oracle01/ebay/oradata/itemarc3.dbf /oracle/rdata01/ebay/oradata/itemarc3.dbf
cp /oracle06/ebay/oradata/itemarc3a.dbf /oracle/rdata01/ebay/oradata/itemarc3a.dbf
alter tablespace ITEMARC3 rename datafile
'/oracle01/ebay/oradata/itemarc3.dbf' to
'/oracle/rdata01/ebay/oradata/itemarc3.dbf';
alter tablespace ITEMARC3 rename datafile
'/oracle06/ebay/oradata/itemarc3a.dbf' to
'/oracle/rdata01/ebay/oradata/itemarc3a.dbf';
alter tablespace ITEMARC3 online;

/oracle10/ebay/oradata/itemarci2.dbf               ITEMARCI2                                        
alter tablespace ITEMARCI2  offline;
cp /oracle10/ebay/oradata/itemarci2.dbf /oracle/rdata01/ebay/oradata/itemarci2.dbf
alter tablespace ITEMARCI2 rename datafile
'/oracle10/ebay/oradata/itemarci2.dbf' to
'/oracle/rdata01/ebay/oradata/itemarci2.dbf';
alter tablespace ITEMARCI2 online;


NOT MOVED:
/oracle01/ebay/oradata/rbsebay1.dbf                RBS1                                             
/oracle07/ebay/oradata/rbsebay2a.dbf               RBS2                                             
/oracle07/ebay/oradata/rbsebay2.dbf                RBS2                                             
/oracle21/ebay/oradata/tempebay01.dbf              TEMP                                             
/oracle07/ebay/oradata/temp02.dat                  TEMP02                                           
/oracle07/ebay/oradata/temp02a.dat                 TEMP02                                           
/export/home/oracle7/dbs/tempdataebay.dbf          TEMPORARY_DATA                                   
/export/home/oracle7/dbs/toolebay.dbf              TOOLS                                            
/export/home/oracle7/dbs/usrebay.dbf               USERS                                            
/export/home/oracle7/dbs/usrdataebay.dbf           USER_DATA                                        
/oracle20/ebay/oradata/arctotaped01.dbf            ARCTOTAPED01                                     

ALREADY MOVED:
QUICK I/O:
/oracle/rdata01/ebay/oradata/accountd03.dbf        ACCOUNTD03                                       
/oracle/rdata01/ebay/oradata/accounti03.dbf        ACCOUNTI03                                       
/oracle/rdata01/ebay/oradata/feedbackd01.dbf       FEEDBACKD01                                      
/oracle/rdata01/ebay/oradata/itemi01.dbf           ITEMI01                                          

Not quick i/o:
/oracle/rdata01/ebay/oradata/ritemi01.dbf          RITEMI01                                         
/oracle/rdata01/ebay/oradata/summaryi01.dbf        SUMMARYI01                                       
/oracle/rdata01/ebay/oradata/taccounti01.dbf       TACCOUNTI01                                      
/oracle/rdata01/ebay/oradata/bizdevi01.dbf         BIZDEVI01                                        
/oracle/rdata01/ebay/oradata/adi01.dbf             ADI01                                            
/oracle/rdata01/ebay/oradata/itemarci1.dbf         ITEMARCI1                                        

/* moving cntrl2ebay.dbf to /oracle07 and move tempebay01.dbf to /oracle01 */

cp /oracle21/ebay/oradata/tempebay01.dbf /oracle01/ebay/oradata/tempebay01.dbf
alter database rename file
'/oracle21/ebay/oradata/tempebay01.dbf' to
'/oracle01/ebay/oradata/tempebay01.dbf';

alter tablespace rbs1 add datafile
'/oracle01/ebay/oradata/rbsebay1a.dbf' size 400M;
cp /oracle01/ebay/oradata/rbsebay1.dbf /oracle/rbackup01/ebay/backup/.

---------------

/* feedback detail move */


vxmkcdev -o oracle_file -s 1530m /oracle/rdata01/ebay/oradata/qfeedbackd02.dbf
vxmkcdev -o oracle_file -s 300m /oracle/rdata01/ebay/oradata/qfeedbacki021.dbf
vxmkcdev -o oracle_file -s 300m /oracle/rdata01/ebay/oradata/qfeedbacki022.dbf

	create tablespace qfeedbackd02
		datafile '/oracle/rdata01/ebay/oradata/qfeedbackd02.dbf'
		size 1530M 
		autoextend off;

	create tablespace qfeedbacki021
	    datafile '/oracle/rdata01/ebay/oradata/qfeedbacki021.dbf'
		size 300M autoextend off;

	create tablespace qfeedbacki022
	    datafile '/oracle/rdata01/ebay/oradata/qfeedbacki022.dbf'
		size 300M autoextend off;

/* in a dir with a lot of space */

exp scott/eif99 tables=ebay_feedback_detail direct=Y indexes=N grants=Y constraints=N buffer=819200 file=fbdet.dmp
/* 12:11 - 12:18 */


rename ebay_feedback_detail to ebay_feedback_detail_old;

 create table ebay_feedback_detail
 (
	id						int
		constraint	feedback_det_id_nn
		not null,
	time					date
		constraint	feedback_det_time_nn
		not null,
	commenting_id		int
		constraint	feedback_det_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_det_host_nn
		not null,
	comment_type		int
		constraint	feedback_det_type_nn
		not null,
	comment_score		int
		constraint	feedback_det_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_det_comment_nn
		not null
 )
 tablespace qfeedbackd02
 storage (initial 1024M next 200M pctincrease 0);
/* TO DO THIS */

5. import new table
imp scott/eif99 file = fbdet.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 47185920
/* was 122880 */

6. reinstate constraints and indices
						
 create index ebay_feedback_id_index
	on ebay_feedback_detail
	(id)
 storage(initial 250m next 50m)
 tablespace qfeedbacki021 unrecoverable parallel (degree 5);
commit;

 create index ebay_feedback_comment_id_index
	on ebay_feedback_detail
	(commenting_id)
 storage(initial 250m next 50m)
 tablespace feedbacki022 unrecoverable parallel (degree 5);
commit;

alter table ebay_feedback_detail
	add constraint		feedback_detail_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail
	add  constraint		feedback_detail_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


7. remove feedback tablespaces:
drop tablespace feedbackd02 including contents;
drop tablespace feedbacki02 including contents;

/* manually delete files for feedbackd02 and feedbacki02 */
rm /oracle/rdata01/ebay/oradata/feedbacki02.dbf
rm /oracle/rdata01/ebay/oradata/feedbacki02a.dbf

rm /oracle/rdata01/ebay/oradata/feedbackd02.dbf
rm /oracle/rdata01/ebay/oradata/feedbackd02a.dbf
rm /oracle/rdata01/ebay/oradata/feedbackd02b.dbf

/* item info */

------------
ebay_item_info
------------
vxmkcdev -o oracle_file -s 400m /oracle/rdata01/ebay/oradata/itemd03.dbf
vxmkcdev -o oracle_file -s 130m /oracle/rdata01/ebay/oradata/itemi03.dbf

1. create tablespace for item_info
	create tablespace itemd03
		datafile '/oracle/rdata01/ebay/oradata/itemd03.dbf'
		size 400M 
		autoextend off;

	create tablespace itemi03
	    datafile '/oracle/rdata01/ebay/oradata/itemi03.dbf'
		size 150M autoextend off;


2. cd to /oracle-items/ebay (space!)
exp scott/haw98 tables=ebay_item_info direct=Y indexes=N grants=Y constraints=N file=iinfo.dmp

/* system can be up from now on */
3. rename existing table? or drop it?
drop table ebay_item_info;

4. create new table with new tablespace 

 create table ebay_item_info
 (
	MARKETPLACE			NUMBER(38)
		constraint		item_info_marketplace_nn
			not null,
	ID						NUMBER(38)
		constraint		item_info_id_nn
			not null,
	NOTICE_TIME			date,
	BILL_TIME			date
 )
tablespace itemd03
storage(initial 200M next 99m);


5. import in new table
imp scott/haw98 file = iinfo.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880

6. reinstate constraints and indices
alter table ebay_item_info
	add constraint		item_info_pk
		primary key		(marketplace, id)
		using index tablespace	itemi03
		storage (initial 75M next 35M);

alter table ebay_item_info
	add constraint		item_info_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id);

7. if successful, 
drop tablespace ritemd03 including contents;
drop tablespace ritemi03 including contents;
delete the file ritemd03.dbf and ritemi03.dbf.

/* user info? */




/* ------------------------------------------------ */
/* ITEMS with quick i/o- TO REVISE */
1. create tablespace
vxmkcdev -o oracle_file -s 2047m /oracle/rdata01/ebay/oradata/itemd01.dbf

create tablespace itemd01
		datafile '/oracle/rdata01/ebay/oradata/itemd01.dbf'
		size 2047M 
		autoextend off;

	create tablespace itemi01
	    datafile '/oracle18/ebay/oradata/itemi01.dbf'
		size 1023M autoextend off;

2. export
exp scott/haw98 tables=ebay_items direct=Y indexes=N grants=Y constraints=N file=items.dmp


5. Rename(!) ebay_items table in production. 

	rename ebay_items to ebayt_items;
	// failed
	rename bids_item_fk to bids_itemt_fk;
	rename item_info_fk to itemt_info_fk;
	rename special_items_fk to special_itemt_fk;

	/*
alter table ebay_bids	drop constraint bids_item_fk;

alter table ebay_item_desc	drop constraint	items_marketplace_id_fk;

alter table ebay_item_info	drop constraint item_info_fk;

alter table ebay_special_items	drop constraint special_items_fk;
	*/

7. create table in new tablespace with a given starting extent (140%)
   with no constraints; to be added after table is imported.

 create table ebay_items
 (
	marketplace			number(38)
		constraint	items_marketplace_nn
				not null,
	id						number(38)
		constraint		items_id_nn
			not null,
	sale_type			number(38)
		constraint		items_sale_type_nn
			not null,
	title				varchar2(254)
		constraint		items_title_nn
			not null,
	location			varchar2(254)
		constraint		items_location_nn
			not null,
	seller				number(38)
		constraint	items_seller_nn
				not null disable,
	owner					number(38)
		constraint	items_owner_nn
				not null disable,
	password			number(38)
		constraint		items_password_nn
			not null,
	category			number(38)
		constraint		items_category_nn
			not null
			disable,
	quantity				number(38)
		constraint		items_quantity_nn
			not null,
	bidcount				number(38)
		constraint		items_bidcount_nn
			not null,
	created				date
		constraint		items_created_nn
			not null,
	sale_start			date
		constraint		items_sale_start_nn
			not null,
	sale_end				date
		constraint		items_sale_end_nn
			not null,
	sale_status			number(38)
		constraint		items_sale_status_nn
			not null,
	current_price		number(11,2)
		constraint		items_current_price_nn
			not null,
	start_price			number(11,2)
		constraint		items_start_price_nn
			not null,
	reserve_price		number(11,2)
		constraint		items_reserve_price_nn
			not null,
	high_bidder			number(38)
			,
	featured				char(1)
			constraint		items_featured_nn
			not null,
	super_featured			char(1)
			constraint		items_super_featured_nn
			not null,
	bold_title			char(1)
			constraint		items_bold_title_nn
			not null,
	private_sale		char(1)
			constraint		items_private_sale_nn
			not null,
	registered_only	char(1)
			constraint		items_registered_only_nn
			not null,
	host				varchar(64),
	visitcount			number(38)
			constraint		items_visit_count_nn
			not null,			
	picture_url			varchar(255),
	last_modified		date
		constraint	items_last_modified_nn
		not null
)
tablespace ritemd01
storage(initial 500m next 100m);

Q: We need the not null constraints because we can't assert it after the import;
Do we want the pk and fk here or create it after import?

Q: ebay_items_sequence? leave it untouched or set it to max item id + 1.


8. import into new table. parameter?
imp scott/tiger
file = expdat.dmp
commit=Y 
grants=Y 
ignore=Y
Full=Y
time: 12:39 - 1:18 =  1095976 rows imported

9. create indices on ebay_items

alter table ebayt_items drop constraint items_pk;

drop index ebay_items_seller_index;
drop index ebay_items_high_bidder_index;
drop index ebay_items_starting_index;
drop index ebay_items_ending_index;
drop index ebay_items_category_index;
drop index ebay_items_last_modified_index;

alter table ebayt_items drop constraint items_category_fk;
alter table ebayt_items drop constraint items_marketplace_fk;
alter table ebayt_items drop constraint items_seller_fk;
alter table ebayt_items drop constraint items_owner_fk;
alter table ebayt_items drop constraint items_high_bidder_fk;


alter table ebay_items 
	add constraint			items_pk
		primary key		(marketplace, id)
		using index tablespace	ritemi01
		storage (initial 70M next 30M) unrecoverable parallel (degree 3);
/* 1:22 - 1:43 - failed because of parallel error  1:44 - 1:49 
ERROR at line 1:
ORA-12812: only one PARALLEL or NOPARALLEL clause may be specified
removed parallel clause. 
*/

 create index ebay_items_seller_index
	on ebay_items(seller)
	tablespace ritemi01
	storage(initial 70m next 30M) unrecoverable parallel (degree 3);
/* 1:50 - 1:53 */

 create index ebay_items_high_bidder_index
   on ebay_items(high_bidder)
   tablespace ritemi01
   storage(initial 70m next 30M) unrecoverable parallel (degree 3);
/* 1:54 - 1:57 */

 create index ebay_items_starting_index
   on ebay_items(sale_start)
   tablespace ritemi01
   storage(initial 70M next 30M) unrecoverable parallel (degree 3);
/* 1:57 - 2:03 */

 create index ebay_items_ending_index
   on ebay_items(sale_end)
   tablespace ritemi01
   storage(initial 70M next 30M) unrecoverable parallel (degree 3);
/* 2:03 - 2:07 */

 create index ebay_items_category_index 
	on ebay_items(category)
	tablespace ritemi01
	storage(initial 70m next 30m) unrecoverable parallel (degree 3);
/* 2:07 - 2:11 */

 create index ebay_items_last_modified_index
   on ebay_items(last_modified)
   tablespace ritemi01
   storage(initial 100m next 30M) unrecoverable parallel (degree 3);
/* 2:11 - 2:16 */

10. reinstate constraints

alter table ebay_bids
    add constraint bids_item_fk
			foreign key (marketplace, item_id)
			references	ebay_items(marketplace, id);
/* 2:16 - 2:20 */
	 
alter table ebay_item_desc
   add constraint items_marketplace_id_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id);
/* 2:20 - 2:26 */

alter table ebay_item_info
	add constraint	item_info_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id);
/* 2:26 - 2: 2:28 */

alter table ebay_special_items
	add constraint	special_items_fk
	foreign key (marketplace, id)
	references	ebay_items(marketplace, id);
/* 2:28 - 2:28 */

alter table ebay_items
	add constraint			items_category_fk
		foreign key(marketplace, category)
		references ebay_categories(marketplace, id);
/* 2:29 - 2:30 */

 alter table ebay_items
	add constraint		items_marketplace_fk
	foreign key (marketplace)
			references ebay_marketplaces(id);
/* 2:31 - 2:*/

 alter table ebay_items
	add constraint		items_seller_fk
	foreign key (seller)
			references ebay_users(id);
/* 2:34 - */

 alter table ebay_items
	add constraint		items_owner_fk
	foreign key (owner)
			references ebay_users(id);
/* 2:36 - */

 alter table ebay_items
	add constraint		items_high_bidder_fk
	foreign key (high_bidder)
				references ebay_users(id);
/* 2:38 - */



/* time taken:  */


/* July 19, 1998 ebay bids coalesce */

1. create tablespaces:

vxmkcdev -o oracle_file -s 2047m /oracle/rdata05/ebay/oradata/qbidd01.dbf
vxmkcdev -o oracle_file -s 1024m /oracle/rdata04/ebay/oradata/qbidi01.dbf
vxmkcdev -o oracle_file -s 1024m /oracle/rdata04/ebay/oradata/qbidi02.dbf

create tablespace qbidd01
	datafile '/oracle/rdata05/ebay/oradata/qbidd01.dbf'
	size 2047M 
	autoextend off;

create tablespace qbidi01
	datafile '/oracle/rdata04/ebay/oradata/qbidi01.dbf'
	size 1024M 
	autoextend off;

create tablespace qbidi02
	datafile '/oracle/rdata04/ebay/oradata/qbidi02.dbf'
	size 1024M 
	autoextend off;

exp scott/eif99 tables=ebay_bids direct=Y indexes=N constraints=N grants=Y buffer=819200 rows=Y file=bids.dmp log=bids.log

rename ebay_bids to ebayt_bids;
// drop index ebay_bids_item_index;
// drop index ebay_bids_item_user_index;
// drop index ebay_bids_user_index;
// alter table ebayt_bids drop constraint bid_check_quantity;
// alter table ebayt_bids drop constraint bid_check_amount;
// alter table ebayt_bids drop constraint bid_item_fk;
// alter table ebayt_bids drop constraint bid_user_fk;


 create table ebay_bids 
 (
	marketplace	int
		constraint	qbid_marketplace_fk
		not null
		references	ebay_marketplaces(id),
 	item_id		int
		constraint	qbid_item_id_nn
		not null,
	user_id		int
		constraint	qbid_user_id_nn
		not null,
	quantity		int
		constraint	qbid_quantity_nn
		not null,
	amount		number(10,2)
		constraint	qbid_amount_nn
		not null,
	value			number(10,2)
		constraint	qbid_value_nn
		not null,
	type			int
		CONSTRAINT qbid_check_type
		CHECK (type IN (0, 1, 2, 3, 4, 5, 6)),
	created		date
		constraint	qbid_created_nn
		not null,
	reason		varchar2(255),
	host		varchar(16)
 )
 tablespace qbidd01
 storage (initial 1024M next 500M);

imp scott/eif99 file = bids.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 819200
/* 12:23 - 1:40 */
/* next time use buffer = 47185920; commits? */

/* use ebaybig.ora */

 create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace qbidi01
	storage(initial 700M next 150M) unrecoverable parallel (degree 6);
/* 1:48 - did not finish at 3:20. Aborted. */

 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace qbidi02
   storage(initial 700M next 150M) unrecoverable parallel (degree 8);

alter table ebay_bids
add	constraint		bid_check_quantity
		check (quantity >= 0);

alter table ebay_bids
add	constraint		bid_check_amount
		check (amount >= 0);

alter table ebay_bids 
add constraint	bid_item_fk	
foreign key (marketplace, item_id)
			references	ebay_items(marketplace, id);

alter table ebay_bids
add	constraint		bid_user_fk
			foreign key (user_id)
			references	ebay_users(id);

/* check to ensure no segments in bidi02 and bidd01 */

drop table ebayt_bids;

drop tablespace rbidd01 including contents;
drop tablespace rbidi01 including contents;
drop tablespace rbidi03 including contents;

exp scott/eif99 tables=ebay_users direct=Y indexes=N grants=Y constraints=N buffer=819200 rows=Y file=users.dmp


drop table ebay_user_info_new;

create table ebay_user_info_new as 
select * from ebay_user_info where id in 
(select id from ebay_users where user_state <> 5 or 
(user_state=2 and last_modified < sysdate-7)) 
tablespace quserd02 storage (initial 500M next 50M) 
unrecoverable;

alter table ebay_user_info_new modify 
(credit_card_on_file default chr(0), 
good_credit default chr(0), gender default 'u', 
interests_1 default 0, interests_2 default 0,
interests_3 default 0, interests_4 default 0, 
partner_id default 0, req_email_count default 0, 
req_info_count default 0);


alter table ebay_user_info_new add constraint 
quser_info_pk primary key(id) using index 
tablespace quseri02 storage (initial 30M next 20M) unrecoverable;
  
alter table ebay_user_info_new add constraint 
quser_info_email_unq unique (email) using 
index tablespace quseri02 storage(initial 70M next 20M) unrecoverable;


drop table ebay_users_new;

create table ebay_users_new as 
select * from ebay_users where 
user_state <> 5 or 
(user_state=2 and last_modified < sysdate-7) 
tablespace quserd01 storage (initial 550M next 100M) unrecoverable;

alter table ebay_users_new modify (flags default 0);

alter table ebay_users_new add constraint rusers_pk 
primary key (id) using index tablespace quseri01 
storage (initial 30M next 20M) unrecoverable;

alter table ebay_users_new add constraint 
qusers_marketplace_userid_unq unique (marketplace, userid) 
using index tablespace quseri01 storage(initial 70m next 20m) unrecoverable;

alter table ebay_users_new add constraint quser_userid_unq unique (userid)
using index tablespace quseri01 storage(initial 70m next 20m) unrecoverable;


/* Create constraints that point to EBAY_USERS(id)			*/

alter table EBAY_FEEDBACK add constraint 
FEEDBACK_FK_QIO foreign key (ID) references EBAY_USERS(id);
alter table EBAY_INTERIM_BALANCES add constraint 
INTERIM_BALANCES_FK_QIO foreign key (ID) references EBAY_USERS(id);
alter table EBAY_ITEMS add constraint 
ITEMS_SELLER_FK_QIO foreign key (SELLER) references EBAY_USERS(id);
alter table EBAY_ITEMS add constraint 
ITEMS_HIGH_BIDDER_FK_QIO foreign key (HIGH_BIDDER) references EBAY_USERS(id);
alter table EBAY_ITEMS add constraint 
ITEMS_OWNER_FK_QIO foreign key (OWNER) references EBAY_USERS(id);
alter table EBAY_RECIPROCAL_LINKS add constraint 
RECIP_USER_FK_QIO foreign key (USERID) references EBAY_USERS(id);
alter table EBAY_USER_ATTRIBUTES add constraint 
RATTR_FK_QIO foreign key (USER_ID) references EBAY_USERS_NEW(id);
alter table EBAY_USER_INFO add constraint 
RUSER_INFO_FK_QIO foreign key (ID) references EBAY_USERS_NEW(id);

alter table EBAY_SPECIAL_ITEMS add constraint SPECIAL_ITEMS_WHO_ADDED_FK_QIO foreign key (WHO_ADDED) references EBAY_USERS(id);
alter table EBAY_SELLER_ITEM_LISTS add constraint ITEM_LISTS_FK_QIOQ foreign key (ID) references EBAY_USERS(id);
alter table EBAY_USER_SURVEY_RESPONSES add constraint USER_SURVEY_RESPONSES_FK_QIO foreign key (USER_ID) references EBAY_USERS(id);
alter table EBAY_XACCOUNTS add constraint XACCOUNTS_FK_QIO foreign key (ID) references EBAY_USERS(id);
alter table EBAY_ACCOUNTS add constraint ACCOUNTS_FK_QIO foreign key (ID) references EBAY_USERS(id);
alter table EBAY_ACCOUNT_BALANCES add constraint ACCOUNT_BALANCES_FK_QIO foreign key (ID) references EBAY_USERS(id);
alter table EBAY_ACCOUNT_XREF add constraint ACCOUNT_XREF_FK_QIO foreign key (ID) references EBAY_USERS(id);
alter table EBAY_ADMIN add constraint ADMIN_FK_QUI foreign key (ID) references EBAY_USERS(id);
alter table EBAY_BIDDER_ITEM_LISTS add constraint ITEM_BLISTS_FK_QIOQ foreign key (ID) references EBAY_USERS(id);
alter table EBAY_BIDS add constraint RBID_USER_FK_QIO foreign key (USER_ID) references EBAY_USERS(id);
alter table EBAY_FEEDBACK_DETAIL add constraint FEEDBACK_DETAIL_FK_QIO1 foreign key (ID) references EBAY_USERS(id);
alter table EBAY_FEEDBACK_DETAIL add constraint FEEDBACK_DETAIL_FK_QIO2 foreign key (COMMENTING_ID) references EBAY_USERS(id);
alter table EBAY_BULLETIN_BOARDS add constraint BBOARD_BOARD_FK_QIOQ foreign key (USER_ID) references EBAY_USERS(id);
alter table EBAY_BULLETIN_BOARDS_BQIO add constraint BBOARD_BOARD_FK_QIO foreign key (USER_ID) references EBAY_USERS(id);
/

/* Move users */
/* create tablespaces */

vxmkcdev -o oracle_file -s 760m /oracle/rdata05/ebay/oradata/quserd01.dbf
vxmkcdev -o oracle_file -s 610m /oracle/rdata01/ebay/oradata/quserd02.dbf

	create tablespace quserd01
		datafile '/oracle/rdata05/ebay/oradata/quserd01.dbf'
		size 760M 
		autoextend off;

	create tablespace quserd02
	    datafile '/oracle/rdata01/ebay/oradata/quserd02.dbf'
		size 610M autoextend off;

vxmkcdev -o oracle_file -s 310m /oracle/rdata04/ebay/oradata/quseri01.dbf
vxmkcdev -o oracle_file -s 210m /oracle/rdata04/ebay/oradata/quseri02.dbf

	create tablespace quseri01
		datafile '/oracle/rdata04/ebay/oradata/quseri01.dbf'
		size 310M 
		autoextend off;

	create tablespace quseri02
	    datafile '/oracle/rdata04/ebay/oradata/quseri02.dbf'
		size 210M autoextend off;

/* create table */

 create table ebay_users2
 (
	marketplace		int,
	id			int 
		constraint	qusers_id_nn
		not null,
	userid			varchar(64)
		constraint	qusers_userid_nn
		not null,
	user_state		int 
		constraint	qusers_user_state_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	last_modified		date
		constraint	qusers_last_modified_nn
		not null,
	userid_last_change date,
	flags			number(16)
	)
 tablespace quserd01
 storage (initial 550M next 100m);

alter table ebay_users2
	add 	constraint	qusers_marketplace_fk
		foreign key (marketplace)
		references	ebay_marketplaces(id);

alter table ebay_users2
	add  constraint		qusers_pk
		primary key(id)
		using index	tablespace quseri01
		storage(initial 30m next 20m);

/* is this necessary?! */
alter table ebay_users2
	add 	constraint		qusers_marketplace_userid_unq
		unique (marketplace, userid)
		using index	tablespace quseri01
		storage(initial 70m next 20m);
					
alter table ebay_users2
	add constraint	quser_userid_unq
		unique (userid)
		using index tablespace quseri01
		storage(initial 70m next 20m);

create table ebay_user_info2
(
	id			int
		constraint	quser_info_id_nn
		not null,
	host			varchar(64)
		constraint	qusers_host_nn
		not null,
	name			varchar(64)
		constraint	qusers_name_nn
		not null,
	company			varchar(64),
	address			varchar(64)
		constraint	qusers_address_nn
		not null,
	city			varchar(64)
		constraint	qusers_city_nn
		not null,
	state			varchar(64)
		constraint	qusers_state_nn
		not null,
	zip			varchar(12)
		constraint	qusers_zip_nn
		not null,
	country			varchar(64)
		constraint	qusers_country_nn
		not null,
	dayphone		varchar(32),
	nightphone		varchar(32),
	faxphone		varchar(32),
	creation		date
		constraint	qusers_creation_nn
		not null,
	email			varchar(64)
		constraint	quser_email_nn
		not null,
	count			int
		default 0,
	credit_card_on_file	char
		default chr(0)
		constraint	qcredit_info_cc_nn
		not null,
	good_credit		char
		default chr(0)
		constraint	qcredit_info_gc_nn
		not null,
	gender			char
		default 'u',
	interests_1		int
		default 0,
	interests_2		int
		default 0,
	interests_3		int
		default 0,
	interests_4		int
		default 0,
	partner_id		number(3,0)
		default 0,
	req_email_count number(10,0) 
		default 0,
	REQ_INFO_COUNT NUMBER(4)
		default 0,
	REQ_INFO_DATE  DATE,
	REQ_INFO_HOST  VARCHAR2(64)
)
 tablespace quserd02
 storage (initial 500M next 50m);

alter table ebay_user_info2
	add constraint		quser_info_pk
      	primary key(id)
      	using index tablespace quseri02
		storage(initial 30m next 20m);
commit;
  
alter table ebay_user_info2
	add	constraint		quser_info_fk
		foreign key (id)
		references	ebay_users2(id);
commit;

alter table ebay_user_info2
	add constraint	quser_info_email_unq
		unique (email)
		using index tablespace quseri02
		storage(initial 70m next 20m);



/* populate table */
1. all active users
2. all suspended users
3. all unconfirmed users < 7 days?
4. count all unconfirmed users < '1998-07-20'; /* 164578 */


insert into ebay_users2 
(select * from ebay_users where user_state = 1 or user_state = 0 or 
user_state = 3);
/* 1:44 - 2:22 rollback segment too small! */

/* split into several passes */
insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id > 0) and (id < 100000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 100000) and (id < 200000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 200000) and (id < 300000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 300000) and (id < 400000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 400000) and (id < 500000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 500000) and (id < 600000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 600000) and (id < 700000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 700000) and (id < 800000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 800000) and (id < 900000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 900000) and (id < 1000000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 1000000) and (id < 1100000));
commit;

insert into ebay_users2 
(select * from ebay_users where 
   (user_state = 1 or user_state = 0 or user_state = 3)
   and (id >= 1100000));
commit;

insert into ebay_users2
(select * from ebay_users where user_state = 2 and 
last_modified >= TO_DATE('1998-07-20 00:00:00', 'YYYY-MM-DD HH24:MI:SS'));


insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id > 0 and id < 100000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 100000 and id < 200000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 200000 and id < 300000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 300000 and id < 400000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 400000 and id < 500000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 500000 and id < 600000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 600000 and id < 700000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 700000 and id < 800000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 800000 and id < 850000)));
commit;
insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 850000 and id < 900000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 900000 and id < 950000)));
commit;
insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 950000 and id < 1000000)));
commit;

/* here */

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 1000000 and id < 1050000)));
commit;
insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 1050000 and id < 1100000)));
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in 
(select id from ebay_users2 where (id >= 1100000)));
commit;

insert into ebay_users2
(select * from ebay_users where user_state = 6);
commit;

insert into ebay_user_info2
(select * from ebay_user_info where id in
(select id from ebay_users where user_state = 6));

/* validate counts */

# users should total up except for those whose state = 2 and is > 7 days old.

/* rename tables */

rename ebay_users to ebay_users_old;
rename ebay_user_info to ebay_user_info_old;

rename ebay_users2 to ebay_users;
rename ebay_user_info2 to ebay_user_info;

rename ebay_users to ebay_users2;
rename ebay_user_info to ebay_user_info2;

rename ebay_users_old to ebay_users;
rename ebay_user_info_old to ebay_user_info;
commit;


/* reassert all constraints to new user tables */

alter table ebay_user_attributes
	add	constraint		rattr_ufk
		foreign key (user_id)
		references	ebay_users(id);
commit;
/* parent key not found? */

/* dropped! */
alter table ebay_aw_credit_status
	add	constraint		rcredit_info_pk
			primary key (userid)
			using index tablespace ruseri05
			storage (initial 500k next 250k);
commit;

alter table ebay_admin
	add		constraint		admin_ufk
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_account_balances
	add	constraint		account_balances_ufk
			foreign key (id)
			references	ebay_users(id);
commit;
/* parent key not found? */

alter table ebay_accounts
	add		constraint		accounts_ufk
			foreign key (id)
			references	ebay_users(id);
commit;
alter table ebay_bids
	add	constraint		bids_user_ufk
			foreign key (user_id)
			references	ebay_users(id);
commit;
alter table ebay_items
	add		constraint		items_seller_ufk
			foreign key (seller)
			references ebay_users(id);
commit;
alter table ebay_items
	add		constraint		items_owner_ufk
			foreign key (owner)
			references ebay_users(id);
commit;
alter table ebay_items
	add		constraint		items_high_bidder_ufk
			foreign key (high_bidder)
			references ebay_users(id);
commit;
alter table ebay_account_xref
	add	constraint			account_xref_ufk
			foreign key(id)
			references ebay_users(id);

alter table ebay_feedback
	add	constraint		feedback_ufk
			foreign key (id)
			references	ebay_users(id);
commit;
alter table ebay_feedback_detail
	add 	constraint		feedback_detail_ufk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;
alter table ebay_feedback_detail
	add 	constraint		feedback_detail_ufk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_seller_item_lists
	add		constraint			item_lists_ufk
			foreign key (id)
			references	ebay_users(id);

alter table ebay_bidder_item_lists
	add		constraint			item_blists_ufk
			foreign key (id)
			references	ebay_users(id);

alter table ebay_bulletin_boards
	add constraint		bboard_board_ufk
		foreign key (user_id)
		references	ebay_users(id)

alter table ebay_special_items
	add constraint	special_items_who_added_ufk
		foreign key (WHO_ADDED)
		references ebay_users(id);
commit;

alter table ebay_iterim_balances
	add	constraint		interim_balances_ufk
			foreign key (id)
			references	ebay_users(id);

alter table ebay_user_survey_responses
	add constraint		user_survey_responses_ufk
		foreign key (user_id)
		references	ebay_users(id);
commit;

alter table ebay_reciprocal_links
	add		constraint		recip_user_ufk
			foreign key (userid)
			references	ebay_users(id);

/* drop constraint to old table */

alter table ebay_account_balances drop constraint account_balances_fk;
alter table ebay_accounts drop constraint accounts_fk;
alter table ebay_account_xref drop constraint account_xref_fk;
alter table ebay_bids drop constraint bids_user_fk;
alter table ebay_items drop constraint items_seller_fk;
alter table ebay_items drop constraint items_owner_fk;
alter table ebay_items drop constraint items_high_bidder_fk;

alter table ebay_feedback drop constraint feedback_fk;

alter table ebay_feedback_detail drop constraint feedback_detail_fk1;
alter table ebay_feedback_detail drop constraint feedback_detail_fk2;
alter table ebay_admin drop constraint admin_fk;

alter table ebay_seller_item_lists drop constraint item_lists_fk;
alter table ebay_bidder_item_lists drop constraint item_blists_fk;
alter table ebay_user_survey_responses
	drop constraint		user_survey_responses_fk;
alter table ebay_bulletin_boards
    drop constraint bboard_board_fk;
alter table ebay_iterim_balances
	drop	constraint		interim_balances_fk;
alter table ebay_reciprocal_links
	drop		constraint		recip_user_fk;


/* drop old table? */
/* user index 04 */

vxmkcdev -o oracle_file -s 1200m /oracle/rdata04/ebay/oradata/qruseri04.dbf

create tablespace qruseri04
	datafile '/oracle/rdata01/ebay/oradata/qruseri04.dbf'
	size 1200M 
	autoextend off;

// 1. put the events on initebay.ora and restart the database
// 2. cd to volume with enough space.
exp scott/eif99 tables=ebay_bidder_item_lists direct=Y indexes=N constraints=N grants=Y buffer=1228800 rows=Y file=biditems.dmp log=biditems.log

rename ebay_bidder_item_lists TO ebay_bidder_item_lists_bad;
// create tablespaces
create tablespace userd07
	datafile '/oracle/rdata09/ebay/oradata/userd07.dbf'
	size 2000M 
	autoextend off;

create tablespace useri07
	datafile '/oracle/rdata07/ebay/oradata/useri07.dbf'
	size 300M 
	autoextend off;


// create new table
	create table ebay_bidder_item_lists
	(
		id						int
			constraint			item_blists_id_nn
			not null,
		item_count				int
			constraint			item_blists_item_count_nn
			not null,
		item_list_size			int
			constraint			item_blists_list_size_nn
			not null,
		item_list_size_used		int
			constraint			item_blists_list_used_nn
			not null,
		item_list_valid			char(1)
			constraint			item_blists_valid_nn
			not null,
		item_list				long raw
	)
	tablespace userd07
	storage (initial 1024M next 100M);

	commit;


imp scott/eif99 file=biditems.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 1228800

// create index
// reinstate constraints

alter table ebay_bidder_item_lists
	add  constraint		item_bidlist_pk
		primary key(id)
		using index	tablespace useri07
		storage(initial 100m next 50m) unrecoverable;

alter table ebay_bidder_item_lists
	add constraint		item_bidlist_fk
		foreign key (id)
		references ebay_users(id);
