/*	$Id: ebay_rename_pending.sql,v 1.2 1999/02/21 02:56:41 josh Exp $	*/
/* This table contains the renames that need verification from user
 */

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
 tablespace tuserd01
 storage (initial 1K next 1K);

 alter table ebay_rename_pending
	add	(id			int);

commit;

 /* fill up old table with ids from the fromuserid */
update ebay_rename_pending set id = 
(select distinct ebay_users.id from ebay_users, ebay_rename_pending
where ebay_users.userid = ebay_rename_pending.fromuserid);

update ebay_rename_pending a
set id = (select id
from ebay_users b
where b.userid = a.fromuserid);


/* disable constraint in readiness to drop it */
alter table ebay_rename_pending disable constraint pending_fromuserid_nn;

/* to do: create a new table, called ebay_ren2 */
 create table ebay_rename_pending2
 (
	marketplace		int
		constraint	renpend_marketplace_fk
		references	ebay_marketplaces(id),
	id				int
		constraint	renpend_id_nn
		not null,
	touserid		varchar(64) 
		constraint	renpend_touserid_nn
		not null,
	password		varchar(64),
	salt			varchar(64),
	created			date
		constraint	renpend_created_nn
		not null
 )
 tablespace tuserd01
 storage (initial 1K next 1K);


/* copy all data over without fromuserid */
insert into ebay_rename_pending2
(select marketplace, id, touserid, password, salt, created from
ebay_rename_pending);

/* delete old ebay_rename_pending table */
drop table ebay_rename_pending;

/* rename ebay_ren2 to ebay_rename_pending */
rename object ebay_rename_pending2 to ebay_rename_pending;

