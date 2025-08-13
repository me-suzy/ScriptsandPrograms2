/*	$Id: ebay_renamed_users.sql,v 1.2 1999/02/21 02:54:02 josh Exp $	*/
/*
 * ebay_renamed_users.sql
 *
 * changed id to userid 
 *
 */

 drop table ebay_renamed_users;

/* This table is for users that are renamed; it contains
 * the old user id and the current userid.
 */
/* obsolete  - newer definition below
 * this table will be replaced by ebay_user_past_aliases
 *
 create table ebay_renamed_users
 (
	fromuserid			varchar(64)
		constraint	renamed_users_fromuserid_unq
		unique
		using index storage(initial 1m next 1m)
				tablespace useri01,
	touserid			varchar(64) 
		constraint	renamed_users_touserid_nn
		not null
 )
 tablespace userd01
 storage (initial 1M next 1m);
*/

 create table ebay_renamed_users
 (
	fromuserid			varchar(64)
		constraint	renamed_rusers_fromuserid_nn
		not null,
	touserid			varchar(64) 
		constraint	renamed_rusers_touserid_nn
		not null
 )
 tablespace ruserd03
 storage (initial 1M next 1m);

alter table ebay_renamed_users
	add	constraint	renamed_rusers_fromuserid_unq
		unique (fromuserid)
		using index tablespace ruseri03
		storage(initial 1m next 1m);
commit;
