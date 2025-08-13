/*	$Id: ebay_items.sql,v 1.5.152.1 1999/08/01 03:02:43 barry Exp $	*/
/*
 * ebay_items.sql
 *
 * ** NOTE **
 * Right now, items numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

/* added last modified field; do we really need it? */

 drop table ebay_items;

/* old definition changed. see below for new table definition.
 create table ebay_items
 (
	MARKETPLACE			NUMBER(38)
		constraint		items_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
		constraint		items_id_nn
			not null,
	SALE_TYPE			NUMBER(38)
		constraint		items_sale_type_nn
			not null,
	TITLE					VARCHAR2(254)
		constraint		items_title_nn
			NOT NULL,
	LOCATION				VARCHAR2(254)
		constraint		items_location_nn
			not null,
	SELLER				NUMBER(38)
		constraint		items_seller_fk
			references ebay_users(id),
	OWNER					NUMBER(38)
		constraint		items_owner_fk
			references ebay_users(id),
	PASSWORD				NUMBER(38)
		constraint		items_password_nn
			not null,
	CATEGORY				NUMBER(38)
		constraint		items_category_nn
			not null
			disable,
	QUANTITY				NUMBER(38)
		constraint		items_quantity_nn
			not null,
	BIDCOUNT				NUMBER(38)
		constraint		items_bidcount_nn
			not null,
	CREATED				DATE
		constraint		items_created_nn
			not null,
	SALE_START			DATE
		constraint		items_sale_start_nn
			not null,
	SALE_END				DATE
		constraint		items_sale_end_nn
			not null,
	SALE_STATUS			NUMBER(38)
		constraint		items_sale_status_nn
			not null,
	CURRENT_PRICE		FLOAT(126)
		constraint		items_current_price_nn
			not null,
	START_PRICE			FLOAT(126)
		constraint		items_start_price_nn
			not null,
	RESERVE_PRICE		FLOAT(126)
		constraint		items_reserve_price_nn
			not null,
	HIGH_BIDDER			NUMBER(38)
			constraint		items_high_bidder_fk
			references ebay_users(id),
	FEATURED				CHAR(1)
			constraint		items_featured_nn
			not null,
	SUPER_FEATURED			CHAR(1)
			constraint		items_super_featured_nn
			not null,
	BOLD_TITLE			CHAR(1)
			constraint		items_bold_title_nn
			not null,
	PRIVATE_SALE		CHAR(1)
			constraint		items_private_sale_nn
			not null,
	REGISTERED_ONLY	CHAR(1)
			constraint		items_registered_only_nn
			not null,
	HOST				varchar(64),
	VISITCOUNT			NUMBER(38)
			constraint		items_visit_count_nn
			not null,			
	PICTURE_URL			VARCHAR(255),
	last_modified		date
		constraint	items_last_modified_nn
		not null,
	constraint			items_pk
		primary key		(marketplace, id)
		using index tablespace	itemi01
		storage (initial 5M next 1M),
	constraint			items_category_fk
		foreign key(marketplace, category)
		references ebay_categories(marketplace, id)
)
tablespace itemd01
storage(initial 60M next 5m);


 alter table ebay_items
	modify (	MARKETPLACE
				constraint	items_marketplace_nn
				not null);

 alter table ebay_items
	modify (	SELLER
				constraint	items_seller_nn
				not null disable);

 alter table ebay_items
	modify (	OWNER
				constraint	items_owner_nn
				not null disable);

 drop sequence ebay_items_sequence;

 create sequence ebay_items_sequence;

 create index ebay_items_last_modified_index
   on ebay_items(last_modified)
   tablespace bidi01
   storage(initial 10m next 2);


to add and remove constraint to category 

alter table ebay_items
  drop constraint 	items_category_fk

alter table ebay_items
  add constraint 	items_category_fk
		foreign key(marketplace, category)
		references ebay_categories(marketplace, id)
		*/


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
	current_price		float(126)
		constraint		items_current_price_nn
			not null,
	start_price			float(126)
		constraint		items_start_price_nn
			not null,
	reserve_price		float(126)
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


