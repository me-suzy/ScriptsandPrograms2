-- Copyright (C) 1997-2003  FishNet, Inc.

-- Oracle tables for FishCartSQL

-- varchar() is limited to about 4000 characters
-- CLOB / BLOB is required to go beyond 4000 character limit

-- CLOB reference
-- http://www.zend.com/manual/ref.oci8.php
-- http://conf.php.net/pres/slides/oci/paper.txt
-- http://www.phpbuilder.com/lists/phplib-list/2000101/0115.php

-- ********** User creation ************

-- drop user USERID;
-- create user USERID identified by USERPW;
-- grant create session to USERID;
-- commit work;

-- drop user ADMID;
-- create user ADMID identified by ADMPW;
-- grant create session to ADMID;
-- commit work;

-- drop user DBDNAME;
-- create user DBDNAME identified by DBDPASS;
-- grant create session to DBDNAME;
-- commit work;

-- ********** Master Table *************

-- drop table MASTERTABLE;
-- commit work;

create table MASTERTABLE (
	zoneid	integer,
	numzone	integer,
	numlang	integer,
	maxzone	integer,
	maxlang	integer
);
commit work;

-- grant select,insert on MASTERTABLE to USERID;
-- grant all on MASTERTABLE to ADMID;
-- commit work;

insert into MASTERTABLE
	(zoneid,numzone,numlang,maxzone,maxlang)
	values
	(1,1,1,1,1);
commit work;

-- ********** Order Processing Tables *************

-- drop table ORDERHEAD;
-- drop table ORDERLINE;
-- drop table ORDERLINEOPT;
-- commit work;

create table ORDERHEAD (
	orderid			varchar(32) not null,
	zone			integer,
	subz			integer,
	shipid			integer,
	tstamp			integer not null,
	trans1			varchar(16),
	aid				char(16),
	purchid			integer,
	complete		integer not null,
	couponid		varchar(32),
	pstotal			decimal(10,2),
	mstotal			decimal(10,2),
	discount		decimal(6,2),
	shamt			decimal(6,2),
	nstax			decimal(6,2),
	tstax			decimal(6,2),
	ostotal			decimal(10,2),
	contrib			decimal(6,2),
	ototal			decimal(10,2),
	ohcheck			integer,
	ohchkacct		varchar(16),
	ohabaroute		char(9),
	cmt1			varchar(80),
	cmt2			varchar(80),
	cmt3			varchar(80),
	cmt4			varchar(80),
	cmt5			varchar(80),
	scity			varchar(64),
	sstate			varchar(64),
	szip			varchar(16),
	scountry		varchar(5),
	payinv			varchar(255),
	oheadgift		integer,
	oheadssal		varchar(20),
	oheadsfname		varchar(80),
	oheadsmname		varchar(80),
	oheadslname		varchar(80),
	oheadscompany	varchar(80),
	oheadsemail		varchar(80),
	oheadsaddr1		varchar(80),
	oheadsaddr2		varchar(80),
	oheadscity		varchar(80),
	oheadsstate		varchar(80),
	oheadszip		varchar(10),
	oheadszip4		varchar(10),
	oheadsnatl		varchar(80),
	oheadsacode		varchar(80),
	oheadsphone		varchar(80),
	oheadsfacode	varchar(80),
	oheadsfax		varchar(80),
	oheadccname		varchar(80),
	oheadccnumber	varchar(80),
	oheadcctype		varchar(32),
	oheadccexpmo	char(2),
	oheadccexpyr	char(4),
	oheadcccvv		integer,
	oheadcustip		varchar(32)
);

create table ORDERLINE (
	olid	integer,
	orderid	varchar(32) not null,
	olzone	integer,
	ollang	integer,
	sku		varchar(20) not null,
	compsku	varchar(128) not null,
	qty		integer,
	invover	integer,
	olprice	decimal(10,2),
	olesd	integer
);

create table ORDERLINEOPT (
	orderid	varchar(32) not null,
	olzone	integer,
	ollang	integer,
	sku		varchar(20) not null,
	compsku	varchar(128) not null,
	poptid	integer not null,
	qty		integer
);
commit work;

create index ohord_INDEXID on ORDERHEAD (orderid);
create index ohtim_INDEXID on ORDERHEAD (tstamp);

create index olord_INDEXID on ORDERLINE (orderid);
create index olsku_INDEXID on ORDERLINE (sku);
create index olcsku_INDEXID on ORDERLINE (compsku);
create index olsnum_INDEXID on ORDERLINE (olesd);
create index olzone_INDEXID on ORDERLINE (olzone);
create index ollang_INDEXID on ORDERLINE (ollang);

create index otord_INDEXID on ORDERLINEOPT (orderid);
create index otsku_INDEXID on ORDERLINEOPT (sku);
create index otcsku_INDEXID on ORDERLINEOPT (compsku);
create index otpopt_INDEXID on ORDERLINEOPT (poptid);
create index otzone_INDEXID on ORDERLINEOPT (olzone);
create index otlang_INDEXID on ORDERLINEOPT (ollang);
commit work;

-- grant all on ORDERHEAD to USERID;
-- grant all on ORDERLINE to USERID;
-- grant all on ORDERLINEOPT to USERID;

-- grant all on ORDERHEAD to ADMID;
-- grant all on ORDERLINE to ADMID;
-- grant all on ORDERLINEOPT to ADMID;
-- commit work;

-- ********** Aid Table *************
--  This is the table for Associate IDs.

-- drop table ASSOCIATETABLE;
-- commit work;

create table ASSOCIATETABLE (
	ascid		integer default '0' not null,
	asczid		integer not null,
	asclid		integer not null,
	ascwebid	integer not null,
	ascdesc		varchar(255),
	ascname		varchar(80),
	ascaddr1	varchar(80),
	ascaddr2	varchar(80),
	asccity		varchar(80),
	ascstate	varchar(40),
	asczip		varchar(20),
	ascnatl		varchar(20),
	ascphone	varchar(40),
	ascfax		varchar(40),
	ascemail	varchar(80),
	ascsvcname	varchar(80),
	ascsvcaddr1	varchar(80),
	ascsvcaddr2	varchar(80),
	ascsvccity	varchar(80),
	ascsvcstate	varchar(40),
	ascsvczip	varchar(20),
	ascsvcnatl	varchar(20),
	ascsvcphone	varchar(40),
	ascsvcfax	varchar(40),
	ascsvcemail	varchar(80),
	asconline	varchar(80),
	ascofline	varchar(80),
	ascoemail	varchar(80),
	ascconfirm	varchar(80),
	ascactive	integer
);
commit work;

create index asc_INDEXID on ASSOCIATETABLE (ascid);
create index ascz_INDEXID on ASSOCIATETABLE (asczid);
create index ascl_INDEXID on ASSOCIATETABLE (asclid);
create index ascw_INDEXID on ASSOCIATETABLE (ascwebid);
commit work;

-- drop sequence asc_SEQID;
-- commit work;

create sequence asc_SEQID;
commit work;

create trigger ascid_autoid_SEQID
before insert on ASSOCIATETABLE for each row
begin
	select asc_SEQID.nextval
	into :new.ascid
	from dual; end;
/
commit work;

-- grant select,insert on ASSOCIATETABLE to USERID;
-- grant all on ASSOCIATETABLE to ADMID;
-- commit work;

-- ************** ACCESS LOG TABLE *****************

-- drop table ACCTABLE;
-- commit work;

create table ACCTABLE (
	accesshost  varchar(40),
	accessip    varchar(16) not null,
	accesstime  integer,
	accesscnt   integer
);
commit work;

create index acc_INDEXID on ACCTABLE (accessip);
commit work;

-- grant insert,select,update on ACCTABLE to USERID;
-- grant all on ACCTABLE to ADMID;
-- commit work;

-- ************** KEYWORD LOG TABLE *****************

-- drop table KEYTABLE;
-- commit work;

create table KEYTABLE (
	keyval  varchar(16) not null,
	keycnt  integer,
	keyres  integer
);
commit work;

create index key_INDEXID on KEYTABLE (keyval);
commit work;

-- grant select,insert,update on KEYTABLE to USERID;
-- grant all on KEYTABLE to ADMID;
-- commit work;

-- ************** LANGUAGE TABLE *****************
--
-- The language chosen is dependent on the zone,
-- as different descriptions may be needed.

-- drop table LANGTABLE;
-- commit work;

create table LANGTABLE ( 
	langid    integer default '0' not null,
	langzid   integer not null,
	langdef   integer,
	-- ISO 639/2 code
	langiso   char(3),
	langdescr varchar(40),
	langtmpl  varchar(40),
	langtdsp  varchar(40),
	langterr  varchar(40),
	langshow  varchar(40),
	langgeo   varchar(40),
	langordr  varchar(40),
	langproc  varchar(40),
	langfinl  varchar(40),
	langstmpl varchar(40),
	-- front page welcome text
	langwelcome   varchar(4000),
	-- copyright, footer text
	langcopy      varchar(4000),
	-- terms, conditions, etc
	langterms     clob,
	-- front page promo cat for this lang
	langfppromo integer
);
commit work;

create index lang_INDEXID on LANGTABLE (langid);
create index langz_INDEXID on LANGTABLE (langzid);
commit work;

-- drop sequence lang_SEQID;
-- commit work;

create sequence lang_SEQID;
commit work;

create trigger langid_autoid_SEQID
before insert on LANGTABLE for each row
begin
	select lang_SEQID.nextval
	into :new.langid
	from dual; end;
/
commit work;

-- grant select on LANGTABLE to USERID;
-- grant all on LANGTABLE to ADMID;
-- grant select,alter on lang_SEQID to ADMID;
-- commit work;

insert into LANGTABLE
    (langzid,langiso,langdescr,langtmpl,langtdsp,langterr,langshow,
     langgeo,langordr,langproc,langfinl,langstmpl,langwelcome,langcopy,
	 langterms,langfppromo)
    values
    (1,'LANGISO','LANGNAME','','','','showcart.php','showgeo.php',
     'orderform.php','orderproc.php','orderfinal.php','',
	 'Welcome to FishCart, the premier open source multinational ecommerce system.  More information about FishCart can be found at fishcart.org.<p><i>This text can be edited in the Language profile in the FishCart administration area.</i>',
	 '<i>Edit this copyright text in the language profile.</i>',
	 '<i>Edit this terms and conditions text in the language profile.</i>',1
	 );
commit work;

-- ************** CATEGORY TABLE *****************
--
-- The category always follows the subzone, as the
-- graphics will be different for each language.

-- drop table CATTABLE;
-- commit work;

create table CATTABLE ( 
	catval   integer default '0' not null,
	catzid   integer not null,
	catlid   integer not null,
	catlogo  varchar(50),
	catlogoh integer,
	catlogow integer,
    catbutton  varchar(50),
	catbuttonh integer,
	catbuttonw integer,
	catdescr varchar(80),
	catback  varchar(50),
	catbg    char(6),
	catlink  char(6),
	catvlink char(6),
	catalink char(6),
	catbanr  varchar(50),
	catbanrh integer,
	catbanrw integer,
	catsku   varchar(20),
	cattext  varchar(255),
	catmast  varchar(4000),
	cattmpl  varchar(40),
	catsort  varchar(40),
	catfree  varchar(80),
	catunder integer not null,
	catpath varchar(250) not null,
	catact   integer not null,
	catprodpage integer,
	catcols  integer,
	catseq  integer
);
commit work;

create index cat_INDEXID on CATTABLE (catval);
create index catl_INDEXID on CATTABLE (catlid);
create index catz_INDEXID on CATTABLE (catzid);
commit work;

-- drop sequence cat_SEQID;
-- commit work;

create sequence cat_SEQID;
commit work;

create trigger catval_autoid_SEQID
before insert on CATTABLE for each row
begin
	select cat_SEQID.nextval
	into :new.catval
	from dual; end;
/
commit work;

