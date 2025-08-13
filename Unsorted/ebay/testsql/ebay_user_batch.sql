/*	$Id: ebay_user_batch.sql,v 1.3 1999/02/21 02:56:52 josh Exp $	*/
/*
 * ebay_user_batch.sql
 *
 * contains user's batches and when batches were first created
 * as well as hostname of the email?
 * id = item id in the batch;
 * buid = seller's id
 * batchid = seller's batch number
 * batchid and uid uniquely identifies batch for the user;
 * id uniquely identifies item within the batch
 */

drop table ebay_user_batch;

 create table ebay_user_batch
 (
	marketplace		int
		constraint	batch_marketplace_fk
		references	ebay_marketplaces(id),
	id				int 
		constraint	batch_id_nn
		not null,
	buid				NUMBER(38)
		constraint	batch_uid_nn
			not null,
	status			number(3)
		constraint	batch_status_nn
		not null,
	created			date
		constraint	batch_created_nn
		not null,
	commit_date		date,
	host			varchar(64)
		constraint	batch_host_nn
		not null,
	constraint			batch_pk
		primary key		(marketplace, id, buid)
		using index tablespace	tuseri01
		storage (initial 200K next 100K))
 tablespace tuserd01
 storage (initial 1M next 100K);

