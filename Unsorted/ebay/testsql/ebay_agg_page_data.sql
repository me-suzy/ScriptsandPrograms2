/*	$Id: ebay_agg_page_data.sql,v 1.2 1999/02/21 02:55:51 josh Exp $	*/
/*
 * ebay_agg_page_data
 *
 *	This table contains a list of the number of
 *  hits the listed page obtained from members
 *  of a particular partner, on a particular day.
 *
 */
 
 drop table ebay_agg_page_data;

create table ebay_agg_page_data
(
	partner_id int
	constraint nn_agg_page_id
		not null,
	covers_day date
	constraint nn_agg_page_date
		not null,
	page_name varchar(255)
	constraint nn_agg_page_page
		not null,
	num_views int
	constraint nn_agg_page_views
		not null
		CHECK (num_views >= 0),
	constraint agg_page_pk
	primary key (covers_day, partner_id, page_name)
		using index tablespace tbizdevi01
		storage (initial 5K next 5K)
		)
tablespace tbizdevd01
storage (initial 10K next 10K);

