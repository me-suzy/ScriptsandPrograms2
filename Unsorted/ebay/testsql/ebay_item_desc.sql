/*	$Id: ebay_item_desc.sql,v 1.2 1999/02/21 02:56:26 josh Exp $	*/
/*
 * ebay_item_desc.sql
 *
 * ** NOTE **
 * Right now, items numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

/*   drop table ebay_item_desc; 
 */

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
		using index tablespace titemi01
			storage(initial 5m next 1m)
	)
	tablespace titemd01
	storage (initial 1M next 1M);


create table ebay_items_bad
( id			number(38)
  constraint item_bad_id_nn
      not null)
tablespace titemd01
storage (initial 1K next 1K);
