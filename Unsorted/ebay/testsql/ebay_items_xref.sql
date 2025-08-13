/*	$Id: ebay_items_xref.sql,v 1.2 1999/02/21 02:56:33 josh Exp $	*/
/*
 * ebay_items_xref.sql
 */

 /*	drop table ebay_items_xref;
 */


	create table ebay_items_xref
	(
		marketplace			int
			default			0
			constraint		items_xref_marketplace_nn
			not null,
		id						int 
			constraint		items_xref_id_nn
			not null,
		old_id				varchar2(8) 
			constraint		items_xref_oldid_nn
			not null,
		constraint		items_xref_pk
			primary key (old_id),
		constraint		items_xref_fk
			foreign key (marketplace, id)
			references ebay_items(marketplace, id)
	);

	commit;
