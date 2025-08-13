/*	$Id: ebay_agg_site_out_data.sql,v 1.2 1999/02/21 02:53:12 josh Exp $	*/
/*
 * ebay_agg_site_out_data
 *
 *	This table contains information
 *  about the sites we (as a company)
 *  link out to. It does not contain
 *  the links made by our users.
 *  It also contains the dates those links
 *  were active -- entries with a null
 *  end date are still active.
 *
 */

drop table ebay_agg_site_out_data;

create table ebay_agg_site_out_data
(
	covers_day_start date
	constraint nn_sol_start_date
		not null,
	covers_day_end date
	constraint cbn_sol_end_date
		null,
	site_out_location varchar(255)
	constraint nn_sol_location
		not null
)
tablespace bizdevd01
storage (initial 10K next 10K);
