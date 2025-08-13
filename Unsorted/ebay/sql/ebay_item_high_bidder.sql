/*	$Id: ebay_item_high_bidder.sql,v 1.2 1999/02/21 02:53:47 josh Exp $	*/
/*
 * ebay_item_dutch_high_bidder.sql
 *
 * Keeps the list of high bidders for dutch auctions
 *
 */
 
 drop table ebay_item_dutch_high_bidder;

 create table ebay_item_dutch_high_bidder
 (
 	MARKETPLACE		NUMBER(38)
		constraint		item_dutch_marketplace_fk
			references ebay_marketplaces(id),
	ID				NUMBER(38)
		constraint		item_dutch_id_nn
			not null,
	HIGH_BIDDER		NUMBER(38)
		constraint		item_hibidder_fk
		references ebay_users(id),
	quantity		int
		constraint	item_hibidder_quantity_nn
		not null,
	amount			float(126)
		constraint	item_hibidder_amount_nn
		not null,
	value			float(126)
		constraint	item_hibidder_value_nn
		not null,
	bid_date		date
		constraint	item_hibidder_bid_date_nn
		not null,
	constraint		item_dutch_id_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id)
				)
tablespace itemd01
storage(initial 5M next 1M);
