/*
 * ebay_notes.sql
 *
 *	The repository for ebay_notes.
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
	user_from			NUMBER(38,0)
		constraint		enotes_user_from_nn
			not null,
	user_to				NUMBER(38,0)
		constraint		enotes_user_to_nn
			not null,
	user_cc				NUMBER(38,0),
	user_about			NUMBER(38,0),
	item_about			NUMBER(38,0),
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
tablespace notesd01
storage(initial 1M next 100K);

/*
 *	Indexes
 */
 create index ebay_notes_id_index
	on ebay_notes(id)
	unique 
	tablespace notesi01
	storage(initial 100K next 10K);

 create index ebay_notes_about_user_index
	on ebay_notes(about_user)
	unique 
	tablespace notesi01
	storage(initial 100K next 10K);

 create index ebay_notes_about_item_index
	on ebay_notes(about_item)
	unique 
	tablespace notesi01
	storage(initial 100K next 10K);

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
 )
 tablespace notesd01
 storage(initial 5M next 200K);

/*
 *	Indexes
 */
 create index ebay_notes_text_id_index
	on ebay_notes_text(id)
	unique 
	tablespace notesi01
	storage(initial 100K next 10K);



 drop sequence ebay_notes_sequence;

 create sequence ebay_notes_sequence;

