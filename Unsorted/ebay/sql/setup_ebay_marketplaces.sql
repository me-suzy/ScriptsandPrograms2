/*	$Id: setup_ebay_marketplaces.sql,v 1.2 1999/02/21 02:55:01 josh Exp $	*/
/*
 * setup_ebay_marketplaces.sql
 *
 * Until we build user functionality to add marketplaces,
 * this is how we do it.
 *
 */

 insert into ebay_marketplaces
 (
	id,
	name
 )
 values
 (
	0,
	'eBay AuctionWeb'
 );

 commit;


