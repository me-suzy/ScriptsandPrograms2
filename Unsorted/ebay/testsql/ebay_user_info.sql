/*	$Id: ebay_user_info.sql,v 1.6 1999/02/21 02:56:54 josh Exp $	*/
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

/*  drop table ebay_user_info;
 */

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
	state			varchar(16)
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
      	using index storage(initial 5M next 1M)
                 tablespace tuseri01,
	constraint		user_info_fk
		foreign key (id)
		references	ebay_users(id)
)
 tablespace tuserd01
 storage (initial 10M next 2M);

alter table ebay_user_info
add (partner_id number(3,0));

alter table ebay_user_info
add (req_email_count number(10,0) default 0);

/* for SOHO */

alter table ebay_user_info
add (aclmask number(3,0) default 0,
	password		varchar(64),
	salt			varchar(64));

/* For the tutorial and opting in and out of email */
-- These will be read and set with bitmasks.
-- Keep track of up to 9 quizzes by using number(3).
-- Keep track of up to 23 opt ins/opt outs by using number(7).

alter table ebay_user_info
	add	(quizzes_passed number(3,0),
	participate_choices number(7,0));

-- These keep track of when a user requests another user's info.
alter table ebay_user_info
add (req_info_count number(4,0) default 0,
	 req_info_date date,
	 req_info_host varchar(64));

alter table ebay_user_info
add (TopSellerInitiatedDate	Date,
 	TopSellerLevel		Number(8));


/* set default aclmask = 0, set all user's aclmask to 0 */

update ebay_user_info set aclmask = 0;


