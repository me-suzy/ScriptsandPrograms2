/*	$Id: ebay_special_users.sql,v 1.2 1999/02/21 02:56:45 josh Exp $	*/
/*
 * ebay_special_users.sql
 *
 * The 500 users who are allowed to list
 *
 */

/* drop table ebay_special_users; */
 


 create table ebay_special_users
 (
	userid			varchar(64) 
		constraint	special_users_userid_unq
		unique
		using index storage(initial 500K next 100K)
				tablespace tuseri01
 )
 tablespace tuserd01
 storage (initial 1M next 500K);

/*
 * These are the constraints, which are 
 * added later to force Oracle to give
 * them names which we like
 */

 alter table ebay_special_users
	modify (	userid
				constraint	special_users_userid_nn
				not null);

