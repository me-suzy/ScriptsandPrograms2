/*
 * ebay_deadbeat_user_items.sql
 *
 * ** NOTE **
 * Right now, item numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

/*  drop table ebay_deadbeat_user_items;
 */

 create table ebay_deadbeat_user_items
 (
	MARKETPLACE			NUMBER(38)
		constraint		deadbeats_marketplace_fk
			references ebay_marketplaces(id),
	ID					NUMBER(38)
		constraint		deadbeats_id_nn
			not null,
	SALE_TYPE			NUMBER(38)
		constraint		deadbeats_sale_type_nn
			not null,
	TITLE				VARCHAR2(254)
		constraint		deadbeats_title_nn
			NOT NULL,
	LOCATION			VARCHAR2(254)
		constraint		deadbeats_location_nn
			not null,
	SELLER				NUMBER(38)
		constraint		deadbeats_seller_fk
			references ebay_users(id),
	OWNER				NUMBER(38)
		constraint		deadbeats_owner_fk
			references ebay_users(id),
	PASSWORD			NUMBER(38)
		constraint		deadbeats_password_nn
			not null,
	CATEGORY			NUMBER(38)
		constraint		deadbeats_category_nn
			not null
			disable,
	QUANTITY			NUMBER(38)
		constraint		deadbeats_quantity_nn
			not null,
	BIDCOUNT			NUMBER(38)
		constraint		deadbeats_bidcount_nn
			not null,
	CREATED				DATE
		constraint		deadbeats_created_nn
			not null,
	SALE_START			DATE
		constraint		deadbeats_sale_start_nn
			not null,
	SALE_END			DATE
		constraint		deadbeats_sale_end_nn
			not null,
	SALE_STATUS			NUMBER(38)
		constraint		deadbeats_sale_status_nn
			not null,
	CURRENT_PRICE		FLOAT(126)
		constraint		deadbeats_current_price_nn
			not null,
	START_PRICE			FLOAT(126)
		constraint		deadbeats_start_price_nn
			not null,
	RESERVE_PRICE		FLOAT(126)
		constraint		deadbeats_reserve_price_nn
			not null,
	HIGH_BIDDER			NUMBER(38)
		constraint		deadbeats_high_bidder_fk
			references ebay_users(id),
	FEATURED			CHAR(1)
		constraint		deadbeats_featured_nn
			not null,
	SUPER_FEATURED		CHAR(1)
		constraint		deadbeats_super_featured_nn
			not null,
	BOLD_TITLE			CHAR(1)
		constraint		deadbeats_bold_title_nn
			not null,
	PRIVATE_SALE		CHAR(1)
		constraint		deadbeats_private_sale_nn
			not null,
	REGISTERED_ONLY	CHAR(1)
		constraint		deadbeats_registered_only_nn
			not null,
	HOST				VARCHAR(64),
	VISITCOUNT			NUMBER(38)
		constraint		deadbeats_visit_count_nn
			not null,			
	PICTURE_URL			VARCHAR(255),
	LAST_MODIFIED		DATE
		constraint		deadbeats_last_modified_nn
			not null,
	REASON_CODE			CHAR(2)
		constraint		deadbeats_reason_code_nn
			not null,
	ACCOUNT_ID			NUMBER(38)
		constraint		deadbeats_account_id_nn
			not null,
	constraint			deadbeats_pk
		primary key		(marketplace, id, seller, high_bidder)
		using index tablespace	titemi01
		storage (initial 1M next 100k)
)
tablespace titemd01
storage(initial 5M next 5M)


 alter table ebay_deadbeat_user_items
	modify (	MARKETPLACE
				constraint	deadbeats_marketplace_nn
				not null)

 alter table ebay_deadbeat_user_items
	modify (	SELLER
				constraint	deadbeats_seller_nn
				not null disable)

 alter table ebay_deadbeat_user_items
	modify (	OWNER
				constraint	deadbeats_owner_nn
				not null disable)

 create index ebay_deadbeat_item_index
   on ebay_deadbeat_user_items(id)
   tablespace titemi01
   storage(initial 1m next 100k)

 create index ebay_deadbeat_bidder_index
   on ebay_deadbeat_user_items(high_bidder)
   tablespace titemi01
   storage(initial 1m next 100k)

 create index ebay_deadbeat_seller_index
   on ebay_deadbeat_user_items(seller)
   tablespace titemi01
   storage(initial 1m next 100k)
