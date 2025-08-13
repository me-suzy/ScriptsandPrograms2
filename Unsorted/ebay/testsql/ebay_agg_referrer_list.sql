/*	$Id: ebay_agg_referrer_list.sql,v 1.2 1999/02/21 02:55:55 josh Exp $	*/
/*
 * ebay_agg_referrer_list
 *
 *	This table contains a lookup list
 *  for referrers.
 *
 */

drop table ebay_agg_referrer_list;

create table ebay_agg_referrer_list
(
	referrer_id int
		constraint agg_rlist_pk
		primary key
		using index tablespace tbizdevi01
		storage (initial 10K next 1K),
	referrer_site varchar(255)
	constraint nn_rlist_site
		not null
)
tablespace tbizdevd01
storage (initial 10K next 10K);

 drop sequence ebay_agg_referrer_sequence;
 create sequence ebay_agg_referrer_sequence
	minvalue 0;

 create index ebay_agg_rindex on
	ebay_agg_referrer_list (referrer_site)
	tablespace tbizdevi01
	storage (initalize 1K next 1K);
