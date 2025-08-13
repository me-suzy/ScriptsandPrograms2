/*	$Id: ebay_cobrand_headers.sql,v 1.2 1999/02/21 02:56:10 josh Exp $	*/
/*
 * ebay_cobrand_headers
 * A table for the headers (and footers) for cobranding
 */

drop table ebay_cobrand_headers;

create table ebay_cobrand_headers
(
	partner_id		number(3)
		constraint ebay_cobrand_headers_id_pos
		check (partner_id >= 0),
	page_type		number(3)
		constraint ebay_cobrand_page_type_nn
		not null,
		constraint ebay_cobrand_page_type_pos
		check (page_type >= 0),
	header_type		number(1)
		constraint ebay_cobrand_header_type_nn
		not null,
		constraint ebay_cobrand_header_type_pos
		check (header_type >= 0),
		-- header_type 0 is a header. header_type 1 is a footer
	header_unq_id	number(38)
		constraint ebay_cobrand_unq_nn
		not null,
	constraint ebay_cobrand_headers_pk
		primary key (partner_id, page_type, header_type)
			using index tablespace	tparti01
			storage (initial 100K next 100K),
	constraint ebay_cobrand_id_fk
		foreign key (partner_id)
		references ebay_cobrand_partners(id),
	constraint ebay_cobrand_header_fk
		foreign key (header_unq_id)
		references ebay_cobrand_headers_text(header_unq_id)
)
tablespace tpartd01
storage (initial 1M next 500K);

/* problem with references ebay_cobrand_headers_text(header_unq_id);
should be foreign key constraint? */
