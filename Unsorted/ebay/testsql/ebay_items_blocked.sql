/*	$Id: ebay_items_blocked.sql,v 1.2 1999/05/19 02:35:11 josh Exp $	*/
/*
 * ebay_items_blocked.sql
 *
 * ** NOTE **
 * Right now, items numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */
/* added last modified field; do we really need it? */

/*  drop table ebay_items_blocked;
 */

 create table ebay_items_blocked
 (
	MARKETPLACE			NUMBER(38)
		constraint		items_blocked_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
		constraint		items_blocked_id_nn
			not null,
	SALE_TYPE			NUMBER(38)
		constraint		items_blocked_sale_type_nn
			not null,
	TITLE					VARCHAR2(254)
		constraint		items_blocked_title_nn
			NOT NULL,
	LOCATION				VARCHAR2(254)
		constraint		items_blocked_location_nn
			not null,
	SELLER				NUMBER(38)
		constraint		items_blocked_seller_fk
			references ebay_users(id),
	OWNER					NUMBER(38)
		constraint		items_blocked_owner_fk
			references ebay_users(id),
	PASSWORD				NUMBER(38)
		constraint		items_blocked_password_nn
			not null,
	CATEGORY				NUMBER(38)
		constraint		items_blocked_category_nn
			not null
			disable,
	QUANTITY				NUMBER(38)
		constraint		items_blocked_quantity_nn
			not null,
	BIDCOUNT				NUMBER(38)
		constraint		items_blocked_bidcount_nn
			not null,
	CREATED				DATE
		constraint		items_blocked_created_nn
			not null,
	SALE_START			DATE
		constraint		items_blocked_sale_start_nn
			not null,
	SALE_END				DATE
		constraint		items_blocked_sale_end_nn
			not null,
	SALE_STATUS			NUMBER(38)
		constraint		items_blocked_sale_status_nn
			not null,
	CURRENT_PRICE		FLOAT(126)
		constraint		items_blocked_current_price_nn
			not null,
	START_PRICE			FLOAT(126)
		constraint		items_blocked_start_price_nn
			not null,
	RESERVE_PRICE		FLOAT(126)
		constraint		items_blocked_reserve_price_nn
			not null,
	HIGH_BIDDER			NUMBER(38)
			constraint		items_blocked_high_bidder_fk
			references ebay_users(id),
	FEATURED				CHAR(1)
			constraint		items_blocked_featured_nn
			not null,
	SUPER_FEATURED			CHAR(1)
			constraint		items_blocked_super_featured_nn
			not null,
	BOLD_TITLE			CHAR(1)
			constraint		items_blocked_bold_title_nn
			not null,
	PRIVATE_SALE		CHAR(1)
			constraint		items_blocked_private_sale_nn
			not null,
	REGISTERED_ONLY	CHAR(1)
			constraint		items_blocked_registered_only_nn
			not null,
	HOST				varchar(64),
	VISITCOUNT			NUMBER(38)
			constraint		items_blocked_visit_count_nn
			not null,			
	PICTURE_URL			VARCHAR(255),
	last_modified		date
		constraint	items_blocked_last_modified_nn
		not null,
	constraint			items_blocked_pk
		primary key		(marketplace, id)
		using index tablespace	titemi01
		storage (initial 2M next 1M),
	constraint			items_blocked_category_fk
		foreign key(marketplace, category)
		references ebay_categories(marketplace, id)
)
tablespace titemd01
storage(initial 20M next 5M);


 alter table ebay_items_blocked
	modify (	MARKETPLACE
				constraint	items_blocked_marketplace_nn
				not null);

 alter table ebay_items
	modify (	SELLER
				constraint	items_blocked_seller_nn
				not null disable);

 alter table ebay_items
	modify (	OWNER
				constraint	items_blocked_owner_nn
				not null disable);

 drop sequence ebay_items_blocked_sequence;

 create sequence ebay_items_blocked_sequence;

 create index ebay_items_blocked_cat_index 
	on ebay_items_blocked(category)
	tablespace titemi01
	storage(initial 1m next 2m);

 create index ebay_items_blocked_seller_index
	on ebay_items_blocked(seller)
	tablespace titemi01
	storage(initial 1m next 2);

 alter table ebay_items_blocked
 add ( GALLERY_STATE   NUMBER(5,0)
   default     0 );

 alter table ebay_items_blocked
 add ( GALLERY_URL VARCHAR2(255));

 alter table ebay_items_blocked
 add ( GALLERY_THUMB_X_SIZE NUMBER(4,0) );

 alter table ebay_items_blocked
 add ( GALLERY_THUMB_Y_SIZE NUMBER(4,0) );