-- grant select on CATTABLE to USERID;
-- grant all on CATTABLE to ADMID;
-- grant select,alter on cat_SEQID to ADMID;
-- commit work;

insert into CATTABLE
        (catval,catzid, catlid, catlogo, catlogoh, catlogow, 
         catbutton, catbuttonh, catbuttonw, catdescr, catback,
         catbg, catlink, catvlink, catalink, catbanr, catbanrh, catbanrw,
         catsku, cattext, catmast, cattmpl, catsort, catfree,
         catact, catunder, catpath, catprodpage,catcols,catseq)
        values 
        (1,1,1,'',0,0,'',0,0,'Front Page Promotions','','','','','','',0,0,'','','','','prodsku','',0,0,':1:',5,1,0);
insert into CATTABLE
        (catval,catzid, catlid, catlogo, catlogoh, catlogow, 
         catbutton, catbuttonh, catbuttonw, catdescr, catback,
         catbg, catlink, catvlink, catalink, catbanr, catbanrh, catbanrw,
         catsku, cattext, catmast, cattmpl, catsort, catfree,
         catact, catunder, catpath, catprodpage,catcols,catseq)
        values 
        (2,1,1,'',0,0,'',0,0,'Suggested Items','','','','','','',0,0,'','','','','prodsku','',0,0,':2:',5,1,0);
insert into CATTABLE
        (catval,catzid, catlid, catlogo, catlogoh, catlogow, 
         catbutton, catbuttonh, catbuttonw, catdescr, catback,
         catbg, catlink, catvlink, catalink, catbanr, catbanrh, catbanrw,
         catsku, cattext, catmast, cattmpl, catsort, catfree,
         catact, catunder, catpath, catprodpage,catcols,catseq)
        values 
        (3,1,1,'',0,0,'',0,0,'Default Category','','','','','','',0,0,'','','','','prodsku','',1,0,':3:',5,1,0);
commit work;

-- ************** PRODUCT TABLE *****************

-- When creating a new product, always create the default.
-- Then in modify, give the option to make a new zoned product.

-- drop table PRODTABLE;
-- commit work;

create table PRODTABLE (
	prodzid      integer not null,
	prodsku      varchar(20) not null,
	prodsetup    decimal(10,2),
	prodstsalebeg  integer not null,
	prodstsaleend  integer not null,
	prodstsaleprice decimal(10,2),
	prodprice    decimal(10,2),
	prodrtlprice decimal(10,2),
	prodstart    integer not null,
	prodstop     integer not null,
	prodsalebeg  integer not null,
	prodsaleend  integer not null,
	prodsaleprice decimal(10,2),
	prodmcode    varchar(40),
	prodwidth    decimal(5,2),
	prodheight   decimal(5,2),
	prodlength   decimal(5,2),
	prodweight   decimal(10,4),
	prodseq      integer,
	produseinvq  integer not null,
	prodinvqty   integer not null,
	prodordmax   integer,
	prodflag1    integer,
	prodcharge   integer,
	prodisbn     char(20),
	prodpromo    integer,
	prodversion  varchar(32),
	prodactcod   varchar(64),
	prodserpfx   varchar(8),
	prodsernum   integer,
	prodserhrs   integer,
	prodsermax   integer,
	prodserfil   varchar(255),
	prodvat      decimal(3,2)
);
commit work;

create unique index prod_INDEXID  on PRODTABLE (prodsku);
create index prodz_INDEXID on PRODTABLE (prodzid);
create index prodinv_INDEXID on PRODTABLE (prodinvqty);
create index produseinv_INDEXID on PRODTABLE (produseinvq);
create index prodpromo_INDEXID on PRODTABLE (prodpromo);
create index prodstart_INDEXID on PRODTABLE (prodstart);
create index prodstop_INDEXID on PRODTABLE (prodstop);
create index prodsalebeg_INDEXID on PRODTABLE (prodsalebeg);
create index prodsaleend_INDEXID on PRODTABLE (prodsaleend);
commit work;

-- grant select,update on PRODTABLE to USERID;
-- grant all on PRODTABLE to ADMID;
-- commit work;

-- ************** PRODUCT LANGUAGE TABLE *****************

-- drop table PRODLANG;
-- commit work;

create table PRODLANG (
	prodlid      integer not null,
	prodlzid     integer not null,
	prodlsku     varchar(20) not null,
	prodpic      varchar(128),
	prodpich     integer,
	prodpicw     integer,
	prodtpic     varchar(128),
	prodtpich    integer,
	prodtpicw    integer,
	prodbanr     varchar(128),
	prodbanrh    integer,
	prodbanrw    integer,
	prodaudio    varchar(128),
	prodvideo    varchar(128),
	prodsplash   varchar(128),
	prodkeywords varchar(255),
	prodname     varchar(255),
	prodsdescr   varchar(255),
	proddescr    varchar(4000),
	installinst  varchar(4000),
	prodoffer    varchar(128),
	prodstyle    varchar(128),
	proddload    varchar(128),
	prodauth     varchar(128),
	prodauthurl  varchar(128),
	prodtitleurl varchar(128),
	prodleadtime varchar(128),
	prodpersvc   varchar(32),
    prodlflag1   integer
);
commit work;

create index plng_INDEXID  on PRODLANG (prodlid);
create index plngz_INDEXID on PRODLANG (prodlzid);
create index plngs_INDEXID on PRODLANG (prodlsku);
commit work;

-- grant select on PRODLANG to USERID;
-- grant all on PRODLANG to ADMID;
-- commit work;

-- ************** PRODUCT OPTION TABLE *****************

-- drop table PRODOPT;
-- drop table PRODOPTGRPNAME;
-- commit work;

create table PRODOPT (
	poptid       integer default '0' not null,
	poptzid      integer not null,
	poptlid      integer not null,
	poptgrp      integer not null,
	poptseq      integer not null,
	poptsku      varchar(20) not null,
	poptskumod   varchar(128),
	poptskusub   varchar(128),
	poptsetup    decimal(10,2),
	poptprice    decimal(10,2),
	poptssalebeg integer not null,
	poptssaleend integer not null,
	poptssaleprice decimal(10,2),
	poptsalebeg  integer not null,
	poptsaleend  integer not null,
	poptsaleprice decimal(10,2),
	poptpic      varchar(128),
	poptpich     integer,
	poptpicw     integer,
	popttpic     varchar(128),
	popttpich    integer,
	popttpicw    integer,
	poptname     varchar(128),
	poptsdescr   varchar(255),
	poptdescr    varchar(4000),
	popttext1    varchar(255),
	popttext2    varchar(255),
	popttext3    varchar(255),
	poptflag1    integer,
	poptflag2    integer
);

create table PRODOPTGRPNAME (
	pgrpzid      integer not null,
	pgrplid      integer not null,
	pgrpgrp      integer not null,
	pgrpname     varchar(128)
);
commit work;

create index poptk_INDEXID on PRODOPT (poptsku);
create index popt_INDEXID  on PRODOPT (poptid);
create index poptg_INDEXID on PRODOPT (poptgrp);
create index poptz_INDEXID on PRODOPT (poptzid);
create index poptl_INDEXID on PRODOPT (poptlid);
create index popts_INDEXID on PRODOPT (poptseq);

create index pgrpz_INDEXID on PRODOPTGRPNAME (pgrpzid);
create index pgrpl_INDEXID on PRODOPTGRPNAME (pgrplid);
create index pgrpg_INDEXID on PRODOPTGRPNAME (pgrpgrp);
commit work;

-- drop sequence popt_SEQID;
-- commit work;

create sequence popt_SEQID;
commit work;

create trigger poptid_autoid_SEQID
before insert on PRODOPT for each row
begin
	select popt_SEQID.nextval
	into :new.poptid
	from dual; end;
/
commit work;

-- grant select,insert,update,delete on PRODOPT to USERID;
-- grant all on PRODOPT to ADMID;
-- grant select,alter on popt_SEQID to ADMID;
-- grant select,insert,update,delete on PRODOPTGRPNAME to USERID;
-- grant all on PRODOPTGRPNAME to ADMID;
-- commit work;

-- ********** PRODUCT/CATEGORY TABLE **************

-- This table has to be fully filled in with existing 
-- cat/prod/zone options to keep searches quick when
-- displaying the shopping tables.

-- When creating a new zone, this table must be filled
-- in with entries for every category / default product.

-- drop table PRODCATTABLE;
-- commit work;

create table PRODCATTABLE (
  pcatval  integer not null,
  pcatsku  varchar(20) not null,
  pcatzid  integer not null,
  pcatseq  integer
);
commit work;

create index pcat_INDEXID  on PRODCATTABLE (pcatval);
create index pcatk_INDEXID on PRODCATTABLE (pcatsku);
create index pcatz_INDEXID on PRODCATTABLE (pcatzid);
commit work;

-- grant select,insert,update on PRODCATTABLE to USERID;
-- grant all on PRODCATTABLE to ADMID;
-- commit work;

-- ************** NEW PRODUCTS TABLE *****************

-- A zone ID of 0 is the mark of a 'default' product to span
-- all zones.

-- When creating a new product, always create the default.
-- Then in modify, give the option to make a new zoned product.

-- drop table NPRODTABLE;
-- commit work;

create table NPRODTABLE (
	nprodsku varchar(20) not null,
	nstart   integer,
	nend     integer,
	nzid     integer not null
);
commit work;

create unique index nprod_INDEXID  on NPRODTABLE (nprodsku);
create index nprodz_INDEXID on NPRODTABLE (nzid);
commit work;

-- grant select on NPRODTABLE to USERID;
-- grant all on NPRODTABLE to ADMID;
-- commit work;

-- ************** SPECIAL PRODUCTS TABLE *****************

-- A zone ID of 0 is the mark of a 'default' product to span
-- all zones.

-- When creating a new product, always create the default.
-- Then in modify, give the option to make a new zoned product.


-- drop table OPRODTABLE;
-- commit work;

create table OPRODTABLE (
	oprodsku varchar(20) not null,
	ostart   integer,
	oend     integer,
	ozid     integer
);
commit work;

create unique index oprod_INDEXID  on OPRODTABLE (oprodsku);
create index oprodz_INDEXID on OPRODTABLE (ozid);
commit work;

-- grant select on OPRODTABLE to USERID;
-- grant all on OPRODTABLE to ADMID;
-- commit work;

-- ************** RELATED PRODUCT TABLE *****************
-- 
-- A table to identify related products; it carries the
-- primary product, the related product, and an order by
-- sequence number

-- drop table PRODREL;
-- commit work;

create table PRODREL (
	relzone      integer,
	relsku       varchar(20) not null,
	relprod      varchar(20) not null,
	relseq       integer
);
commit work;

create index relsku_INDEXID  on PRODREL (relsku);
create index relprod_INDEXID on PRODREL (relprod);
create index relzone_INDEXID on PRODREL (relzone);
commit work;

-- grant select on PRODREL to USERID;
-- grant all    on PRODREL to ADMID;
-- commit work;

-- ************** ZONE TABLE *****************
--
-- The zone table covers an area in which the
-- currency is always the same.  Products must
-- be tied to a currency, as the product price
-- will be shown in the currency of the zone.
--
-- The subzones cover different areas under
-- the zone, for different tax/shipping rates;
-- the language specific product pages are also
-- selected by the subzone code.

-- drop table ZONETABLE;
-- drop table SUBZONETABLE;
-- commit work;

create table ZONETABLE (
	zoneid        integer default '0' not null,
	zonedescr     varchar(80),
	zonecurrsym   varchar(16),
	zoneact       integer,
	zflag1        integer,
	zonedeflid    integer
);
commit work;

create table SUBZONETABLE (
	subzid			integer not null,
	subzsid			integer default '0' not null,
	subzdescr		varchar(80),
	subztaxpern		decimal(6,4),
	subztaxpers		decimal(6,4),
	subztaxcmtn		varchar(128),
	subztaxcmts		varchar(128),
	subzvendid		integer,
	subzwhsid		integer,
	subzflag0		integer,
	subzvat			integer not null,
	subzzip			integer,
	subzcity		integer,
	subzstate		integer,
	subzcntry		integer,
	subzparent		integer,
	subzseq			integer
);
commit work;

