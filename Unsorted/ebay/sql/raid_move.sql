/*	$Id: raid_move.sql,v 1.4 1999/02/21 02:54:50 josh Exp $	*/
/* steps to move items data to the raid device */
Testing:
1. create test user
alter tablespace achistoryd01
  rename datafile '/oracle07/ebay/oradata/achistoryd01.dbf'
  to '/oracle-items/ebay/oradata/achistoryd01.dbf';


create user tini identified by xxx;
grant connect, resource to tini;

2. create new tablespace

create tablespace tinid01
	datafile '/oracle-items/ebay/oradata/tinid01.dbf'
	size 400M 
	autoextend on next 100M;
create tablespace tinii01
	datafile '/oracle-items/ebay/oradata/tinii01.dbf'
	size 400M
	autoextend on next 100M;

3. create new item table (temp)

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
	current_price		number(10,2)
		constraint		items_current_price_nn
			not null,
	start_price			number(10,2)
		constraint		items_start_price_nn
			not null,
	reserve_price		number(10,2)
		constraint		items_reserve_price_nn
			not null,
	high_bidder			number(38),
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
		not null,
	constraint			items_pk
		primary key		(marketplace, id)
		using index tablespace	tinii01
		storage (initial 60m next 10m)
)
tablespace tinid01
storage(initial 499m next 99m);

4. import.
imp tini/tigger
file = expdat.dmp
commit=Y 
grants=Y 
ignore=Y
Full=Y
...

5. test create of sale_start and last_modified index.

create temporary tablespace:

create tablespace temp
	datafile '/oracle21/ebay/oradata/tempebay01.dbf' size 200M
	online temporary;
alter user tini temporary tablespace tinitemp;

 create index ebay_items_starting_index
   on ebay_items(sale_start)
   tablespace tinii01
   storage(initial 40M next 20M);

 create index ebay_items_ending_index
   on ebay_items(sale_end)
   tablespace tinii01
   storage(initial 60M next 20M);

 create index ebay_items_last_modified_index
   on ebay_items(last_modified)
   tablespace tinii01
   storage(initial 200M next 50M);

---------------------

ITEM TABLE MOVE
---------------
0. run iowts before change

1. cold backup (database up, iis on komodo & iguana down);
   cold backup and arcs to tape.
   comment out all cron jobs for the time.

2. calculate space required and time expected to export and import;
   find the space (/oracle05/ebay/oradata/).
   ebay_items_arc: 247573 rows takes: 1 min export, 6 min imp, 45MB.
   ebay_items: 1346757/1M after arcs expected: 5 min export, 30 min import, 250MB. 
   
   using dba_segments, ebay_items size approx: 320MB, next extent 150MB.
   new tablespace: 500MB, next extent 100MB.
    
3. drop all indices on the item table:
/* DO NOT USE -- USE RENAME */
alter user scott temporary tablespace tinitemp;
/* was TEMP */

    drop index ebay_items_starting_index
	drop index ebay_items_ending_index
	drop index ebay_items_category_index
	drop index ebay_items_seller_index
	drop index ebay_items_high_bidder_index
	drop index ebay_items_last_modified_index
	drop index items_pk

    drop constraint items_category_fk
	drop constraint	items_marketplace_fk
	drop constraint	items_high_bidder_fk
	commit
	
4. export item table into /oracle03/ebay.
#   export parameters file
userid=scott
buffer=640K
file=items.export
full=y
inctype=complete
log=export.log
rows=y
indexes=n
grants=y
constraints=n
compress=y
consistent=y
statistics=none

cd /oracle03/ebay
exp scott/tiger tables=ebay_items indexes=N grants=Y constraints=N
time: 12:21 - 12:27 = 6 mins; 1095976 rows exported

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

