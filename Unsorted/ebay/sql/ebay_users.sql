/*	$Id: ebay_users.sql,v 1.5 1999/02/21 02:54:18 josh Exp $	*/
/*
 * ebay_users.sql
 *
 * *** NOTE ***
 * Is it legit for password, salt to be null? I don't
 * think so....
 * *** NOTE ***
 */

 drop table ebay_users;

/* This table is the minimal user information table;
 * other user info is stored in ebay_user_info table.
 */
/* obsolete - new definition below 
 create table ebay_users
 (
	marketplace		int
		constraint	users_marketplace_fk
		references	ebay_marketplaces(id),
	id			int 
		constraint	users_id_nn
		not null,
	userid			varchar(64) 
		constraint	user_userid_unq
		unique
		using index storage(initial 15m next 2m)
				tablespace useri01,
	user_state		int 
		constraint	users_user_state_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	last_modified		date
		constraint	users_last_modified_nn
		not null,
	constraint		users_pk
		primary key(id)
		using index	storage(initial 5m next 1m)
						tablespace useri01,
	constraint		users_marketplace_userid_unq
		unique (marketplace, userid)
		using index	storage(initial 22m next 1m)
					tablespace useri01
 )
 tablespace userd01
 storage (initial 18M next 2m);
*/
/*
 * These are the constraints, which are 
 * added later to force Oracle to give
 * them names which we like
 */
/* obsolete 
 alter table ebay_users
	modify (	userid
				constraint	users_userid_nn
				not null);
*/
 drop sequence ebay_users_sequence;

 create sequence ebay_users_sequence start with 150000;

 create table ebay_users
 (
	marketplace		int,
	id			int 
		constraint	rusers_id_nn
		not null,
	userid			varchar(64)
		constraint	rusers_userid_nn
		not null,
	user_state		int 
		constraint	rusers_user_state_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	last_modified		date
		constraint	rusers_last_modified_nn
		not null,
	userid_last_change date
 )
 tablespace ruserd01
 storage (initial 60M next 10m);

alter table ebay_users
	add 	constraint	rusers_marketplace_fk
		foreign key (marketplace)
		references	ebay_marketplaces(id);

alter table ebay_users
	add  constraint		rusers_pk
		primary key(id)
		using index	tablespace ruseri01
		storage(initial 10m next 5m);

alter table ebay_users
	add 	constraint		rusers_marketplace_userid_unq
		unique (marketplace, userid)
		using index	tablespace ruseri01
		storage(initial 35m next 10m);
					
alter table ebay_users
	add constraint	ruser_userid_unq
		unique (userid)
		using index tablespace ruseri01
		storage(initial 30m next 10m);

alter table ebay_users
add (flags number(16) default 0);

alter table ebay_users
add (email varchar2(64));

/* move email over, then reinstate constraint */
alter table ebay_users
modify (email varchar2(64) constraint users_email_nn not null);

/* add unique constraint on email - add to new tablespace? */
alter table ebay_users
	add constraint	user_email_unq
		unique (email)
		using index tablespace useri01
		storage(initial 50m next 10m);

alter tabel ebay_users
modify (flags default 0);
commit;

alter table ebay_users_bio
	add constraint	user_email_unq
		unique (email)
		using index tablespace ruseri01
		storage(initial 50m next 10m);

alter table ebay_users_bio
	add constraint	ruser_userid_unq
		unique (userid)
		using index tablespace ruseri01
		storage(initial 30m next 10m);

/* Keep track of the user's country by id in ebay_users. */
/* 0 is unknown. We'll have to write a program that goes */
/* through and makes this assignment based on the country */
/* in ebay_user_info for those who have already registered. */
/* Need to do it in 2 steps to make it not null. */

alter table ebay_users
	add (country_id number(10) default 0);

alter table ebay_users
	add	constraint		user_country_id_nn
		not null (country_id)
		references ebay_countries(id);
