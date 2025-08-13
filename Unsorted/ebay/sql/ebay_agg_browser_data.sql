/*	$Id: ebay_agg_browser_data.sql,v 1.2 1999/02/21 02:53:05 josh Exp $	*/
/*
 * ebay_agg_browser_data
 *
 *	This table contains a daily total of 'number of hits'
 *  from each browser by partner.
 *
 */

drop table ebay_agg_browser_data;

create table ebay_agg_browser_data
(
	partner_id int
	constraint nn_agg_browser_partner_id
		not null,
	covers_day date
	constraint nn_agg_browser_date
		not null,
	browser_id int
	constraint nn_agg_browser_browser
		not null,
	num_views int
	constraint nn_agg_browser_views
		not null
	constraint pos_agg_browser_views
		CHECK (num_views >= 0),
	constraint agg_browser_pk
	primary key (covers_day, partner_id, browser_id)
		using index tablespace bizdevi01
		storage (initial 6K next 1K)
	)
tablespace bizdevd01
storage (initial 60K next 1K);
