create table mgb_brackets
(
  bid int not null auto_increment,
  type varchar(10)  not null default '',
  name varchar(30) not null default '',
  top1 varchar(100) not null default '',
  top2 varchar(100) not null default '',
  top3 varchar(100) not null default '',
  DATA text,
  primary key (bid)
);