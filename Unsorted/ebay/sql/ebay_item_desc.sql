/*	$Id: ebay_item_desc.sql,v 1.3 1999/03/22 00:09:46 josh Exp $	*/
/*
 * ebay_item_desc.sql
 *
 * ** NOTE **
 * Right now, items numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

drop table ebay_item_desc;
/* obsolete - new definition below
 create table ebay_item_desc
 (
	MARKETPLACE			NUMBER(38)
		constraint		item_desc_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
		constraint		item_desc_id_nn
			not null,
 	DESCRIPTION_LEN	NUMBER
		constraint		item_desc_len_nn
			not null,
	DESCRIPTION			LONG RAW,
	constraint			item_desc_pk
		primary key		(marketplace, id)
		using index tablespace itemi01
		storage (initial 5M next 1M),
	constraint			items_marketplace_id_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id)
	)
	tablespace itemd01 
	storage (initial 20M next 5M);
*/

 create table ebay_item_desc
 (
	MARKETPLACE			NUMBER(38),
	ID						NUMBER(38)
		constraint		ritem_desc_id_nn
			not null,
 	DESCRIPTION_LEN	NUMBER
		constraint		ritem_desc_len_nn
			not null,
	DESCRIPTION			LONG RAW
	)
	tablespace ritemd02 
	storage (initial 2000M next 100M);

alter table ebay_item_desc
	add constraint		ritem_desc_pk
		primary key		(marketplace, id)
		using index tablespace ritemi02
		storage (initial 100M next 50M) unrecoverable parallel (degree 3);

alter table ebay_item_desc
	add	constraint		ritems_marketplace_id_fk
		foreign key(marketplace, id)
		references ebay_items(marketplace, id);
commit;

alter table ebay_item_desc
	add	constraint		ritem_desc_marketplace_fk
		foreign key(marketplace)
			references ebay_marketplaces(id);
commit;

-- Jan 31, 1999
alter table ebay_item_desc
	drop	constraint		ritem_desc_marketplace_fk;

alter table ebay_item_desc 
disable constraint ritems_marketplace_id_fk;

alter table ebay_item_desc
drop constraint ritems_marketplace_id_fk;
