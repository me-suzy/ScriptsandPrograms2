/*	$Id: ebay_messages.sql,v 1.2 1999/05/19 02:35:08 josh Exp $	*/
/*
 * ebay_messages.sql
 *	contains message information
 */

drop table ebay_messages;

 create table ebay_messages
 (
	ID					NUMBER(38)
		constraint		message_id_nn
			not null,
	NAME				VARCHAR2(64)
		constraint		message_name_nn
			not null,
	TYPE 				NUMBER(38)
		constraint		message_type_nn 
			not null,
	TEXT_LEN 			NUMBER(38)
		constraint		message_text_len_nn 
			not null,
	TEXT				LONG RAW
		constraint		message_text_nn 
			not null
)
tablespace itemd01
storage (initial 1M next 500K);

alter table ebay_messages
	add constraint		messages_pk
		primary key		(id)
		using index tablespace statmisci
		storage (initial 1M next 500K);

 drop sequence ebay_messages_sequence;

 create sequence ebay_messages_sequence;