create index zone_INDEXID    on ZONETABLE (zoneid);
create index szvat_INDEXID   on SUBZONETABLE (subzvat);
create index szone_INDEXID   on SUBZONETABLE (subzid);
create index szones_INDEXID  on SUBZONETABLE (subzsid);
create index szonep_INDEXID  on SUBZONETABLE (subzparent);
commit work;

-- drop sequence  zone_SEQID;
-- drop sequence  subzone_SEQID;
-- commit work;

create sequence zone_SEQID;
create sequence subzone_SEQID;
commit work;

create trigger zoneid_autoid_SEQID
before insert on ZONETABLE for each row
begin
	select zone_SEQID.nextval
	into :new.zoneid
	from dual; end;
/

create trigger subzsid_autoid_SEQID
before insert on SUBZONETABLE for each row
begin
	select subzone_SEQID.nextval
	into :new.subzsid
	from dual; end;
/
commit work;

-- grant select on ZONETABLE    to USERID;
-- grant all    on ZONETABLE    to ADMID;
-- grant select on SUBZONETABLE to USERID;
-- grant all    on SUBZONETABLE to ADMID;
-- grant select,update on zone_SEQID    to ADMID;
-- grant select,update on subzone_SEQID to ADMID;
-- commit work;

insert into ZONETABLE values (1, 'COMPANY', '$', 1, 17, 1); 

insert into SUBZONETABLE
 (subzid,subzdescr,subztaxpern,subztaxpers,
  subztaxcmtn,subztaxcmts,subzvendid,subzwhsid,subzflag0,
  subzvat,subzzip,subzcity,subzstate,subzcntry,subzparent,subzseq)
 values (1,'Default Subzone', 0.0, 0.0, '', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0); 
commit work;

-- ************** VENDOR INFO TABLE *****************

-- drop table VENDORTABLE;
-- commit work;

create table VENDORTABLE (
	vendid     integer default '0' not null,
	vendzid    integer not null,
	vendname   varchar(80),
	vendaddr1  varchar(80),
	vendaddr2  varchar(80),
	vendcity   varchar(80),
	vendstate  varchar(80),
	vendzip    varchar(20),
	vendnatl   varchar(80),
	vendphone  varchar(80),
	vendfax    varchar(80),
	vendemail  varchar(80),
	vsvcname   varchar(80),
	vsvcaddr1  varchar(80),
	vsvcaddr2  varchar(80),
	vsvccity   varchar(80),
	vsvcstate  varchar(80),
	vsvczip    varchar(20),
	vsvcnatl   varchar(80),
	vsvcphone  varchar(80),
	vsvcfax    varchar(80),
	vsvcemail  varchar(80),
	vendonline varchar(80),
	vendofline varchar(80),
	vendoemail varchar(80),
	vendconfirm varchar(80),
	vflag1     integer
);
commit work;

create index vend_INDEXID  on VENDORTABLE (vendid);
create index vendz_INDEXID on VENDORTABLE (vendzid);
commit work;

-- drop sequence vendor_SEQID;
-- commit work;

create sequence vendor_SEQID;
commit work;

create trigger vendid_autoid_SEQID
before insert on VENDORTABLE for each row
begin
	select vendor_SEQID.nextval
	into :new.vendid
	from dual; end;
/
commit work;

-- grant select on VENDORTABLE to USERID;
-- grant all on VENDORTABLE to ADMID;
-- grant select,update on vendor_SEQID to ADMID;
-- commit work;

insert into VENDORTABLE
        (vendzid, vendname, vendaddr1, vendaddr2, vendcity, vendstate, vendzip,
         vendnatl, vendphone, vendfax, vendemail,
         vsvcname, vsvcaddr1, vsvcaddr2, vsvccity, vsvcstate, vsvczip,
         vsvcnatl, vsvcphone, vsvcfax, vsvcemail,
         vendonline, vendofline, vendoemail, vendconfirm, vflag1)
        values
        (1,'COMPANY','','','','','','','','','ORDERCONF',
           'COMPANY','','','','','','','','','ORDERCONF',
           'emailorder.php','offline.php','ORDEREMAIL','emailconfirm.php',0);
commit work;

-- ************** WEB TABLE *****************

-- drop table WEBTABLE;
-- commit work;

create table WEBTABLE (
	webid        integer default '0' not null,
	webzid       integer not null,
	weblid       integer not null,
	webdescr     varchar(80),
	realhome     varchar(80),
	carthome     varchar(80),
	webtitle     varchar(80),
	webback      varchar(80),
	weblogo      varchar(80),
	weblogow     integer,
	weblogoh     integer,
	webbg        char(6),
	webtext      char(6),
	weblink      char(6),
	webvlink     char(6),
	webalink     char(6),
	webhdsku     varchar(20),
	webhdtext    varchar(255),
	webhdgraph   varchar(80),
	webhdgraphw  integer,
	webhdgraphh  integer,
	webftsku     varchar(20),
	webfttext    varchar(255),
	webftgraph   varchar(80),
	webftgraphw  integer,
	webftgraphh  integer,
	webdaysinnew integer,
	webnewlogo   varchar(80),
	webnewlogow  integer,
	webnewlogoh  integer,
	webnewmast   varchar(80),
	webnewmastw  integer,
	webnewmasth  integer,
	webspeclogo  varchar(80),
	webspeclogow integer,
	webspeclogoh integer,
	webspecmast  varchar(80),
	webspecmastw integer,
	webspecmasth integer,
	webviewlogo  varchar(80),
	webviewlogow integer,
	webviewlogoh integer,
	webcattext   varchar(255),
	webautodom   char,
	websort      varchar(16),
	webfree      varchar(80),
	webdesctmpl  varchar(4000),
	webflags1    integer,
	webprodpage  integer
);
commit work;

create index web_INDEXID  on WEBTABLE (webid);
create index webl_INDEXID on WEBTABLE (weblid);
create index webz_INDEXID on WEBTABLE (webzid);
commit work;

-- drop sequence web_SEQID;
-- commit work;

create sequence web_SEQID;
commit work;

create trigger webid_autoid_SEQID
before insert on WEBTABLE for each row
begin
	select web_SEQID.nextval
	into :new.webid
	from dual; end;
/
commit work;


-- grant select,insert,update on WEBTABLE to USERID;
-- grant all on WEBTABLE to ADMID;
-- grant select,update on web_SEQID to ADMID;
-- commit work;

insert into WEBTABLE
   (webzid, weblid, webdescr, realhome, carthome, webtitle, webback,
	weblogo, weblogow, weblogoh,
	webbg, webtext, weblink, webvlink, webalink,
   	webhdsku, webhdtext, webhdgraph, webhdgraphw, webhdgraphh,
   	webftsku, webfttext, webftgraph, webftgraphw, webftgraphh, webdaysinnew,
	webnewlogo, webnewlogow, webnewlogoh,
	webnewmast, webnewmastw, webnewmasth,
	webspeclogo, webspeclogow, webspeclogoh,
	webspecmast, webspecmastw, webspecmasth,
	webviewlogo, webviewlogow, webviewlogoh, webcattext,
	webautodom, websort, webfree, webdesctmpl, webflags1,
	webprodpage)
	values
   (1,1,'COMPANY','HOMEURL','CATALOGURL','COMPANY','BACKGRAPHIC',
	'MASTGRAPHIC',0,0,
	'','','','','',
	'','','',0,0,
	'','','',0,0,30,
	'images/new_btn.gif',130,20,
	'images/new_btn.gif',130,20,
	'images/special_bnt.gif',130,20,
	'images/special_btn.gif',130,20,
	'images/view_btn.gif',130,20,'',
	'','prodsku','<b><i><font color="#ff0000">Free!</font></i></b>','',3,5
	);
commit work;

-- *********** SHIPPING TABLE(S) *************
-- drop table SHIPTABLE;
-- drop table SHIPTHRESH;
-- drop table WEIGHTTHRESH;
-- drop table SUBZSHIPTABLE;
-- commit work;

create table SHIPTABLE (
	shipid        integer default '0' not null,
	shipzid       integer not null,
	shiplid       integer not null,
	shipdescr     varchar(80),
	shipmeth      integer,
	shippercent   decimal(4,4),
	shipitem      decimal(10,4),
	shipitem2     decimal(10,4),
	shipcalc      varchar(40),
	shipadd       varchar(40),
	shipmaint     varchar(40),
	shipupdate    varchar(40),
	shipaux1      varchar(40),
	shipaux2      varchar(40),
	shipsvccode   varchar(20),
	active	      integer
);

create table SHIPTHRESH (
	shipid        integer default '0' not null,
	shipzid       integer not null,
	shipszid      integer not null,
	shiplid       integer not null,
	shipseq       integer,
	shipamt       decimal(10,2),
	shiplow       decimal(10,2),
	shiphigh      decimal(10,2)
);

create table WEIGHTTHRESH (
	shipid        integer default '0' not null,
	shipzid       integer not null,
	shipszid      integer not null,
	shiplid       integer not null,
	shipseq       integer,
	shipamt       decimal(10,2),
	shiplow       decimal(10,2),
	shiphigh      decimal(10,2)
);

create table SUBZSHIPTABLE (
	shipszid	integer not null,
	shipid      integer not null,
	shipdef     integer,
	shiplid     integer
);
commit work;

create index ship_INDEXID  on SHIPTABLE (shipid);
create index shipl_INDEXID on SHIPTABLE (shiplid);
create index shipz_INDEXID on SHIPTABLE (shipzid);

create index shiptid_INDEXID on SHIPTHRESH (shipid);
create index shiptzid_INDEXID on SHIPTHRESH (shipzid);
create index shiptlid_INDEXID on SHIPTHRESH (shiplid);
create index shiptszid_INDEXID on SHIPTHRESH (shipszid);

create index shipwid_INDEXID on WEIGHTTHRESH (shipid);
create index shipwzid_INDEXID on WEIGHTTHRESH (shipzid);
create index shipwlid_INDEXID on WEIGHTTHRESH (shiplid);
create index shipwszid_INDEXID on WEIGHTTHRESH (shipszid);

create index shipsszid_INDEXID on SUBZSHIPTABLE (shipszid);
create index shipsslid_INDEXID on SUBZSHIPTABLE (shiplid);
create index shipssid_INDEXID on SUBZSHIPTABLE (shipid);
commit work;

-- drop sequence ship_SEQID;
-- drop sequence shipthresh_SEQID;
-- drop sequence weightthresh_SEQID;
-- commit work;

create sequence ship_SEQID;
create sequence shipthresh_SEQID;
create sequence weightthresh_SEQID;
commit work;

create trigger shipid_autoid_SEQID
before insert on SHIPTABLE for each row
begin
	select ship_SEQID.nextval
	into :new.shipid
	from dual; end;
/

create trigger ship_autoid_SEQID
before insert on SHIPTHRESH for each row
begin
	select shipthresh_SEQID.nextval
	into :new.shipid
	from dual; end;
/

create trigger weight_autoid_SEQID
before insert on WEIGHTTHRESH for each row
begin
	select weightthresh_SEQID.nextval
	into :new.shipid
	from dual; end;
/
commit work;

-- grant select on SHIPTABLE to USERID;
-- grant select on SHIPTHRESH to USERID;
-- grant select on SUBZSHIPTABLE to USERID;
-- grant select on WEIGHTTHRESH to USERID;
-- grant all on SHIPTABLE to ADMID;
-- grant all on SHIPTHRESH to ADMID;
-- grant all on WEIGHTTHRESH to ADMID;
-- grant select on SUBZSHIPTABLE to ADMID;
-- grant select,alter on ship_SEQID to ADMID;
-- grant select,alter on shipthresh_SEQID to ADMID;
-- grant select,alter on weightthresh_SEQID to ADMID;
-- commit work;

