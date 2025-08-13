/*	$Id: ebay_transact_record.sql,v 1.4 1999/02/21 02:54:08 josh Exp $	*/
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
 tablespace feedbackd03
 storage (initial 400M next 100M);

 alter table ebay_transact_record
	add constraint		transact_record_pk
			primary key (item, sellerid, bidderid)
			using index	storage(initial 300M next 100M)
						tablespace feedbacki03;