6. create tablespace for ebay_items on raid device (what's the size?)

	create tablespace ritemd01
		datafile '/oracle-items/ebay/oradata/ritemd01.dbf'
		size 501M 
		autoextend on next 101M;

	create tablespace ritemi01
	    datafile '/oracle18/ebay/oradata/ritemi01.dbf'
		size 500M autoextend on next 100M;

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

alter table ebay_items drop constraint items_category_fk;
alter table ebay_items drop constraint items_marketplace_fk;
alter table ebay_items drop constraint items_seller_fk;
alter table ebay_items drop constraint items_owner_fk;
alter table ebay_items drop constraint items_high_bidder_fk;


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


11. reinstate cron jobs

12. all systems up: run iowts after change.


/* in parallel, in place -- drop and recreate IF THERE IS TIME */
- rebuild bids indices -- these point to the old item table

create tablespace bidi02
	datafile '/oracle18/ebay/oradata/bidi02.dbf'
	size 810M 
	autoextend on next 100M;

drop index ebay_bids_item_user_index;
drop index ebay_bids_item_index;
drop index ebay_bids_user_index; /* dropped by accident */

create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace bidi02
	storage(initial 300M next 50M)  unrecoverable parallel (degree 3);
/* 2:47 - 3:06 */

create index ebay_bids_item_index
	on ebay_bids(item_id)
	tablespace bidi02
	storage(initial 300M next 50M)  unrecoverable parallel (degree 3);
/* 3:06 - 3:18 */


 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace bidi02
   storage(initial 200M next 50M) unrecoverable parallel (degree 3);
/* 3:18 - 3:30 */

/* check bidi01 contents and drop tablespace bidi01 including contents */
drop tablespace bidi01 including contents;

 alter tablespace bidi02 rename datafile 
 '/oracle18/ebay/oradata/bidi02.dbf' to '/oracle07/ebay/oradata/bidi02.dbf' ;

13. drop old ebay_items.

	drop table ebayt_items;



/******************************************************************/
/* RAID PART II  ------------ TO BE DONE */
/******************************************************************/

-----------------
moving ebay_categories and ebay_special_items to staticmisc and dynmisc
tablespaces
-----------------

1. create the necessary tablespaces:

create tablespace statmiscd
	datafile '/oracle-items/ebay/oradata/statmiscd.dbf'
	size 101M 
	autoextend on next 50M;

create tablespace statmisci
	datafile '/oracle18/ebay/oradata/statmisci.dbf'
	size 101M 
	autoextend on next 50M;

create tablespace dynmiscd
	datafile '/oracle-items/ebay/oradata/dynmiscd.dbf'
	size 101M 
	autoextend on next 50M;

create tablespace dynmisci
	datafile '/oracle18/ebay/oradata/dynmisci.dbf'
	size 101M 
	autoextend on next 50M;

2. export the categories table (in /oracle-items/ebay/cat.dmp)
exp scott/eif99 tables=ebay_categories direct=Y indexes=N grants=Y constraints=N file=cat.dmp

3. drop old table

drop constraints first:
alter table ebay_items drop constraint items_category_fk;


drop table ebay_categories;

4. create new table with new tablespace 
/* note: change featuredcost from float to number(10,2) */

 create table ebay_categories
 (
	MARKETPLACE			NUMBER(38)
		constraint		categories_marketplace_fk
			references ebay_marketplaces(id),
	ID					NUMBER(10)
		constraint		categories_id_nn
			not null,
	NAME				VARCHAR2(20)
		constraint		categories_name_nn
			not null,
	DESCRIPTION			VARCHAR2(255)
		constraint		categories_desc_nn
			not null,
	ADULT				CHAR(1)
		constraint		categories_adult_nn
			not null,
	ISLEAF				CHAR(1)
		constraint		categories_isleaf_nn
			not null,
	ISEXPIRED			CHAR(1)
		constraint		categories_isexpired_nn
			not null,
	LEVEL1				NUMBER(10)
		constraint		categories_level1_nn
			not null,
	LEVEL2				NUMBER(10)
		constraint		categories_level2_nn
			not null,
	LEVEL3				NUMBER(10)
		constraint		categories_level3_nn
			not null,
	LEVEL4				NUMBER(10)
		constraint		categories_level4_nn
			not null,
	NAME1				VARCHAR2(20),
	NAME2				VARCHAR2(20),	
	NAME3				VARCHAR2(20),
	NAME4				VARCHAR2(20),				
	PREVCATEGORY		NUMBER(10)
		constraint		categories_prev_cat_nn
			not null,
	NEXTCATEGORY		NUMBER(10)
		constraint		categories_next_cat_nn
			not null,
	FEATUREDCOST		NUMBER(10,2)
		constraint		categories_featured_cost_nn
			not null,
	CREATED				DATE
		constraint		categories_created_nn
			not null,
	FILEREFERENCE		VARCHAR2(255),
	last_modified		date
		constraint		categories_last_modified_nn
		not null,
	order_no			number(10)
		default 0
)
tablespace statmiscd
storage (initial 1M next 500K);


5. import in new table
imp scott/tiger file = cat.dmp commit=Y grants=Y ignore=Y Full=Y

6. reinstate constraints and indices
create index ebay_categories_sort_index
	on ebay_categories(order_no)
   tablespace statmisci
	storage(initial 1M next 500K) unrecoverable parallel (degree 3);

alter table ebay_categories
	add constraint			categories_pk
		primary key		(marketplace, id)
		using index tablespace statmisci
		storage (initial 1M next 500K);


------------
ebay_special_items
------------
1. drop and recreate table in new tablespace.
drop table ebay_special_items;

create table ebay_special_items
 (	marketplace			NUMBER(38)
			constraint	special_items_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
			constraint	special_items_id_nn
			not null,
	ADD_DATE			DATE
			constraint	special_items_added_nn
			not null,
	WHO_ADDED			NUMBER(38)
			constraint	special_items_who_added_fk
			references ebay_users(id),
	KIND				CHAR(1)
			constraint	special_items_kind_nn
			not null,
	constraint	special_items_fk
	foreign key (marketplace, id)
	references	ebay_items(marketplace, id)
)
tablespace dynmiscd
storage(initial 10M next 5M);

alter table ebay_special_items
	modify (	marketplace
				constraint	special_items_marketplace_nn
				not null);

alter table ebay_special_items
	modify (	WHO_ADDED
				constraint	special_items_who_added_nn
				not null);

------------
ebay_item_info
------------
1. create tablespace for item_info
	create tablespace ritemd03
		datafile '/oracle-items/ebay/oradata/ritemd03.dbf'
		size 131M 
		autoextend on next 41M;

	create tablespace ritemi03
	    datafile '/oracle18/ebay/oradata/ritemi03.dbf'
		size 76M autoextend on next 26M;


2. cd to /oracle-items/ebay (space!)
exp scott/tiger tables=ebay_item_info direct=Y indexes=N grants=Y constraints=N file=iinfo.dmp

/* system can be up from now on */
3. rename existing table? or drop it?
rename ebay_item_info to ebayt_item_info;


4. create new table with new tablespace 

 create table ebay_item_info
 (
	MARKETPLACE			NUMBER(38)
		constraint		ritem_info_marketplace_nn
			not null,
	ID						NUMBER(38)
		constraint		ritem_info_id_nn
			not null,
	NOTICE_TIME			date,
	BILL_TIME			date
 )
tablespace ritemd03
storage(initial 130M next 40m);


5. import in new table
imp scott/tiger file = iinfo.dmp commit=Y grants=Y ignore=Y Full=Y

6. reinstate constraints and indices
alter table ebay_item_info
	add constraint		ritem_info_pk
		primary key		(marketplace, id)
		using index tablespace	ritemi03
		storage (initial 75M next 25M);

alter table ebay_item_info
	add constraint		ritem_info_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id);

