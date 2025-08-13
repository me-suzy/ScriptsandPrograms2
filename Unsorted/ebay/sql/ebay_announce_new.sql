/*	$Id: ebay_announce_new.sql,v 1.1.20.1 1999/08/01 03:02:41 barry Exp $	*/
/*
 * ebay_announce.sql
 *
 *	This table contains announcements for different
 *  parts of the system.
 *
 */

	drop table ebay_announce;
/*
	create table ebay_announce
	(
		marketplace			int
			constraint	announce_id_nn
			not null,
		id					number(3,0)
			constraint	announce_action_nn
			not null,
		location			number(1,0)
			constraint	announce_amount_nn
			not null,
		code				varchar(20)
			constraint	announce_code_nn
			not null,
		last_modified		date
			constraint	announce_last_modified_nn
			not null,
		description_len		number
			constraint	announce_desc_len_nn
			not null,
		description			LONG RAW,
		constraint			announce_pk
			primary key		(marketplace, id, location)
			using index tablespace useri01
			storage (initial 10K next 10K)
	)
tablespace userd01
storage (initial 1M next 1M);
*/

create table ebay_announce_new
(
	marketplace		number(38)
		constraint	announce_new_id_nn
		not null,
	site_id			number(3,0)
		constraint	announce_new_site_nn
		not null,
	partner_id		number(4,0)
		constraint	announce_new_partner_nn
		not null,
	id				number(3,0)
		constraint	announce_new_action_nn
		not null,
	location			number(1,0)
		constraint	announce_new_amount_nn
		not null,
	code				varchar(20)
		constraint	announce_new_code_nn
		not null,
	last_modified		date
		constraint	announce_new_last_modified_nn
		not null,
	description_len		number
		constraint	announce__new_desc_len_nn
		not null,
	description			LONG RAW
)
tablespace dynmiscd
storage (initial 1M next 1M);

alter table ebay_announce_new
	add	constraint	announce__new_pk
			primary key		(marketplace, site_id, partner_id, id, location)
			using index tablespace dynmisci
			storage (initial 20K next 10K) unrecoverable;
commit;		

