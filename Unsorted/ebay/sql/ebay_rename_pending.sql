/*	$Id: ebay_rename_pending.sql,v 1.2 1999/02/21 02:54:01 josh Exp $	*/
/*
 * ebay_rename_pending.sql
 *
 */

 drop table ebay_rename_pending;

/* This table contains the renames that need verification from user
 */
/*
 create table ebay_rename_pending
 (
	marketplace		int
		constraint	pending_marketplace_fk
		references	ebay_marketplaces(id),
	fromuserid		varchar(64) 
		constraint	pending_fromuserid_nn
		not null,
	touserid		varchar(64) 
		constraint	pending_touserid_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	created			date
		constraint	pending_created_nn
		not null
 )
 tablespace userd01
 storage (initial 1M next 1M);
*/

 create table ebay_rename_pending
 (
	marketplace		int,
	fromuserid		varchar(64) 
		constraint	pending_fromuserid_nn
		not null,
	touserid		varchar(64) 
		constraint	pending_touserid_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	created			date
		constraint	pending_created_nn
		not null
 )
 tablespace dynmiscd
 storage (initial 1M next 1M);

alter table ebay_rename_pending
	add	constraint	pending_marketplace_fk
		foreign key (marketplace)
		references	ebay_marketplaces(id);
commit;

update ebay_rename_pending set id = 
(select ebay_users.id from ebay_users, ebay_rename_pending
where ebay_users.userid = ebay_rename_pending.fromuserid);

alter table ebay_rename_pending disable constraint pending_fromuserid_nn;
