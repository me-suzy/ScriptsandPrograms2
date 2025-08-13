/*	$Id: ebay_items_arc.sql,v 1.5 1999/05/19 02:35:08 josh Exp $	*/
/*
 * ebay_items_arc.sql
 *
 * archive of ebay_items
 */

 drop table ebay_items_arc;

 create table ebay_items_arc
 (
	MARKETPLACE			NUMBER(38)
		constraint		items_arc_marketplace_nn
			not null,
	ID						NUMBER(38)
		constraint		items_arc_id_nn
			not null,
	SALE_TYPE			NUMBER(38)
		constraint		items_arc_sale_type_nn
			not null,
	TITLE					VARCHAR2(254)
		constraint		items_arc_title_nn
			NOT NULL,
	LOCATION				VARCHAR2(254)
		constraint		items_arc_location_nn
			not null,
	SELLER				NUMBER(38)
		constraint		items_arc_seller_nn
			not null,
	OWNER					NUMBER(38)
		constraint		items_arc_owner_nn
			not null,
	PASSWORD				NUMBER(38)
		constraint		items_arc_password_nn
			not null,
	CATEGORY				NUMBER(38)
		constraint		items_arc_category_nn
			not null
			disable,
	QUANTITY				NUMBER(38)
		constraint		items_arc_quantity_nn
			not null,
	BIDCOUNT				NUMBER(38)
		constraint		items_arc_bidcount_nn
			not null,
	CREATED				DATE
		constraint		items_arc_created_nn
			not null,
	SALE_START			DATE
		constraint		items_arc_sale_start_nn
			not null,
	SALE_END				DATE
		constraint		items_arc_sale_end_nn
			not null,
	SALE_STATUS			NUMBER(38)
		constraint		items_arc_sale_status_nn
			not null,
	CURRENT_PRICE		FLOAT(126)
		constraint		items_arc_current_price_nn
			not null,
	START_PRICE			FLOAT(126)
		constraint		items_arc_start_price_nn
			not null,
	RESERVE_PRICE		FLOAT(126)
		constraint		items_arc_reserve_price_nn
			not null,
	HIGH_BIDDER			NUMBER(38),
	FEATURED				CHAR(1)
			constraint		items_arc_featured_nn
			not null,
	SUPER_FEATURED			CHAR(1)
			constraint		items_arc_super_featured_nn
			not null,
	BOLD_TITLE			CHAR(1)
			constraint		items_arc_bold_title_nn
			not null,
	PRIVATE_SALE		CHAR(1)
			constraint		items_arc_private_sale_nn
			not null,
	REGISTERED_ONLY	CHAR(1)
			constraint		items_arc_registered_only_nn
			not null,
	HOST				varchar(64),
	VISITCOUNT			NUMBER(38)
			constraint		items_arc_visit_count_nn
			not null,			
	PICTURE_URL			VARCHAR(255),
	last_modified		date
		constraint	items_arc_last_modified_nn
		not null
)
tablespace itemarc3
storage(initial 100M next 50m);

create index ebay_itemarc_id_index
   on ebay_items_arc(id)
   tablespace itemarci1
   storage(initial 50m next 50M);
commit;

/* temporary to generate focus group reports 12/27/98 */
 create index ebay_itemarc_seller_index
	on ebay_items_arc(seller)
	tablespace itemarci3
	storage(initial 50m next 50M pctincrease 0) 
	unrecoverable parallel (degree 3);

 create index ebay_itemarc_high_bidder_index
   on ebay_items_arc(high_bidder)
   tablespace itemarci3
   storage(initial 50m next 50M pctincrease 0) 
   unrecoverable parallel (degree 3);


/* to be done only for summary report use */
 create index ebay_itemarc_ending_index
   on ebay_items_arc(sale_end)
   tablespace bidarci1
   storage(initial 50M next 10M) unrecoverable;

alter table ebay_items_arc add ( ORIG_SALE_END date);
alter table ebay_items_arc add ( CONTENT_MODIFIED date);
alter table ebay_items_arc add ( ICON_FLAGS VARCHAR2(3));
/* Where the item is located by country. */
alter table ebay_items_arc
	add (country_id number(10));
alter table ebay_items_arc add ( GALLERY_URL VARCHAR2(255));
alter table ebay_items_arc add ( GALLERY_THUMB_X_SIZE NUMBER(4,0) );
alter table ebay_items_arc add ( GALLERY_THUMB_Y_SIZE NUMBER(4,0) );
alter table ebay_items_arc add ( GALLERY_TYPE NUMBER(2,0));

alter table ebay_items_arc
add (  NOTICE_TIME     DATE);

alter table ebay_items_arc
add ( BILL_TIME   DATE);

alter table ebay_items
	add (currency_id number(3,0));

alter table ebay_items add(DUTCH_GMS float(126));