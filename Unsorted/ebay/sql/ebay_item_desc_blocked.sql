/*	$Id: ebay_item_desc_blocked.sql,v 1.2 1999/05/19 02:35:08 josh Exp $	*/
/*
 * ebay_item_blocked_desc.sql
 *
 * ** NOTE **
 * Right now, items numbers are unique across 
 * marketplaces, though this doesn't have to 
 * be so.
 * ** NOTE **
 */

drop table ebay_item_blocked_desc;

 create table ebay_item_blocked_desc
 (
	MARKETPLACE			NUMBER(38),
	ID					NUMBER(38)
		constraint		ritem_blocked_desc_id_nn
			not null,
 	DESCRIPTION_LEN		NUMBER
		constraint		ritem_blocked_desc_len_nn
			not null,
	DESCRIPTION			LONG RAW
 )
 tablespace ritemd02 
	storage (initial 2000M next 100M);

alter table ebay_item_blocked_desc
	add constraint		ritem_blocked_desc_pk
		primary key		(marketplace, id)
		using index tablespace ritemi02
		storage (initial 100M next 50M) unrecoverable parallel (degree 3);

commit;

