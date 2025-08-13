/*	$Id: ebay_user_info.sql,v 1.5 1999/03/22 00:09:46 josh Exp $	*/
/*
 * ebay_user_info.sql
 *
 * This table contains ebay specific information
 * about a user. It's a bit "different" from the
 * ebay_users table, because the users table
 * reflects membership and access information,
 * with minimal footprint where users are referenced,
 * whereas this table reflect the detailed info of
 * the user and their usage of the system.
 * 
 * This table assumes ids are unique across
 * marketplaces.
 *
 */

drop table ebay_user_info;
/* obsolete - new definition below 
create table ebay_user_info
(
	id			int
		constraint	user_info_id_nn
		not null,
	host			varchar(64)
		constraint	users_host_nn
		not null,
	name			varchar(64)
		constraint	users_name_nn
		not null,
	company			varchar(64),
	address			varchar(64)
		constraint	users_address_nn
		not null,
	city			varchar(64)
		constraint	users_city_nn
		not null,
	state			varchar(64)
		constraint	users_state_nn
		not null,
	zip			varchar(12)
		constraint	users_zip_nn
		not null,
	country			varchar(64)
		constraint	users_country_nn
		not null,
	dayphone			varchar(32),
	nightphone		varchar(32),
	faxphone			varchar(32),
	creation		date
		constraint	users_creation_nn
		not null,
	email			varchar(64)
		constraint	user_email_nn
		not null,
	count					int
		default 0,
	credit_card_on_file	char
		default chr(0)
		constraint	credit_info_cc_nn
		not null,
	good_credit				char
		default chr(0)
		constraint	credit_info_gc_nn
		not null,
	gender					char
		default 'u',
	interests_1				int
		default 0,
	interests_2				int
		default 0,
	interests_3				int
		default 0,
	interests_4				int
		default 0,
	constraint		user_info_pk
      	primary key(id)
      	using index storage(initial 5m next 1m)
                 tablespace useri01,
	constraint		user_info_fk
		foreign key (id)
		references	ebay_users(id)
)
 tablespace userd01
 storage (initial 30M next 2m);

*/

create table ebay_user_info
(
	id			int
		constraint	ruser_info_id_nn
		not null,
	host			varchar(64)
		constraint	rusers_host_nn
		not null,
	name			varchar(64)
		constraint	rusers_name_nn
		not null,
	company			varchar(64),
	address			varchar(64)
		constraint	rusers_address_nn
		not null,
	city			varchar(64)
		constraint	rusers_city_nn
		not null,
	state			varchar(64)
		constraint	rusers_state_nn
		not null,
	zip			varchar(12)
		constraint	rusers_zip_nn
		not null,
	country			varchar(64)
		constraint	rusers_country_nn
		not null,
	dayphone		varchar(32),
	nightphone		varchar(32),
	faxphone		varchar(32),
	creation		date
		constraint	rusers_creation_nn
		not null,
	email			varchar(64)
		constraint	ruser_email_nn
		not null,
	count			int
		default 0,
	credit_card_on_file	char
		default chr(0)
		constraint	rcredit_info_cc_nn
		not null,
	good_credit		char
		default chr(0)
		constraint	rcredit_info_gc_nn
		not null,
	gender			char
		default 'u',
	interests_1		int
		default 0,
	interests_2		int
		default 0,
	interests_3		int
		default 0,
	interests_4		int
		default 0,
	partner_id		number(3,0)
		default 0
)
 tablespace ruserd02
 storage (initial 80M next 20m);

alter table ebay_user_info
	add constraint		ruser_info_pk
      	primary key(id)
      	using index tablespace ruseri02
		storage(initial 10m next 2m);
commit;
  
alter table ebay_user_info
	add	constraint		ruser_info_fk
		foreign key (id)
		references	ebay_users(id);
commit;

alter table ebay_user_info
	add constraint	ruser_info_email_unq
		unique (email)
		using index tablespace ruseri02
		storage(initial 30m next 10m);

alter table ebay_user_info
add (req_email_count number(10,0) default 0);

/* don't do this till much much later */
alter table ebay_user_info
modify(email null);

alter table ebay_user_info_bio
	add constraint		ruser_info_pk
      	primary key(id)
      	using index tablespace ruseri02
		storage(initial 50m next 20m);
commit;

 create index ebay_user_info_creation_index
	on ebay_user_info(creation)
	tablespace quseri02
	storage(initial 100m next 100M) unrecoverable;
	commit;
