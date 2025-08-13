/*	$Id: ebay_agg_referrer_data.sql,v 1.2 1999/02/21 02:55:54 josh Exp $	*/
/*
 * ebay_agg_referrer_data
 *
 *	This table contains information about
 *  the number of referrals given to use from
 *  external sites, on a daily basis.
 *
 */
 
 drop table ebay_agg_referrer_data;

create table ebay_agg_referrer_data
(
	partner_id int
	constraint nn_agg_referrer_partner_id
		not null,
	covers_day date
	constraint nn_agg_referrer_date
		not null,
	referrer_id int
	constraint nn_agg_referrer_id
		not null
		references ebay_agg_referrer_list(referrer_id),
	num_views int
	constraint nn_pos_agg_referrer_views
		not null
		CHECK (num_views >= 0),
	constraint agg_refer_pk
	primary key (covers_day, partner_id, referrer_id)
		using index tablespace tbizdevi01
		storage (initial 1K next 1K)
)
tablespace tbizdevd01
storage (initial 10K next 10K);

