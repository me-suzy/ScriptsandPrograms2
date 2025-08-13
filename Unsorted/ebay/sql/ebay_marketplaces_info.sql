/*	$Id: ebay_marketplaces_info.sql,v 1.2 1999/02/21 02:53:57 josh Exp $	*/
/*
 * ebay_marketplaces_info.sql
 *
 * It holds information abount number of items, and bids in a
 * marketplace.
 *
 */

	drop table ebay_marketplaces_info;
/*
	create table ebay_marketplaces_info
	(
		id						int
			constraint	marketplaces_info_id_nn
			not null,
		item_count				int
			default 0,
		daily_item_count		int
			default 0,
		bid_count				int
			default 0,
		constraint		marketplaces_info_fk
			foreign key (id)
			references	ebay_marketplaces(id),
		constraint		marketplaces_info_pk
			primary key (id)
			using index tablespace useri01
			storage (initial 1K next 1K)
	)
	tablespace userd01
	storage (initial 1K next 1K);
*/

create table ebay_marketplaces_info
	(
		id						int
			constraint	marketplaces_info_id_nn
			not null,
		item_count				int
			default 0,
		daily_item_count		int
			default 0,
		bid_count				int
			default 0
	)
	tablespace ruserd05
	storage (initial 1K next 1K);

alter table ebay_marketplaces_info
	add	constraint		marketplaces_info_fk
			foreign key (id)
			references	ebay_marketplaces(id);
commit;
alter table ebay_marketplaces_info
	add	constraint		marketplaces_info_pk
			primary key (id)
			using index tablespace ruseri05
			storage (initial 1K next 1K) unrecoverable;
commit;

/*
 * Prime the table
 */
	insert into ebay_marketplaces_info
		(id)
		select id from ebay_marketplaces;

	commit;
