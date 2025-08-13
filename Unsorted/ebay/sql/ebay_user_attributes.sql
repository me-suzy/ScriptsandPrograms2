/*	$Id: ebay_user_attributes.sql,v 1.5 1999/03/22 00:09:46 josh Exp $	*/
/*
 * ebay_user_attributes.sql
 *
 * This table contains user attribute information.
 * There's one row for each unique user / attribute
 * combination
 *
 */

drop table ebay_user_attributes;
/* obsolete - newer definition below 
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
      	using index storage(initial 5K next 1K)
                 tablespace tuseri01,
	constraint		attr_fk
		foreign key (user_id)
		references	ebay_users(id)
)
 tablespace tuserd01
 storage (initial 30K next 2K);
*/

create table ebay_user_attributes
(
	user_id		int
		constraint	rattr_user_id_nn
		not null,
	attribute_id	number(3,0)
		constraint	rattr_attr_id_nn
		not null,
	first_entered	date
		constraint	rattr_first_entered_nn
		not null,
	last_updated	date
		constraint	rattr_updated_nn
		not null,
	boolean_value	char(1),
	number_value	number,
	text_value		varchar(256)
)
 tablespace ruserd04
 storage (initial 30M next 10M);

alter table ebay_user_attributes
	add	constraint		rattr_pk
      	primary key(user_id, attribute_id)
      	using index storage(initial 5M next 1M)
                 tablespace ruseri04;
commit;
alter table ebay_user_attributes
	add	constraint		rattr_fk
		foreign key (user_id)
		references	ebay_users(id);
commit;

create index ebay_user_attributes_n1
on ebay_user_attributes(attribute_id,number_value)
tablespace ruseri04
storage(initial 100M next 20M minextents 1 pctincrease 0)
unrecoverable parallel (degree 3);
commit;

/* clean up ebay_user_attributes */
host vxmkcdev -h -s 2001M /oracle/rdata03/ebay/oradata/userd04.dbf
host vxmkcdev -h -s 501M /oracle/rdata03/ebay/oradata/userd04a.dbf
host vxmkcdev -h -s 2001M /oracle/rdata05/ebay/oradata/useri04.dbf
host vxmkcdev -h -s 2001M /oracle/rdata05/ebay/oradata/useri04a.dbf

create tablespace userd04
	datafile '/oracle/rdata03/ebay/oradata/userd04.dbf' size 2001M,
	'/oracle/rdata03/ebay/oradata/userd04a.dbf' size 501M
	 autoextend off;

create tablespace useri04
	datafile '/oracle/rdata05/ebay/oradata/useri04.dbf' size 2001M,
	'/oracle/rdata05/ebay/oradata/useri04a.dbf' size 2001M
	 autoextend off;

rename ebay_user_attributes to ebay_user_attributes_old;

create table ebay_user_attributes tablespace USERD04 storage 
(initial 2000M next 250M pctincrease 0)  unrecoverable as 
select * from ebay_user_attributes_old 
where attribute_id not in (9,10,11,12,13);
/* 1:13 - 1:39 */

alter table ebay_user_attributes 
add constraint ATTR_PK primary key (user_id, attribute_id) 
using index storage (initial 1000M next 100M pctincrease 0)
tablespace USERI04 unrecoverable;
/* 1:40 - 2:41 */

/* don't need this?! 
create index ebay_user_attributes_n1 
on ebay_user_attributes(attribute_id, number_value)
tablespace USERI04 
storage (initial 1000M next 100M pctincrease 0)
unrecoverable;
*/
/* clean up ebay_user_attributes */
host vxmkcdev -h -s 2001M /oracle/rdata03/ebay/oradata/userd04.dbf
host vxmkcdev -h -s 501M /oracle/rdata03/ebay/oradata/userd04a.dbf
host vxmkcdev -h -s 2001M /oracle/rdata05/ebay/oradata/useri04.dbf
host vxmkcdev -h -s 2001M /oracle/rdata05/ebay/oradata/useri04a.dbf

create tablespace userd04
	datafile '/oracle/rdata03/ebay/oradata/userd04.dbf' size 2001M,
	'/oracle/rdata03/ebay/oradata/userd04a.dbf' size 501M
	 autoextend off;

create tablespace useri04
	datafile '/oracle/rdata05/ebay/oradata/useri04.dbf' size 2001M,
	'/oracle/rdata05/ebay/oradata/useri04a.dbf' size 2001M
	 autoextend off;

rename ebay_user_attributes to ebay_user_attributes_old;

create table ebay_user_attributes tablespace USERD04 storage 
(initial 2000M next 250M pctincrease 0)  unrecoverable as 
select * from ebay_user_attributes_old 
where attribute_id not in (9,10,11,12,13);
/* 1:13 - 1:39 */

alter table ebay_user_attributes 
add constraint ATTR_PK primary key (user_id, attribute_id) 
using index storage (initial 1000M next 100M pctincrease 0)
tablespace USERI04 unrecoverable;
/* 1:40 - 2:41 */

/* don't need this?! 
create index ebay_user_attributes_q1 
on ebay_user_attributes(attribute_id, number_value)
tablespace USERI04 
storage (initial 1000M next 100M pctincrease 0)
unrecoverable;
*/
