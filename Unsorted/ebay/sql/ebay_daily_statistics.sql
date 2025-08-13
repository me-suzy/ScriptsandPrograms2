/*	$Id: ebay_daily_statistics.sql,v 1.3 1999/02/21 02:53:34 josh Exp $	*/
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
		using index tablespace	statsi01
		storage (initial 2M next 1M)
	)
tablespace statsd01
storage (initial 20M next 5M);

/* Alex */
/* add row that represents bids from the old AuctionWeb days */
INSERT INTO ebay_dailystatistics
( MARKETPLACE,
  WHEN,
  TRANSACTION_TYPE,
  CATEGORYID,
  ITEMS,
  DOLLAR,
  BIDCOUNT
)
values
( 0,
  TO_DATE('1990-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS'),
  0,
  0,
  0,
  0.0,
  8864234
);

/* add row that represents bids from days of new eBay, but for which daily stats were never run */
INSERT INTO ebay_dailystatistics
( MARKETPLACE,
  WHEN,
  TRANSACTION_TYPE,
  CATEGORYID,
  ITEMS,
  DOLLAR,
  BIDCOUNT
)
values
( 0,
  TO_DATE('1990-01-02 00:00:00', 'YYYY-MM-DD HH24:MI:SS'),
  0,
  0,
  0,
  0.0,
  787004
);
