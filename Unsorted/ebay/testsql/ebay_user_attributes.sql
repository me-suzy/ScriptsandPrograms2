/*	$Id: ebay_user_attributes.sql,v 1.2 1999/02/21 02:56:51 josh Exp $	*/
drop table ebay_user_attributes;

create table ebay_user_attributes
(
	user_id		int
		constraint	attr_user_id_nn
		not null,
	attribute_id	number(3,0)
		constraint	attr_attr_id_nn
		not null,
	first_entered	date
		constraint	attr_first_entered_nn
		not null,
	last_updated	date
		constraint	attr_updated_nn
		not null,
	boolean_value	char(1),
	number_value	number,
	text_value		varchar(256),
	constraint		attr_pk
      	primary key(user_id, attribute_id)
      	using index storage(initial 5m next 1m)
                 tablespace useri01,
	constraint		attr_fk
		foreign key (user_id)
		references	ebay_users(id)
)
 tablespace userd01
 storage (initial 30M next 2m);