insert into SHIPTHRESH
	(shipid, shipzid, shipszid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,1,0,3.00,0.0,19.99);
insert into SHIPTHRESH
	(shipid, shipzid, shipszid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,1,1,0.05,20.0,10000000.);

insert into WEIGHTTHRESH
	(shipid, shipzid, shipszid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,1,0,3.00,0.0,19.99);
insert into WEIGHTTHRESH
	(shipid, shipzid, shipszid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,1,1,0.05,20.0,10000000.);
insert into SUBZSHIPTABLE (shipszid,shipid,shipdef,shiplid) values (1,1,1,1);
commit work;

-- *********** CUSTOMER TABLE *************

-- drop table CUSTTABLE;
-- commit work;

create table CUSTTABLE (
	custid         integer not null,
	custbsal       varchar(20),
	custbfname     varchar(80),
	custbmname     varchar(80),
	custblname     varchar(80),
	custbcompany   varchar(80),
	custbemail     varchar(80) not null,
	custbaddr1     varchar(80),
	custbaddr2     varchar(80),
	custbcity      varchar(80),
	custbstate     varchar(80),
	custbzip       varchar(10),
	custbzip4      varchar(10),
	custbnatl      varchar(80),
	custbacode     varchar(80),
	custbphone     varchar(80),
	custbfacode    varchar(80),
	custbfax       varchar(80),
	custssal       varchar(20),
	custsfname     varchar(80),
	custsmname     varchar(80),
	custslname     varchar(80),
	custscompany   varchar(80),
	custsemail     varchar(80),
	custsaddr1     varchar(80),
	custsaddr2     varchar(80),
	custscity      varchar(80),
	custsstate     varchar(80),
	custszip       varchar(10),
	custszip4      varchar(10),
	custsnatl      varchar(80),
	custsacode     varchar(80),
	custsphone     varchar(80),
	custsfacode    varchar(80),
	custsfax       varchar(80),
	custccname     varchar(80),
	custccnumber   varchar(80),
	custcctype     varchar(32),
	custccexpmo    char(2),
	custccexpyr    char(4),
	custcccvv      integer,
	custototal     integer,
	custbtotal     decimal(12,2),
	custloamt      decimal(12,2),
	custlodate     integer,
	custfodate     integer,
	custpromoemail integer
);
commit work;

create index custid_INDEXID on CUSTTABLE (custid);
create index cbemail_INDEXID on CUSTTABLE (custbemail);
create index cbpromo_INDEXID on CUSTTABLE (custpromoemail);
commit work;

-- grant select,insert,update on CUSTTABLE to USERID;
-- grant all on CUSTTABLE to ADMID;
-- commit work;

-- ********** Order Summary Table *************
--
-- This must be run only once at initial FishCart installation

-- drop table INSTALLID_ccnums;
-- drop table INSTALLID_users;
-- commit work;

create table INSTALLID_ccnums (
	userid     integer not null,
	tstamp     integer not null,
	fetched    char(1),
	orderid    char(32),
	cc6        char(6)
	);

create table INSTALLID_users (
	userid     integer default '0' not null,
	username   char(16),
	fullname   char(50)
	);
commit work;

create index INSTALLID_ccindex on INSTALLID_ccnums (userid,tstamp);
create index INSTALLID_uindex on INSTALLID_users (userid,username);
create unique index INSTALLID_ccuserid on INSTALLID_users (userid);
commit work;

-- drop sequence cc_SEQID;
-- commit work;

create sequence cc_SEQID;
commit work;

create trigger cc_autoid_SEQID
before insert on INSTALLID_users for each row
begin
	select cc_SEQID.nextval
	into :new.userid
	from dual; end;
/
commit work;

-- grant all on INSTALLID_ccnums to DBDNAME;
-- grant all on INSTALLID_users to DBDNAME;
-- commit work;

insert into INSTALLID_users
	(userid, username, fullname)
	values
	(1,'INSTALLID','COMPANY');
commit work;

-- ********** Coupon Table *************
--
-- Layout for the FishCart coupon table
--
-- This must be run only once at initial FishCart installation

-- drop table COUPONTABLE;
-- commit work;

create table COUPONTABLE (
	cpnid		varchar(32) not null,
	type		integer,
	cpnsku		varchar(32) not null,
	cpnstart	integer,
	cpnstop		integer,
	cpnminqty	integer,
	cpnminamt	decimal(12,2),
	discount	decimal(12,4),
	cpnredeem   integer,
	cpnmaximum  integer
);
commit work;

create index cpnid_INDEXID on COUPONTABLE (cpnid);
create index cpnsku_INDEXID on COUPONTABLE (cpnsku);
commit work;

-- grant select,update on COUPONTABLE to USERID;
-- grant all on COUPONTABLE to ADMID;
-- commit work;

-- ********** Catalog User Password Table *************
--
-- Layout for the FishCart user administration table
--
-- This must be run only once at initial FishCart installation

-- drop table PASSWORDTABLE;
-- commit work;

create table PASSWORDTABLE (
	pwactive	integer not null,
	pwzone		integer,
	pwjan 		integer,
	pwfeb 		integer,
	pwmar 		integer,
	pwapr 		integer,
	pwmay 		integer,
	pwjun 		integer,
	pwjul 		integer,
	pwaug 		integer,
	pwsep 		integer,
	pwoct 		integer,
	pwnov 		integer,
	pwdec 		integer,
	pwexp		integer,
	pwdescr		varchar(80),
	pwemail		varchar(80),
	pwuid		varchar(32) not null,
	pwpw		varchar(32) not null,
	pwoid		varchar(32)
);
commit work;

create index pwuid_INDEXID on PASSWORDTABLE (pwuid);
create index pwpw_INDEXID  on PASSWORDTABLE (pwpw);
create index pwact_INDEXID  on PASSWORDTABLE (pwactive);
create index pwoid_INDEXID  on PASSWORDTABLE (pwoid);
commit work;

-- grant all on PASSWORDTABLE to ADMID;
-- grant select,insert,update on PASSWORDTABLE to USERID;
-- commit work;

-- ********** Maintenance Password Table *************
--
-- Layout for the FishCart maintenance user administration table
--
-- This must be run only once at initial FishCart installation

-- drop table ADMPASSWORDTABLE;
-- commit work;

create table ADMPASSWORDTABLE (
	admpwactive	integer not null,
	admpwzone	integer,
	pwjan 		integer,
	pwfeb 		integer,
	pwmar 		integer,
	pwapr 		integer,
	pwmay 		integer,
	pwjun 		integer,
	pwjul 		integer,
	pwaug 		integer,
	pwsep 		integer,
	pwoct 		integer,
	pwnov 		integer,
	pwdec 		integer,
	admpwexp	integer,
	admpwdescr	varchar(80),
	admpwemail	varchar(80),
	admpwuid	varchar(32) not null,
	admpwpw		varchar(32) not null
);
commit work;

create index admpwuid_INDEXID on ADMPASSWORDTABLE (admpwuid);
create index admpwpw_INDEXID  on ADMPASSWORDTABLE (admpwpw);
create index admpwact_INDEXID on ADMPASSWORDTABLE (admpwactive);
commit work;

-- grant all on ADMPASSWORDTABLE to ADMID;
-- commit work;

-- ********** ESD Control Table *************
--
-- Layout for the FishCart Electronic Software Delivery (ESD)
-- control table
--
-- This must be run only once at initial FishCart installation

-- drop table ESDTABLE;
-- commit work;

create table ESDTABLE (
	esdid		integer default '0' not null,
	esdact		integer not null,
	esdoid		varchar(32) not null,
	esdolid		integer not null,
	esdpurchid	integer not null,
	esddlact	integer,
	esddlexp	integer not null,
	esddlcnt	integer,
	esddlmax	integer,
	esdversion	varchar(32),
	esdprodnam	varchar(64),
	esdsernum	varchar(64) not null,
	esdactcod	varchar(64),
	esddlfile	varchar(255)
);
commit work;

create unique index esdid_INDEXID on ESDTABLE (esdid);
create unique index esdolid_INDEXID on ESDTABLE (esdolid);
create unique index esdsernum_INDEXID on ESDTABLE (esdsernum);
create unique index esdpurchid_INDEXID on ESDTABLE (esdpurchid);
create        index esdact_INDEXID on ESDTABLE (esdact);
create        index esddlexp_INDEXID on ESDTABLE (esddlexp);
commit work;

-- drop sequence esd_SEQID;
-- commit work;

create sequence esd_SEQID;
commit work;

create trigger esd_autoid_SEQID
before insert on ESDTABLE for each row
begin
	select esd_SEQID.nextval
	into :new.esdid
	from dual; end;
/
commit work;

-- grant select,insert,update on ESDTABLE to USERID;
-- grant all on ESDTABLE to ADMID;
-- commit work;


-- ********** Auxilliary Links Table *************
--
-- Additional links

-- drop table AUXLINKTABLE;
-- commit work;

create table AUXLINKTABLE (
    rid     integer default '0' not null,
	seq     integer,
	loc     integer,
	title   varchar(64),
	url     varchar(255)
);
commit work;

create unique index auxlrid_INDEXID on AUXLINKTABLE (rid);
commit work;

-- drop sequence auxl_SEQID;
-- commit work;

create sequence auxl_SEQID;
commit work;

create trigger auxl_autoid_SEQID
before insert on AUXLINKTABLE for each row
begin
	select auxl_SEQID.nextval
	into :new.rid
	from dual; end;
/
commit work;

-- grant select,insert,update on AUXLINKTABLE to USERID;
-- grant all on AUXLINKTABLE to ADMID;
-- commit work;


-- ********** Auxilliary Text Table *************
--
-- Additional text

-- drop table AUXTXTTABLE;
-- commit work;

create table AUXTXTTABLE (
	rid     integer default '0' not null,
	seq     integer,
	loc     integer,
	title       varchar(64),
	text        varchar(255)
);
commit work;

create unique index auxtrid_INDEXID on AUXTXTTABLE (rid);
commit work;

-- drop sequence auxtext_SEQID;
-- commit work;

create sequence auxtext_SEQID;
commit work;

create trigger auxt_autoid_SEQID
before insert on AUXTXTTABLE for each row
begin
	select auxtext_SEQID.nextval
	into :new.rid
	from dual; end;
/
commit work;

-- grant select,insert,update on AUXTXTTABLE to USERID;
-- grant all on AUXTXTTABLE to ADMID;
-- commit work;

-- ********** Cart ID Sequence Control Table *************
--
-- Layout for the single row cart id sequence number table

-- drop table SEQTABLE;
-- commit work;

create table SEQTABLE (
	cartseq		integer,
	lastday		integer
);
commit work;

-- grant select,insert,update on SEQTABLE to USERID;
-- grant all on SEQTABLE to ADMID;
-- commit work;

insert into SEQTABLE (cartseq,lastday) values (1,0);
commit work;



# ********** Country ISO Code Table *************
#

-- drop table COUNTRYTABLE;
-- drop table COUNTRYLANG;
-- commit work;

create table COUNTRYTABLE (
	ctryzid		integer,
	ctrylid		integer,
	ctryiso		char(3),
	ctryseq		integer,
	ctryactive	integer
);
commit work;

create table COUNTRYLANG (
	ctrylangliso	char(3),
	ctrylangciso	char(3),
	ctrylangciso2	char(2),
	ctrylangname	varchar(64)
);
commit work;

grant select on COUNTRYTABLE to USERID;
grant all on COUNTRYTABLE to ADMID;
grant select on COUNTRYLANG to USERID;
grant all on COUNTRYLANG to ADMID;
commit work;

create index ctryzid_INDEXID on COUNTRYTABLE (ctryzid);
create index ctrylid_INDEXID on COUNTRYTABLE (ctrylid);
create index ctryiso_INDEXID on COUNTRYTABLE (ctryiso);
create index ctryactive_INDEXID on COUNTRYTABLE (ctryactive);

