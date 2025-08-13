/*	$Id: ebay_admin.sql,v 1.2 1999/02/21 02:53:04 josh Exp $	*/
/*
 * ebay_admin.sql
 *
 *	This table is actually the admin access control
 * information for a user. Currently, if a user's id
 * is in the table, he/she has admin admin rights.
 *
 */

 	drop table ebay_admin;
/*
	create table ebay_admin
	(
		marketplace		int
		constraint	admin_marketplace_fk
		references	ebay_marketplaces(id),
		id				int
			constraint	admin_id_nn
			not null,
		adcode			number(3,0)
			constraint	admin_adcode_nn
			not null,
		constraint		admin_fk
			foreign key (id)
			references	ebay_users(id)
	)
	tablespace userd01
	storage (initial 10K next 10K);
*/

create table ebay_admin
	(
		marketplace		int
		constraint	admin_marketplace_fk
		references	ebay_marketplaces(id),
		id				int
			constraint	admin_id_nn
			not null,
		adcode			number(3,0)
			constraint	admin_adcode_nn
			not null
	)
	tablespace ruserd05
	storage (initial 10K next 10K);

alter table ebay_admin
	add		constraint		admin_fk
			foreign key (id)
			references	ebay_users(id);
commit;