alter table ebay_items 
	add constraint			items_pk
		primary key		(marketplace, id)
		using index tablespace	ritemi01
		storage (initial 70M next 30M) unrecoverable parallel (degree 3);

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

alter table ebay_items add ( ORIG_SALE_END date);
alter table ebay_items add ( CONTENT_MODIFIED date);
alter table ebay_items add ( ICON_FLAGS VARCHAR2(3));
/* Where the item is located by country. */
alter table ebay_items
	add (country_id number(10));
alter table ebay_items add ( GALLERY_URL VARCHAR2(255));
alter table ebay_items add ( GALLERY_THUMB_X_SIZE NUMBER(4,0) );
alter table ebay_items add ( GALLERY_THUMB_Y_SIZE NUMBER(4,0) );
alter table ebay_items add ( GALLERY_TYPE NUMBER(2,0));

/* time to reindex */
create tablespace qitemsi01 datafile
'/oracle/rdata12/ebay/oradata/qitemi01a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi01b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi01c.dbf' size 501m;

create tablespace qitemsi02 datafile
'/oracle/rdata12/ebay/oradata/qitemi02a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi02b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi02c.dbf' size 501m;

alter index ebay_items_seller_index rebuild parallel 2 tablespace qitemsi01;

alter index ebay_items_high_bidder_index rebuild parallel 2 tablespace qitemsi02;

-----------
CREATE INDEX EBAY_ITEMS_SELLER_IDX ON EBAY_ITEMS (SELLER )
PCTFREE 10 INITRANS 2 MAXTRANS 255 STORAGE (INITIAL 500m NEXT
500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1)
TABLESPACE QITEMSI01 unrecoverable;

CREATE INDEX EBAY_ITEMS_HIGH_BIDDER_IDX ON EBAY_ITEMS
(HIGH_BIDDER ) PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL
500m NEXT 500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0
FREELISTS 1)
TABLESPACE QITEMSI02  unrecoverable;

OR THIS IS WHAT GOT DONE!

create tablespace qitemsi01 datafile
'/oracle/rdata12/ebay/oradata/qitemi01a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi01b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi01c.dbf' size 501m;

create tablespace qitemsi02 datafile
'/oracle/rdata12/ebay/oradata/qitemi02a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi02b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi02c.dbf' size 501m;


5. drop index EBAY_ITEMS_HIGH_BIDDER_INDEX ;

CREATE INDEX EBAY_ITEMS_HIGH_BIDDER_INDEX ON EBAY_ITEMS
(HIGH_BIDDER ) PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL
500m NEXT 500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0
FREELISTS 1)
TABLESPACE QITEMSI02  unrecoverable;

6. drop index EBAY_ITEMS_ENDING_INDEX;

 CREATE INDEX EBAY_ITEMS_ENDING_INDEX ON EBAY_ITEMS (SALE_END )
PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL 500m NEXT 500m
MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1)
TABLESPACE QITEMSI01  unrecoverable;
/* 14 mins */

 create tablespace qitemsi05 datafile
'/oracle/rdata12/ebay/oradata/qitemi05a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi05b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/qitemi05c.dbf' size 501m; */


drop index EBAY_ITEMS_SELLER_INDEX ;

 CREATE INDEX EBAY_ITEMS_SELLER_INDEX ON EBAY_ITEMS (SELLER )
PCTFREE 10 INITRANS 2 MAXTRANS 255 STORAGE (INITIAL 500m NEXT
500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1)
TABLESPACE QITEMSI03 unrecoverable;

/* start of reorg items */

alter table ebay_items
	add (currency_id number(3,0));

alter table ebay_items
	add	constraint		items_country_id_nn
		not null (country_id)
		references ebay_countries(id);

