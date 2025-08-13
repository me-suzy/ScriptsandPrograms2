/*	$Id: ebay_users.sql,v 1.3 1999/02/21 02:56:57 josh Exp $	*/
/*
 * ebay_users.sql
 *
 * *** NOTE ***
 * Is it legit for password, salt to be null? I don't
 * think so....
 * *** NOTE ***
 */

/* drop table ebay_users;
 */

/* This table is the minimal user information table;
 * other user info is stored in ebay_user_info table.
 */

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
	email			varchar(64)
		constraint	user_email_nn
		not null,
	constraint		users_pk
		primary key(id)
		using index	storage(initial 5M next 1M)
						tablespace tuseri01,
	constraint		users_marketplace_userid_unq
		unique (marketplace, userid)
		using index	storage(initial 22M next 1M)
					tablespace tuseri01
 )
 tablespace tuserd01
 storage (initial 18M next 2M);

/*
 * These are the constraints, which are 
 * added later to force Oracle to give
 * them names which we like
 */

 alter table ebay_users
	modify (	userid
				constraint	users_userid_nn
				not null);

/* alter table ebay_users
	add		constraint	user_info_email_unq
		unique (email)
		using index storage(initial 500K next 100K)
				tablespace tuseri01;
 */

/* drop sequence ebay_users_sequence;
 */

 create sequence ebay_users_sequence start with 150000;

/* cannot make it not null */
/* set default to user info creation date? */
alter table ebay_users
add (userid_last_change date);

/* alex's UV stuff */
alter table ebay_users add UVDETAIL number(16) default 0;
alter table ebay_users add UVRATING number(5) default -99999;
