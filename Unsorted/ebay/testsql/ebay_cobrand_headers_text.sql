/*	$Id: ebay_cobrand_headers_text.sql,v 1.2 1999/02/21 02:56:11 josh Exp $	*/
/*
 * ebay_cobrand_headers_text
 * A table for the text of headers (and footers) for cobranding
 */

drop table ebay_cobrand_headers_text;
drop sequence ebay_cobrand_unq_sequence;

create table ebay_cobrand_headers_text
(
	header_unq_id	number(38)
		constraint ebay_cobrand_headers_text_pk
		primary key,
	header_length	number(38)
		constraint	ebay_cobrand_header_length_nn
		not null,
	header_desc		varchar(255)
		constraint		cobrand_header_text_desc_nn
		not null,
--		constraint		cobrand_header_text_desc_unq
--		unique,
	header_text		long raw
		constraint ebay_cobrand_header_text_nn
		not null
)
tablespace tpartd01
storage (initial 1M next 500K);


create sequence ebay_cobrand_unq_sequence
	start with 1
	increment by 1;

/* cannot start sequence with 0!!! */
