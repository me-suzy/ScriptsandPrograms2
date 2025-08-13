/*	$Id: ebay_items.sql,v 1.4 1999/04/07 05:42:50 josh Exp $	*/
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

/*  drop table ebay_items;
 */

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
		using index tablespace	titemi01
		storage (initial 2M next 1M),
	constraint			items_category_fk
		foreign key(marketplace, category)
		references ebay_categories(marketplace, id)
)
tablespace titemd01
storage(initial 20M next 5M);


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

 alter table ebay_items
	add (currency_id number(3,0));




 drop sequence ebay_items_sequence;

 create sequence ebay_items_sequence;

 create index ebay_items_starting_index
   on ebay_items(sale_start)
   tablespace titemi01
   storage(initial 1M next 100K);

 create index ebay_items_ending_index
   on ebay_items(sale_end)
   tablespace titemi01
   storage(initial 1M next 100K);

 create index ebay_items_category_index 
	on ebay_items(category)
	tablespace titemi01
	storage(initial 1m next 2m);

 create index ebay_items_seller_index
	on ebay_items(seller)
	tablespace titemi01
	storage(initial 1m next 2);

 create index ebay_items_high_bidder_index
   on ebay_items(high_bidder)
   tablespace titemi01
   storage(initial 1m next 2);

 create index ebay_items_last_modified_index
   on ebay_items(last_modified)
   tablespace titemi01
   storage(initial 1m next 2);


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
tablespace titemd01
storage(initial 1M next 50K);

 alter table ebay_items
 add ( GALLERY_STATE   NUMBER(5,0)
   default     0 );

 alter table ebay_items
 add ( GALLERY_URL VARCHAR2(255));

 alter table ebay_items
 add ( GALLERY_THUMB_X_SIZE NUMBER(4,0) );

 alter table ebay_items
 add ( GALLERY_THUMB_Y_SIZE NUMBER(4,0) );

 alter table ebay_items_arc
 add (currency_id number(3,0));


 alter table ebay_items_ended
 add (currency_id number(3,0));