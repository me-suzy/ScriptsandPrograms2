/*	$Id: ebay_user_acl.sql,v 1.2 1999/02/21 02:56:50 josh Exp $	*/
/*
 * ebay_user_acl.sql
 *
 *	This table is actually the access control
 * information for a user. Currently, if a user's id
 * is in the table, he/she has some rights specified in the aclmask.
 *
 */

/*  	drop table ebay_user_acl;
FOLDED INTO EBAY_USER_INFO

 	drop table ebay_user_acl;

	create table ebay_user_acl
	(
		marketplace		int
		constraint	acl_marketplace_fk
		references	ebay_marketplaces(id),
		id				int
			constraint	acl_id_nn
			not null,
		aclmask			number(3,0)
			constraint	acl_mask_nn
			not null,
		constraint		acl_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace tuserd01
	storage (initial 5K next 5K);
 */