7. if successful, 
drop table ebayt_item_info;


---------------------

ITEM DESCRIPTION TABLE MOVE
---------------------
1. create tablespace for ebay_item_desc on raid device (2GB).

	create tablespace ritemd02
		datafile '/oracle-items/ebay/oradata/ritemd02.dbf'
		size 2001M 
		autoextend on next 101M;

	create tablespace ritemi02
	    datafile '/oracle18/ebay/oradata/ritemi02.dbf'
		size 101M autoextend on next 51M;

	


2. cd to /oracle-items/ebay (space!)
exp scott/tiger tables=ebay_item_desc direct=Y indexes=N grants=Y constraints=N file=desc.dmp

3. Rename(!) ebay_item_desc table in production. 

rename ebay_item_desc to ebayt_item_desc;


4. create table in new tablespace with given starting extent and no constraints.
/* note not null constraints have slightly different names than present because
we have not deleted the old table yet */

 create table ebay_item_desc
 (
	MARKETPLACE			NUMBER(38),
	ID						NUMBER(38)
		constraint		ritem_desc_id_nn
			not null,
 	DESCRIPTION_LEN	NUMBER
		constraint		ritem_desc_len_nn
			not null,
	DESCRIPTION			LONG RAW
	)
	tablespace ritemd02 
	storage (initial 2000M next 100M);

5. import data 
imp scott/tiger file = desc.dmp commit=Y grants=Y ignore=Y Full=Y
buffer size 30720 default> 122880

6. add constraints

alter table ebay_item_desc
	add constraint		ritem_desc_pk
		primary key		(marketplace, id)
		using index tablespace ritemi02
		storage (initial 100M next 50M) unrecoverable parallel (degree 3);

alter table ebay_item_desc
	add	constraint		ritems_marketplace_id_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id);
commit;

alter table ebay_item_desc
	add	constraint		ritem_desc_marketplace_fk
		foreign key(marketplace)
			references ebay_marketplaces(id);
commit;


7. If successful, drop table ebayt_item_desc.
drop table ebayt_item_desc;

-----------------
8. We can drop tbe tablespaces itemd01 and itemi01 once we verified there
are no tables in that space.

drop tablespace itemd01 including contents;
drop tablespace itemi01 including contents;

/* go manually delete the files for itemd01 and itemi01 */
rm /oracle12/ebay/oradata/itemd01*.dbf
rm /oracle06/ebay/oradata/itemd01e.dbf
rm /oracle18/ebay/oradata/itemd01f.dbf

rm /oracle18/ebay/oradata/itemi01*.dbf


/************ next steps *****************/
/* what goes on the raid device:
 * ebay_feedback, ebay_categories, ebay_users, ebay_user_info? 
 */

move ebay_feedback to /oracle-items; coalesce ebay_feedback_detail into 1 extent;
recreate indices;

-------------
ebay_feedback and ebay_feedback_detail
-------------
1. create tablespace for ebay_feedback, ebay_feedback_detail
	create tablespace rfeedbackd01
		datafile '/oracle-items/ebay/oradata/rfeedbackd01.dbf'
		size 16M 
		autoextend on next 5M;

	create tablespace rfeedbacki01
	    datafile '/oracle18/ebay/oradata/rfeedbacki01.dbf'
		size 11M autoextend on next 5M;

	create tablespace feedbackd02
		datafile '/oracle07/ebay/oradata/feedbackd02.dbf'
		size 410M autoextend on next 51M;

	create tablespace feedbacki02
		datafile '/oracle18/ebay/oradata/feedbacki02.dbf'
		size 255M autoextend on next 51M;


2. cd to /oracle-items/ebay (for space)
exp scott/tiger tables=ebay_feedback direct=Y indexes=N grants=Y constraints=N file=fb.dmp
exp scott/tiger tables=ebay_feedback_detail direct=Y indexes=N grants=Y constraints=N file=fbdet.dmp

3. drop existing table
drop table ebay_feedback;
drop table ebay_feedback_detail;

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
 tablespace rfeedbackd01
 storage (initial 15M next 5M);


 create table ebay_feedback_detail
 (
	id						int
		constraint	feedback_detail_id_nn
		not null,
	time					date
		constraint	feedback_detail_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail_comment_nn
		not null
 )
 tablespace feedbackd02
 storage (initial 400M next 50M);
/* TO DO THIS */

5. import new table
imp scott/tiger file = fb.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file = fbdet.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880

6. reinstate constraints and indices
alter table ebay_feedback
   add constraint		feedback_pk
			primary key (id)
			using index	tablespace rfeedbacki01
			storage(initial 10M next 5m) unrecoverable;
						
 create index ebay_feedback_id_index
	on ebay_feedback_detail
	(id)
 storage(initial 65m next 20m)
 tablespace feedbacki02 unrecoverable;
commit;

 create index ebay_feedback_comment_id_index
	on ebay_feedback_detail
	(commenting_id)
 storage(initial 65m next 20m)
 tablespace feedbacki02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback
	add	constraint		feedback_fk
			foreign key (id)
			references	ebay_users(id);
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
drop tablespace feedbackd01 including contents;
drop tablespace feedbacki01 including contents;

