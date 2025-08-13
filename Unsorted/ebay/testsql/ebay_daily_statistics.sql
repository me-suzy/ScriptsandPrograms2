/*	$Id: ebay_daily_statistics.sql,v 1.2 1999/02/21 02:56:16 josh Exp $	*/
/*
 * ebay_daily_statistics.sql
 *
 *	This table is the account information about
 *	the auctions added each day.
 *
 */

	drop table ebay_dailystatistics;

	create table ebay_dailystatistics
	(
		marketplace		NUMBER(38)
			constraint	dailystats_marketplace_fk
			references ebay_marketplaces(id),
		when			date
			constraint	dailystats_when_nn
			not null,
		transaction_type	number(38)
			constraint	dailystats_xaction_fk
			not null,
		categoryid			number(38)
			constraint	dailystats_category_nn
			not null,
		items			number(38)
			constraint	dailystats_items_nn
			not null,
		dollar			FLOAT(126)
			constraint	dailystats_dollar_nn
			not null,
		bidcount		number(38)
			constraint	dailystats_bidcount_nn
			not null,
		constraint		dailystats_pk
		primary key		(marketplace, when, transaction_type, categoryid)
		using index tablespace	tstatsi01
		storage (initial 10K next 1K)
	)
tablespace tstatsd01
storage (initial 20K next 1K);

