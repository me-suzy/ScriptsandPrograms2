/*	$Id: ebay_neighbors.sql,v 1.3 1999/02/21 02:56:37 josh Exp $	*/
/*
 * ebay_neighbors has information about
 * which users are 'neighbors' for the purpose
 * of the user pages -- being 'neighbors' is
 * a voluntary act on the part of two users.
 *
 * N.B.: Table and index space still need to be
 * fixed for this.
 */

drop table ebay_neighbors;

create table ebay_neighbors
(
user_id 	number(38)
	constraint friends_user_id_fk
		references ebay_users(id),
friend_id	number(38)
	constraint friends_friend_id_fk
		references ebay_users(id),
approved	char(1)
	constraint friends_approved_nn
		not null,
comment	varchar2(254),
constraint	friends_pk
	primary_key	(user_id)
	using index tablespace tbizdevi01
	storage (initial 5K next 5K)
)
tablespace tbizdevd01
storage (initial 10K next 10K);
