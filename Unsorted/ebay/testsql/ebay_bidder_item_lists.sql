/*	$Id: ebay_bidder_item_lists.sql,v 1.2 1999/02/21 02:56:04 josh Exp $	*/
/*
 * ebay_bidder_item_lists.sql
 *
 */
	drop table ebay_bidder_item_lists;

	create table ebay_bidder_item_lists
	(
		id						int
			constraint			item_blists_id_nn
			not null,
		item_count				int
			constraint			item_blists_item_count_nn
			not null,
		item_list_size			int
			constraint			item_blists_list_size_nn
			not null,
		item_list_size_used		int
			constraint			item_blists_list_used_nn
			not null,
		item_list_valid			char(1)
			constraint			item_blists_valid_nn
			not null,
		item_list				long raw,
		constraint				item_blists_pk
			primary key(id)
			using index	storage(initial 1m next 1m)
							tablespace tuseri01,
		constraint				item_blists_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace tuserd01
	storage (initial 1M next 1M);

	commit;
