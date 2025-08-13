/*	$Id: ebay_items_wait.sql,v 1.2 1999/02/21 02:56:32 josh Exp $	*/
/*
 * ebay_items_wait.sql
 *
 * ebay items in abeyance - transaction id is the batch transaction;
 * id is bogus till its moved to ebay_items;
 * uid and batchid uniquely identifies batch per user
 */

  drop table ebay_items_wait;

 create table ebay_items_wait
 (
	MARKETPLACE			NUMBER(38)
		constraint		iwait_marketplace_fk
			references ebay_marketplaces(id),
	ID					NUMBER(38)
		constraint		iwait_id_nn
			not null,
	User_ID				NUMBER(38)
		constraint		iwait_uid_nn
			not null,
	BATCHID				NUMBER(38)
		constraint		iwait_batchid_nn
			not null,
	SALE_TYPE			NUMBER(3)
		constraint		iwait_sale_type_nn
			not null,
	TITLE				VARCHAR2(254)
		constraint		iwait_title_nn
			NOT NULL,
	LOCATION			VARCHAR2(254)
		constraint		iwait_location_nn
			not null,
	SELLER				NUMBER(38)
		constraint		iwait_seller_fk
			references ebay_users(id),
	OWNER				NUMBER(38)
		constraint		iwait_owner_fk
			references ebay_users(id),
	CATEGORY			NUMBER(38)
		constraint		iwait_category_nn
			not null,
	QUANTITY			NUMBER(10)
		constraint		iwait_quantity_nn
			not null,
	DURATION			NUMBER(3)
		constraint		iwait_bidcount_nn
			not null,
	SALE_START			DATE
		constraint		iwait_sale_start_nn
			not null,
	START_PRICE			NUMBER(10,2)
		constraint		iwait_start_price_nn
			not null,
	RESERVE_PRICE		NUMBER(10,2)
		constraint		iwait_reserve_price_nn
			not null,
	FEATURED			CHAR(1)
			constraint		iwait_featured_nn
			not null,
	SUPER_FEATURED		CHAR(1)
			constraint		iwait_super_featured_nn
			not null,
	BOLD_TITLE			CHAR(1)
			constraint		iwait_bold_title_nn
			not null,
	PRIVATE_SALE		CHAR(1)
			constraint		iwait_private_sale_nn
			not null,
	HOST				varchar(64),
	PICTURE_URL			VARCHAR(255),
	last_modified		date
		constraint	iwait_last_modified_nn
		not null,
	constraint			iwait_pk
		primary key		(marketplace, id, user_id, batchid)
		using index tablespace	titemi01
		storage (initial 2M next 1M),
	constraint			iwait_category_fk
		foreign key(marketplace, category)
		references ebay_categories(marketplace, id)
)
tablespace titemd01
storage(initial 20M next 5M);