create index ctrylangliso_INDEXID on COUNTRYLANG (ctrylangliso);
create index ctrylangciso_INDEXID on COUNTRYLANG (ctrylangciso);
create index ctrylangciso2_INDEXID on COUNTRYLANG (ctrylangciso2);
commit work;

--
-- Country table for French
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ALB','AL','Albania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','DZA','DZ','Algeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ASM','AS','American Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ATG','AG','Antigua And Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ARG','AR','Argentina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ARM','AM','Armenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','AUS','AU','Australia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','AUT','AT','Austria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','AZE','AZ','Azerbaijan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BHR','BH','Bahrain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BLR','BY','Belarus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BEL','BE','Belgium');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BVT','BV','Bouvet Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BRA','BR','Brazil');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BRN','BN','Brunei Darussalam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BGR','BG','Bulgaria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KHM','KH','Cambodia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CMR','CM','Cameroon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CPV','CV','Cape Verde');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CYM','KY','Cayman Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CAF','CF','Central African Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TCD','TD','Chad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CHL','CL','Chile');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CXR','CX','Christmas Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','COM','KM','Comoros');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','COK','CK','Cook Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CIV','CI','Cote D''Ivoire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CZE','CZ','Czech Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','DNK','DK','Denmark');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','DOM','DO','Dominican Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TLS','TL','East Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','EGY','EG','Egypt');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','EST','EE','Estonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ETH','ET','Ethiopia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','FRO','FO','Faroe Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','FRA','FR','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','FXX','FX','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GUF','GF','French Guiana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PYF','PF','French Polynesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ATF','TF','French Southern Territories');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GEO','GE','Georgia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','DEU','DE','Germany');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GRC','GR','Greece');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GRL','GL','Greenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GNB','GW','Guinea Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','HUN','HU','Hungary');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ISL','IS','Iceland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','IDN','ID','Indonesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','IRL','IE','Ireland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ITA','IT','Italy');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','JOR','JO','Jordan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KAZ','KZ','Kazakhstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PRK','KP','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KOR','KR','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KWT','KW','Kuwait');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KGZ','KG','Kyrgyzstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LVA','LV','Latvia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LBN','LB','Lebanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LBR','LR','Liberia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LTU','LT','Lithuania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LUX','LU','Luxembourg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MKD','MK','Macedonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MYS','MY','Malaysia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MDV','MV','Maldives');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MHL','MH','Marshall Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MRT','MR','Mauritania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','FSM','FM','Micronesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MDA','MD','Moldova');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MNG','MN','Mongolia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MAR','MA','Morocco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NAM','NA','Namibia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NLD','NL','Netherlands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ANT','AN','Netherlands Antilles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NCL','NC','New Caledonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NZL','NZ','New Zealand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NFK','NF','Norfolk Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','MNP','MP','Northern Mariana Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','NOR','NO','Norway');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PNG','PG','Papua New Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PHL','PH','Philippines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','POL','PL','Poland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ROU','RO','Romania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','RUS','RU','Russian Federation');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LCA','LC','Saint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','STP','ST','Sao Tome And Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SAU','SA','Saudi Arabia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SCG','CS','Serbia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SYC','SC','Seychelles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SVN','SI','Slovenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SLB','SB','Solomon Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SOM','SO','Somalia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ZAF','ZA','South Africa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ESP','ES','Spain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SHN','SH','Saint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SDN','SD','Sudan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SWE','SE','Sweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','CHE','CH','Switzerland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','SYR','SY','Syrian Arab Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TJK','TJ','Tajikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TTO','TT','Trinidad And Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TUN','TN','Tunisia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TUR','TR','Turkey');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','UGA','UG','Uganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','UKR','UA','Ukraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ARE','AE','United Arab Emirates');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','GBR','GB','United Kingdom');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','USA','US','United States');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','UZB','UZ','Uzbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','VAT','VA','Vatican City State');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','VNM','VN','Viet Nam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','VGB','VG','Virgin Islands (British)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ESH','EH','Western Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','YUG','YU','Yugoslavia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('fra','ZWE','ZW','Zimbabwe');


--
-- Country table for Portuguese
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ALB','AL','Albania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','DZA','DZ','Algeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ASM','AS','American Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ATG','AG','Antigua And Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ARG','AR','Argentina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ARM','AM','Armenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','AUS','AU','Australia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','AUT','AT','Austria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','AZE','AZ','Azerbaijan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BHR','BH','Bahrain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BLR','BY','Belarus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BEL','BE','Belgium');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BVT','BV','Bouvet Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BRA','BR','Brazil');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BRN','BN','Brunei Darussalam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BGR','BG','Bulgaria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KHM','KH','Cambodia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CMR','CM','Cameroon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CPV','CV','Cape Verde');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CYM','KY','Cayman Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CAF','CF','Central African Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TCD','TD','Chad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CHL','CL','Chile');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CXR','CX','Christmas Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','COM','KM','Comoros');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','COK','CK','Cook Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CIV','CI','Cote D''Ivoire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CZE','CZ','Czech Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','DNK','DK','Denmark');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','DOM','DO','Dominican Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TLS','TL','East Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','EGY','EG','Egypt');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','EST','EE','Estonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ETH','ET','Ethiopia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','FRO','FO','Faroe Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','FRA','FR','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','FXX','FX','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GUF','GF','French Guiana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PYF','PF','French Polynesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ATF','TF','French Southern Territories');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GEO','GE','Georgia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','DEU','DE','Germany');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GRC','GR','Greece');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GRL','GL','Greenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GNB','GW','Guinea Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','HUN','HU','Hungary');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ISL','IS','Iceland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','IDN','ID','Indonesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','IRL','IE','Ireland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ITA','IT','Italy');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','JOR','JO','Jordan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KAZ','KZ','Kazakhstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PRK','KP','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KOR','KR','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KWT','KW','Kuwait');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KGZ','KG','Kyrgyzstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LVA','LV','Latvia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LBN','LB','Lebanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LBR','LR','Liberia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LTU','LT','Lithuania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LUX','LU','Luxembourg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MKD','MK','Macedonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MYS','MY','Malaysia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MDV','MV','Maldives');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MHL','MH','Marshall Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MRT','MR','Mauritania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','FSM','FM','Micronesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MDA','MD','Moldova');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MNG','MN','Mongolia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MAR','MA','Morocco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NAM','NA','Namibia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NLD','NL','Netherlands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ANT','AN','Netherlands Antilles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NCL','NC','New Caledonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NZL','NZ','New Zealand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NFK','NF','Norfolk Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','MNP','MP','Northern Mariana Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','NOR','NO','Norway');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PNG','PG','Papua New Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PHL','PH','Philippines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','POL','PL','Poland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ROU','RO','Romania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','RUS','RU','Russian Federation');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LCA','LC','Saint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','STP','ST','Sao Tome And Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SAU','SA','Saudi Arabia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SCG','CS','Serbia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SYC','SC','Seychelles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SVN','SI','Slovenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SLB','SB','Solomon Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SOM','SO','Somalia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ZAF','ZA','South Africa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ESP','ES','Spain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SHN','SH','Saint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SDN','SD','Sudan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SWE','SE','Sweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','CHE','CH','Switzerland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','SYR','SY','Syrian Arab Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TJK','TJ','Tajikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TTO','TT','Trinidad And Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TUN','TN','Tunisia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TUR','TR','Turkey');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','UGA','UG','Uganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','UKR','UA','Ukraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ARE','AE','United Arab Emirates');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','GBR','GB','United Kingdom');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','USA','US','United States');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','UZB','UZ','Uzbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','VAT','VA','Vatican City State');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','VNM','VN','Viet Nam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','VGB','VG','Virgin Islands (British)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ESH','EH','Western Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','YUG','YU','Yugoslavia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('por','ZWE','ZW','Zimbabwe');


--
-- Country table for German
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ALB','AL','Albania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','DZA','DZ','Algeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ASM','AS','American Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ATG','AG','Antigua And Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ARG','AR','Argentina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ARM','AM','Armenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','AUS','AU','Australia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','AUT','AT','Austria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','AZE','AZ','Azerbaijan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BHR','BH','Bahrain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BLR','BY','Belarus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BEL','BE','Belgium');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BVT','BV','Bouvet Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BRA','BR','Brazil');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BRN','BN','Brunei Darussalam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BGR','BG','Bulgaria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KHM','KH','Cambodia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CMR','CM','Cameroon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CPV','CV','Cape Verde');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CYM','KY','Cayman Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CAF','CF','Central African Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TCD','TD','Chad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CHL','CL','Chile');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CXR','CX','Christmas Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','COM','KM','Comoros');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','COK','CK','Cook Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CIV','CI','Cote D''Ivoire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CZE','CZ','Czech Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','DNK','DK','Denmark');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','DOM','DO','Dominican Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TLS','TL','East Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','EGY','EG','Egypt');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','EST','EE','Estonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ETH','ET','Ethiopia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','FRO','FO','Faroe Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','FRA','FR','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','FXX','FX','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GUF','GF','French Guiana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PYF','PF','French Polynesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ATF','TF','French Southern Territories');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GEO','GE','Georgia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','DEU','DE','Germany');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GRC','GR','Greece');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GRL','GL','Greenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GNB','GW','Guinea Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','HUN','HU','Hungary');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ISL','IS','Iceland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','IDN','ID','Indonesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','IRL','IE','Ireland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ITA','IT','Italy');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','JOR','JO','Jordan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KAZ','KZ','Kazakhstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PRK','KP','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KOR','KR','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KWT','KW','Kuwait');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KGZ','KG','Kyrgyzstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LVA','LV','Latvia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LBN','LB','Lebanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LBR','LR','Liberia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LTU','LT','Lithuania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LUX','LU','Luxembourg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MKD','MK','Macedonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MYS','MY','Malaysia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MDV','MV','Maldives');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MHL','MH','Marshall Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MRT','MR','Mauritania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','FSM','FM','Micronesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MDA','MD','Moldova');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MNG','MN','Mongolia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MAR','MA','Morocco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NAM','NA','Namibia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NLD','NL','Netherlands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ANT','AN','Netherlands Antilles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NCL','NC','New Caledonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NZL','NZ','New Zealand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NFK','NF','Norfolk Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','MNP','MP','Northern Mariana Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','NOR','NO','Norway');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PNG','PG','Papua New Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PHL','PH','Philippines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','POL','PL','Poland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ROU','RO','Romania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','RUS','RU','Russian Federation');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LCA','LC','Saint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','STP','ST','Sao Tome And Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SAU','SA','Saudi Arabia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SCG','CS','Serbia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SYC','SC','Seychelles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SVN','SI','Slovenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SLB','SB','Solomon Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SOM','SO','Somalia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ZAF','ZA','South Africa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ESP','ES','Spain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SHN','SH','Saint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SDN','SD','Sudan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SWE','SE','Sweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','CHE','CH','Switzerland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','SYR','SY','Syrian Arab Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TJK','TJ','Tajikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TTO','TT','Trinidad And Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TUN','TN','Tunisia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TUR','TR','Turkey');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','UGA','UG','Uganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','UKR','UA','Ukraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ARE','AE','United Arab Emirates');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','GBR','GB','United Kingdom');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','USA','US','United States');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','UZB','UZ','Uzbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','VAT','VA','Vatican City State');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','VNM','VN','Viet Nam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','VGB','VG','Virgin Islands (British)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ESH','EH','Western Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','YUG','YU','Yugoslavia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ger','ZWE','ZW','Zimbabwe');