/* manually delete files for feedbackd01 and feedbacki01 */
rm /oracle01/ebay/oradata/feedbacki01.dbf
rm /oracle01/ebay/oradata/feedbacki01a.dbf
rm /oracle01/ebay/oradata/feedbacki01b.dbf
rm /oracle01/ebay/oradata/feedbacki01c.dbf

rm /oracle02/ebay/oradata/feedbackd01.dbf
rm /oracle02/ebay/oradata/feedbackd01a.dbf
rm /oracle02/ebay/oradata/feedbackd01b.dbf
rm /oracle02/ebay/oradata/feedbackd01c.dbf
rm /oracle02/ebay/oradata/feedbackd01d.dbf

-------------
ebay_users and ebay_user_info etc.
because users have a lot of constraints, we have to move several tables at once and
reinstate the constraints only once.
-------------
1. create tablespace for ebay_users, ebay_users_info
	create tablespace ruserd01
		datafile '/oracle-items/ebay/oradata/ruserd01.dbf'
		size 61M 
		autoextend on next 11M;

	create tablespace ruseri01
	    datafile '/oracle18/ebay/oradata/ruseri01.dbf'
		size 76M autoextend on next 21M;

	create tablespace ruserd02
		datafile '/oracle-items/ebay/oradata/ruserd02.dbf'
		size 81M autoextend on next 21M;

	create tablespace ruseri02
		datafile '/oracle18/ebay/oradata/ruseri02.dbf'
		size 31M autoextend on next 2M;

	create tablespace ruserd03
		datafile '/oracle-items/ebay/oradata/ruserd03.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruseri03
		datafile '/oracle18/ebay/oradata/ruseri03.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruserd04
		datafile '/oracle-items/ebay/oradata/ruserd04.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruseri04
		datafile '/oracle18/ebay/oradata/ruseri04.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruserd05
		datafile '/oracle-items/ebay/oradata/ruserd05.dbf'
		size 50M autoextend on next 10M;

	create tablespace ruseri05
		datafile '/oracle18/ebay/oradata/ruseri05.dbf'
		size 30M autoextend on next 10M;


2. cd to /oracle-items/ebay (for space)
exp scott/tiger tables=ebay_users direct=Y indexes=N grants=Y constraints=N file=userd.dmp
exp scott/tiger tables=ebay_user_info direct=Y indexes=N grants=Y constraints=N file=userinf.dmp
exp scott/tiger tables=ebay_renamed_users direct=Y indexes=N grants=Y constraints=N file=userren.dmp
exp scott/tiger tables=ebay_user_attributes direct=Y indexes=N grants=Y constraints=N file=userattr.dmp
exp scott/tiger tables=ebay_user_survey_responses direct=Y indexes=N grants=Y constraints=N file=usersurv.dmp
exp scott/tiger tables=ebay_user_code direct=Y indexes=N grants=Y constraints=N file=usercode.dmp
exp scott/tiger tables=ebay_admin direct=Y indexes=N grants=Y constraints=N file=admn.dmp
exp scott/tiger tables=ebay_marketplaces_info direct=Y indexes=N grants=Y constraints=N file=mktinf.dmp
exp scott/tiger tables=ebay_aw_credit_status direct=Y indexes=N grants=Y constraints=N file=awcred.dmp
exp scott/tiger tables=ebay_rename_pending direct=Y indexes=N grants=Y constraints=N file=renpend.dmp
exp scott/tiger tables=ebay_special_users direct=Y indexes=N grants=Y constraints=N file=specusr.dmp
exp scott/tiger tables=ebay_announce direct=Y indexes=N grants=Y constraints=N file=annc.dmp

3. rename existing table & constraints
rename ebay_users to ebay_tusers;
rename ebay_user_info to ebay_tuser_info;
rename ebay_renamed_users to ebay_trenamed_users;
rename ebay_aw_credit_status to ebay_taw_credit_status;

drop table ebay_user_attributes;
drop table ebay_user_survey_responses;
drop table ebay_user_code;
drop table ebay_admin;
drop table ebay_marketplaces_info;
drop table ebay_rename_pending;
drop table ebay_special_users;
drop table ebay_announce;

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
alter table ebay_aolbb_board drop constraint aolbb_board_fk;
alter table ebay_aolsupport_board drop constraint aolsup_board_fk;
alter table ebay_bb_board drop constraint bb_board_fk;
alter table ebay_qa_board drop constraint qa_board_fk;
alter table ebay_beta_board drop constraint	beta_board_fk;
alter table ebay_uifeedback_board drop constraint	uifeedback_board_fk;
alter table ebay_wanted_board drop constraint	wanted_board_fk;
alter table ebay_xmas_board drop constraint	xmas_board_fk;
alter table ebay_special_items drop constraint special_items_who_added_fk;


4. create new table with new tablespace

 create table ebay_users
 (
	marketplace		int,
	id			int 
		constraint	rusers_id_nn
		not null,
	userid			varchar(64)
		constraint	rusers_userid_nn
		not null,
	user_state		int 
		constraint	rusers_user_state_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	last_modified		date
		constraint	rusers_last_modified_nn
		not null,
	userid_last_change date
 )
 tablespace ruserd01
 storage (initial 60M next 10m);


