/*	$Id: ebay_notes.sql,v 1.3 1999/02/21 02:56:38 josh Exp $	*/
/*
 * ebay_notes.sql
 *
 */

 drop table	ebay_notes;

 create table ebay_notes
 (
	id					NUMBER(38,0)
		constraint		enotes_id_nn
			not null,
	about_type			NUMBER(10,0)
		constraint		enotes_about_type_nn
			not null,
	from_type			NUMBER(10,0)
		constraint		enotes_from_type_nn
			not null,
	visibility			NUMBER(10,0)
		constraint		enotes_visibility_nn
			not null,
	user_from			NUMBER(38)
		constraint		enotes_user_from_nn
			not null,
	user_to				NUMBER(38)
		constraint		enotes_user_to_nn
			not null,
	user_cc				NUMBER(38),
	user_about			NUMBER(38),
	item_about			NUMBER(38),
	when				date
		constraint		enotes_when_nn
			not null,
	expiration			date,
	subject				VARCHAR(256)
		constraint		enotes_subject_nn
			not null,
	constraint			enotes_user_from_fk
		foreign key(user_from)
		references ebay_users(id),
	constraint			enotes_user_to_fk
		foreign key(user_to)
		references ebay_users(id),
	constraint			enotes_user_cc_fk
		foreign key(user_cc)
		references ebay_users(id),
	constraint			enotes_user_about_fk
		foreign key(user_about)
		references ebay_users(id)
)
tablespace tnotesd01
storage(initial 1M next 100K);

 drop table	ebay_notes_text;

 create table ebay_notes_text
 (
	id					NUMBER(38,0)
		constraint		enotes_text_id_nn
			not null,
	text_len			NUMBER(38,0)
		constraint		enotes_text_len_nn
			not null,
	text				LONG RAW,
	constraint			enotes_user_id_pk
		primary key(id)
 );

 drop sequence ebay_notes_sequence;

 create sequence ebay_notes_sequence;

