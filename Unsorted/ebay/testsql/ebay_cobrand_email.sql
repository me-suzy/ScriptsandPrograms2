/*	$Id: ebay_cobrand_email.sql,v 1.2 1999/02/21 02:56:08 josh Exp $	*/
/*
 * ebay_cobrand_email
 * A table for the headers (and footers) for cobranding email
 */

drop table ebay_cobrand_email;

create table ebay_cobrand_email
(
	partner_id		number(3)
		constraint ebay_cobrand_e_headers_id_pos
		check (partner_id >= 0),
	email_type		number(3)
		constraint ebay_cobrand_email_type_nn
		not null,
		constraint ebay_cobrand_email_type_pos
		check (email_type >= 0),
	header_type		number(1)
		constraint ebay_cobrand_e_header_type_nn
		not null,
		constraint ebay_cobrand_e_header_type_pos
		check (header_type >= 0),
		-- header_type 0 is a header. header_type 1 is a footer
	header_unq_id	number(38)
		constraint ebay_cobrand_e_unq_nn
		not null,
	constraint ebay_cobrand_e_headers_pk
		primary key (partner_id, email_type, header_type)
			using index tablespace	tparti01
			storage (initial 100K next 100K),
	constraint ebay_cobrand_e_id_fk
		foreign key (partner_id)
		references ebay_cobrand_partners(id),
	constraint ebay_cobrand_e_header_fk
		foreign key (header_unq_id)
		references ebay_cobrand_email_text(header_unq_id)
)
tablespace tpartd01
storage (initial 1M next 500K);