create table ebay_user_info
(
	id			int
		constraint	ruser_info_id_nn
		not null,
	host			varchar(64)
		constraint	rusers_host_nn
		not null,
	name			varchar(64)
		constraint	rusers_name_nn
		not null,
	company			varchar(64),
	address			varchar(64)
		constraint	rusers_address_nn
		not null,
	city			varchar(64)
		constraint	rusers_city_nn
		not null,
	state			varchar(64)
		constraint	rusers_state_nn
		not null,
	zip			varchar(12)
		constraint	rusers_zip_nn
		not null,
	country			varchar(64)
		constraint	rusers_country_nn
		not null,
	dayphone		varchar(32),
	nightphone		varchar(32),
	faxphone		varchar(32),
	creation		date
		constraint	rusers_creation_nn
		not null,
	email			varchar(64)
		constraint	ruser_email_nn
		not null,
	count			int
		default 0,
	credit_card_on_file	char
		default chr(0)
		constraint	rcredit_info_cc_nn
		not null,
	good_credit		char
		default chr(0)
		constraint	rcredit_info_gc_nn
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
		default 0
)
 tablespace ruserd02
 storage (initial 80M next 20m);

 create table ebay_renamed_users
 (
	fromuserid			varchar(64)
		constraint	renamed_rusers_fromuserid_nn
		not null,
	touserid			varchar(64) 
		constraint	renamed_rusers_touserid_nn
		not null
 )
 tablespace ruserd03
 storage (initial 1M next 1m);

create table ebay_user_attributes
(
	user_id		int
		constraint	rattr_user_id_nn
		not null,
	attribute_id	number(3,0)
		constraint	rattr_attr_id_nn
		not null,
	first_entered	date
		constraint	rattr_first_entered_nn
		not null,
	last_updated	date
		constraint	rattr_updated_nn
		not null,
	boolean_value	char(1),
	number_value	number,
	text_value		varchar(256)
)
 tablespace ruserd04
 storage (initial 30M next 10M);

create table ebay_aw_credit_status
	(
		userid						varchar(255)	
			constraint	raw_credit_status_userid_nn
			not null,
		credit_card_on_file	char
			constraint	raw_credit_status_cc_nn
			not null,
		good_credit				char
			constraint	raw_credit_status_gc_nn
			not null
	)
	tablespace ruserd05
	storage (initial 1M next 500k);

 create table ebay_user_survey_responses
 (
	marketplace				int
		constraint	responses_marketplace_fk
		references	ebay_marketplaces(id),
	user_id					int 
		constraint	responses_id_nn
		not null,
	survey_id				number(3,0)
		not null,
	question_id				number(2,0)
		not null,
	boolean_response		char,
	number_response			number,
	text_response_length	int,
	text_response			long raw
 )
 tablespace ruserd05
 storage (initial 10M next 2m);

create table ebay_user_code
(	question_id number(3,0)
		constraint	user_code_attr_nn
		not null,
	question_code number(3,0)
		constraint user_code_code_nn
		not null,
	order_no number(6,0)
		default 0,
	type_code number(3,0)
		not null,
	question varchar(255)
		constraint	contact_desc_nn
		not null)
tablespace statmiscd
storage(initial 100K next 100K);

create table ebay_admin
	(
		marketplace		int
		constraint	admin_marketplace_fk
		references	ebay_marketplaces(id),
		id				int
			constraint	admin_id_nn
			not null,
		adcode			number(3,0)
			constraint	admin_adcode_nn
			not null
	)
	tablespace ruserd05
	storage (initial 10K next 10K);

create table ebay_marketplaces_info
	(
		id						int
			constraint	marketplaces_info_id_nn
			not null,
		item_count				int
			default 0,
		daily_item_count		int
			default 0,
		bid_count				int
			default 0
	)
	tablespace ruserd05
	storage (initial 1K next 1K);

 create table ebay_rename_pending
 (
	marketplace		int,
	fromuserid		varchar(64) 
		constraint	pending_fromuserid_nn
		not null,
	touserid		varchar(64) 
		constraint	pending_touserid_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	created			date
		constraint	pending_created_nn
		not null
 )
 tablespace dynmiscd
 storage (initial 1M next 1M);


	create table ebay_announce
	(
		marketplace			int
			constraint	announce_id_nn
			not null,
		id					number(3,0)
			constraint	announce_action_nn
			not null,
		location			number(1,0)
			constraint	announce_amount_nn
			not null,
		code				varchar(20)
			constraint	announce_code_nn
			not null,
		last_modified		date
			constraint	announce_last_modified_nn
			not null,
		description_len		number
			constraint	announce_desc_len_nn
			not null,
		description			LONG RAW
	)
tablespace dynmiscd
storage (initial 1M next 1M);
 
  create table ebay_special_users
 (
	userid			varchar(64) 
		constraint	special_users_userid_nn
				not null
 )
 tablespace dynmiscd
 storage (initial 1M next 500K);
commit;

5. import new table
imp scott/tiger file = userd.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file = userinf.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=userren.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=userattr.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=usersurv.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=usercode.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=admn.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=mktinf.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=awcred.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=renpend.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=specusr.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880
imp scott/tiger file=annc.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880

6. reinstate constraints and indices

alter table ebay_users
	add 	constraint	rusers_marketplace_fk
		foreign key (marketplace)
		references	ebay_marketplaces(id);

alter table ebay_users
	add  constraint		rusers_pk
		primary key(id)
		using index	tablespace ruseri01
		storage(initial 10m next 5m);

alter table ebay_users
	add 	constraint		rusers_marketplace_userid_unq
		unique (marketplace, userid)
		using index	tablespace ruseri01
		storage(initial 35m next 10m);
					
alter table ebay_users
	add constraint	ruser_userid_unq
		unique (userid)
		using index tablespace ruseri01
		storage(initial 30m next 10m);

