/*	$Id: ebay_category_filters.sql,v 1.2 1999/05/19 02:35:10 josh Exp $	*/
/*
 * ebay_category_filters.sql
 *	contains category-filter cross-reference information
 */

drop table ebay_category_filters;

 create table ebay_category_filters
 (
	CATEGORY_ID			NUMBER(38)
		constraint		cat_filters_cat_id_nn
			not null,
	FILTER_ID			NUMBER(38)
		constraint		cat_filters_filter_id_nn
			not null
)
tablespace itemd01
storage (initial 1M next 500K);

alter table ebay_category_filters
	add constraint			category_filters_pk
		primary key		(category_id, filter_id)
		using index tablespace statmisci
		storage (initial 1M next 500K);

