/* $Id: ebay_user_pages.sql,v 1.2 1998/10/16 01:09:40 josh Exp $ */
/*
 * ebay_user_pages --
 * storage of the information about a user's page
 * Does not contain the raw page data -- that's in
 * ebay_user_pages_text
 *
 * N.B.: Table and index spaces still need to be fixed here.
 */

drop table ebay_user_pages;

create table ebay_user_pages
(
user_id		number(38)
	constraint	upages_user_id_fk
		references ebay_users(id),
page_number	number(5)
	constraint	upages_pn_nn
		not null,
last_updated	date
	constraint	upages_date_nn
		not null,
page_size number(38)
	constraint	upages_page_size_nn
		not null,
page_text_size number(38)
	constraint	upages_page_text_size_nn
		not null,
last_viewed	date
	constraint upages_vdate_nn
		not null,
num_views	number(38)
	constraint upages_nv_nn
		not null,
constraint	ebay_user_pages_pk
	primary key	(user_id, page_number)
	using index tablespace tbizdevi01
	storage (initial 5K next 5K)
)
tablespace tbizdevd01
storage (initial 10K next 10K);
