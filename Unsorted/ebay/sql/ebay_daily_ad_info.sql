/*	$Id: ebay_daily_ad_info.sql,v 1.2 1999/02/21 02:53:33 josh Exp $	*/
/*
 * ebay_daily_ad_info.sql
 *
 *	This table contains information about the ad
 * i.e. number of page view per day.
 *
 */

 	drop table ebay_daily_ad_info;

	create table ebay_daily_ad_info
	(
		adid			int
			constraint	dailyadinfo_adid_fk
			references	ebay_adinfo(id),
		page_type		int
			constraint	dailyadinfo_type_nn
			not null,
		categoryid		int,
		impressions		int
			constraint	dailyadinfo_impressions_nn
			not null,

		constraint		dailyadinfo_pk
			primary key		(adid, page_type, categoryid)
			using index tablespace	adi01
			storage (initial 10K next 10K)

	)
	tablespace add01
	storage (initial 1M next 100K);

