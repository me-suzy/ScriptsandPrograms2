/*	$Id: ebay_feedback_list.sql,v 1.3 1999/02/21 02:56:19 josh Exp $	*/
/*
 * ebay_feedback_lists.sql
 * 
 * id = id of user
 * fb_count = # feedback in the list
 * fb_list_size = size of the feedback list
 * fb_list_size_used = what parts of the list size is used
 * fb_list_valid = flag to indicate feedback cache validity
 * fb_last_modified = date cache was last modified
 * fb_list = list of type, commenting id, rowid of feedback detail
 *       left for this user (same as Minimal feedback vector)
 *
 */
	drop table ebay_feedback_lists;

	create table ebay_feedback_lists
	(
		id						int
			constraint			feedback_lists_id_nn
			not null,
		fb_count				int
			constraint			feedback_fb_count_nn
			not null,
		fb_list_size			int
			constraint			feedback_fb_list_size_nn
			not null,
		fb_list_size_used		int
			constraint			feedback_fb_list_used_nn
			not null,
		fb_list_valid			char(1)
			constraint			feedback_fb_lists_valid_nn
			not null,
		fb_last_modified		date
			constraint			feedback_fb_last_modified_nn
			not null,
		fb_endian				char(1)
			constraint			feedback_fb_endian_nn
			not null,
		fb_list					long raw,
		constraint				feedback_lists_pk
			primary key(id)
			using index	storage(initial 1m next 1m)
							tablespace tfeedbacki01,
		constraint				feedback_lists_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace tfeedbackd01
	storage (initial 1M next 1M);

	commit;
