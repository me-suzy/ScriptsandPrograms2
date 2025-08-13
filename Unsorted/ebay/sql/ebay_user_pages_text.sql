/* $Id: ebay_user_pages_text.sql,v 1.2 1998/10/16 01:09:34 josh Exp $ */
/*
 * ebay_user_pages_text --
 * storage of the raw data for serving user
 * pages.
 *
 * N.B.: Index and tablespace still need to be fixed for this.
 */

drop table ebay_user_pages_text;

create table ebay_user_pages_text
(
user_id		number(38)
	constraint	upagest_user_id_fk
		references ebay_users(id),
page_number	number(5)
	constraint	upagest_pn_nn
		not null,
data_dict long raw
	constraint	upagest_dd_nn
		not null,
constraint ebay_user_pagest_pk
	primary key (user_id, page_number)
	using index tablespace qpagesi02
	storage (initial 100M next 50M)
)
tablespace qpagesd02
storage (initial 500M next 300M);