/* correct bio */
alter table ebay_users_bio
	add  constraint		rusers_pk
		primary key(id)
		using index	tablespace ruseri01
		storage(initial 60m next 30m);
				
-- user info

alter table ebay_user_info
	add constraint		ruser_info_pk
      	primary key(id)
      	using index tablespace ruseri02
		storage(initial 10m next 2m);
commit;
  
alter table ebay_user_info
	add	constraint		ruser_info_fk
		foreign key (id)
		references	ebay_users(id);
commit;
---renamed users

alter table ebay_renamed_users
	add	constraint	renamed_rusers_fromuserid_unq
		unique (fromuserid)
		using index tablespace ruseri03
		storage(initial 1m next 1m);
commit;
--- user attributes
alter table ebay_user_attributes
	add	constraint		rattr_pk
      	primary key(user_id, attribute_id)
      	using index storage(initial 5M next 1M)
                 tablespace ruseri04;
commit;
alter table ebay_user_attributes
	add	constraint		rattr_fk
		foreign key (user_id)
		references	ebay_users(id);
commit;

alter table ebay_aw_credit_status
	add	constraint		rcredit_info_pk
			primary key (userid)
			using index tablespace ruseri05
			storage (initial 500k next 250k);
commit;
alter table ebay_user_survey_responses
	add constraint		user_survey_response_pk
		primary key(marketplace, user_id, survey_id, question_id)
		using index	storage(initial 100K next 100K)
						tablespace ruseri05;
commit;
alter table ebay_user_survey_responses
	add constraint		user_survey_responses_fk
		foreign key (user_id)
		references	ebay_users(id);
commit;
alter table ebay_admin
	add		constraint		admin_fk
			foreign key (id)
			references	ebay_users(id);
commit;
alter table ebay_user_code
	add constraint		user_attr_code_pk
      	primary key(question_id, question_code)
      	using index storage(initial 100K next 100K)
        tablespace statmisci  unrecoverable;
commit;
alter table ebay_marketplaces_info
	add	constraint		marketplaces_info_fk
			foreign key (id)
			references	ebay_marketplaces(id);
commit;
alter table ebay_marketplaces_info
	add	constraint		marketplaces_info_pk
			primary key (id)
			using index tablespace ruseri05
			storage (initial 1K next 1K) unrecoverable;
commit;
alter table ebay_rename_pending
	add	constraint	pending_marketplace_fk
		foreign key (marketplace)
		references	ebay_marketplaces(id);
commit;
alter table ebay_announce
	add	constraint	announce_pk
			primary key		(marketplace, id, location)
			using index tablespace dynmisci
			storage (initial 20K next 10K) unrecoverable;
commit;		
alter table ebay_special_users
	add	constraint	special_users_userid_unq
		unique (userid)
		using index storage(initial 500K next 100K)
				tablespace dynmisci unrecoverable;
commit;
--- others

alter table ebay_account_balances
	add	constraint		account_balances_fk
			foreign key (id)
			references	ebay_users(id);
commit;
alter table ebay_accounts
	add		constraint		accounts_fk
			foreign key (id)
			references	ebay_users(id);
commit;
alter table ebay_bids
	add	constraint		bids_user_fk
			foreign key (user_id)
			references	ebay_users(id);
commit;
alter table ebay_items
	add		constraint		items_seller_fk
			foreign key (seller)
			references ebay_users(id);
commit;
alter table ebay_items
	add		constraint		items_owner_fk
			foreign key (owner)
			references ebay_users(id);
commit;
alter table ebay_items
	add		constraint		items_high_bidder_fk
			foreign key (high_bidder)
			references ebay_users(id);
commit;
alter table ebay_account_xref
	add	constraint			account_xref_fk
			foreign key(id)
			references ebay_users(id);

alter table ebay_feedback
	add	constraint		feedback_fk
			foreign key (id)
			references	ebay_users(id);
commit;
alter table ebay_feedback_detail
	add 	constraint		feedback_detail_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;
alter table ebay_feedback_detail
	add 	constraint		feedback_detail_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_qa_board
	add	constraint		qa_board_fk
		foreign key (id)
		references	ebay_users(id);
commit;
alter table ebay_beta_board
	add	constraint		beta_board_fk
		foreign key (id)
		references	ebay_users(id);
commit;
alter table ebay_uifeedback_board
	add	constraint		uifeedback_board_fk
		foreign key (id)
		references	ebay_users(id);
commit;
alter table ebay_aolbb_board
	add	constraint		aolbb_board_fk
		foreign key (id)
		references	ebay_users(id);
commit;
alter table ebay_aolsupport_board
	add	constraint		aolsup_board_fk
		foreign key (id)
		references	ebay_users(id);
commit;
alter table ebay_bb_board
	add	constraint		bb_board_fk
		foreign key (id)
		references	ebay_users(id);
commit;
alter table ebay_wanted_board
	add	constraint		wanted_board_fk
		foreign key (id)
		references	ebay_users(id);
commit;
alter table ebay_xmas_board
	add constraint		xmas_board_fk
		foreign key (id)
		references ebay_users(id);
commit;
alter table ebay_special_items
	add constraint	special_items_who_added_fk
		foreign key (WHO_ADDED)
		references ebay_users(id);
commit;

/* drop tablespace userd01 and useri01 */


/* moving datafiles around -- sample */
 alter tablespace feedbackd02   offline;
 alter tablespace statsd01   offline;
 alter tablespace bizdevd01 offline;
 alter tablespace bizdevi01 offline;
 alter tablespace summaryi01 offline;
 alter tablespace itemarci1 offline;

