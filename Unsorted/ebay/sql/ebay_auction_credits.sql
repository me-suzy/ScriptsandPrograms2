/*
 * ebay_auction_credits.sql
 *
 * ** NOTE **
 * Right now, item numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

/*  drop table ebay_auction_credits;
 */

 create table ebay_auction_credits
 (
	item_id				number(38)
		constraint		credits_item_id_nn
			not null,
	bidder_id			number(38)
		constraint		credits_bidder_id_nn
			not null,
	amount				number(10,2)
		constraint		credits_amount_nn
			not null,
	last_modified		date
		constraint		credits_issue_date_nn
			not null,
	reason_code			char(2)
		constraint		credits_reason_code_nn
			not null,
	credit_type			char(1) default 'n'
		constraint		credits_type_code_nn
			not null,
	quantity			number(10)
		constraint		credits_quantity_nn
			not null,
	batch_id			number(2) default -1
		constraint		credits_batch_id_nn
			not null,

	constraint			credits_pk
		primary key		(item_id, bidder_id)
		using index tablespace	critemi01
		storage (initial 1M next 100k)
)

tablespace critemd01
storage(initial 50M next 5M);


commit;
