/*
 * ebay_sites.sql
 *
 * This table contains our knowledge about different
 * sites. 
 */

drop table ebay_sites;

create table ebay_sites
(
site_id				number(3)
    constraint   sites_site_id_nn
          not null,
name			varchar2(63)
	constraint	sites_name_nn
		not null,
PARSED_STRING             VARCHAR2(15), 
TIMEZONE_ID               NUMBER(3), 
DEFAULT_LISTING_CURRENCY  NUMBER(3)
)
tablespace tmiscd01
storage (initial 10K next 5K pctincrease 0);



alter table ebay_sites
	add constraint		sites_pk
		primary key		(site_id)
		using index tablespace tmiscd01
		storage (initial 2K next 1K);

commit;

// site ids can be found in eBayTypes.h

// Main
insert into ebay_sites (site_id, name, parsed_string, timezone_id, default_listing_currency)
 values (0, 'ebay', '', 0, 1);

// United States
insert into ebay_sites (site_id, name, parsed_string, timezone_id, default_listing_currency)
 values (1, 'usa', '', 0, 1);

// Canada
insert into ebay_sites (site_id, name, parsed_string, timezone_id, default_listing_currency)
 values (2, 'canada', 'ca', 3, 2);

// United Kingdom
insert into ebay_sites (site_id, name, parsed_string, timezone_id, default_listing_currency)
 values (3, 'uk', 'uk', 1, 3);

// Germany
insert into ebay_sites (site_id, name, parsed_string, timezone_id, default_listing_currency)
 values (77, 'germany', 'de', 5, 4);

// Australia
insert into ebay_sites (site_id, name, parsed_string, timezone_id, default_listing_currency)
 values (15, 'australia', 'au', 4, 5);


