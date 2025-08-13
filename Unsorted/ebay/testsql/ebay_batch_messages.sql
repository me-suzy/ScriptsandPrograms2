/*	$Id: ebay_batch_messages.sql,v 1.2 1999/02/21 02:56:02 josh Exp $	*/
/*
 * ebay_batch_messages.sql
 *
 * contains user's batch messages for items in the batch.
 * ebay_messages tells what the text of the msg is given the message id.
 *
 */

 drop table ebay_batch_messages;


 create table ebay_batch_messages
 (
	marketplace		int
		constraint	batchmsg_marketplace_fk
		references	ebay_marketplaces(id),
	user_id			number(38)
		constraint	batchmsg_uid_nn
		not null,
	batchid			number(38)
		constraint  batchmsg_batchid_nn
		not null,
	itemid				int 
		constraint	batchmsg_itemid_nn
		not null,
	msgid			number(6)
		constraint	batchmsg_message_nn
		not null,
	moremsg			varchar(256),
	eff_date		date
		constraint	batchmsg_effdate_nn
		not null,
	constraint			batchmsg_pk
		primary key		(marketplace, user_id, batchid, itemid)
		using index tablespace	tuseri01
		storage (initial 200K next 100K)
 )
 tablespace tuserd01
 storage (initial 1M next 100K);



drop table ebay_messages;

 create table ebay_messages
 (
	marketplace		int
		constraint	msg_marketplace_fk
		references	ebay_marketplaces(id),
	msgid		number(6)
	constraint  msg_id_nn
	not null,
	msgtxt		varchar(256)
	constraint	msg_txt_nn
	not null,
	constraint message_pk
	primary key (marketplace, msgid)
	using index tablespace tuseri01
	storage (initial 100K next 100K)
	)
tablespace tuserd01
storage (initial 10K next 10K);
