/*	$Id: ebay_announce.sql,v 1.2 1999/02/21 02:55:57 josh Exp $	*/
/*
 * ebay_announce.sql
 *
 *	This table contains announcements for different
 *  parts of the system.
 *
 */

	drop table ebay_announce;

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
			using index tablespace tuseri01
			storage (initial 10K next 10K)
	)
tablespace tuserd01
storage (initial 10K next 10K);

