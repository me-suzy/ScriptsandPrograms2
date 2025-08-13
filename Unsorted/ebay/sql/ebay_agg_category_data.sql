/*	$Id: ebay_agg_category_data.sql,v 1.2 1999/02/21 02:53:06 josh Exp $	*/
/*
 * ebay_agg_category_data
 *
 *	This table contains a daily summary of all
 *  information about activity in auctions and
 *  in viewing items, seperated by partner and
 *  category ids.
 *
 */

drop table ebay_agg_category_data;

create table ebay_agg_category_data
(
	partner_id int
	constraint nn_agg_cat_partner_id
		not null,
	category_id int
	constraint nn_agg_cat_category_id
		not null
	constraint pos_agg_cat_category_id
		CHECK (category_id >= 0),
	covers_day date
	constraint nn_agg_cat_date
		not null,
	new_items int
	constraint nn_agg_cat_new_items
		not null
	constraint pos_agg_cat_new_items
		CHECK (new_items >= 0),
	new_bids_by_partner int
	constraint nn_agg_cat_new_bids_by
		not null
	constraint pos_agg_cat_new_bids_by
		CHECK (new_bids_by_partner >= 0),
	new_bids_on_partner int
	constraint nn_agg_cat_new_bids_on
		not null
	constraint pos_agg_cat_new_bids_on
		CHECK (new_bids_on_partner >= 0),
	successful_auctions int
	constraint nn_agg_cat_successful_a
		not null
	constraint pos_agg_cat_successful_a
		CHECK (successful_auctions >= 0),
	closed_bids_by_partner int
	constraint nn_agg_cat_closed_bids_by
		not null
	constraint pos_agg_cat_closed_bids_by
		CHECK (closed_bids_by_partner >= 0),
	closed_bids_on_partner int
	constraint nn_agg_cat_closed_bids_on
		not null
	constraint pos_agg_cat_closed_bids_on
		CHECK (closed_bids_on_partner >= 0),
	unsuccessful_auctions int
	constraint nn_agg_cat_unsuccessful_a
		not null
	constraint pos_agg_cat_unsuccessful_a
		CHECK (unsuccessful_auctions >= 0),
	auction_days4_successful int
	constraint nn_agg_cat_ad4_successful
		not null
	constraint pos_agg_cat_ad4_successful
		CHECK (auction_days4_successful >= 0),
	auction_days4_unsuccessful int
	constraint nn_agg_cat_ad4_fail
		not null
	constraint pos_agg_cat_ad4_fail
		CHECK (auction_days4_unsuccessful >= 0),
	closing_bid_total int
	constraint nn_agg_cat_bid_total
		not null
	constraint pos_agg_cat_bid_total
		CHECK (closing_bid_total >= 0),
	new_bold int
	constraint nn_agg_cat_new_bold
		not null
	constraint pos_agg_cat_new_bold
		CHECK (new_bold >= 0),
	new_featured int
	constraint nn_agg_cat_new_featured
		not null
	constraint pos_agg_cat_new_featured
		CHECK (new_featured >= 0),
	new_super_featured int
	constraint nn_agg_cat_new_s_featured
		not null
	constraint pos_agg_cat_new_s_featured
		CHECK (new_super_featured >= 0),
	num_page_views int
	constraint nn_agg_cat_num_views
		not null
	constraint pos_agg_cat_num_views
		CHECK (num_page_views >= 0),
	total_min_successful int
	constraint nn_agg_cat_total_min_s
		not null
	constraint pos_agg_cat_total_min_s
		CHECK (total_min_successful >= 0),
	total_min_unsuccessful int
	constraint nn_agg_cat_total_min_f
		not null
	constraint pos_agg_cat_total_min_f
		CHECK (total_min_unsuccessful >= 0),
	highest_closing_bid int
	constraint nn_agg_cat_max_closing
		not null
	constraint pos_agg_cat_max_closing
		CHECK (highest_closing_bid >= 0),
	total_open_value int
	constraint nn_agg_cat_total_open
		not null
	constraint pos_agg_cat_total_open
		CHECK (total_open_value >= 0),
	total_revenue int
	constraint nn_agg_cat_total_revenue
		not null,
	super_category int
	constraint nn_agg_cat_super_cat
		not null
	constraint pos_agg_cat_super_cat
		CHECK (super_category >= 0),
	category_name varchar2(255)
	constraint cbn_pos_agg_cat_cat_name
		null,
	constraint agg_category_pk
	primary key (covers_day, partner_id, category_id)
		using index tablespace bizdevi01
		storage (initial 3M next 50K)
		)
tablespace bizdevd01
storage (initial 30M next 6M);
