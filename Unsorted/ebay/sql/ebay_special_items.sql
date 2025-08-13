/*	$Id: ebay_special_items.sql,v 1.2 1999/02/21 02:54:04 josh Exp $	*/
/*
 * ebay_special_items.sql
 *
 * Table for marking items that are somehow special.
 * For instance, items with KIND=1 are staff picks, and items with
 * KIND=2 are black-listed items.
*/

/*  drop table ebay_special_items;
 */
/* obsolete - new definition below
create table ebay_special_items
 (	marketplace			NUMBER(38)
			constraint	special_items_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
			constraint	special_items_id_nn
			not null,
	ADD_DATE			DATE
			constraint	special_items_added_nn
			not null,
	WHO_ADDED			NUMBER(38)
			constraint	special_items_who_added_fk
			references ebay_users(id),
	KIND				CHAR(1)
			constraint	special_items_kind_nn
			not null,
	constraint	special_items_fk
	foreign key (marketplace, id)
	references	ebay_items(marketplace, id)
)
tablespace itemd01
storage(initial 10M next 10M);

alter table ebay_special_items
	modify (	marketplace
				constraint	special_items_marketplace_nn
				not null);

alter table ebay_special_items
	modify (	WHO_ADDED
				constraint	special_items_who_added_nn
				not null);
*/

create table ebay_special_items
 (	marketplace			NUMBER(38)
			constraint	special_items_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
			constraint	special_items_id_nn
			not null,
	ADD_DATE			DATE
			constraint	special_items_added_nn
			not null,
	WHO_ADDED			NUMBER(38)
			constraint	special_items_who_added_fk
			references ebay_users(id),
	KIND				CHAR(1)
			constraint	special_items_kind_nn
			not null,
	constraint	special_items_fk
	foreign key (marketplace, id)
	references	ebay_items(marketplace, id)
)
tablespace dynmiscd
storage(initial 10M next 5M);

alter table ebay_special_items
	modify (	marketplace
				constraint	special_items_marketplace_nn
				not null);

alter table ebay_special_items
	modify (	WHO_ADDED
				constraint	special_items_who_added_nn
				not null);