/* copy the data file to the new location */
cp /oracle07/ebay/oradata/feedback* /oracle06/ebay/oradata/.
cp /oracle07/ebay/oradata/statsd* /oracle06/ebay/oradata/.

cp /oracle03/ebay/oradata/bizdevd* /oracle21/ebay/oradata/.
cp /oracle05/ebay/oradata/bizdevi* /oracle20/ebay/oradata/.
cp /oracle05/ebay/oradata/summaryi* /oracle20/ebay/oradata/.
cp /oracle05/ebay/oradata/itemarci* /oracle20/ebay/oradata/.


 alter tablespace feedbackd02 rename datafile 
 '/oracle07/ebay/oradata/feedbackd02.dbf' to '/oracle06/ebay/oradata/feedbackd02.dbf' ;
 alter tablespace statsd01 rename datafile 
 '/oracle07/ebay/oradata/statsd01.dbf' to '/oracle06/ebay/oradata/statsd01.dbf' ;
  alter tablespace bizdevd01 rename datafile 
 '/oracle03/ebay/oradata/bizdevd01.dbf' to '/oracle21/ebay/oradata/bizdevd01.dbf' ;
 alter tablespace bizdevi01 rename datafile 
 '/oracle05/ebay/oradata/bizdevi01.dbf' to '/oracle20/ebay/oradata/bizdevi01.dbf' ;
 alter tablespace summaryi01 rename datafile 
 '/oracle05/ebay/oradata/summaryi01.dbf' to '/oracle20/ebay/oradata/summaryi01.dbf' ;
 alter tablespace itemarci1 rename datafile 
 '/oracle05/ebay/oradata/itemarci1.dbf' to '/oracle20/ebay/oradata/itemarci1.dbf' ;
 

 alter tablespace feedbackd02 online;
 alter tablespace statsd01 online;
 alter tablespace bizdevd01 online;
alter tablespace bizdevi01 online;
alter tablespace summaryi01 online;
alter tablespace itemarci1 online;

/* coalesce bids */

1. create tablespaces:

create tablespace bidd02
	datafile '/oracle03/ebay/oradata/bidd02.dbf'
	size 610M 
	autoextend on next 100M;

exp scott/tiger tables=ebay_bids direct=Y indexes=N grants=Y constraints=N file=bids.dmp

rename ebay_bids to ebayt_bids;
drop index ebay_bids_item_index;
drop index ebay_bids_item_user_index;
drop index ebay_bids_user_index;
alter table ebayt_bids drop constraint bid_check_quantity;
alter table ebayt_bids drop constraint bid_check_amount;
alter table ebayt_bids drop constraint bid_item_fk;
alter table ebayt_bids drop constraint bid_user_fk;

/* as system */
alter tablespace bidi02 coalesce;


 create table ebay_bids 
 (
	marketplace	int
		constraint	bid_marketplace_fk
		not null
		references	ebay_marketplaces(id),
 	item_id		int
		constraint	bid_item_id_nn
		not null,
	user_id		int
		constraint	bid_user_id_nn
		not null,
	quantity		int
		constraint	bid_quantity_nn
		not null,
	amount		number(10,2)
		constraint	bid_amount_nn
		not null,
	value			number(10,2)
		constraint	bid_value_nn
		not null,
	type			int
		CONSTRAINT bid_check_type
		CHECK (type IN (0, 1, 2, 3, 4, 5, 6)),
	created		date
		constraint	bid_created_nn
		not null,
	reason		varchar2(255),
	host		varchar(16)
 )
 tablespace bidd02
 storage (initial 600M next 100M);

imp scott/tiger file = bids.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880

 create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace bidi02
	storage(initial 300M next 50M) unrecoverable parallel (degree 3);

 create index ebay_bids_item_index
	on ebay_bids(item_id)
	tablespace bidi02
	storage(initial 300M next 50M) unrecoverable parallel (degree 3);

 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace bidi02
   storage(initial 200M next 50M) unrecoverable parallel (degree 3);

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

drop tablespace bidd01 including contents;


/* split up bid index tablespaces */

1. delete indices
drop index ebay_bids_item_index;
drop index ebay_bids_item_user_index;
drop index ebay_bids_user_index;

2. drop tablespace:
drop tablespace bidi02 including contents;
/* check to ensure no segments in bidi02 */

3. create tablespaces bidi01 - bidi03
create tablespace bidi01
	datafile '/oracle12/ebay/oradata/bidi01.dbf'
	size 401M 
	autoextend on next 100M;

create tablespace bidi02
	datafile '/oracle18/ebay/oradata/bidi02.dbf'
	size 401M 
	autoextend on next 100M;

create tablespace bidi03
	datafile '/oracle18/ebay/oradata/bidi03.dbf'
	size 401M 
	autoextend on next 100M;
	/* to be moved to /oracle07 */

 create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace bidi01
	storage(initial 400M next 100M) unrecoverable parallel (degree 3);

 create index ebay_bids_item_index
	on ebay_bids(item_id)
	tablespace bidi02
	storage(initial 400M next 100M) unrecoverable parallel (degree 3);

 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace bidi03
   storage(initial 400M next 100M) unrecoverable parallel (degree 3);

/* delete bidi02 from /oracle07/ebay/oradata */
cd /oracle07/ebay/oradata
rm bidi02.dbf

/* move datafile from /oracle18 to /oracle07 */
alter tablespace bidi02 offline;

/* cp /oracle07/ebay/oradata/bidi02.dbf /oracle21/ebay/oradata/. */

alter tablespace bidi02 rename datafile 
 '/oracle07/ebay/oradata/bidi02.dbf' to '/oracle21/ebay/oradata/bidi02.dbf' ;

