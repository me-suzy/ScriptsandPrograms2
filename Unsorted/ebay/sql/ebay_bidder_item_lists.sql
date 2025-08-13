/*	$Id: ebay_bidder_item_lists.sql,v 1.2 1999/02/21 02:53:21 josh Exp $	*/
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
		item_list				long raw
	)
	tablespace userd07
	storage (initial 1024M next 100M);

	commit;

alter table ebay_bidder_item_lists
	add  constraint		item_bidlist_pk
		primary key(id)
		using index	tablespace useri07
		storage(initial 100m next 50m) unrecoverable;

alter table ebay_bidder_item_lists
	add constraint		item_bidlist_fk
		foreign key (id)
		references ebay_users(id);

/*	old
		constraint				item_blists_pk
			primary key(id)
			using index	storage(initial 100m next 20m)
							tablespace useri07,
		constraint				item_blists_fk
			foreign key (id)
			references	ebay_users(id)
*/
/* Merge conflict note: The lines up until "comment" came
up as merge conflicts in the second E109_prod to E110_prod merge. */
/*
	)
	tablespace ruserd07
	storage (initial 100M next 20M);

	commit;

*/
