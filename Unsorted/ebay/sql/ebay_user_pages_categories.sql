/* $Id: ebay_user_pages_categories.sql,v 1.2 1998/10/16 01:09:33 josh Exp $ */
/*
 * ebay_user_pages_categories -- a table to hold
 * information about listing pages in categories.
 */

drop table ebay_user_pages_categories;

create table ebay_user_pages_categories
(
user_id		number(38)
	constraint	upages_c_user_id_fk
		references ebay_users(id),
page_number	number(5)
	constraint upages_c_pn_nn
		not null,
page_title varchar2(254)
	constraint	upages_c_page_title_nn
		not null,
category	number(38)
	constraint	upages_c_category_nn
	not null,
constraint	ebay_user_pages_c_pk
	primary key	(user_id, category)
	using index tablespace qpagesi03
	storage (initial 50M next 50M)
)
tablespace qpagesd03
storage (initial 100M next 100M);

create index ebay_user_pages_c_index
	on ebay_user_pages_categories(category)
	tablespace qpagesi03
	storage (initial 50M next 50M);

create index ebay_user_pages_c_user_index
	on ebay_user_pages_categories(user_id)
	tablespace qpagesi03
	storage (initial 50M next 50M);
