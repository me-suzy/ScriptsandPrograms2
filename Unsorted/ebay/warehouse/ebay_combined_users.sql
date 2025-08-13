/*	$Id: ebay_combined_users.sql,v 1.2 1999/02/21 02:57:43 josh Exp $	*/
create table ebay_combined_users(
 id                  number(38) not null,
 userid              varchar2(64),
 user_state          number(8),
 password            varchar2(64),
 last_modified       date,
 name                varchar2(64),
 company             varchar2(64),
 address             varchar2(64),
 city                varchar2(64),
 state               varchar2(64),
 zip                 varchar2(64),
 country             varchar2(64),
 dayphone            varchar2(32),
 nightphone          varchar2(32),
 faxphone            varchar2(32),
 creation            date,
 email               varchar2(64),
 count               number(8),
 credit_card_on_file char(1),
 good_credit         char(1),
 gender              char(1),
 partner_id          NUMBER(3))
tablespace USERSD01
storage(initial 70M next 10M minextents 1 maxextents 99 pctincrease 0)
/
create index ebay_combined_users_n1 on ebay_combined_users(id)
tablespace USERSI01
storage(initial 10M next 10M minextents 1 maxextents 99 pctincrease 0)
/
