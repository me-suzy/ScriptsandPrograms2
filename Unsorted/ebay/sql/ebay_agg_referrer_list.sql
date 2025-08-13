/*	$Id: ebay_agg_referrer_list.sql,v 1.2 1999/02/21 02:53:11 josh Exp $	*/
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
		using index tablespace bizdevi01
		storage (initial 10K next 1K),
	referrer_site varchar(255)
	constraint nn_rlist_site
		not null
)
tablespace bizdevd01
storage (initial 20K next 5K);

 drop sequence ebay_agg_referrer_sequence;
 create sequence ebay_agg_referrer_sequence
	minvalue 1;

 create index ebay_agg_rindex on
	ebay_agg_referrer_list (referrer_site)
	tablespace bizdevi01
	storage (initalize 1K next 1K);
