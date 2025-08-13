/*	$Id: ebay_traffic_info.sql,v 1.2 1999/02/21 02:54:07 josh Exp $	*/
/*
 * ebay_traffic_info.sql
 *
 *	This table is contains information about the traffic
 * i.e. number of page view per day.
 *
 */

 	drop table ebay_traffic_info;

	create table ebay_traffic_info
	(
		marketplace		int
			constraint	trafficinfo_marketplace_fk
			references	ebay_marketplaces(id),
		page_type		int
			constraint	trafficinfo_type_nn
			not null,
		categoryid		int
			constraint	trafficinfo_category_nn
			not null,
		page_view		int
			constraint	trafficinfo_view_nn
			not null,

		constraint		trafficinfo_pk
			primary key		(marketplace, page_type, categoryid)
			using index tablespace	adi01
			storage (initial 1K next 1K)
	)
	tablespace add01
	storage (initial 10K next 10K);

