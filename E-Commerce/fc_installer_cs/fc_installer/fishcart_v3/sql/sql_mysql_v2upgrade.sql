--
-- Upgrade the v2.21 database to v.3.05
--
-- USE AT YOUR OWN RISK!!!  BACK EVERYTHING UP FIRST!!!

alter table ORDERHEAD add scity varchar(20);
alter table ORDERHEAD add sstate varchar(20);
alter table ORDERHEAD add szip varchar(16);
alter table ORDERHEAD add scountry varchar(5);

alter table SUBZONETABLE add subzzip integer;
alter table SUBZONETABLE add subzcity integer;
alter table SUBZONETABLE add subzstate integer;
alter table SUBZONETABLE add subzcntry integer;

alter table SHIPTABLE drop shipszid;
alter table SHIPTABLE drop shipdef;
alter table SHIPTABLE add shipsvccode varchar(20);
alter table SHIPTABLE add active tinyint;
alter table SHIPTABLE add index shipszid (shipszid);

alter table CUSTTABLE add custpromoemail tinyint;
alter table CUSTTABLE add index custpromoemail (custpromoemail);

create table SUBZSHIPTABLE (
	shipszid	integer not null,
	shipid      integer not null,
	shipdef     integer,
	key shipszid  (shipszid),
	key shipid  (shipid)
);
 
insert into SUBZSHIPTABLE (shipszid,shipid,shipdef) values (1,1,1);
 
create table AUXLINKTABLE (
    rid     integer default '0' not null auto_increment,
	seq     integer,
	loc     integer,
	title   varchar (64),
	url     varchar(255),
	unique index rid    (rid)
);

create table AUXTXTTABLE (
	rid     integer default '0' not null auto_increment,
	seq     integer,
	loc     integer,
	title       varchar (64),
	text        varchar(255),
	unique index rid    (rid)
);
