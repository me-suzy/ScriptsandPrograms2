/*	$Id: ebay_special_items.sql,v 1.2 1999/02/21 02:56:44 josh Exp $	*/
/*
 * ebay_special_items.sql
 *
 * Table for marking items that are somehow special.
 * For instance, items with KIND=1 are staff picks
*/

/*  drop table ebay_special_items;
 */

create table ebay_special_items
 (	marketplace			NUMBER(38)
		constraint		special_items_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
		constraint		special_items_id_nn
			not null,
	ADD_DATE			DATE
		constraint		special_items_added_nn
			not null,
	WHO_ADDED			NUMBER(38)
			constraint	special_items_who_added_fk
			references ebay_users(id),
	KIND				CHAR(1)
			constraint		special_items_kind_nn
			not null,
	constraint		special_items_fk
			foreign key (marketplace, id)
			references	ebay_items(marketplace, id)
)
tablespace tuserd01
storage(initial 10K next 1K);

alter table ebay_special_items
	modify (	marketplace
				constraint	special_items_marketplace_nn
				not null);

alter table ebay_special_items
	modify (	who_added
				constraint	special_items_who_added_nn
				not null);
