/*	$Id: ebay_aw_credit_status.sql,v 1.2 1999/02/21 02:56:01 josh Exp $	*/
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
	tablespace tuserd01
	storage (initial 100K next 50k);

alter table ebay_aw_credit_status
	add	constraint		rcredit_info_pk
			primary key (userid)
			using index tablespace tuseri01
			storage (initial 50k next 25k);
commit;
