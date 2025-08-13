/*	$Id: ebay_bids_arc.sql,v 1.3 1999/02/21 02:53:23 josh Exp $	*/
/*
 * ebay_bids_arc.sql
 *
 * ebay_bids_arc contains detailed information
 * about the archived bids on an item.
 *
 */

 drop table ebay_bids_arc;

 create table ebay_bids_arc 
 (
	marketplace	int
		constraint	bids_arc_marketplace_nn
		not null,
 	item_id		int
		constraint	bids_arc_item_id_nn
		not null,
	user_id		int
		constraint	bids_arc_user_id_nn
		not null,
	quantity		int
		constraint	bids_arc_quantity_nn
		not null,
	amount		number(10,2)
		constraint	bids_arc_amount_nn
		not null,
	value			number(10,2)
		constraint	bids_arc_value_nn
		not null,
	type			int
		CONSTRAINT bids_arc_check_type
		CHECK (type IN (0, 1, 2, 3, 4, 5, 6)),
	created		date
		constraint	bid_arc_create_nn
		not null,
	reason		varchar2(255)
 )
 tablespace bidarc1
 storage (initial 60M next 10M);


 create index ebay_bidsarc_item_index
	on ebay_bids_arc(item_id)
	tablespace bidarci1
	storage(initial 10M next 2M) unrecoverable;

--- temporary for first bidder sweepstakes
create table ebay_bids_arc_temp
 (
	marketplace	int
		constraint	bids_arc_marketplace_nn
		not null,
 	item_id		int
		constraint	bids_arc_item_id_nn
		not null,
	user_id		int
		constraint	bids_arc_user_id_nn
		not null,
	quantity		int
		constraint	bids_arc_quantity_nn
		not null,
	amount		number(10,2)
		constraint	bids_arc_amount_nn
		not null,
	value			number(10,2)
		constraint	bids_arc_value_nn
		not null,
	type			int
		CONSTRAINT bids_arc_check_type
		CHECK (type IN (0, 1, 2, 3, 4, 5, 6)),
	created		date
		constraint	bid_arc_create_nn
		not null,
	reason		varchar2(255),
	host		varchar2(16)
 )
 tablespace playd01
 storage (initial 30M next 10M pctincrease 0);

