/*	$Id: ebay_filter_messages.sql,v 1.2 1999/05/19 02:35:07 josh Exp $	*/
/*
 * ebay_filter_messages.sql
 *	contains filter-message cross-reference information
 */

drop table ebay_filter_messages;

 create table ebay_filter_messages
 (
	FILTER_ID			NUMBER(38)
		constraint		filter_messages_filter_id_nn
			not null,
	MESSAGE_ID			NUMBER(38)
		constraint		filter_messages_msg_id_nn
			not null
)
tablespace itemd01
storage (initial 1M next 500K);

alter table ebay_filter_messages
	add constraint		filter_messages_pk
		primary key		(filter_id, message_id, message_type)
		using index tablespace statmisci
		storage (initial 1M next 500K);

