/*	$Id: ebay_item_desc_arc.sql,v 1.3 1999/03/07 08:17:06 josh Exp $	*/
/*
 * ebay_item_desc_arc.sql
 *
 * archive of ebay_item_desc data
 */

drop table ebay_item_desc_arc;

 create table ebay_item_desc_arc
 (
	MARKETPLACE			NUMBER(38)
		constraint		item_desc_arc_marketplace_nn
			not null,
	ID						NUMBER(38)
		constraint		item_desc_arc_id_nn
			not null,
 	DESCRIPTION_LEN	NUMBER
		constraint		item_desc_arc_len_nn
			not null,
	DESCRIPTION			LONG RAW
	)
	tablespace itemarcd03
	storage (initial 500M next 500M);

create table ebay_items_to_archive
( id			number(38)
  constraint item_to_arc_id_nn
      not null)
tablespace itemarc1
storage (initial 5M next 5M);


create table ebay_items_bad
( id			number(38)
  constraint item_bad_id_nn
      not null)
tablespace itemarc1
storage (initial 1M next 1M);

/* took too long 
alter table ebay_item_desc_arc
	add constraint		ritem_desc_arc_pk
		primary key		(marketplace, id)
		using index tablespace itemarci03
		storage (initial 300M next 100M) unrecoverable;

*/
-- to try next time

 create index item_desc_arc_idx
   on ebay_item_desc_arc(id)
   tablespace itemarci03
   storage(initial 300M next 50M) unrecoverable;