create tablespace itemd01 datafile
'/oracle/rdata14/ebay/oradata/itemd01a.dbf' size 501m,
'/oracle/rdata14/ebay/oradata/itemd01b.dbf' size 501m,
'/oracle/rdata14/ebay/oradata/itemd01c.dbf' size 501m,
'/oracle/rdata14/ebay/oradata/itemd01d.dbf' size 501m,
'/oracle/rdata14/ebay/oradata/itemd01e.dbf' size 501m,
'/oracle/rdata14/ebay/oradata/itemd01f.dbf' size 501m,
'/oracle/rdata14/ebay/oradata/itemd01g.dbf' size 501m,
'/oracle/rdata14/ebay/oradata/itemd01h.dbf' size 501m;


create tablespace itemi01 datafile
'/oracle/rdata12/ebay/oradata/itemi01a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi01b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi01c.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi01d.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi01e.dbf' size 501m;


create tablespace itemi02 datafile
'/oracle/rdata12/ebay/oradata/itemi02a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi02b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi02c.dbf' size 501m;

create tablespace itemi03 datafile
'/oracle/rdata12/ebay/oradata/itemi03a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi03b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi03c.dbf' size 501m;

create tablespace itemi04 datafile
'/oracle/rdata12/ebay/oradata/itemi04a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi04b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi04c.dbf' size 501m;

create tablespace itemi05 datafile
'/oracle/rdata12/ebay/oradata/itemi05a.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi05b.dbf' size 501m,
'/oracle/rdata12/ebay/oradata/itemi05c.dbf' size 501m;

rename ebay_items to ebay_items_old;

/* use ebaybig.ora */

CREATE TABLE EBAY_ITEMS (
MARKETPLACE NUMBER(38, 0) CONSTRAINT ITEMS_MARKETPLACE2_NN NOT NULL,
ID NUMBER(38, 0) CONSTRAINT ITEMS_ID2_NN NOT NULL,
SALE_TYPE NUMBER(38, 0) CONSTRAINT ITEMS_SALE_TYPE2_NN NOT NULL,
TITLE VARCHAR2(254) CONSTRAINT ITEMS_TITLE2_NN NOT NULL,
LOCATION VARCHAR2(254) CONSTRAINT ITEMS_LOCATION2_NN NOT NULL,
SELLER NUMBER(38, 0) CONSTRAINT ITEMS_SELLER2_NN NOT NULL DISABLE,
OWNER NUMBER(38, 0) CONSTRAINT ITEMS_OWNER2_NN NOT NULL DISABLE,
PASSWORD NUMBER(38, 0) CONSTRAINT ITEMS_PASSWORD2_NN NOT NULL,
CATEGORY NUMBER(38, 0) CONSTRAINT ITEMS_CATEGORY2_NN NOT NULL DISABLE,
QUANTITY NUMBER(38, 0) CONSTRAINT ITEMS_QUANTITY2_NN NOT NULL,
BIDCOUNT NUMBER(38, 0) CONSTRAINT ITEMS_BIDCOUNT2_NN NOT NULL,
CREATED DATE CONSTRAINT ITEMS_CREATED2_NN NOT NULL,
SALE_START DATE CONSTRAINT ITEMS_SALE_START2_NN NOT NULL,
SALE_END DATE CONSTRAINT ITEMS_SALE_END2_NN NOT NULL,
SALE_STATUS NUMBER(38, 0) CONSTRAINT ITEMS_SALE_STATUS2_NN NOT NULL,
CURRENT_PRICE FLOAT(126) CONSTRAINT ITEMS_CURRENT_PRICE2_NN NOT NULL,
START_PRICE FLOAT(126) CONSTRAINT ITEMS_START_PRICE2_NN NOT NULL,
RESERVE_PRICE FLOAT(126) CONSTRAINT ITEMS_RESERVE_PRICE2_NN NOT NULL,
HIGH_BIDDER NUMBER(38, 0),
FEATURED CHAR(1) CONSTRAINT ITEMS_FEATURED2_NN NOT NULL,
SUPER_FEATURED CHAR(1) CONSTRAINT ITEMS_SUPER_FEATURED2_NN NOT NULL,
BOLD_TITLE CHAR(1) CONSTRAINT ITEMS_BOLD_TITLE2_NN NOT NULL,
PRIVATE_SALE CHAR(1) CONSTRAINT ITEMS_PRIVATE_SALE2_NN NOT NULL,
REGISTERED_ONLY CHAR(1) CONSTRAINT ITEMS_REGISTERED_ONLY2_NN NOT NULL,
HOST VARCHAR2(64),
VISITCOUNT NUMBER(38, 0) CONSTRAINT ITEMS_VISIT_COUNT2_NN NOT NULL,
PICTURE_URL VARCHAR2(255),
LAST_MODIFIED DATE CONSTRAINT ITEMS_LAST_MODIFIED2_NN NOT NULL,
ICON_FLAGS VARCHAR2(3),
GALLERY_URL VARCHAR2(255),
GALLERY_THUMB_X_SIZE NUMBER(4, 0),
GALLERY_THUMB_Y_SIZE NUMBER(4, 0),
GALLERY_STATE NUMBER(5, 0),
GALLERY_TYPE NUMBER(2, 0),
COUNTRY_ID NUMBER(4, 0))
PCTFREE 10 PCTUSED 40 INITRANS 5 MAXTRANS 255
STORAGE(INITIAL 500m NEXT 500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0
FREELISTS 1 FREELIST GROUPS 1)
TABLESPACE ITEMD01 unrecoverable as select * from ebay_items_old
where sale_end >= TO_DATE('1999-01-22 00:00:00','YYYY-MM-DD HH24:MI:SS');

