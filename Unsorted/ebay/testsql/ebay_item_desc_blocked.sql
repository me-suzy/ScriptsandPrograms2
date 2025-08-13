/*	$Id: ebay_item_desc_blocked.sql,v 1.2 1999/05/19 02:35:10 josh Exp $	*/
/*
 * ebay_item_desc_blocked.sql
 *
 * ** NOTE **
 * Right now, items numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

/*   drop table ebay_item_desc_blocked; 
 */

 create table ebay_item_desc_blocked
 (
	MARKETPLACE			NUMBER(38)
		constraint		item_desc_blocked_marketplace_fk
			references ebay_marketplaces(id),
	ID						NUMBER(38)
		constraint		item_desc_blocked_id_nn
			not null,
 	DESCRIPTION_LEN	NUMBER
		constraint		item_desc_blocked_len_nn
			not null,
	DESCRIPTION			LONG RAW,
	constraint			item_desc_blocked_pk
		primary key		(marketplace, id)
		using index tablespace titemi01
			storage(initial 5m next 1m)
	)
	tablespace titemd01
	storage (initial 1M next 1M);

