/*	$Id: ebay_transact_record.sql,v 1.3 1999/02/21 02:56:48 josh Exp $	*/
/*
 * ebay_transact_record.sql
 *
 * ebay_transact_record contains ids of
 * seller and bidders of a specified item. 
 *
 */

/*  drop table ebay_transact_record;
 */


create table ebay_transact_record
(
	item		int
		constraint transact_rec_item_nn
		not null,
	sellerid	int
		constraint transact_rec_sellerid_nn
		not null,
	bidderid	int
		constraint transact_rec_bidderid_nn
		not null,
	ending_date	date
		constraint transact_rec_date_nn
		not null,
	used		char
		default chr('0')
)
 tablespace summaryd02
 storage (initial 10M next 2M);

 alter table ebay_transact_record
	add constraint		transact_record_pk
			primary key (item, sellerid, bidderid)
			using index	storage(initial 5k next 5k)
						tablespace tfeedbacki01;
