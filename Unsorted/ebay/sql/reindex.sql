/*	$Id: reindex.sql,v 1.3 1999/02/21 02:54:54 josh Exp $	*/
/* reindex item indices */

alter index ebay_items_seller_index rebuild parallel 2 tablespace ritemi01;
/* 11:50pm - 12:02am */

alter index ebay_items_high_bidder_index rebuild parallel 2 tablespace ritemi01;
/* 12:02am -  12:20 */

alter index ebay_items_category_index rebuild parallel 2 tablespace ritemi01;

/* had problem!
alter index ebay_items_ending_index rebuild parallel 2 tablespace itemi01;
alter index ebay_items_last_modified_index rebuild parallel 2 tablespace itemi01;
alter index ebay_items_starting_index rebuild parallel 2 tablespace itemi01;
*/

alter index ritem_desc_pk rebuild unrecoverable tablespace ritemi02;

alter index items_pk rebuild parallel 2 tablespace itemi01; -- not done

/*
alter tablespace itemd01 coalesce;
alter tablespace bidd01 coalesce;
*/

alter index ebay_bids_item_index rebuild parallel 2 tablespace bidi01;
alter index ebay_bids_user_index rebuild parallel 2 tablespace bidi01;
alter index ebay_bids_item_user_index rebuild parallel 2 tablespace bidi01;

/* moving datafiles around -- sample */
 alter tablespace bidi01   offline;

/* copy the data file to the new location */
cp /oracle05/ebay/oradata/bidi01* /oracle18/ebay/oradata/.

 alter tablespace bidi01 rename datafile 
 '/oracle05/ebay/oradata/bidi01a.dbf' to '/oracle18/ebay/oradata/bidi01a.dbf' ;
 alter tablespace bidi01 rename datafile 
 '/oracle05/ebay/oradata/bidi01b.dbf' to '/oracle18/ebay/oradata/bidi01b.dbf' ;
  alter tablespace bidi01 rename datafile 
 '/oracle05/ebay/oradata/bidi01c.dbf' to '/oracle18/ebay/oradata/bidi01c.dbf' ;
 alter tablespace bidi01 online;
  
/* remove the  the data file from the old location, using the unix command. */
/* then change the hot backups */


/* test new index */

create index tini_last_mod ON ebay_items(last_modified)
   TABLESPACE itemi01
   STORAGE (INITIAL 20M
      NEXT 10M
      PCTINCREASE 0)
   PCTFREE 0;


    alter tablespace feedbacki01 rename datafile 
 '/oracle01/ebay/oradata/feedacki01c.dbf' to 
 '/oracle01/ebay/oradata/feedbacki01c.dbf' ;


alter index ebay__index rebuild parallel 2 tablespace bidi01;

/* set up temporary tablespace for temporary workspace */
create tablespace temp02
	datafile '/oracle-items/ebay/oradata/temp02.dat' size 800M
	online temporary;

alter user scott temporary tablespace temp02;

alter index ebay_accounts_id_index
   rebuild unrecoverable tablespace accounti02;

create tablespace temp01
	datafile '/oracle-items/ebay/oradata/temp01.dat' size 300M
	online temporary;

alter user scott temporary tablespace temp01;
drop tablespace temp02 including contents;
drop tablespace tinitemp including contents;


/* move file in tablespace ritemi01 */

 alter tablespace ritemi01   offline;

/* copy the data file to the new location */
cp /oracle-items/ebay/oradata/ritemi01.dbf /oracle20/ebay/oradata/.

    alter tablespace ritemi01 rename datafile 
 '/oracle-items/ebay/oradata/ritemi01.dbf' to 
 '/oracle20/ebay/oradata/ritemi01.dbf' ;

 alter tablespace ritemi01   online;


create tablespace temp02
	datafile '/oracle07/ebay/oradata/temp02.dat' size 400M
	online temporary;
alter user scott temporary tablespace temp02;

alter tablespace bidarc1 add datafile
'/oracle12/ebay/oradata/bidarc1a.dbf' size 400M;


alter tablespace itemarc1 add datafile
'/oracle10/ebay/oradata/itemarc1a.dbf' size 400M;

alter tablespace itemarc2 add datafile
'/oracle03/ebay/oradata/itemarc2a.dbf' size 100M;

alter tablespace itemarc3 add datafile
'/oracle01/ebay/oradata/itemarc3a.dbf' size 400M;

/* to do ? */
alter tablespace temp02
add datafile '/oracle07/ebay/oradata/temp02a.dat' size 400M;

/* move file in tablespace radi01 */

 alter tablespace adi01   offline;

/* copy the data file to the new location */
cp /oracle05/ebay/oradata/adi01.dbf /oracle01/ebay/oradata/.

alter tablespace adi01 rename datafile 
 '/oracle05/ebay/oradata/adi01.dbf' to 
 '/oracle01/ebay/oradata/adi01.dbf' ;

 alter tablespace adi01   online;


alter tablespace ritemd02 add datafile
'/oracle-items/ebay/oradata/ritemd02e.dbf' size 301M;

alter tablespace accountd01 add datafile
'/oracle01/ebay/oradata/accountd01b.dbf' size 200M;

/* move accounti01.dbf to /oracle20 */

alter tablespace accounti01 offline;
cp /oracle01/ebay/oradata/accounti01.dbf /oracle20/ebay/oradata/.

alter tablespace accounti01 rename datafile 
 '/oracle01/ebay/oradata/accounti01.dbf' to 
 '/oracle20/ebay/oradata/accounti01.dbf' ;

 alter tablespace accounti01 online;

/* verify then delete */
 rm /oracle01/ebay/oradata/accounti01.dbf

/* to delete accounti01 tablespace */

1. create new tablespace for index: XXACTION_XREF_AW_ITEM_PK (20M)
create tablespace taccounti01
		datafile '/oracle20/ebay/oradata/taccounti01.dbf'
		size 20M 
		autoextend on next 10M;

2. drop xxaction_xref_aw_item_pk index
alter table ebay_xtransaction_xref_aw_item
	drop constraint			xxaction_xref_aw_item_pk;

drop index xxaction_xref_aw_item_pk;

3. recreate in new tablespace (10M)
alter table ebay_xtransaction_xref_aw_item
	add constraint			xxaction_xref_aw_item_pk
		primary key		(id)
		using index tablespace	taccounti01
		storage (initial 10M next 10M) unrecoverable;

/* verify nothing is in accounti01 */
select substr(segment_name,1,40), tablespace_name from dba_segments
where tablespace_name = 'ACCOUNTI01';

drop tablespace accounti01 including contents;

/* delete files from /oracle20 */

/* move adi01 from /oracle01 to /oracle21 */

alter tablespace adi01 offline;
cp /oracle01/ebay/oradata/adi01.dbf /oracle20/ebay/oradata/.

alter tablespace adi01 rename datafile 
 '/oracle01/ebay/oradata/adi01.dbf' to 
 '/oracle20/ebay/oradata/adi01.dbf' ;

 alter tablespace adi01 online;

alter tablespace ritemd02 add datafile
'/oracle/rdata01/ebay/oradata/ritem02g.dbf' size 2000M;