--
-- Country table for English
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','AFG','AF','Afghanistan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'AFG',1,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ALB','AL','Albania');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ALB',2,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','DZA','DZ','Algeria');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'DZA',3,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ASM','AS','American Samoa');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ASM',4,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','AND','AD','Andorra');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'AND',5,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','AGO','AO','Angola');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'AGO',6,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','AIA','AI','Anguilla');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'AIA',7,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ATA','AQ','Antarctica');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ATA',8,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ATG','AG','Antigua And Barbuda');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ATG',9,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ARG','AR','Argentina');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ARG',10,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ARM','AM','Armenia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ARM',11,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ABW','AW','Aruba');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ABW',12,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','AUS','AU','Australia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'AUS',13,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','AUT','AT','Austria');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'AUT',14,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','AZE','AZ','Azerbaijan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'AZE',15,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BHS','BS','Bahamas');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BHS',16,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BHR','BH','Bahrain');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BHR',17,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BGD','BD','Bangladesh');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BGD',18,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BRB','BB','Barbados');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BRB',19,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BLR','BY','Belarus');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BLR',20,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BEL','BE','Belgium');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BEL',21,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BLZ','BZ','Belize');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BLZ',22,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BEN','BJ','Benin');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BEN',23,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BMU','BM','Bermuda');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BMU',24,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BTN','BT','Bhutan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BTN',25,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BOL','BO','Bolivia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BOL',26,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BIH',27,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BWA','BW','Botswana');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BWA',28,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BVT','BV','Bouvet Island');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BVT',29,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BRA','BR','Brazil');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BRA',30,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'IOT',31,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BRN','BN','Brunei Darussalam');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BRN',32,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BGR','BG','Bulgaria');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BGR',33,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BFA','BF','Burkina Faso');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BFA',34,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','BDI','BI','Burundi');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'BDI',35,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KHM','KH','Cambodia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KHM',36,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CMR','CM','Cameroon');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CMR',37,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CAN','CA','Canada');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CAN',38,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CPV','CV','Cape Verde');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CPV',39,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CYM','KY','Cayman Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CYM',40,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CAF','CF','Central African Republic');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CAF',41,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TCD','TD','Chad');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TCD',42,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CHL','CL','Chile');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CHL',43,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CHN','CN','China');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CHN',44,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CXR','CX','Christmas Island');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CXR',45,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CCK',46,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','COL','CO','Colombia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'COL',47,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','COM','KM','Comoros');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'COM',48,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','COG','CG','Congo');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'COG',49,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','COK','CK','Cook Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'COK',50,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CRI','CR','Costa Rica');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CRI',51,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CIV','CI','Cote D''Ivoire');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CIV',52,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'HRV',53,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CUB','CU','Cuba');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CUB',54,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CYP','CY','Cyprus');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CYP',55,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CZE','CZ','Czech Republic');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CZE',56,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','DNK','DK','Denmark');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'DNK',57,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','DJI','DJ','Djibouti');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'DJI',58,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','DMA','DM','Dominica');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'DMA',59,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','DOM','DO','Dominican Republic');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'DOM',60,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TLS','TL','East Timor');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TLS',61,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ECU','EC','Ecuador');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ECU',62,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','EGY','EG','Egypt');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'EGY',63,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SLV','SV','El Salvador');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SLV',64,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GNQ',65,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ERI','ER','Eritrea');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ERI',66,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','EST','EE','Estonia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'EST',67,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ETH','ET','Ethiopia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ETH',68,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'FLK',69,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','FRO','FO','Faroe Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'FRO',70,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','FJI','FJ','Fiji');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'FJI',71,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','FIN','FI','Finland');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'FIN',72,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','FRA','FR','France');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'FRA',73,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','FXX','FX','France');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'FXX',74,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GUF','GF','French Guiana');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GUF',75,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PYF','PF','French Polynesia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PYF',76,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ATF','TF','French Southern Territories');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ATF',77,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GAB','GA','Gabon');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GAB',78,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GMB','GM','Gambia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GMB',79,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GEO','GE','Georgia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GEO',80,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','DEU','DE','Germany');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'DEU',81,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GHA','GH','Ghana');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GHA',82,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GIB','GI','Gibraltar');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GIB',83,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GRC','GR','Greece');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GRC',84,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GRL','GL','Greenland');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GRL',85,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GRD','GD','Grenada');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GRD',86,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GLP','GP','Guadeloupe');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GLP',87,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GUM','GU','Guam');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GUM',88,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GTM','GT','Guatemala');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GTM',89,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GIN','GN','Guinea');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GIN',90,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GNB','GW','Guinea Bissau');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GNB',91,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GUY','GY','Guyana');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GUY',92,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','HTI','HT','Haiti');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'HTI',93,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'HMD',94,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','HND','HN','Honduras');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'HND',95,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','HKG','HK','Hong Kong');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'HKG',96,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','HUN','HU','Hungary');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'HUN',97,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ISL','IS','Iceland');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ISL',98,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','IND','IN','India');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'IND',99,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','IDN','ID','Indonesia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'IDN',100,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','IRN','IR','Iran');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'IRN',101,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','IRQ','IQ','Iraq');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'IRQ',102,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','IRL','IE','Ireland');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'IRL',103,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ISR','IL','Israel');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ISR',104,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ITA','IT','Italy');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ITA',105,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','JAM','JM','Jamaica');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'JAM',106,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','JPN','JP','Japan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'JPN',107,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','JOR','JO','Jordan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'JOR',108,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KAZ','KZ','Kazakhstan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KAZ',109,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KEN','KE','Kenya');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KEN',110,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KIR','KI','Kiribati');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KIR',111,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PRK','KP','Korea');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PRK',112,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KOR','KR','Korea');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KOR',113,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KWT','KW','Kuwait');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KWT',114,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KGZ','KG','Kyrgyzstan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KGZ',115,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LAO',116,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LVA','LV','Latvia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LVA',117,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LBN','LB','Lebanon');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LBN',118,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LSO','LS','Lesotho');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LSO',119,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LBR','LR','Liberia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LBR',120,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LBY',121,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LIE','LI','Liechtenstein');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LIE',122,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LTU','LT','Lithuania');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LTU',123,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LUX','LU','Luxembourg');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LUX',124,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MAC','MO','Macau');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MAC',125,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MKD','MK','Macedonia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MKD',126,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MDG','MG','Madagascar');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MDG',127,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MWI','MW','Malawi');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MWI',128,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MYS','MY','Malaysia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MYS',129,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MDV','MV','Maldives');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MDV',130,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MLI','ML','Mali');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MLI',131,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MLT','MT','Malta');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MLT',132,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MHL','MH','Marshall Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MHL',133,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MTQ','MQ','Martinique');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MTQ',134,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MRT','MR','Mauritania');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MRT',135,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MUS','MU','Mauritius');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MUS',136,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MYT','YT','Mayotte');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MYT',137,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MEX','MX','Mexico');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MEX',138,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','FSM','FM','Micronesia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'FSM',139,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MDA','MD','Moldova');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MDA',140,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MCO','MC','Monaco');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MCO',141,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MNG','MN','Mongolia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MNG',142,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MSR','MS','Montserrat');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MSR',143,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MAR','MA','Morocco');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MAR',144,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MOZ','MZ','Mozambique');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MOZ',145,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MMR','MM','Myanmar');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MMR',146,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NAM','NA','Namibia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NAM',147,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NRU','NR','Nauru');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NRU',148,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NPL','NP','Nepal');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NPL',149,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NLD','NL','Netherlands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NLD',150,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ANT','AN','Netherlands Antilles');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ANT',151,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NCL','NC','New Caledonia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NCL',152,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NZL','NZ','New Zealand');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NZL',153,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NIC','NI','Nicaragua');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NIC',154,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NER','NE','Niger');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NER',155,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NGA','NG','Nigeria');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NGA',156,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NIU','NU','Niue');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NIU',157,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NFK','NF','Norfolk Island');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NFK',158,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','MNP','MP','Northern Mariana Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'MNP',159,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','NOR','NO','Norway');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'NOR',160,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','OMN','OM','Oman');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'OMN',161,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PAK','PK','Pakistan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PAK',162,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PLW','PW','Palau');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PLW',163,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PAN','PA','Panama');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PAN',164,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PNG','PG','Papua New Guinea');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PNG',165,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PRY','PY','Paraguay');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PRY',166,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PER','PE','Peru');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PER',167,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PHL','PH','Philippines');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PHL',168,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PCN','PN','Pitcairn');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PCN',169,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','POL','PL','Poland');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'POL',170,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PRT','PT','Portugal');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PRT',171,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','PRI','PR','Puerto Rico');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'PRI',172,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','QAT','QA','Qatar');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'QAT',173,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','REU','RE','Reunion');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'REU',174,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ROU','RO','Romania');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ROU',175,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','RUS','RU','Russian Federation');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'RUS',176,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','RWA','RW','Rwanda');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'RWA',177,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'KNA',178,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LCA','LC','Saint Lucia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LCA',179,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'VCT',180,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','WSM','WS','Samoa');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'WSM',181,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SMR','SM','San Marino');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SMR',182,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','STP','ST','Sao Tome And Principe');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'STP',183,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SAU','SA','Saudi Arabia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SAU',184,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SEN','SN','Senegal');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SEN',185,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SCG','CS','Serbia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SCG',186,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SYC','SC','Seychelles');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SYC',187,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SLE','SL','Sierra Leone');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SLE',188,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SGP','SG','Singapore');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SGP',189,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SVK',190,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SVN','SI','Slovenia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SVN',191,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SLB','SB','Solomon Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SLB',192,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SOM','SO','Somalia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SOM',193,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ZAF','ZA','South Africa');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ZAF',194,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ESP','ES','Spain');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ESP',195,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','LKA','LK','Sri Lanka');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'LKA',196,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SHN','SH','Saint Helena');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SHN',197,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SPM',198,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SDN','SD','Sudan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SDN',199,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SUR','SR','Suriname');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SUR',200,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SJM',201,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SWZ','SZ','Swaziland');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SWZ',202,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SWE','SE','Sweden');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SWE',203,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','CHE','CH','Switzerland');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'CHE',204,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','SYR','SY','Syrian Arab Republic');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'SYR',205,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TWN','TW','Taiwan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TWN',206,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TJK','TJ','Tajikistan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TJK',207,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TZA','TZ','Tanzania');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TZA',208,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TAT','TA','Tatarstan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TAT',209,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','THA','TH','Thailand');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'THA',210,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TGO','TG','Togo');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TGO',211,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TKL','TK','Tokelau');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TKL',212,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TON','TO','Tonga');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TON',213,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TTO','TT','Trinidad And Tobago');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TTO',214,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TUN','TN','Tunisia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TUN',215,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TUR','TR','Turkey');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TUR',216,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TKM','TM','Turkmenistan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TKM',217,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TCA',218,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','TUV','TV','Tuvalu');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'TUV',219,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','UGA','UG','Uganda');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'UGA',220,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','UKR','UA','Ukraine');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'UKR',221,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ARE','AE','United Arab Emirates');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ARE',222,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','GBR','GB','United Kingdom');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'GBR',223,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','USA','US','United States');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'USA',224,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'UMI',225,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','URY','UY','Uruguay');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'URY',226,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','UZB','UZ','Uzbekistan');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'UZB',227,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','VUT','VU','Vanuatu');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'VUT',228,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','VAT','VA','Vatican City State');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'VAT',229,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','VEN','VE','Venezuela');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'VEN',230,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','VNM','VN','Viet Nam');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'VNM',231,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','VGB','VG','Virgin Islands (British)');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'VGB',232,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'VIR',233,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'WLF',234,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ESH','EH','Western Sahara');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ESH',235,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','YEM','YE','Yemen');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'YEM',236,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','YUG','YU','Yugoslavia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'YUG',237,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ZAR','ZR','Zaire');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ZAR',238,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ZMB','ZM','Zambia');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ZMB',239,0);
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('eng','ZWE','ZW','Zimbabwe');
insert into COUNTRYTABLE (ctryzid,ctrylid,ctryiso,ctryseq,ctryactive)
   values (1,1,'ZWE',240,0);


