/*	$Id: ebay_item_high_bidder.sql,v 1.2 1999/02/21 02:56:29 josh Exp $	*/
/*
 * ebay_item_dutch_high_bidder.sql
 *
 * Keeps the list of high bidders for dutch auctions
 *
 */
 
/*  drop table ebay_item_dutch_high_bidder; */

 create table ebay_item_dutch_high_bidder
 (
 	MARKETPLACE			NUMBER(38)
		constraint		item_dutch_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
		constraint		item_dutch_id_nn
			not null,
	HIGH_BIDDER			NUMBER(38)
		constraint		item_dutch_high_bidder_fk
		references ebay_users(id),
	constraint			item_dutch_id_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id)
		)
tablespace titemd01
storage(initial 20M next 5M);