alter tablespace bidi02 online;
 
/* move datafile from /oracle18 to /oracle07 */
alter tablespace bidi03 offline;

/* cp /oracle07/ebay/oradata/bidi03.dbf /oracle21/ebay/oradata/. */

alter tablespace bidi03 rename datafile 
 '/oracle07/ebay/oradata/bidi03.dbf' to '/oracle21/ebay/oradata/bidi03.dbf' ;

alter tablespace bidi03 online;


/* change hot backup script to the resp. bid index tablespaces */

/* change cold backup */

/* run hot backup again */

/* coalesce bids_arc table */

/* in a directory with enough space */
exp scott/haw98 tables=ebay_bids_arc direct=Y indexes=N grants=Y constraints=N file=bidsarc.dmp

drop table ebay_bids_arc;
/* as system */
drop tablespace bidarc1;
drop tablespace bidarci1;

1. create tablespaces:

create tablespace bidarc1
	datafile '/oracle12/ebay/oradata/bidarc1.dbf'
	size 610M 
	autoextend on next 100M;

/* check disk */
create tablespace bidarci1
	datafile '/oracle09/ebay/oradata/bidarci1.dbf'
	size 300M
	autoextend on next 100M;


 create table ebay_bids_arc 
 (
	marketplace	int
		constraint	bidarc_marketplace_nn
		not null,
 	item_id		int
		constraint	bidarc_item_id_nn
		not null,
	user_id		int
		constraint	bidarc_user_id_nn
		not null,
	quantity		int
		constraint	bidarc_quantity_nn
		not null,
	amount		number(10,2)
		constraint	bidarc_amount_nn
		not null,
	value			number(10,2)
		constraint	bidarc_value_nn
		not null,
	type			int
		CONSTRAINT bidarc_check_type
		CHECK (type IN (0, 1, 2, 3, 4, 5, 6)),
	created		date
		constraint	bidarc_created_nn
		not null,
	reason		varchar2(255),
	host		varchar(16)
 )
 tablespace bidarc1
 storage (initial 600M next 100M);

imp scott/haw98 file = bidsarc.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880

 create index ebay_bidsarc_item_index
	on ebay_bids_arc(item_id)
	tablespace bidarci1
	storage(initial 300M next 100M) unrecoverable;

----------

Moving to /oracle/rdata01
----------------------------------- ---------- ---------- ----------
EBAY_TRANSACTION_XREF_ITEM          ACCOUNTD01         12  369129472
EBAY_MIGRATED_ACCOUNTS              ACCOUNTD01          1    1054720
EBAY_ACCOUNT_XREF                   ACCOUNTD01          1    5242880
EBAY_TRANSACTION_XREF_AW_ITEM       ACCOUNTD01          9  113827840
EBAY_XACCOUNTS                      ACCOUNTD01          1   10485760
EBAY_ACCOUNTS                       ACCOUNTD01          7  960102400
EBAY_ACCOUNT_BALANCES               ACCOUNTD01          1   31457280
EBAY_XTRANSACTION_XREF_AW_ITEM      ACCOUNTD01          3    3686400
XACTION_XREF_ITEM_PK                ACCOUNTI01         11  242944000
MIGRATED_ACCOUNTS_PK                ACCOUNTI01          7    1208320
ACCOUNT_XREF_PK                     ACCOUNTI01          1    5242880

SUBSTR(SEGMENT_NAME,1,35)           SUBSTR(TAB    EXTENTS      BYTES
----------------------------------- ---------- ---------- ----------
EBAY_ACCOUNT_XREF_AWID_INDEX        ACCOUNTI01          1    5242880
XACTION_XREF_AW_ITEM_PK             ACCOUNTI01          8   72744960
ACCOUNT_BALANCES_PK                 ACCOUNTI01          1    5242880
EBAY_ACCOUNTS_ID_INDEX              ACCOUNTI02          3  690735104
XXACTION_XREF_AW_ITEM_PK            ACCOUNTI01          2    2109440

16 rows selected.


vxmkcdev -o oracle_file -s 2047m FILENAME

And then you add the file specified as FILENAME as a table space.  That's all. ;)


exp scott/haw98 tables=ebay_users direct=Y indexes=N grants=Y constraints=N file=users.dmp
create temp tablespaces for users;

vxmkcdev -o oracle_file -s 100m /oracle/rdata01/ebay/oradata/tuserd01.dbf
vxmkcdev -o oracle_file -s 100m /oracle/rdata01/ebay/oradata/tuseri01.dbf

	create tablespace tuserd01
		datafile '/oracle/rdata01/ebay/oradata/tuserd01.dbf'
		size 100M autoextend off;

	create tablespace tuseri01
		datafile '/oracle/rdata01/ebay/oradata/tuseri01.dbf'
		size 100M autoextend off;

/* as tini */
 create table ebay_users
 (
	marketplace		int,
	id			int 
		constraint	rusers_id_nn
		not null,
	userid			varchar(64)
		constraint	rusers_userid_nn
		not null,
	user_state		int 
		constraint	rusers_user_state_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	last_modified		date
		constraint	rusers_last_modified_nn
		not null,
	userid_last_change date
 )
 tablespace tuserd01
 storage (initial 60M next 10m);

alter table ebay_users
	add  constraint		rusers_pk
		primary key(id)
		using index	tablespace tuseri01
		storage(initial 10m next 5m);

alter table ebay_users
	add constraint	ruser_userid_unq
		unique (userid)
		using index tablespace tuseri01
		storage(initial 30m next 10m);

imp tini/tigger file = users.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880