--
-- Country table for Castillian Spanish
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ALB','AL','Albania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','DZA','DZ','Algeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ASM','AS','American Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ATG','AG','Antigua And Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ARG','AR','Argentina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ARM','AM','Armenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','AUS','AU','Australia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','AUT','AT','Austria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','AZE','AZ','Azerbaijan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BHR','BH','Bahrain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BLR','BY','Belarus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BEL','BE','Belgium');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BVT','BV','Bouvet Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BRA','BR','Brazil');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BRN','BN','Brunei Darussalam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BGR','BG','Bulgaria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KHM','KH','Cambodia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CMR','CM','Cameroon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CPV','CV','Cape Verde');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CYM','KY','Cayman Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CAF','CF','Central African Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TCD','TD','Chad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CHL','CL','Chile');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CXR','CX','Christmas Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','COM','KM','Comoros');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','COK','CK','Cook Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CIV','CI','Cote D''Ivoire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CZE','CZ','Czech Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','DNK','DK','Denmark');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','DOM','DO','Dominican Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TLS','TL','East Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','EGY','EG','Egypt');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','EST','EE','Estonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ETH','ET','Ethiopia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','FRO','FO','Faroe Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','FRA','FR','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','FXX','FX','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GUF','GF','French Guiana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PYF','PF','French Polynesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ATF','TF','French Southern Territories');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GEO','GE','Georgia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','DEU','DE','Germany');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GRC','GR','Greece');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GRL','GL','Greenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GNB','GW','Guinea Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','HUN','HU','Hungary');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ISL','IS','Iceland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','IDN','ID','Indonesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','IRL','IE','Ireland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ITA','IT','Italy');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','JOR','JO','Jordan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KAZ','KZ','Kazakhstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PRK','KP','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KOR','KR','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KWT','KW','Kuwait');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KGZ','KG','Kyrgyzstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LVA','LV','Latvia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LBN','LB','Lebanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LBR','LR','Liberia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LTU','LT','Lithuania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LUX','LU','Luxembourg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MKD','MK','Macedonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MYS','MY','Malaysia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MDV','MV','Maldives');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MHL','MH','Marshall Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MRT','MR','Mauritania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','FSM','FM','Micronesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MDA','MD','Moldova');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MNG','MN','Mongolia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MAR','MA','Morocco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NAM','NA','Namibia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NLD','NL','Netherlands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ANT','AN','Netherlands Antilles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NCL','NC','New Caledonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NZL','NZ','New Zealand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NFK','NF','Norfolk Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','MNP','MP','Northern Mariana Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','NOR','NO','Norway');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PNG','PG','Papua New Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PHL','PH','Philippines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','POL','PL','Poland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ROU','RO','Romania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','RUS','RU','Russian Federation');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LCA','LC','Saint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','STP','ST','Sao Tome And Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SAU','SA','Saudi Arabia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SCG','CS','Serbia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SYC','SC','Seychelles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SVN','SI','Slovenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SLB','SB','Solomon Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SOM','SO','Somalia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ZAF','ZA','South Africa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ESP','ES','Spain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SHN','SH','Saint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SDN','SD','Sudan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SWE','SE','Sweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','CHE','CH','Switzerland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','SYR','SY','Syrian Arab Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TJK','TJ','Tajikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TTO','TT','Trinidad And Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TUN','TN','Tunisia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TUR','TR','Turkey');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','UGA','UG','Uganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','UKR','UA','Ukraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ARE','AE','United Arab Emirates');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','GBR','GB','United Kingdom');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','USA','US','United States');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','UZB','UZ','Uzbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','VAT','VA','Vatican City State');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','VNM','VN','Viet Nam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','VGB','VG','Virgin Islands (British)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ESH','EH','Western Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','YUG','YU','Yugoslavia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('spa','ZWE','ZW','Zimbabwe');


--
-- Country table for Polish
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ALB','AL','Albania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','DZA','DZ','Algeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ASM','AS','American Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ATG','AG','Antigua And Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ARG','AR','Argentina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ARM','AM','Armenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','AUS','AU','Australia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','AUT','AT','Austria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','AZE','AZ','Azerbaijan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BHR','BH','Bahrain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BLR','BY','Belarus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BEL','BE','Belgium');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BVT','BV','Bouvet Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BRA','BR','Brazil');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BRN','BN','Brunei Darussalam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BGR','BG','Bulgaria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KHM','KH','Cambodia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CMR','CM','Cameroon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CPV','CV','Cape Verde');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CYM','KY','Cayman Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CAF','CF','Central African Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TCD','TD','Chad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CHL','CL','Chile');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CXR','CX','Christmas Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','COM','KM','Comoros');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','COK','CK','Cook Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CIV','CI','Cote D''Ivoire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CZE','CZ','Czech Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','DNK','DK','Denmark');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','DOM','DO','Dominican Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TLS','TL','East Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','EGY','EG','Egypt');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','EST','EE','Estonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ETH','ET','Ethiopia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','FRO','FO','Faroe Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','FRA','FR','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','FXX','FX','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GUF','GF','French Guiana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PYF','PF','French Polynesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ATF','TF','French Southern Territories');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GEO','GE','Georgia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','DEU','DE','Germany');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GRC','GR','Greece');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GRL','GL','Greenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GNB','GW','Guinea Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','HUN','HU','Hungary');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ISL','IS','Iceland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','IDN','ID','Indonesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','IRL','IE','Ireland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ITA','IT','Italy');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','JOR','JO','Jordan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KAZ','KZ','Kazakhstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PRK','KP','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KOR','KR','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KWT','KW','Kuwait');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KGZ','KG','Kyrgyzstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LVA','LV','Latvia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LBN','LB','Lebanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LBR','LR','Liberia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LTU','LT','Lithuania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LUX','LU','Luxembourg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MKD','MK','Macedonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MYS','MY','Malaysia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MDV','MV','Maldives');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MHL','MH','Marshall Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MRT','MR','Mauritania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','FSM','FM','Micronesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MDA','MD','Moldova');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MNG','MN','Mongolia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MAR','MA','Morocco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NAM','NA','Namibia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NLD','NL','Netherlands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ANT','AN','Netherlands Antilles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NCL','NC','New Caledonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NZL','NZ','New Zealand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NFK','NF','Norfolk Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','MNP','MP','Northern Mariana Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','NOR','NO','Norway');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PNG','PG','Papua New Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PHL','PH','Philippines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','POL','PL','Poland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ROU','RO','Romania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','RUS','RU','Russian Federation');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LCA','LC','Saint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','STP','ST','Sao Tome And Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SAU','SA','Saudi Arabia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SCG','CS','Serbia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SYC','SC','Seychelles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SVN','SI','Slovenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SLB','SB','Solomon Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SOM','SO','Somalia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ZAF','ZA','South Africa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ESP','ES','Spain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SHN','SH','Saint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SDN','SD','Sudan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SWE','SE','Sweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','CHE','CH','Switzerland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','SYR','SY','Syrian Arab Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TJK','TJ','Tajikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TTO','TT','Trinidad And Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TUN','TN','Tunisia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TUR','TR','Turkey');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','UGA','UG','Uganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','UKR','UA','Ukraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ARE','AE','United Arab Emirates');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','GBR','GB','United Kingdom');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','USA','US','United States');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','UZB','UZ','Uzbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','VAT','VA','Vatican City State');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','VNM','VN','Viet Nam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','VGB','VG','Virgin Islands (British)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ESH','EH','Western Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','YUG','YU','Yugoslavia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('pol','ZWE','ZW','Zimbabwe');


--
-- Country table for Norwegian
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ALB','AL','Albania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','DZA','DZ','Algeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ASM','AS','American Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ATG','AG','Antigua And Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ARG','AR','Argentina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ARM','AM','Armenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','AUS','AU','Australia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','AUT','AT','Austria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','AZE','AZ','Azerbaijan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BHR','BH','Bahrain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BLR','BY','Belarus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BEL','BE','Belgium');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BVT','BV','Bouvet Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BRA','BR','Brazil');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BRN','BN','Brunei Darussalam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BGR','BG','Bulgaria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KHM','KH','Cambodia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CMR','CM','Cameroon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CPV','CV','Cape Verde');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CYM','KY','Cayman Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CAF','CF','Central African Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TCD','TD','Chad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CHL','CL','Chile');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CXR','CX','Christmas Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','COM','KM','Comoros');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','COK','CK','Cook Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CIV','CI','Cote D''Ivoire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CZE','CZ','Czech Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','DNK','DK','Denmark');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','DOM','DO','Dominican Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TLS','TL','East Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','EGY','EG','Egypt');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','EST','EE','Estonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ETH','ET','Ethiopia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','FRO','FO','Faroe Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','FRA','FR','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','FXX','FX','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GUF','GF','French Guiana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PYF','PF','French Polynesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ATF','TF','French Southern Territories');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GEO','GE','Georgia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','DEU','DE','Germany');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GRC','GR','Greece');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GRL','GL','Greenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GNB','GW','Guinea Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','HUN','HU','Hungary');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ISL','IS','Iceland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','IDN','ID','Indonesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','IRL','IE','Ireland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ITA','IT','Italy');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','JOR','JO','Jordan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KAZ','KZ','Kazakhstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PRK','KP','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KOR','KR','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KWT','KW','Kuwait');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KGZ','KG','Kyrgyzstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LVA','LV','Latvia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LBN','LB','Lebanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LBR','LR','Liberia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LTU','LT','Lithuania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LUX','LU','Luxembourg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MKD','MK','Macedonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MYS','MY','Malaysia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MDV','MV','Maldives');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MHL','MH','Marshall Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MRT','MR','Mauritania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','FSM','FM','Micronesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MDA','MD','Moldova');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MNG','MN','Mongolia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MAR','MA','Morocco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NAM','NA','Namibia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NLD','NL','Netherlands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ANT','AN','Netherlands Antilles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NCL','NC','New Caledonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NZL','NZ','New Zealand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NFK','NF','Norfolk Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','MNP','MP','Northern Mariana Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','NOR','NO','Norway');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PNG','PG','Papua New Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PHL','PH','Philippines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','POL','PL','Poland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ROU','RO','Romania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','RUS','RU','Russian Federation');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LCA','LC','Saint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','STP','ST','Sao Tome And Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SAU','SA','Saudi Arabia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SCG','CS','Serbia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SYC','SC','Seychelles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SVN','SI','Slovenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SLB','SB','Solomon Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SOM','SO','Somalia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ZAF','ZA','South Africa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ESP','ES','Spain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SHN','SH','Saint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SDN','SD','Sudan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SWE','SE','Sweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','CHE','CH','Switzerland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','SYR','SY','Syrian Arab Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TJK','TJ','Tajikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TTO','TT','Trinidad And Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TUN','TN','Tunisia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TUR','TR','Turkey');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','UGA','UG','Uganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','UKR','UA','Ukraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ARE','AE','United Arab Emirates');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','GBR','GB','United Kingdom');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','USA','US','United States');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','UZB','UZ','Uzbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','VAT','VA','Vatican City State');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','VNM','VN','Viet Nam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','VGB','VG','Virgin Islands (British)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ESH','EH','Western Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','YUG','YU','Yugoslavia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nor','ZWE','ZW','Zimbabwe');


