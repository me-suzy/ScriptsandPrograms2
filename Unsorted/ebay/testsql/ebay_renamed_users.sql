/*	$Id: ebay_renamed_users.sql,v 1.2 1999/02/21 02:56:42 josh Exp $	*/
/*
 * ebay_renamed_users.sql
 *
 * changed id to userid 
 *
 */

/*  drop table ebay_renamed_users;
 */

/* This table is for users that are renamed; it contains
 * the old user id and the current userid.
 */

 create table ebay_renamed_users
 (
	fromuserid			varchar(64)
		constraint	renamed_users_fromuserid_unq
		unique
		using index storage(initial 1m next 1m)
				tablespace tuseri01,
	touserid			varchar(64) 
		constraint	renamed_users_touserid_nn
		not null
 )
 tablespace tuserd01
 storage (initial 1K next 1K);

