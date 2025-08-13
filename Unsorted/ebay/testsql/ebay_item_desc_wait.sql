/*	$Id: ebay_item_desc_wait.sql,v 1.2 1999/02/21 02:56:28 josh Exp $	*/
/*
 * ebay_item_desc_wait.sql
 *
 * description of ebay items in abeyance
 */

drop table ebay_item_desc_wait;

 create table ebay_item_desc_wait
 (
	MARKETPLACE			NUMBER(38)
		constraint		iwait_desc_marketplace_fk
			references ebay_marketplaces(id),
	ID					NUMBER(38)
		constraint		iwait_desc_id_nn
			not null,
	User_ID				NUMBER(38)
		constraint		iwait_desc_uid_nn
			not null,
	BATCHID				NUMBER(38)
		constraint		iwait_desc_batchid_nn
			not null,
 	DESCRIPTION_LEN	NUMBER
		constraint		iwait_desc_len_nn
			not null,
	DESCRIPTION			LONG RAW,
	constraint			iwait_desc_pk
		primary key		(marketplace, id, user_id, batchid)
		using index tablespace titemi01
		storage (initial 100K next 10K),
	constraint			iwait_marketplace_id_batch_fk
		foreign key(marketplace, id, user_id, batchid)
		references ebay_items_wait(marketplace, id, user_id, batchid)
	)
	tablespace titemd01 
	storage (initial 1M next 1M);