--
-- Country table for Italian
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ALB','AL','Albania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','DZA','DZ','Algeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ASM','AS','American Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ATG','AG','Antigua And Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ARG','AR','Argentina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ARM','AM','Armenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','AUS','AU','Australia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','AUT','AT','Austria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','AZE','AZ','Azerbaijan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BHR','BH','Bahrain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BLR','BY','Belarus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BEL','BE','Belgium');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BIH','BA','Bosnia And Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BVT','BV','Bouvet Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BRA','BR','Brazil');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','IOT','IO','British Indian Ocean Territory');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BRN','BN','Brunei Darussalam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BGR','BG','Bulgaria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KHM','KH','Cambodia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CMR','CM','Cameroon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CPV','CV','Cape Verde');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CYM','KY','Cayman Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CAF','CF','Central African Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TCD','TD','Chad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CHL','CL','Chile');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CXR','CX','Christmas Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CCK','CC','Cocos (Keeling) Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','COM','KM','Comoros');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','COK','CK','Cook Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CIV','CI','Cote D''Ivoire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','HRV','HR','Croatia (Hrvatska)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CZE','CZ','Czech Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','DNK','DK','Denmark');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','DOM','DO','Dominican Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TLS','TL','East Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','EGY','EG','Egypt');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GNQ','GQ','Equatorial Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','EST','EE','Estonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ETH','ET','Ethiopia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','FLK','FK','Falkland Islands (Malvinas)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','FRO','FO','Faroe Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','FRA','FR','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','FXX','FX','France');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GUF','GF','French Guiana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PYF','PF','French Polynesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ATF','TF','French Southern Territories');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GEO','GE','Georgia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','DEU','DE','Germany');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GRC','GR','Greece');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GRL','GL','Greenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GNB','GW','Guinea Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','HMD','HM','Heard Island & Mcdonald Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','HUN','HU','Hungary');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ISL','IS','Iceland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','IDN','ID','Indonesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','IRL','IE','Ireland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ITA','IT','Italy');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','JOR','JO','Jordan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KAZ','KZ','Kazakhstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PRK','KP','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KOR','KR','Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KWT','KW','Kuwait');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KGZ','KG','Kyrgyzstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LAO','LA','Lao People''s Democratic Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LVA','LV','Latvia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LBN','LB','Lebanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LBR','LR','Liberia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LBY','LY','Libyan Arab Jamahiriya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LTU','LT','Lithuania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LUX','LU','Luxembourg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MKD','MK','Macedonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MYS','MY','Malaysia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MDV','MV','Maldives');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MHL','MH','Marshall Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MRT','MR','Mauritania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','FSM','FM','Micronesia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MDA','MD','Moldova');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MNG','MN','Mongolia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MAR','MA','Morocco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NAM','NA','Namibia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NLD','NL','Netherlands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ANT','AN','Netherlands Antilles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NCL','NC','New Caledonia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NZL','NZ','New Zealand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NFK','NF','Norfolk Island');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','MNP','MP','Northern Mariana Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','NOR','NO','Norway');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PNG','PG','Papua New Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PHL','PH','Philippines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','POL','PL','Poland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ROU','RO','Romania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','RUS','RU','Russian Federation');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','KNA','KN','Saint Kitts And Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LCA','LC','Saint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','VCT','VC','Saint Vincent And The Grenadines');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','STP','ST','Sao Tome And Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SAU','SA','Saudi Arabia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SCG','CS','Serbia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SYC','SC','Seychelles');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SVK','SK','Slovakia (Slovak Republic)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SVN','SI','Slovenia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SLB','SB','Solomon Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SOM','SO','Somalia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ZAF','ZA','South Africa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ESP','ES','Spain');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SHN','SH','Saint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SPM','PM','Saint Pierre And Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SDN','SD','Sudan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SJM','SJ','Svalbard And Jan Mayen Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SWE','SE','Sweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','CHE','CH','Switzerland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','SYR','SY','Syrian Arab Republic');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TJK','TJ','Tajikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TTO','TT','Trinidad And Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TUN','TN','Tunisia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TUR','TR','Turkey');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TCA','TC','Turks And Caicos Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','UGA','UG','Uganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','UKR','UA','Ukraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ARE','AE','United Arab Emirates');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','GBR','GB','United Kingdom');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','USA','US','United States');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','UMI','UM','United States Minor Outlying Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','UZB','UZ','Uzbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','VAT','VA','Vatican City State');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','VNM','VN','Viet Nam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','VGB','VG','Virgin Islands (British)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','VIR','VI','Virgin Islands (U.S.)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','WLF','WF','Wallis And Futuna Islands');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ESH','EH','Western Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','YUG','YU','Yugoslavia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('ita','ZWE','ZW','Zimbabwe');


--
-- Country table for Dutch
--

insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','AFG','AF','Afghanistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ALB','AL','Albanie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','DZA','DZ','Algerije');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ASM','AS','Amerikaans Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','VIR','VI','Amerikaanse Maagdeneilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','AND','AD','Andorra');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','AGO','AO','Angola');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','AIA','AI','Anguilla');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ATA','AQ','Antarctica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ATG','AG','Antigua en Barbuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ARG','AR','Argentinie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ARM','AM','Armenie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ABW','AW','Aruba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','AUS','AU','Australie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','AZE','AZ','Azerbeidzjan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BHS','BS','Bahamas');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BHR','BH','Bahrein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BGD','BD','Bangladesh');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BRB','BB','Barbados');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BEL','BE','Belgie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BLZ','BZ','Belize');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BEN','BJ','Benin');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BMU','BM','Bermuda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BTN','BT','Bhutan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BOL','BO','Bolivia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BIH','BA','Bosnie-Herzegovina');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BWA','BW','Botswana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BVT','BV','Bouvet (eiland)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BRA','BR','Brazilie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','IOT','IO','Britse gebiedsdelen in de Indische Oceaan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','VGB','VG','Britse Maagdeneilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BRN','BN','Brunei');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BGR','BG','Bulgarije');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BFA','BF','Burkina Faso');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BDI','BI','Burundi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KHM','KH','Cambodja');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CAN','CA','Canada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CYM','KY','Caymaneilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CAF','CF','Centraal Afrikaanse Republiek');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CHL','CL','Chili');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CHN','CN','China');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CXR','CX','Christmaseiland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CCK','CC','Cocos (Keeling) eilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','COL','CO','Colombia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','COM','KM','Comoren');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','COG','CG','Congo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','COK','CK','Cookeilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CRI','CR','Costa Rica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CUB','CU','Cuba');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CYP','CY','Cyprus');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','DNK','DK','Denemarken');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','DJI','DJ','Djibouti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','DMA','DM','Dominica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','DOM','DO','Dominicaanse Republiek');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','DEU','DE','Duitsland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ECU','EC','Ecuador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','EGY','EG','Egypte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SLV','SV','El Salvador');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GNQ','GQ','Equatoriaal-Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ERI','ER','Eritrea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','EST','EE','Estland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ETH','ET','Ethiopie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','FRO','FO','Faeröer');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','FLK','FK','Falklandeilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','FJI','FJ','Fiji');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PHL','PH','Filipijnen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','FIN','FI','Finland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','FRA','FR','Frankrijk');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','FXX','FX','Frankrijk');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ATF','TF','Franse Zuidelijke gebieden ');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GUF','GF','Frans-Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PYF','PF','Frans-Polynesie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GAB','GA','Gabon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GMB','GM','Gambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GEO','GE','Georgie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GHA','GH','Ghana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GIB','GI','Gibraltar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GRD','GD','Grenada');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GRC','GR','Griekenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GRL','GL','Groenland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GBR','GB','Groot-Brittannië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GLP','GP','Guadeloupe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GUM','GU','Guam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GTM','GT','Guatemala');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GIN','GN','Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GNB','GW','Guinea-Bissau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','GUY','GY','Guyana');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','HTI','HT','Haiti');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','HMD','HM','Heard en McDonald (eilanden)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','HND','HN','Honduras');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','HKG','HK','Hong Kong');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','HUN','HU','Hungarije');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','IRL','IE','Ierland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ISL','IS','IJsland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','IND','IN','India');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','IDN','ID','Indonesie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','IRN','IR','Iran');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','IRQ','IQ','Iraq');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ISR','IL','Israel');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ITA','IT','Italie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CIV','CI','Ivoorkust');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','JAM','JM','Jamaica');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','JPN','JP','Japan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','YUG','YU','Joegoslavië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','JOR','JO','Jordanie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CPV','CV','Kaapverdië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CMR','CM','Kameroen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KAZ','KZ','Kazachstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KEN','KE','Kenya');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KGZ','KG','Kirgizië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KIR','KI','Kiribati');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KWT','KW','Koeweit');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','HRV','HR','Kroatie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LAO','LA','Laos');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LSO','LS','Lesotho');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LVA','LV','Letland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LBN','LB','Libanon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LBR','LR','Liberie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LBY','LY','Libië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LIE','LI','Liechtenstein');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LTU','LT','Litouwen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LUX','LU','Luxemburg');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MAC','MO','Macau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MKD','MK','Macedonie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MDG','MG','Madagascar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MWI','MW','Malawi');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MDV','MV','Maldiven');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MYS','MY','Maleisië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MLI','ML','Mali');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MLT','MT','Malta');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MHL','MH','Marshalleilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MTQ','MQ','Martinique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MRT','MR','Mauritanie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MUS','MU','Mauritius');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MYT','YT','Mayotte');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MEX','MX','Mexico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','FSM','FM','Micronesie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MDA','MD','Moldovie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MCO','MC','Monaco');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MNG','MN','Mongolie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MSR','MS','Montserrat');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MAR','MA','Morokko');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MOZ','MZ','Mozambique');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MMR','MM','Myanmar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NAM','NA','Namibie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NRU','NR','Nauru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NLD','NL','Nederland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ANT','AN','Nederlandse Antillen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NPL','NP','Nepal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NIC','NI','Nicaragua');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NCL','NC','Nieuw Caledonië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NZL','NZ','Nieuw Zeeland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NER','NE','Niger');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NGA','NG','Nigeria');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NIU','NU','Niue');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PRK','KP','Noord Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','MNP','MP','Noordelijke Mariana Eilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NOR','NO','Noorwegen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','NFK','NF','Norfolk Eiland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','UGA','UG','Oeganda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','UKR','UA','Oekraine');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','UZB','UZ','Oezbekistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','OMN','OM','Oman');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TLS','TL','Oost Timor');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','AUT','AT','Oostenrijk');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PAK','PK','Pakistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PLW','PW','Palau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PAN','PA','Panama');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PNG','PG','Papua Nieuw Guinea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PRY','PY','Paraguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PER','PE','Peru');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PCN','PN','Pitcairn');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','POL','PL','Polen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PRT','PT','Portugal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','PRI','PR','Puerto Rico');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','QAT','QA','Qatar');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','REU','RE','Reunion');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ROU','RO','Roemenië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','RUS','RU','Rusland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','RWA','RW','Rwanda');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SLB','SB','Salomonseilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','WSM','WS','Samoa');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SMR','SM','San Marino');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','STP','ST','Sao Tome en Principe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SAU','SA','Saoedi-Arabië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SEN','SN','Senegal');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SCG','CS','Servie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SYC','SC','Seychellen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SLE','SL','Sierra Leone');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SGP','SG','Singapore');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SHN','SH','Sint Helena');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KNA','KN','Sint Kitts en Nevis');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LCA','LC','Sint Lucia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SPM','PM','Sint Pierre en Miquelon');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','VCT','VC','Sint Vincent en de Grenadines Eilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SVN','SI','Slovenie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SVK','SK','Slowakije');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SDN','SD','Soedan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SOM','SO','Somalie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ESP','ES','Spanje');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','LKA','LK','Sri Lanka');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SUR','SR','Suriname');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SJM','SJ','Svalbard en Jan Mayen Eilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SWZ','SZ','Swaziland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SYR','SY','Syrie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TJK','TJ','Tadzjikistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TWN','TW','Taiwan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TZA','TZ','Tanzania');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TAT','TA','Tatarstan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','THA','TH','Thailand');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TGO','TG','Togo');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TKL','TK','Tokelau');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TON','TO','Tonga');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TTO','TT','Trinidad en Tobago');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TCD','TD','Tsjaad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CZE','CZ','Tsjechië');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TUN','TN','Tunisie');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TUR','TR','Turkije');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TKM','TM','Turkmenistan');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TCA','TC','Turkse en Caicos Eilanden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','TUV','TV','Tuvalu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','URY','UY','Uruguay');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','VUT','VU','Vanuatu');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','VAT','VA','Vaticaanstad');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','VEN','VE','Venezuela');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ARE','AE','Verenigde Arabische Emiraten ');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','USA','US','Verenigde Staten');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','UMI','UM','Verenigde Staten (kleine eilanden)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','VNM','VN','Vietnam');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','WLF','WF','Wallis en Futuna (eilanden)');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ESH','EH','West-Sahara');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','BLR','BY','Wit-Rusland');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','YEM','YE','Yemen');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ZAR','ZR','Zaire');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ZMB','ZM','Zambia');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ZWE','ZW','Zimbabwe');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','ZAF','ZA','Zuid Afrika');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','KOR','KR','Zuid Korea');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','SWE','SE','Zweden');
insert into COUNTRYLANG (ctrylangliso,ctrylangciso,ctrylangciso2,ctrylangname)
   values ('nld','CHE','CH','Zwitzerland');
