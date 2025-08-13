
drop table if exists topics;
drop table if exists newsletters;
drop table if exists templates;
drop table if exists subscribedUsers;
drop table if exists subscriptions;

create table topics
(
  pk_tId int auto_increment not null,
  tName varchar(50),
  primary key(pk_tId),
  unique id(pk_tId)
);

create table newsletters
(
  pk_nId int auto_increment not null,
  nName varchar(50),
  nTitle varchar(100),
  nContent text,
  nTemplateId int,
  nStatus enum('pending', 'sent'),
  primary key(pk_nId),
  unique id(pk_nId)
);

create table templates
(
  pk_nId int auto_increment not null,
  nName varchar(50),
  nDesc text,
  nTopicId int,
  nFromEmail varchar(250),
  nReplyToEmail varchar(250),
  nFrequency1 int,
  nFrequency2 int,
  nFormat enum('text', 'html'),
  primary key(pk_nId),
  unique id(pk_nId)
);

create table subscribedUsers
(
  pk_suId int auto_increment not null,
  suFName varchar(30),
  suLName varchar(30),
  suEmail varchar(250),
  suPassword varchar(70),
  suStatus enum('pending', 'subscribed'),
  suDateSubscribed timestamp,
  primary key(pk_suId),
  unique id(pk_suId),
  fulltext(suFName, suLName, suEmail)
);

create table subscriptions
(
  pk_sId int auto_increment not null,
  sNewsletterId int,
  sSubscriberId int,
  primary key(pk_sId),
  unique id(pk_sId)
);
