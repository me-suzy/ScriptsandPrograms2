/*	$Id: ebay_aw_credit_status.sql,v 1.2 1999/02/21 02:53:17 josh Exp $	*/
/*
 * ebay_users.sql
 */

	drop table ebay_aw_credit_status;
/* obsolete - newer definition below
	create table ebay_aw_credit_status
	(
		userid						varchar(255)	
			constraint	aw_credit_status_userid_nn
			not null,
		credit_card_on_file	char
			constraint	aw_credit_status_cc_nn
			not null,
		good_credit				char
			constraint	aw_credit_status_gc_nn
			not null,
		constraint		credit_info_pk
			primary key (userid)
			using index tablespace useri01
			storage (initial 500k next 250k)
	)
	tablespace userd01
	storage (initial 1M next 500k);

	commit;
*/

create table ebay_aw_credit_status
	(
		userid						varchar(255)	
			constraint	raw_credit_status_userid_nn
			not null,
		credit_card_on_file	char
			constraint	raw_credit_status_cc_nn
			not null,
		good_credit				char
			constraint	raw_credit_status_gc_nn
			not null
	)
	tablespace ruserd05
	storage (initial 1M next 500k);

alter table ebay_aw_credit_status
	add	constraint		rcredit_info_pk
			primary key (userid)
			using index tablespace ruseri05
			storage (initial 500k next 250k);
commit;
