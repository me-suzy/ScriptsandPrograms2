/*
 * ebay_deadbeat_user_items.sql
 *
 * ** NOTE **
 * Right now, item numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

 drop table ebay_deadbeat_user_items;

 create table ebay_deadbeat_user_items
 (
	marketplace			number(38)
		constraint		deadbeat_items_marketplace_nn
			not null,
	id					number(38)
		constraint		deadbeat_items_id_nn
			not null,
	sale_type			number(38)
		constraint		deadbeat_items_sale_type_nn
			not null,
	title				varchar2(254)
		constraint		deadbeat_items_title_nn
			not null,
	location			varchar2(254)
		constraint		deadbeat_items_location_nn
			not null,
	seller				number(38)
		constraint		deadbeat_items_seller_nn
			not null,
	owner				number(38)
		constraint		deadbeat_items_owner_nn
			not null 
			disable,
	password			number(38)
		constraint		deadbeat_items_password_nn
			not null,
	category			number(38)
		constraint		deadbeat_items_category_nn
			not null
			disable,
	quantity			number(38)
		constraint		deadbeat_items_quantity_nn
			not null,
	bidcount			number(38)
		constraint		deadbeat_items_bidcount_nn
			not null,
	created				date
		constraint		deadbeat_items_created_nn
			not null,
	sale_start			date
		constraint		deadbeat_items_sale_start_nn
			not null,
	sale_end			date
		constraint		deadbeat_items_sale_end_nn
			not null,
	sale_status			number(38)
		constraint		deadbeat_items_sale_status_nn
			not null,
	current_price		float(126)
		constraint		deadbeat_items_current_price_nn
			not null,
	start_price			float(126)
		constraint		deadbeat_items_start_price_nn
			not null,
	reserve_price		float(126)
		constraint		deadbeat_items_reserve_price_nn
			not null,
	high_bidder			number(38)
		constraint		deadbeat_items_high_bidder_nn
			not null,
	featured			char(1)
		constraint		deadbeat_items_featured_nn
			not null,
	super_featured		char(1)
		constraint		deadbeat_items_super_featured_nn
			not null,
	bold_title			char(1)
		constraint		deadbeat_items_bold_title_nn
			not null,
	private_sale		char(1)
		constraint		deadbeat_items_private_sale_nn
			not null,
	registered_only	char(1)
		constraint		deadbeat_items_registered_only_nn
			not null,
	host				varchar(64),
	visitcount			number(38)
		constraint		deadbeat_items_visit_count_nn
			not null,			
	picture_url			varchar(255),
	last_modified		date
		constraint		deadbeat_items_last_modified_nn
			not null,
	reason_code			CHAR(2)
		constraint		deadbeats_reason_code_nn
			not null,
	account_id			NUMBER(38)
		constraint		deadbeats_account_id_nn
			not null
)
tablespace titemd01
storage(initial 500m next 100m);


alter table ebay_deadbeat_user_items 
	add constraint		deadbeats_pk
		primary key		(marketplace, id, seller, high_bidder)
		using index tablespace	titemi01
		storage (initial 70M next 30M) unrecoverable parallel (degree 3);

 create index ebay_deadbeat_item_index
   on ebay_deadbeat_user_items(id)
   tablespace titemi01
   storage(initial 70m next 30M) unrecoverable parallel (degree 3);

 create index ebay_deadbeat_bidder_index
   on ebay_deadbeat_user_items(high_bidder)
   tablespace titemi01
   storage(initial 70m next 30M) unrecoverable parallel (degree 3);
/* 1:54 - 1:57 */

 create index ebay_deadbeat_seller_index
   on ebay_deadbeat_user_items(seller)
   tablespace titemi01
   storage(initial 70m next 30M) unrecoverable parallel (degree 3);