/* rename ebay_items_old's index */
/* drop the index and recreate on ebay_items_old 
EBAY_ITEMS_SELLER_INDEX                  QITEMSI06
EBAY_ITEMS_HIGH_BIDDER_INDEX             QITEMSI04
EBAY_ITEMS_STARTING_INDEX                QITEMSI07
EBAY_ITEMS_ENDING_INDEX                  QITEMSI03
ITEMS_PK                                 QITEMSI
*/
alter table ebay_items_old drop index items_pk;

ALTER TABLE EBAY_ITEMS_OLD ADD  CONSTRAINT ITEM_OLD_PK PRIMARY KEY
(ID,MARKETPLACE) USING INDEX STORAGE
pctfree 0 initrans 5 (INITIAL 500m NEXT 500m MINEXTENTS 1 MAXEXTENTS 121
PCTINCREASE 0 FREELISTS 1 ) TABLESPACE qitemsi;


ALTER TABLE EBAY_ITEMS ADD  CONSTRAINT ITEMS_PK PRIMARY KEY
(ID,MARKETPLACE) USING INDEX STORAGE
pctfree 0 initrans 5 (INITIAL 500m NEXT 500m MINEXTENTS 1 MAXEXTENTS 121
PCTINCREASE 0 FREELISTS 1 ) TABLESPACE itemi01;

alter table ebay_items_old drop index EBAY_ITEMS_ENDING_INDEX;

CREATE INDEX EBAY_ITEMS_ENDING_IDX ON EBAY_ITEMS_OLD (SALE_END )
PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL 500m NEXT 500m
MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1)
TABLESPACE QITEMSI03 unrecoverable;

CREATE INDEX EBAY_ITEMS_ENDING_IDX ON EBAY_ITEMS (SALE_END )
PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL 500m NEXT 500m
MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1)
TABLESPACE ITEMI05  unrecoverable;

CREATE INDEX EBAY_ITEMS_STARTING_IDX ON EBAY_ITEMS
(SALE_START ) PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL
500m NEXT 500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0
FREELISTS 1) TABLESPACE ITEMI04  unrecoverable;

