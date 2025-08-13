/*	$Id: ebay_filters.sql,v 1.2 1999/05/19 02:35:10 josh Exp $	*/
/*
 * ebay_filters.sql
 *	contains filter information
 */

drop table ebay_filters;

 create table ebay_filters
 (
	ID					NUMBER(38)
		constraint		filter_id_nn
			not null,
	NAME				VARCHAR2(64)
		constraint		filter_name_nn
			not null,
	PATTERN 			VARCHAR2(255)
		constraint		filter_pattern_nn 
			not null,
	ACTION_TYPE			NUMBER(38)
		constraint		filter_action_nn 
			not null,
	FLAG_ITEM 			VARCHAR2(1)
		constraint		filter_flag_item_nn 
			not null,
	NOTIFY_TYPE 		NUMBER(38)
		constraint		filter_notify_nn 
			not null,
	BLOCKED_MSG_ID 		NUMBER(38)
		constraint		filter_blocked_msg_id_nn 
			not null,
	FLAGGED_MSG_ID		NUMBER(38),
	FILTER_MSG_ID 		NUMBER(38),
	BUDDY_MSG_ID 		NUMBER(38),
	FILTER_EMAILS 		VARCHAR2(255),
	BUDDY_EMAILS 		VARCHAR2(255)
)
tablespace itemd01
storage (initial 1M next 500K);

alter table ebay_filters
	add constraint		filters_pk
		primary key		(id)
		using index tablespace statmisci
		storage (initial 1M next 500K);

 drop sequence ebay_filters_sequence;

 create sequence ebay_filters_sequence;
