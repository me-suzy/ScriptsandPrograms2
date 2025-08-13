/*	$Id: ebay_item_counts.sql,v 1.2 1999/02/21 02:53:44 josh Exp $	*/
/*
 * ebay_item_counts.sql
 *
 * Right now, this is a simple table which tells
 * us how many items have been listed in a marketplace.
 * One day, it will be more complex!
 *
 */

	drop table ebay_item_counts;

	create table ebay_item_counts
	(
		marketplace			int
			constraint	item_counts_marketplace_nn
			not null,
		count					int
			default 0,
		constraint		item_counts_fk
			foreign key (marketplace)
			references	ebay_marketplaces(id),
		constraint		item_counts_pk
			primary key (marketplace)
			using index tablespace itemi01
			storage (initial 1K next 1K)
	)
	tablespace itemd01
	storage (initial 1K next 1K);

/*
 * Prime the table
 */
	insert into ebay_item_counts
		(marketplace)
		select id from ebay_marketplaces;

	commit;
