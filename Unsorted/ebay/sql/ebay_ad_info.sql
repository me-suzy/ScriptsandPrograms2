/*	$Id: ebay_ad_info.sql,v 1.2 1999/02/21 02:53:03 josh Exp $	*/
/*
 * ebay_ad_info.sql
 *
 *	This table is contains information about the traffic
 * i.e. number of page view per day.
 *
 */

 	drop table ebay_ad_info;

	create table ebay_ad_info
	(
		id				int
			constraint	adinfo_id_nn
			not null,
		companyid		int
			constraint	adinfo_company_fk
			references	ebay_companies(id),
		page_type		int
			constraint	adinfo_type_nn
			not null,
		impressions		int
			constraint	adinfo_impressions_nn
			not null,
		start_date		date
			constraint	adinfo_start_nn
			not null,
		end_date		date
			constraint	adinfo_end_nn
			not null,
		categoryid		int,
		url				varchar(255)
			constraint	adinfo_url_nn
			not null,
		image			varchar(255),
		alt				varchar(128),
		other			varchar(255),
		shown			int
			constraint	adinfo_shown_nn
			not null,
		click_through	int
			constraint	adinfo_click_nn
			not null,

		constraint		adinfo_pk
			primary key		(id)
			using index tablespace	adi01
			storage (initial 10K next 10K)
	)
	tablespace add01
	storage (initial 1M next 1M);

