/*	$Id: ebay_bids.sql,v 1.2 1999/02/21 02:53:22 josh Exp $	*/
/*
 * ebay_bids.sql
 *
 * ebay_bids contains detailed information
 * about the bids on an item.
 *
 */

 drop table ebay_bids;

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
 tablespace bidd01
 storage (initial 50M next 10M);

/* obsolete - modified - see below 
 create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace bidi01
	storage(initial 10M next 2M);

 create index ebay_bids_item_index
	on ebay_bids(item_id)
	tablespace bidi01
	storage(initial 10M next 2M);

 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace bidi01
   storage(initial 10M next 2M);
*/

create index ebay_bids_item_user_index
	on ebay_bids(item_id, user_id)
   tablespace bidi02
	storage(initial 300M next 50M)  unrecoverable parallel (degree 3);
/* 2:47 - 3:06 */

create index ebay_bids_item_index
	on ebay_bids(item_id)
	tablespace bidi02
	storage(initial 300M next 50M)  unrecoverable parallel (degree 3);
/* 3:06 - 3:18 */


 create index ebay_bids_user_index
   on ebay_bids(user_id)
   tablespace bidi02
   storage(initial 200M next 50M) unrecoverable parallel (degree 3);
/* 3:18 - 3:30 */
