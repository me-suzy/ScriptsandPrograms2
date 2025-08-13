/*	$Id: ebay_item_info_arc.sql,v 1.2 1999/02/21 02:53:51 josh Exp $	*/
/*
 * ebay_item_info_arc.sql
 *
 *	This table contains archive information about various 
 * "wrap up" activities for auctions -- notices
 * (telling sellers and high bidders the auction is
 * over), and billing (telling the seller they've
 * been billed).
 * 
 */

 drop table ebay_item_info_arc;

 create table ebay_item_info_arc
 (
	MARKETPLACE			NUMBER(38)
		constraint		item_info_arc_marketplace_nn
			not null,
	ID						NUMBER(38)
		constraint		item_info_arc_id_nn
			not null,
	NOTICE_TIME			date,
	BILL_TIME			date
 )
tablespace itemarc2
storage(initial 20M next 10M);
