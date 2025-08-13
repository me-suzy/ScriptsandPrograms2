/*	$Id: ebay_credit_info.sql,v 1.2 1999/02/21 02:56:14 josh Exp $	*/
/*
 * ebay_users.sql
 */

/* drop table ebay_credit_info;
 */


create table ebay_credit_info
	(
		id							int
			constraint	credit_info_nn
			not null,
		credit_card_on_file	char
			constraint	credit_info_cc_nn
			not null,
		good_credit				char
			constraint	credit_info_gc_nn
			not null,
		constraint		credit_info_pk
			primary key (id),
		constraint		credit_info_fk
			foreign key (id)
			references	ebay_users(id)
	);

	commit;
