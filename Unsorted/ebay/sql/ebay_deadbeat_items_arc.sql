/*
 * ebay_deadbeat_items_arc.sql
 *
 * archive of ebay_deadbeat_items
 */

 drop table ebay_deadbeat_items_arc;

 create table ebay_deadbeat_items_arc
 (
	MARKETPLACE			NUMBER(38)
		constraint		deadbeat_item_marketplace_fk
			references ebay_marketplaces(id),
	ID					NUMBER(38)
		constraint		deadbeat_item_id_nn
			not null,
	SELLER				NUMBER(38)
		constraint		deadbeat_item_seller_fk
			references ebay_users(id),
	BIDDER				NUMBER(38)
		constraint		deadbeat_item_bidder_fk
			references ebay_users(id),
	SALE_START			DATE
		constraint		deadbeat_item_sale_start_nn
			not null,
	SALE_END			DATE
		constraint		deadbeat_item_sale_end_nn
			not null,
	TITLE				VARCHAR2(254)
		constraint		deadbeat_item_title_nn
			NOT NULL,
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
			not null
)
tablespace itemarc3
storage(initial 100M next 50m);

create index ebay_deadbeat_items_arc_id_index
   on ebay_deadbeat_items_arc(id)
   tablespace itemarci1
   storage(initial 50m next 50M);
commit;
