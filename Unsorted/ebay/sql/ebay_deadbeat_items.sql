/*
 * ebay_deadbeat_items.sql
 *
 * ** NOTE **
 * Right now, item numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

 drop table ebay_deadbeat_items;

 create table ebay_deadbeat_items
 (
	MARKETPLACE			NUMBER(38)
		constraint		deadbeat_item_marketplace_nn
			not null,
	ID					NUMBER(38)
		constraint		deadbeat_item_id_nn
			not null,
	SELLER				NUMBER(38)
		constraint		deadbeat_item_seller_nn
			not null,
	BIDDER				NUMBER(38)
		constraint		deadbeat_item_bidder_nn
			not null,
	SALE_START			DATE
		constraint		deadbeat_item_sale_start_nn
			not null,
	SALE_END			DATE
		constraint		deadbeat_item_sale_end_nn
			not null,
	TITLE				VARCHAR2(254)
		constraint		deadbeat_item_title_nn
			not null,
	PRICE				FLOAT(126)
		constraint		deadbeat_item_price_nn
			not null,
	QUANTITY			NUMBER(38)
		constraint		deadbeat_item_quantity_nn
			not null,
	REASON_CODE			VARCHAR2(2)
		constraint		deadbeat_item_reason_code_nn
			not null,
	TRANSACTION_ID		NUMBER(38)
		constraint		deadbeat_item_xaction_id_nn
			not null,
	NOTIFICATION_SENT	VARCHAR2(1)
		constraint		deadbeat_item_notification_nn
			not null,
	CREATED				DATE
		constraint		deadbeat_item_created_nn
			not null,
	LAST_MODIFIED		DATE
		constraint		deadbeat_item_last_modified_nn
			not null,
	constraint			deadbeat_item_pk
		primary key		(marketplace, id, seller, bidder)
		using index tablespace	deadbeati01
		storage (initial 50M next 50M)
)
tablespace deadbeatd01
storage(initial 200M next 50M);


 create index ebay_deadbeat_item_id_index
   on ebay_deadbeat_items(id)
   tablespace deadbeati01
   storage(initial 50m next 10M) unrecoverable parallel (degree 3);

 create index ebay_deadbeat_item_sell_index
   on ebay_deadbeat_items(seller)
   tablespace deadbeati01
   storage(initial 50m next 10M) unrecoverable parallel (degree 3);

 create index ebay_deadbeat_item_bid_index
   on ebay_deadbeat_items(bidder)
   tablespace deadbeati01
   storage(initial 50m next 10M) unrecoverable parallel (degree 3);
