/*	$Id: ebay_bids.sql,v 1.3 1999/02/21 02:56:05 josh Exp $	*/
/*
 * ebay_bids.sql
 *
 * ebay_bids contains detailed information
 * about the bids on an item.
 *
 */


 create table ebay_bids 
 (
	marketplace	int
		constraint	bids_marketplace_fk
		not null
		references	ebay_marketplaces(id),
 	item_id		int
		constraint	bids_item_id_nn
		not null,
	user_id		int
		constraint	bids_user_id_nn
		not null,
	quantity		int
		constraint	bids_quantity_nn
		not null,
	amount		float(126)
		constraint	bids_amount_nn
		not null,
	value			float(126)
		constraint	bids_value_nn
		not null,
	type			int
		CONSTRAINT bids_check_type
		CHECK (type IN (0, 1, 2, 3, 4, 5, 6)),
	created		date
		constraint	bid_create_nn
		not null,
	reason		varchar2(255),
	constraint		bids_check_quantity
		check (quantity >= 0),
	constraint		bids_check_amount
		check (amount >= 0),
	constraint		bids_item_fk
			foreign key (marketplace, item_id)
			references	ebay_items(marketplace, id),
	constraint		bids_user_fk
			foreign key (user_id)
			references	ebay_users(id)
 )
 tablespace tbidd01
 storage (initial 7M next 2M);

 create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace tbidi01
	storage(initial 1m next 1m);

 create index ebay_bids_item_index
	on ebay_bids(item_id)
	tablespace tbidi01
	storage(initial 1m next 1m);

 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace tbidi02
   storage(initial 1M next 1M) unrecoverable parallel (degree 3);

