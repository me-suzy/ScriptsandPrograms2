/*	$Id: ebay_agg_referrer_data.sql,v 1.2 1999/02/21 02:53:10 josh Exp $	*/
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
	constraint nn_agg_referrer_views
		not null
	constraint pos_agg_referrer_views
		CHECK (num_views >= 0),
	constraint agg_refer_pk
	primary key (covers_day, partner_id, referrer_id)
		using index tablespace bizdevi01
		storage (initial 6K next 2K)
)
tablespace bizdevd01
storage (initial 60K next 10K);

