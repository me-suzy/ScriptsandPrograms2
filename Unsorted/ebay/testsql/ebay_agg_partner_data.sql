/*	$Id: ebay_agg_partner_data.sql,v 1.2 1999/02/21 02:55:53 josh Exp $	*/
/*
 * ebay_agg_partner_data
 *
 *	This table contains information about
 *  the number of views attributed to a partner,
 *  and the number of registrations, on a daily
 *  basis.
 *
 */

 drop table ebay_agg_partner_data;

create table ebay_agg_partner_data
(
	partner_id int
	constraint nn_agg_partner_id
		not null,
	page_views int
	constraint nn_pos_agg_partner_views
		not null
		CHECK (page_views >= 0),
	covers_day date
	constraint nn_agg_partner_date
		not null,
	new_user_registrations int
	constraint nn_agg_partner_new_reg
		not null
		CHECK (new_user_registrations >= 0),
	new_user_registrations_ever int
	constraint nn_agg_partner_ever_reg
		not null
		CHECK (new_user_registrations_ever >= 0),
	constraint agg_partner_pk
	primary key (covers_day, partner_id)
		using index tablespace tbizdevi01
		storage (initial 1K next 1K)
		)
tablespace tbizdevd01
storage (initial 10K next 10K);