CREATE INDEX EBAY_ITEMS_SELLER_IDX ON EBAY_ITEMS (SELLER )
PCTFREE 10 INITRANS 2 MAXTRANS 255 STORAGE (INITIAL 500m NEXT
500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1)
TABLESPACE ITEMI02 unrecoverable;

CREATE INDEX EBAY_ITEMS_HIGH_BIDDER_IDX ON EBAY_ITEMS
(HIGH_BIDDER ) PCTFREE 10 INITRANS 5 MAXTRANS 255 STORAGE (INITIAL
500m NEXT 500m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0
FREELISTS 1)
TABLESPACE ITEMI03  unrecoverable;

ALTER TABLE EBAY_ITEMS ADD CONSTRAINT ITEMS_CATEGORY2_FK FOREIGN KEY
(MARKETPLACE,CATEGORY) REFERENCES EBAY_CATEGORIES (MARKETPLACE,ID) ;
ALTER TABLE EBAY_ITEMS ADD CONSTRAINT ITEMS_MARKETPLACE2_FK FOREIGN KEY
(MARKETPLACE) REFERENCES EBAY_MARKETPLACES (ID);
ALTER TABLE EBAY_ITEMS ADD CONSTRAINT ITEMS_SELLER2_FK FOREIGN KEY
(SELLER) REFERENCES EBAY_USERS (ID);
ALTER TABLE EBAY_ITEMS ADD CONSTRAINT ITEMS_HIGH_BIDDER2_FK FOREIGN KEY
(HIGH_BIDDER) REFERENCES EBAY_USERS (ID);
ALTER TABLE EBAY_ITEMS ADD CONSTRAINT ITEMS_OWNER2_FK FOREIGN KEY
(OWNER) REFERENCES EBAY_USERS (ID);

/* Run the following in parallel */

ALTER TABLE EBAY_SPECIAL_ITEMS ADD CONSTRAINT SPECIAL_ITEMS2_FK FOREIGN
KEY (ID,MARKETPLACE) REFERENCES EBAY_ITEMS (ID,MARKETPLACE);
ALTER TABLE EBAY_ITEM_INFO ADD CONSTRAINT RITEM_INFO2_FK FOREIGN KEY
(ID,MARKETPLACE) REFERENCES EBAY_ITEMS (ID,MARKETPLACE);
ALTER TABLE EBAY_BIDS ADD CONSTRAINT QBID_ITEM2_FK FOREIGN KEY
(ITEM_ID,MARKETPLACE) REFERENCES EBAY_ITEMS (ID,MARKETPLACE);
/* Probably dont need .....ALTER TABLE "EBAY_ITEM_DESC" ADD CONSTRAINT
"RITEMS_MARKETPLACE_ID2_FK" FOREIGN KEY ("ID","MARKETPLACE") REFERENCES
"EBAY_ITEMS" ("ID","MARKETPLACE") DISABLE; */

/* Following should only be run if we KNOWWWW the previous has worked */

-- alter table EBAY_SPECIAL_ITEMS drop constraint SPECIAL_ITEMS_FK;
-- alter table EBAY_ITEM_INFO drop constraint RITEM_INFO_FK;
-- alter table EBAY_BIDS  drop constraint QBID_ITEM2_FK;
-- alter table EBAY_ITEM_DESC drop constraint RITEMS_MARKETPLACE_ID_FK;
-- drop table ebay_items_old;

alter table ebay_items
add (  NOTICE_TIME     DATE);

alter table ebay_items
add ( BILL_TIME   DATE);

alter table ebay_items add(DUTCH_GMS float(126));

ALTER TABLE EBAY_ITEMS ADD (SHIPPING_OPTION NUMBER(3) NULL);
ALTER TABLE EBAY_ITEMS ADD (SHIP_REGION_FLAGS NUMBER(16) NULL);
ALTER TABLE EBAY_ITEMS ADD (DESC_LANG NUMBER(3) NULL);

