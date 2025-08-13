/*	$Id: ebay_accounts_n.sql,v 1.3 1999/02/21 02:53:02 josh Exp $	*/
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata02/ebay/oradata/raccountd0.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata02/ebay/oradata/raccountd1.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata02/ebay/oradata/raccountd2.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata02/ebay/oradata/raccountd3.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata02/ebay/oradata/raccountd4.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata03/ebay/oradata/raccountd5.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata03/ebay/oradata/raccountd6.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata03/ebay/oradata/raccountd7.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata03/ebay/oradata/raccountd8.dbf
host /usr/sbin/vxmkcdev -h 2k -s 600 /oracle/rdata03/ebay/oradata/raccountd9.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata04/ebay/oradata/raccounti0.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata04/ebay/oradata/raccounti1.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata04/ebay/oradata/raccounti2.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata04/ebay/oradata/raccounti3.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata04/ebay/oradata/raccounti4.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata05/ebay/oradata/raccounti5.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata05/ebay/oradata/raccounti6.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata05/ebay/oradata/raccounti7.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata05/ebay/oradata/raccounti8.dbf
host /usr/sbin/vxmkcdev -h 2k -s 60 /oracle/rdata05/ebay/oradata/raccounti9.dbf

create tablespace raccountd0 datafile '/oracle/rdata02/ebay/oradata/raccountd0.dbf' size 600M;
create tablespace raccountd1 datafile '/oracle/rdata02/ebay/oradata/raccountd1.dbf' size 600M;
create tablespace raccountd2 datafile '/oracle/rdata02/ebay/oradata/raccountd2.dbf' size 600M;
create tablespace raccountd3 datafile '/oracle/rdata02/ebay/oradata/raccountd3.dbf' size 600M;
create tablespace raccountd4 datafile '/oracle/rdata02/ebay/oradata/raccountd4.dbf' size 600M;
create tablespace raccountd5 datafile '/oracle/rdata03/ebay/oradata/raccountd5.dbf' size 600M;
create tablespace raccountd6 datafile '/oracle/rdata03/ebay/oradata/raccountd6.dbf' size 600M;
create tablespace raccountd7 datafile '/oracle/rdata03/ebay/oradata/raccountd7.dbf' size 600M;
create tablespace raccountd8 datafile '/oracle/rdata03/ebay/oradata/raccountd8.dbf' size 600M;
create tablespace raccountd9 datafile '/oracle/rdata03/ebay/oradata/raccountd9.dbf' size 600M;
create tablespace raccounti0 datafile '/oracle/rdata04/ebay/oradata/raccounti0.dbf' size 600M;
create tablespace raccounti1 datafile '/oracle/rdata04/ebay/oradata/raccounti1.dbf' size 60M;
create tablespace raccounti2 datafile '/oracle/rdata04/ebay/oradata/raccounti2.dbf' size 60M;
create tablespace raccounti3 datafile '/oracle/rdata04/ebay/oradata/raccounti3.dbf' size 60M;
create tablespace raccounti4 datafile '/oracle/rdata04/ebay/oradata/raccounti4.dbf' size 60M;
create tablespace raccounti5 datafile '/oracle/rdata05/ebay/oradata/raccounti5.dbf' size 60M;
create tablespace raccounti6 datafile '/oracle/rdata05/ebay/oradata/raccounti6.dbf' size 60M;
create tablespace raccounti7 datafile '/oracle/rdata05/ebay/oradata/raccounti7.dbf' size 60M;
create tablespace raccounti8 datafile '/oracle/rdata05/ebay/oradata/raccounti8.dbf' size 60M;
create tablespace raccounti9 datafile '/oracle/rdata05/ebay/oradata/raccounti9.dbf' size 60M;

CREATE TABLE EBAY_ACCOUNTS_0

  ID			NUMBER		constraint accounts_0_id_nn		not null,
  WHEN			DATE		constraint accounts_0_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_0_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_0_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_0_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_0_fk foreign key (id) references ebay_users(id));
tablespace raccountd0	storage (initial 200M next 80M);

