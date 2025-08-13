/*	$Id: ebay_item_info.sql,v 1.2 1999/02/21 02:56:30 josh Exp $	*/
/*
 * ebay_item_info.sql
 *
 *	This table contains information about various 
 * "wrap up" activities for auctions -- notices
 * (telling sellers and high bidders the auction is
 * over), and billing (telling the seller they've
 * been billed).
 *
 * A row is added to this table by the End of auction
 * process when the user(s) are actually notified that
 * the auction is over. The billing process uses this
 * to bill users who have received end of auction 
 * notices. 
 * 
 */

/*  drop table ebay_item_info; */


 create table ebay_item_info
 (
	MARKETPLACE			NUMBER(38)
		constraint		item_info_marketplace_nn
			not null,
	ID						NUMBER(38)
		constraint		item_info_id_nn
			not null,
	NOTICE_TIME			date,
	BILL_TIME			date,
	constraint			item_info_pk
		primary key		(marketplace, id)
		using index tablespace	titemi01
		storage (initial 500K next 100K),
	constraint			item_info_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id)
)
tablespace titemd01
storage(initial 5M next 1m);
