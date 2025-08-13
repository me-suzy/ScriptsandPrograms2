/*	$Id: ebay_category_messages.sql,v 1.2 1999/05/19 02:35:10 josh Exp $	*/
/*
 * ebay_category_messages.sql
 *	contains category-message cross-reference information
 */

drop table ebay_category_messages;

 create table ebay_category_messages
 (
	CATEGORY_ID			NUMBER(38)
		constraint		cat_messages_cat_id_nn
			not null,
	MESSAGE_ID			NUMBER(38)
		constraint		cat_messages_id_nn
			not null
)
tablespace itemd01
storage (initial 1M next 500K);

alter table ebay_category_messages
	add constraint		category_messages_pk
		primary key		(category_id, message_id)
		using index tablespace statmisci
		storage (initial 1M next 500K);