create index ebay_accounts_0_id_index on ebay_accounts_0(id) tablespace raccounti0 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_1

  ID			NUMBER		constraint accounts_1_id_nn		not null,
  WHEN			DATE		constraint accounts_1_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_1_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_1_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_1_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_1_fk foreign key (id) references ebay_users(id));
tablespace raccountd1	storage (initial 200M next 80M);

create index ebay_accounts_1_id_index on ebay_accounts_1(id) tablespace raccounti1 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_2

  ID			NUMBER		constraint accounts_2_id_nn		not null,
  WHEN			DATE		constraint accounts_2_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_2_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_2_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_2_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_2_fk foreign key (id) references ebay_users(id));
tablespace raccountd2	storage (initial 200M next 80M);

create index ebay_accounts_2_id_index on ebay_accounts_2(id) tablespace raccounti2 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_3

  ID			NUMBER		constraint accounts_3_id_nn		not null,
  WHEN			DATE		constraint accounts_3_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_3_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_3_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_3_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_3_fk foreign key (id) references ebay_users(id));
tablespace raccountd3	storage (initial 200M next 80M);

create index ebay_accounts_3_id_index on ebay_accounts_3(id) tablespace raccounti3 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_4

  ID			NUMBER		constraint accounts_4_id_nn		not null,
  WHEN			DATE		constraint accounts_4_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_4_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_4_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_4_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_4_fk foreign key (id) references ebay_users(id));
tablespace raccountd4	storage (initial 200M next 80M);

create index ebay_accounts_4_id_index on ebay_accounts_4(id) tablespace raccounti4 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_5

  ID			NUMBER		constraint accounts_5_id_nn		not null,
  WHEN			DATE		constraint accounts_5_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_5_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_5_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_5_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_5_fk foreign key (id) references ebay_users(id));
tablespace raccountd5	storage (initial 200M next 80M);

create index ebay_accounts_5_id_index on ebay_accounts_5(id) tablespace raccounti5 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_6

  ID			NUMBER		constraint accounts_6_id_nn		not null,
  WHEN			DATE		constraint accounts_6_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_6_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_6_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_6_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_6_fk foreign key (id) references ebay_users(id));
tablespace raccountd6	storage (initial 200M next 80M);

create index ebay_accounts_6_id_index on ebay_accounts_6(id) tablespace raccounti6 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_7

  ID			NUMBER		constraint accounts_7_id_nn		not null,
  WHEN			DATE		constraint accounts_7_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_7_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_7_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_7_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_7_fk foreign key (id) references ebay_users(id));
tablespace raccountd7	storage (initial 200M next 80M);

create index ebay_accounts_7_id_index on ebay_accounts_7(id) tablespace raccounti7 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_8

  ID			NUMBER		constraint accounts_8_id_nn		not null,
  WHEN			DATE		constraint accounts_8_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_8_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_8_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_8_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_8_fk foreign key (id) references ebay_users(id));
tablespace raccountd8	storage (initial 200M next 80M);

create index ebay_accounts_8_id_index on ebay_accounts_8(id) tablespace raccounti8 storage(initial 20M next 8M);

CREATE TABLE EBAY_ACCOUNTS_9

  ID			NUMBER		constraint accounts_9_id_nn		not null,
  WHEN			DATE		constraint accounts_9_when_nn		not null,
  ACTION		NUMBER(3)	constraint accounts_9_action_nn		not null,
  AMOUNT		NUMBER(10,2)	constraint accounts_9_amount_nn		not null,
  TRANSACTION_ID	NUMBER(38)	constraint accounts_9_xaction_nn	not null,
  MEMO			VARCHAR2(255),
  MIGRATION_BATCH_ID 	NUMBER(3),
  BATCH_ID		NUMBER(38),
  ITEM_ID		NUMBER(38)	constraint accounts_9_fk foreign key (id) references ebay_users(id));
tablespace raccountd9	storage (initial 200M next 80M);

create index ebay_accounts_9_id_index on ebay_accounts_9(id) tablespace raccounti9 storage(initial 20M next 8M);

alter table ebay_account_balances add (table_indicator number(3) default -1);
