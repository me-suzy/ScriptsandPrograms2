-- Copyright (C) 1997-2002  FishNet, Inc.

-- PostGreSQL tables for FishCartSQL
-- 
-- Modification comments:
-- 98/03/31: Removed 'long' qualifier from catmast and proddescr.
-- 99/10/29:  Zot converting to postgres from solid pages.
--  deleted all commit work lines (postgres is usually autocommit).
--  insert are from mysql.
--  sequences are done via integer DEFAULT NEXTVAL ('scat_SEQID'),
--  instead of 50 line solid functions (mysql is shoter with auto_increment).
--  varchar is limited to 8103, not 8192.  This should probably be text
--  decimal must have a precison of 1?  That seems silly

-- large text objects
-- http://www.postgresql.org/docs/7.2/interactive/datatype-character.html

-- I guess solid assumes you can add users.  I assume not, and this is
-- done with createuser and alteruser.

-- drop user USERID;
-- create user USERID password 'USERPW';

-- drop user ADMID;
-- create user ADMID password 'ADMPW';

-- drop user DBDNAME;
-- create user DBDNAME password 'DBDPASS';


-- ********** Master Table *************

-- drop table MASTERTABLE;

create table MASTERTABLE (
	-- default zone
	zoneid  integer,
	-- current number of zones
	numzone integer,
	-- current number of languages
	numlang integer,
	-- maximum number of zones
	maxzone integer,
	-- maximum number of languages
	maxlang	integer
);

grant select,insert on MASTERTABLE to USERID;
grant all on MASTERTABLE to ADMID;

-- drop index masterzid_INDEXID;
-- drop index masterlid_INDEXID;

-- create index masterzid_INDEXID on MASTERTABLE (zoneid);
-- create index masterlid_INDEXID on MASTERTABLE (langid);

insert into MASTERTABLE
	(zoneid,numzone,numlang,maxzone,maxlang)
	values
	(1,1,1,1,1);


-- ********** Order Processing Tables *************

-- drop index ohord_INDEXID;
-- drop index ohtim_INDEXID;
-- drop index olord_INDEXID;
-- drop index olsku_INDEXID;

-- drop table ORDERHEAD;
-- drop table ORDERLINE;
-- drop table ORDERLINEOPT;


create table ORDERHEAD (
	-- unique order line ID
	olid    integer,
	-- unique order ID
	orderid varchar,
	-- zone ID
	zone    integer,
	-- subzone ID
	subz    integer,
	-- shipping ID
	shipid  integer,
	-- epoch timestamp
	tstamp  integer not null,
	-- 
	trans1  varchar,
	-- associate ID
	aid     char(16),
	-- purchaser ID (corresponds to custid column in custtable)
	purchid	integer,
	-- order process state
	complete integer not null,
	-- coupon ID for this order
	couponid varchar(32),
	-- product subtotal (before discounts)
	pstotal decimal(10,2),
	-- periodic service subtotal
	mstotal decimal(10,2),
	-- discount amount
	discount decimal(6,2),
	-- shipping amount
	shamt decimal(6,2),
	-- sales tax total, no shipping
	nstax decimal(6,2),
	-- sales tax total, shipping
	tstax decimal(6,2),
	-- order subtotal before contribution
	ostotal decimal(10,2),
	-- contribution amount
	contrib decimal(6,2),
	-- total order amount
	ototal decimal(10,2),
	-- check number
	ohcheck		integer,
	-- checking account number
	ohchkacct	varchar(16),
	-- bank routing number
	ohabaroute	char(9),
	cmt1		varchar(80),
	cmt2		varchar(80),
	cmt3		varchar(80),
	cmt4		varchar(80),
	cmt5		varchar(80),
	scity		varchar(64),
	sstate		varchar(64),
	szip		varchar(16),
	scountry	varchar(5),
	-- invoices being paid
	payinv	varchar(255),
	-- gift order flag
	oheadgift       integer,
	-- customer shipping columns
	oheadssal       varchar(20),
	oheadsfname     varchar(80),
	oheadsmname     varchar(80),
	oheadslname     varchar(80),
	oheadscompany   varchar(80),
	oheadsemail     varchar(80),
	oheadsaddr1     varchar(80),
	oheadsaddr2     varchar(80),
	oheadscity      varchar(80),
	oheadsstate     varchar(80),
	oheadszip       varchar(10),
	oheadszip4      varchar(10),
	oheadsnatl      varchar(80),
	oheadsacode     varchar(80),
	oheadsphone     varchar(80),
	oheadsfacode    varchar(80),
	oheadsfax       varchar(80),
	-- credit card info
	oheadccname     varchar(80),
	oheadccnumber   varchar(80),
	oheadcctype     varchar(32),
	oheadccexpmo    char(2),
	oheadccexpyr    char(4),
	oheadcccvv      integer,
	-- purchaser's ip address
	oheadcustip     varchar(32)
);

create table ORDERLINE (
	-- unique order line ID
	olid		integer,
	-- unique order ID
	orderid		varchar,
	-- zone ID (may be different from the order header zone)
	olzone		integer,
	-- lang ID
	ollang		integer,
	-- product SKU
	sku			varchar,
	-- the composite product SKU after options
	compsku		varchar,
	-- ordered quantity
	qty			integer,
	-- inventory overage
	invover		integer,
	-- product unit price; may fluctuate from product table
	-- price as discounts are applied
	olprice decimal(10,2),
	-- ESD table entry
	olesd		integer
);

create table ORDERLINEOPT (
	-- By pointing to the specific product options selected (poptid)
	-- we only need to track the quantity chosen, if any.
	-- unique order ID
	orderid varchar,
	-- zone ID (may be different from the order header zone)
	olzone  integer,
	-- lang ID
	ollang  integer,
	-- product SKU
	sku     varchar,
	-- the composite product SKU after options
	compsku     varchar,
	-- product option ID
	poptid  integer,
	-- quantity for this option
	qty     integer
);

grant all on ORDERHEAD to USERID;
grant all on ORDERLINE to USERID;
grant all on ORDERLINEOPT to USERID;

grant all on ORDERHEAD to ADMID;
grant all on ORDERLINE to ADMID;
grant all on ORDERLINEOPT to ADMID;

-- drop index ohord_INDEXID;
-- drop index ohtim_INDEXID;
-- drop index ohcmp_INDEXID;

-- drop index olord_INDEXID;
-- drop index olsku_INDEXID;
-- drop index olcsku_INDEXID;
-- drop index olsnum_INDEXID;
-- drop index olzone_INDEXID;
-- drop index ollang_INDEXID;

-- drop index otord_INDEXID;
-- drop index otsku_INDEXID;
-- drop index otcsku_INDEXID;
-- drop index otpopt_INDEXID;
-- drop index otzone_INDEXID;
-- drop index otlang_INDEXID;

create index ohord_INDEXID on ORDERHEAD (orderid);
create index ohtim_INDEXID on ORDERHEAD (tstamp);
create index ohcmp_INDEXID on ORDERHEAD (complete);

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


-- ********** Aid Table *************
--  This is the table for Associate IDs.

-- drop sequence asc_SEQID;
create sequence asc_SEQID;

-- drop table ASSOCIATETABLE;

-- drop index asc_INDEXID;
-- drop index ascz_INDEXID;
-- drop index ascl_INDEXID;
-- drop index ascw_INDEXID;

create table ASSOCIATETABLE (
	ascid integer DEFAULT NEXTVAL ('asc_seq'),
	asczid  integer,
	asclid  integer,
	ascwebid integer,
	ascdesc varchar,
	ascname varchar,
	ascaddr1  varchar,
	ascaddr2  varchar,
	asccity   varchar,
	ascstate  varchar,
	asczip    varchar,
	ascnatl   varchar,
	ascphone  varchar,
	ascfax    varchar,
	ascemail  varchar,
	ascsvcname   varchar,
	ascsvcaddr1  varchar,
	ascsvcaddr2  varchar,
	ascsvccity   varchar,
	ascsvcstate  varchar,
	ascsvczip    varchar,
	ascsvcnatl   varchar,
	ascsvcphone  varchar,
	ascsvcfax    varchar,
	ascsvcemail  varchar,
	asconline varchar,
	ascofline varchar,
	ascoemail varchar,
	ascconfirm varchar,
	ascactive integer


);

create index asc_INDEXID on ASSOCIATETABLE (ascid);
create index ascz_INDEXID on ASSOCIATETABLE (asczid);
create index ascl_INDEXID on ASSOCIATETABLE (asclid);
create index ascw_INDEXID on ASSOCIATETABLE (ascwebid);

grant select,insert on ASSOCIATETABLE to USERID;
grant all on ASSOCIATETABLE to ADMID;


-- ************** ACCESS LOG TABLE *****************

-- drop index acc_INDEXID;
-- drop table ACCTABLE;

create table ACCTABLE (
	accesshost  varchar,
	accessip    varchar,
	accesstime  integer,
	accesscnt   integer
);

grant insert,select,update on ACCTABLE to USERID;
grant all on ACCTABLE to ADMID;



create index acc_INDEXID on ACCTABLE (accessip);

-- ************** KEYWORD LOG TABLE *****************

-- drop index key_INDEXID;
-- drop table KEYTABLE;

create table KEYTABLE (
	keyval  varchar,
	keycnt  integer,
	keyres  integer
);

grant select,insert,update on KEYTABLE to USERID;
grant all on KEYTABLE to ADMID;



create index key_INDEXID on KEYTABLE (keyval);

-- drop sequence lang_SEQID;
create sequence lang_SEQID;

-- ************** LANGUAGE TABLE *****************
--
-- The language chosen is dependent on the zone,
-- as different descriptions may be needed.

-- drop index lang_INDEXID;
-- drop index langz_INDEXID;

-- drop table LANGTABLE;

create table LANGTABLE ( 
	langid    integer DEFAULT NEXTVAL ('lang_SEQID') ,
	langzid   integer,
	langdef   integer,
	-- ISO 639/2 code
	langiso   char(3),
	langdescr varchar,
	langtmpl  varchar,
	langtdsp  varchar,
	langterr  varchar,
	langshow  varchar,
	langgeo   varchar,
	langordr  varchar,
	langproc  varchar,
	langfinl  varchar,
	langstmpl varchar,
	-- front page welcome text
	langwelcome   text,
	-- copyright, footer text
	langcopy      text,
	-- terms, conditions, etc
	langterms     text,
	-- front page promo cat for this lang
	langfppromo integer
);

grant select on LANGTABLE to USERID;
grant all on LANGTABLE to ADMID;

-- from mysql

insert into LANGTABLE
    (langzid,langiso,langdescr,langtmpl,langtdsp,langterr,langshow,
     langgeo,langordr,langproc,langfinl,langstmpl,langwelcome,langcopy,
	 langterms,langfppromo)
    values
    (1,'LANGISO','LANGNAME','','','','showcart.php','showgeo.php',
     'orderform.php','orderproc.php','orderfinal.php','',
	 'Welcome to FishCart, the premier open source multinational ecommerce system.  More information about FishCart can be found at fishcart.org.<p><i>This text can be edited in the Language profile in the FishCart administration area.</i>','<i>Edit this copyright text in the language profile.</i>',
	 '<i>Edit this terms and conditions text in the language profile.</i>',1);

grant select,update on lang_SEQID to ADMID;

create index lang_INDEXID on LANGTABLE (langid);
create index langz_INDEXID on LANGTABLE (langzid);

-- ************** CATEGORY TABLE *****************
--
-- The category always follows the subzone, as the
-- graphics will be different for each language.

-- drop index cat_INDEXID;
-- drop index catl_INDEXID;
-- drop index catz_INDEXID;

-- drop table CATTABLE;
-- drop sequence cat_SEQID;
create sequence cat_SEQID;

create table CATTABLE ( 
	catval   integer DEFAULT NEXTVAL ('cat_SEQID') ,
	catzid   integer,
	catlid   integer,
	catlogo  varchar,
	catlogoh integer,
	catlogow integer,
    catbutton  varchar,
	catbuttonh integer,
	catbuttonw integer,
	catdescr varchar,
	catback  varchar,
	catbg    varchar,
	catlink  varchar,
	catvlink varchar,
	catalink varchar,
	catbanr  varchar,
	catbanrh integer,
	catbanrw integer,
	catsku   varchar,
	cattext  varchar,
	catmast  text,
	cattmpl  varchar,
	catsort  varchar,
	catfree  varchar,
	catunder integer, 
	catpath varchar,
	catact   integer,
	catprodpage integer,
	-- number of subcat columns to show across
	catcols  integer,
	-- category sequence number
	catseq  integer
);

grant select on CATTABLE to USERID;
grant all on CATTABLE to ADMID;

grant select,update on cat_SEQID to ADMID;

create index cat_INDEXID on CATTABLE (catval);
create index catl_INDEXID on CATTABLE (catlid);
create index catz_INDEXID on CATTABLE (catzid);

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


-- ************** PRODUCT TABLE *****************

-- When creating a new product, always create the default.
-- Then in modify, give the option to make a new zoned product.

-- drop index prod_INDEXID;
-- drop index prodz_INDEXID;

-- drop table PRODTABLE;

create table PRODTABLE (
	prodzid      integer,
	prodsku      varchar,
	-- one time setup fee
	prodsetup    decimal(10,2),
	prodstsalebeg  integer,
	prodstsaleend  integer,
	prodstsaleprice decimal(10,2),
	prodprice    decimal(10,2),
	prodrtlprice decimal(10,2),
	prodstart    integer,
	prodstop     integer,
	prodsalebeg  integer,
	prodsaleend  integer,
	prodsaleprice decimal(10,2),
	prodmcode    varchar,
	prodwidth    decimal(5,2),
	prodheight   decimal(5,2),
	prodlength   decimal(5,2),
	prodweight   decimal(12,4),
	prodseq      integer,
	produseinvq  integer,
	prodinvqty   integer,
	prodordmax   integer,
	prodflag1    integer,
	prodcharge   smallint,
	prodisbn     char(20),
	prodpromo    integer,
	-- vendor defined product version
	prodversion  varchar(32),
	-- product activation code
	prodactcod   varchar(64),
	-- product serial number prefix
	prodserpfx   varchar(8),
	-- product serial number
	prodsernum   integer,
	-- product serial number duration in hours
	prodserhrs   integer,
	-- product serial number max downloads
	prodsermax   integer,
	-- product serial number d/l file name
	prodserfil   varchar(255),
	-- VAT percentage given as .??
	prodvat      decimal(3,2)
);


grant select,update on PRODTABLE to USERID;
grant all on PRODTABLE to ADMID;


create unique index prod_INDEXID  on PRODTABLE (prodsku);
create index prodz_INDEXID on PRODTABLE (prodzid);


-- ************** PRODUCT LANGUAGE TABLE *****************

-- drop index plng_INDEXID;
-- drop index plngs_INDEXID;
-- drop index plngz_INDEXID;

-- drop table PRODLANG;

create table PRODLANG (
	prodlid      integer,
	prodlzid     integer,
	prodlsku     varchar,
	prodpic      varchar,
	prodpich     integer,
	prodpicw     integer,
	prodtpic     varchar,
	prodtpich    integer,
	prodtpicw    integer,
	prodbanr     varchar,
	prodbanrh    integer,
	prodbanrw    integer,
	prodaudio    varchar,
	prodvideo    varchar,
	prodsplash   varchar,
	prodkeywords varchar,
        prodlflag1   integer,
	prodname     varchar(255),
	prodsdescr   varchar(255),
	proddescr    text,
	installinst  text,
	prodoffer    varchar,
	prodstyle    varchar,
	-- product download link
	proddload    varchar,
	-- artist/author name
	prodauth     varchar,
	-- external URL for this author
	prodauthurl  varchar,
	-- external URL for this product
	prodtitleurl varchar,
	-- product lead time information
	prodleadtime varchar,
	-- periodic service description
	prodpersvc   varchar(32)
);


grant select on PRODLANG to USERID;
grant all on PRODLANG to ADMID;


create index plng_INDEXID  on PRODLANG (prodlid);
create index plngs_INDEXID on PRODLANG (prodlsku);
create index plngz_INDEXID on PRODLANG (prodlzid);


-- ************** PRODUCT OPTION TABLE *****************

-- drop table PRODOPT;
-- drop table PRODOPTGRPNAME;

-- drop sequence popt_SEQID;
create sequence popt_SEQID;
grant select,update on popt_SEQID to ADMID;

create table PRODOPT (
	-- unique product option ID
	poptid       integer DEFAULT NEXTVAL ('popt_SEQID') ,
	-- zone ID
	poptzid      integer,
	-- language ID
	poptlid      integer,
	-- user entered option group number
	poptgrp      integer,
	-- user entered sequence number for ORDER BY
	poptseq      integer,
	-- SKU of the base product
	poptsku      varchar(20),
	-- product option SKU modifier (modifies or replaces)
	poptskumod   varchar(128),
	-- SKU substitution pattern
	poptskusub   varchar(128),
	-- one time setup fee
	poptsetup    decimal(10,2),
	-- product option price (relative or absolute)
	poptprice    decimal(10,2),
	-- product option setup sale price (relative or absolute)
	poptssalebeg  integer,
	poptssaleend  integer,
	poptssaleprice decimal(10,2),
	-- product option sale price (relative or absolute)
	poptsalebeg  integer,
	poptsaleend  integer,
	poptsaleprice decimal(10,2),
	-- product option picture
	poptpic      varchar(128),
	poptpich     integer,
	poptpicw     integer,
	-- product option thumbnail picture
	popttpic     varchar(128),
	popttpich    integer,
	popttpicw    integer,
	-- option name
	poptname     varchar(128),
	-- option short description
	poptsdescr   varchar(255),
	-- option full description
	poptdescr    text,
	-- text for various uses
	popttext1    varchar(255),
	popttext2    varchar(255),
	popttext3    varchar(255),
	-- product option control flags
	poptflag1    integer,
	poptflag2    integer
);

create table PRODOPTGRPNAME (
	-- zone ID
	pgrpzid      integer not null,
	-- language ID
	pgrplid      integer not null,
	-- numeric option group number
	pgrpgrp      integer not null,
	-- option group name
	pgrpname      varchar(128)
);

grant select,insert,update,delete on PRODOPT to USERID;
grant all on PRODOPT to ADMID;

grant select,insert,update,delete on PRODOPTGRPNAME to USERID;
grant all on PRODOPTGRPNAME to ADMID;

-- drop index poptk_INDEXID;
-- drop index popt_INDEXID;
-- drop index poptg_INDEXID;
-- drop index poptz_INDEXID;
-- drop index poptl_INDEXID;
-- drop index popts_INDEXID;

-- drop index pgrpz_INDEXID;
-- drop index pgrpl_INDEXID;
-- drop index pgrpg_INDEXID;

create index poptk_INDEXID on PRODOPT (poptsku);
create index popt_INDEXID  on PRODOPT (poptid);
create index poptg_INDEXID on PRODOPT (poptgrp);
create index poptz_INDEXID on PRODOPT (poptzid);
create index poptl_INDEXID on PRODOPT (poptlid);
create index popts_INDEXID on PRODOPT (poptseq);

create index pgrpz_INDEXID on PRODOPTGRPNAME (pgrpzid);
create index pgrpl_INDEXID on PRODOPTGRPNAME (pgrplid);
create index pgrpg_INDEXID on PRODOPTGRPNAME (pgrpgrp);


-- ********** PRODUCT/CATEGORY TABLE **************

-- This table has to be fully filled in with existing 
-- cat/prod/zone options to keep searches quick when
-- displaying the shopping tables.

-- When creating a new zone, this table must be filled
-- in with entries for every category / default product.

-- drop index pcat_INDEXID;
-- drop index pcatk_INDEXID;
-- drop index pcatz_INDEXID;
-- drop index pcats_INDEXID;

-- drop table PRODCATTABLE;

create table PRODCATTABLE (
  pcatval  integer,
  pcatsku  varchar,
  pcatzid  integer,
  pcatseq  integer
);

grant select,insert,update on PRODCATTABLE to USERID;
grant all on PRODCATTABLE to ADMID;

create index pcat_INDEXID  on PRODCATTABLE (pcatval);
create index pcatk_INDEXID on PRODCATTABLE (pcatsku);
create index pcatz_INDEXID on PRODCATTABLE (pcatzid);



-- ************** NEW PRODUCTS TABLE *****************

-- A zone ID of 0 is the mark of a 'default' product to span
-- all zones.

-- When creating a new product, always create the default.
-- Then in modify, give the option to make a new zoned product.

-- drop index nprod_INDEXID;
-- drop index nprodz_INDEXID;

-- drop table NPRODTABLE;

create table NPRODTABLE (
	nprodsku varchar,
	nstart   integer,
	nend     integer,
	nzid     integer
);

grant select on NPRODTABLE to USERID;
grant all on NPRODTABLE to ADMID;

create unique index nprod_INDEXID  on NPRODTABLE (nprodsku);
create index nprodz_INDEXID on NPRODTABLE (nzid);


-- ************** SPECIAL PRODUCTS TABLE *****************

-- A zone ID of 0 is the mark of a 'default' product to span
-- all zones.

-- When creating a new product, always create the default.
-- Then in modify, give the option to make a new zoned product.

-- drop index oprod_INDEXID;
-- drop index oprodz_INDEXID;

-- drop table OPRODTABLE;

create table OPRODTABLE (
	oprodsku varchar,
	ostart   integer,
	oend     integer,
	ozid     integer
);

grant select on OPRODTABLE to USERID;
grant all on OPRODTABLE to ADMID;

create unique index oprod_INDEXID  on OPRODTABLE (oprodsku);
create index oprodz_INDEXID on OPRODTABLE (ozid);


-- ************** RELATED PRODUCT TABLE *****************
-- 
-- A table to identify related products; it carries the
-- primary product, the related product, and an order by
-- sequence number

-- drop table PRODREL;

create table PRODREL (
	-- related product zone
	relzone      integer,
	-- base product sku
	relsku       varchar not null,
	-- product related to relsku
	relprod      varchar not null,
	-- ordering sequence number
	relseq       integer
);

grant select on PRODREL to USERID;
grant all    on PRODREL to ADMID;

-- drop index relsku_INDEXID;
-- drop index relprod_INDEXID;
-- drop index relzone_INDEXID;

create index relsku_INDEXID  on PRODREL (relsku);
create index relprod_INDEXID on PRODREL (relprod);
create index relzone_INDEXID on PRODREL (relzone);

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

-- drop sequence  zone_SEQID;
-- drop sequence  subzone_SEQID;

create sequence zone_SEQID;
create sequence subzone_SEQID;

-- drop index zone_INDEXID;
-- drop index szone_INDEXID;
-- drop index szones_INDEXID;


-- drop table ZONETABLE;
-- drop table SUBZONETABLE;

create table ZONETABLE (
	-- unique numeric zone identifier
	zoneid        integer DEFAULT NEXTVAL ('zone_SEQID'),
	-- description
	zonedescr     varchar,
	-- default currency symbol for this zone
	zonecurrsym   varchar,
	-- zone active flag
	zoneact       integer,
	-- bit flags
	zflag1        integer,
	-- default language for this zone
	zonedeflid    integer
);

create table SUBZONETABLE (
	-- zone ID
	subzid        integer,
	-- unique row ID
	subzsid       integer DEFAULT NEXTVAL ('subzone_SEQID'),
	-- subzone description
	subzdescr     varchar,
	-- tax percent non shipping
	subztaxpern   decimal(6,4),
	-- tax percent shipping
	subztaxpers   decimal(6,4),
	-- tax description non shipping
	subztaxcmtn   varchar(128),
	-- tax description shipping
	subztaxcmts   varchar(128),
	-- vendor ID
	subzvendid    integer,
	-- warehouse ID
	subzwhsid     integer,
	subzflag0     integer,
	-- apply VAT or not?
	subzvat       integer,
	-- require a zipcode
	subzzip       integer,
	-- require a city
	subzcity      integer,
	-- require a state
	subzstate     integer,
	-- require a country
	subzcntry     integer,
	-- parent of this subzone (0 if top level)
	subzparent		integer,
	-- subzone sort sequence number
	subzseq			integer
);

create index zone_INDEXID    on ZONETABLE (zoneid);
create index szvat_INDEXID   on SUBZONETABLE (subzvat);
create index szone_INDEXID   on SUBZONETABLE (subzid);
create index szones_INDEXID  on SUBZONETABLE (subzsid);
create index szonep_INDEXID  on SUBZONETABLE (subzparent);

grant select on ZONETABLE    to USERID;
grant all    on ZONETABLE    to ADMID;
grant select on SUBZONETABLE to USERID;
grant all    on SUBZONETABLE to ADMID;

grant select,update on zone_SEQID    to ADMID;
grant select,update on subzone_SEQID to ADMID;


insert into ZONETABLE values (1, 'COMPANY', '$', 1, 17, 1); 

insert into SUBZONETABLE
 (subzid,subzdescr,subztaxpern,subztaxpers,
  subztaxcmtn,subztaxcmts,subzvendid,subzwhsid,subzflag0,
  subzvat,subzzip,subzcity,subzstate,subzcntry,subzparent,subzseq)
 values (1,'Default Subzone', 0.0, 0.0, '', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0); 


-- ************** VENDOR INFO TABLE *****************

-- drop sequence vendor_SEQID;
create sequence vendor_SEQID;

-- drop index vend_INDEXID;
-- drop index vendz_INDEXID;

-- drop table VENDORTABLE;

create table VENDORTABLE (
  vendid     integer DEFAULT NEXTVAL ('vendor_SEQID'),
  vendzid    integer,
  vendname   varchar,
  vendaddr1  varchar,
  vendaddr2  varchar,
  vendcity   varchar,
  vendstate  varchar,
  vendzip    varchar,
  vendnatl   varchar,
  vendphone  varchar,
  vendfax    varchar,
  vendemail  varchar,
  vsvcname   varchar,
  vsvcaddr1  varchar,
  vsvcaddr2  varchar,
  vsvccity   varchar,
  vsvcstate  varchar,
  vsvczip    varchar,
  vsvcnatl   varchar,
  vsvcphone  varchar,
  vsvcfax    varchar,
  vsvcemail  varchar,
  vendonline varchar,
  vendofline varchar,
  vendoemail varchar,
  vendconfirm varchar,
  vflag1     integer
);


create index vend_INDEXID  on VENDORTABLE (vendid);
create index vendz_INDEXID on VENDORTABLE (vendzid);

grant select on VENDORTABLE to USERID;
grant all on VENDORTABLE to ADMID;


grant select,update on vendor_SEQID to ADMID;


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

-- ************** WEB TABLE *****************

-- drop sequence web_SEQID;
create sequence web_SEQID;

-- drop index web_INDEXID;
-- drop index webl_INDEXID;
-- drop index webz_INDEXID;

-- drop table WEBTABLE;

create table WEBTABLE (
	webid        integer DEFAULT NEXTVAL ('web_SEQID'),
	webzid       integer,
	weblid       integer,
	webdescr     varchar,
	realhome     varchar,
	carthome     varchar,
	webtitle     varchar,
	webback      varchar,
	weblogo      varchar,
	weblogow     integer,
	weblogoh     integer,
	webbg        varchar,
	webtext      varchar,
	weblink      varchar,
	webvlink     varchar,
	webalink     varchar,
	webhdsku     varchar,
	webhdtext    varchar,
	webhdgraph   varchar,
	webhdgraphw  integer,
	webhdgraphh  integer,
	webftsku     varchar,
	webfttext    varchar,
	webftgraph   varchar,
	webftgraphw  integer,
	webftgraphh  integer,
	webdaysinnew integer,
	webnewlogo   varchar,
	webnewlogow  integer,
	webnewlogoh  integer,
	webnewmast   varchar,
	webnewmastw  integer,
	webnewmasth  integer,
	webspeclogo  varchar,
	webspeclogow integer,
	webspeclogoh integer,
	webspecmast  varchar,
	webspecmastw integer,
	webspecmasth integer,
	webviewlogo  varchar,
	webviewlogow integer,
	webviewlogoh integer,
	webcattext   varchar,
	webautodom   char,
	websort      varchar,
	webfree      varchar,
	webdesctmpl  varchar(4096),
	webflags1    integer,
	webprodpage  integer
);

create index web_INDEXID  on WEBTABLE (webid);
create index webl_INDEXID on WEBTABLE (weblid);
create index webz_INDEXID on WEBTABLE (webzid);

grant select,insert,update on WEBTABLE to USERID;
grant all on WEBTABLE to ADMID;


grant select,update on web_SEQID to ADMID;

insert into WEBTABLE
   (webzid, weblid, webdescr, realhome, carthome, webtitle, webback,
        weblogo, weblogow, weblogoh,
        webbg, webtext, weblink, webvlink, webalink,
        webhdsku, webhdtext, webhdgraph, webhdgraphw, webhdgraphh,
        webftsku, webfttext, webftgraph, webftgraphw, webftgraphh, webdaysinnew,        webnewlogo, webnewlogow, webnewlogoh,
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
		'','prodsku','<b><i><font color="#ff0000">Free!</font></i></b>','',3,5);


-- *********** SHIPPING TABLE(S) *************
-- drop sequence ship_SEQID;
-- drop sequence shipthresh_SEQID;
-- drop sequence shipweight_SEQID;
create sequence ship_SEQID;


-- drop index ship_INDEXID;
-- drop index shipl_INDEXID;
-- drop index ships_INDEXID;
-- drop index shipz_INDEXID;

-- drop table SHIPTABLE;
-- drop table SHIPTHRESH;
-- drop table WEIGHTTHRESH;
-- drop table SUBZSHIPTABLE;

create table SHIPTABLE (
	-- shipping table ID, not connected to zone ID
	shipid        integer DEFAULT NEXTVAL ('ship_SEQID'),
	shipzid       integer,
	shiplid       integer,
	shipdescr     varchar,
	-- identifies percent, qty, threshold, etc.
	shipmeth      integer,
	-- percent of total, percent model
	-- this is bad 4,4 is 0.XXXX but postgres say 0.0 is too big....
	shippercent   decimal(5,4),
	-- cost per first item, item model
	shipitem      decimal(10,4),
	-- cost per subsequent item, item model
	shipitem2     decimal(10,4),
	-- method to calculate shipping charges
	shipcalc      varchar,
	-- method to show columns to add (maintenance)
	shipadd       varchar,
	-- method to show columns to update (maintenance)
	shipmaint     varchar,
	-- method to update the SQL tables (maintenance)
	shipupdate    varchar,
	-- auxliary table for shipping data
	shipaux1      varchar,
	-- auxliary table for shipping data
	shipaux2      varchar,
	shipsvccode   varchar,
	active        integer
);

grant select,update on ship_SEQID to ADMID;

--from mysql

insert into SHIPTABLE
        (shipzid, shiplid, shipdescr, shipmeth, shippercent,
         shipitem, shipitem2, shipcalc, shipadd, shipmaint, shipupdate,
         shipaux1, shipaux2, shipsvccode, active)
        values
        (1,1,'Default Shipping',1,0.0,0.0,0.0,'shipthreshper.php','','','','','','',1);


create table SHIPTHRESH (
	shipid        integer,
	shipzid       integer,
	shipszid      integer,
	shiplid       integer,
	shipseq       integer,
	shipamt       decimal(10,2),
	shiplow       decimal(10,2),
	shiphigh      decimal(10,2)
);

create table WEIGHTTHRESH (
	shipid        integer,
	shipzid       integer,
	shipszid      integer,
	shiplid       integer,
	shipseq       integer,
	shipamt       decimal(10,2),
	shiplow       decimal(10,2),
	shiphigh      decimal(10,2)
);

create table SUBZSHIPTABLE (
    shipszid      integer,
    shipid        integer,
    shipdef       integer,
	shiplid       integer
);

create index ship_INDEXID  on SHIPTABLE (shipid);
create index shipl_INDEXID on SHIPTABLE (shiplid);
create index shipz_INDEXID on SHIPTABLE (shipzid);

create index shiptid_INDEXID on SHIPTHRESH (shipid);
create index shiptzid_INDEXID on SHIPTHRESH (shipzid);
create index shiptlid_INDEXID on SHIPTHRESH (shiplid);
create index shiptszid_INDEXID on SHIPTHRESH (shipszid);

create index shipsszid_INDEXID on SUBZSHIPTABLE (shipszid);
create index shipsslid_INDEXID on SUBZSHIPTABLE (shiplid);
create index shipssid_INDEXID on SUBZSHIPTABLE (shipid);

grant select on SHIPTABLE to USERID;
grant select on SHIPTHRESH to USERID;
grant select on SUBZSHIPTABLE to USERID;
grant select on WEIGHTTHRESH to USERID;
grant all on SHIPTABLE to ADMID;
grant all on SHIPTHRESH to ADMID;
grant all on WEIGHTTHRESH to ADMID;
grant all on SUBZSHIPTABLE to ADMID;


insert into SHIPTHRESH
	(shipid, shipzid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,0,3.00,0.0,19.99);
insert into SHIPTHRESH
	(shipid, shipzid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,1,0.05,20.0,10000000.);

insert into WEIGHTTHRESH
	(shipid, shipzid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,0,3.00,0.0,0.99);
insert into WEIGHTTHRESH
	(shipid, shipzid, shiplid, shipseq, shipamt, shiplow, shiphigh)
	values
	(1,1,1,1,0.05,1.0,10000000.);

insert into SUBZSHIPTABLE (shipszid,shipid,shipdef,shiplid) values (1,1,1,1);



-- *********** CUSTOMER TABLE *************

-- drop sequence cust_SEQID;
create sequence cust_SEQID;

-- drop index cbemail_INDEXID;

-- drop table CUSTTABLE;

create table CUSTTABLE (
	-- custid corresponds to purchid in ORDERHEAD table
	custid         integer,
	-- customer billing columns
	custbsal       varchar(20),
	custbfname     varchar(80),
	custbmname     varchar(80),
	custblname     varchar(80),
	custbcompany   varchar(80),
	custbemail     varchar(80),
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
	-- customer shipping columns
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
	-- credit card info
	custccname     varchar(80),
	custccnumber   varchar(80),
	custcctype     varchar(32),
	custccexpmo    char(2),
	custccexpyr    char(4),
	custcccvv      integer,
	-- total orders
	custototal     integer,
	-- total billing
	custbtotal     decimal(12,2),
	-- amount of last order
	custloamt      decimal(12,2),
	-- date of last order
	custlodate     integer,
	-- date of first order
	custfodate     integer,
	-- promo email subscription state
	custpromoemail smallint
);


grant select,update on cust_SEQID to USERID;
grant select,update on cust_SEQID to ADMID;

create index cbemail_INDEXID on CUSTTABLE (custbemail);
create index cbpromo_INDEXID on CUSTTABLE (custpromoemail);

grant select,insert,update on CUSTTABLE to USERID;
grant all on CUSTTABLE to ADMID;


-- *********** SEQUENTIAL CART ID *************
-- 
-- This is unique to the Solid version of FishCartSQL.
-- It is used for sequential order IDs.

-- drop sequence  order_SEQID;
-- create sequence order_SEQID;
-- 
-- grant select,update on order_SEQID to USERID;
-- grant select,update on order_SEQID to ADMID;
-- 
-- "create procedure reset_INSID
-- returns (cartid_seq integer)
-- begin
-- declare next_seq integer;
-- next_seq = 0;
-- exec sequence order_SEQID set value using next_seq;
-- exec sequence order_SEQID.current into next_seq;
-- cartid_seq := next_seq;
-- return;
-- end";
-- 
-- grant execute on reset_INSID to ADMID;


-- ********** Order Summary Table *************
--
-- PGSql Server Layout for the FishCart split CC number delivery tables
--
-- This must be run only once at initial FishCart installation

create table INSTALLID_ccnums (
	userid   integer,
	tstamp   integer,
	fetched  char(1),
	orderid  char(32),
	cc6      char(6)
	);

create index INSTALLID_ccindex on INSTALLID_ccnums ("userid","tstamp");

create table INSTALLID_users (
	userid     integer not null unique,
	username   char(16) not null,
	fullname   char(50) not null
	);

create index INSTALLID_uindex on INSTALLID_users ("userid","username");

insert into INSTALLID_users
	(userid, username, fullname)
	values
	(1,'INSTALLID','COMPANY');

-- create user DBDNAME password DBDPASS;
grant all on INSTALLID_ccnums to DBDNAME;
grant all on INSTALLID_users to DBDNAME;


-- ********** Coupon Table *************
--
-- Layout for the FishCart coupon table
--
-- This must be run only once at initial FishCart installation

-- drop table COUPONTABLE;

create table COUPONTABLE (
	cpnid		varchar(32) not null,
	type		integer,
	cpnsku		varchar(32),
	cpnstart	integer,
	cpnstop		integer,
	cpnminqty	integer,
	cpnminamt	decimal(12,2),
	discount	decimal(12,4),
	-- number of times redeemed
	cpnredeem   integer,
	-- max redemptions this coupon
	cpnmaximum  integer
);

create index cpnid_INDEXID on COUPONTABLE ("cpnid");
create index cpnsku_INDEXID on COUPONTABLE ("cpnsku");

grant select,update on COUPONTABLE to USERID;
grant all on COUPONTABLE to ADMID;


-- ********** Catalog User Password Table *************
--
-- Layout for the FishCart user administration table
--
-- This must be run only once at initial FishCart installation

-- drop table PASSWORDTABLE;

create table PASSWORDTABLE (
	-- non zero if profile active
	pwactive	integer,
	-- active zone for this user, 0 for all zones
	pwzone		integer,
	-- January - December login stats
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
	-- description
	pwdescr		varchar(80),
	-- email address
	pwemail		varchar(80),
	-- user login
	pwuid		varchar(32),
	-- user password
	pwpw		varchar(32),
	-- possible cart order ID for ESD
	pwoid		varchar(32)
);

create index pwuid_INDEXID on PASSWORDTABLE ("pwuid");
create index pwpw_INDEXID  on PASSWORDTABLE ("pwpw");
create index pwact_INDEXID  on PASSWORDTABLE ("pwactive");
create index pwoid_INDEXID  on PASSWORDTABLE ("pwoid");

grant all on PASSWORDTABLE to ADMID;
grant select,insert,update on PASSWORDTABLE to USERID;


-- ********** Maintenance Password Table *************
--
-- Layout for the FishCart maintenance user administration table
--
-- This must be run only once at initial FishCart installation

-- drop table ADMPASSWORDTABLE;

create table ADMPASSWORDTABLE (
	-- non zero if profile active
	admpwactive	integer,
	-- active zone for this user, 0 for all zones
	admpwzone	integer,
	-- January - December login stats
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
	-- description
	admpwdescr	varchar(80),
	-- email address
	admpwemail	varchar(80),
	-- user login
	admpwuid	varchar(32),
	-- user password
	admpwpw		varchar(32)
);

create index admpwuid_INDEXID on ADMPASSWORDTABLE ("admpwuid");
create index admpwpw_INDEXID  on ADMPASSWORDTABLE ("admpwpw");
create index admpwact_INDEXID on ADMPASSWORDTABLE ("admpwactive");

grant all on ADMPASSWORDTABLE to ADMID;


-- ********** ESD Control Table *************
--
-- Layout for the FishCart Electronic Software Delivery (ESD)
-- control table
--
-- This must be run only once at initial FishCart installation

-- drop sequence esd_SEQID;
create sequence esd_SEQID;

-- drop table ESDTABLE;

create table ESDTABLE (
	-- unique row ID
	esdid		integer DEFAULT NEXTVAL ('esd_SEQID'),
	-- boolean row active column (0=inactive, !0=active)
	esdact		integer,
	-- order line ID from purchase
	esdolid		integer,
	-- custtable purchaser ID
	esdpurchid	integer,
	-- file d/l start (epoch time)
	esddlact	integer,
	-- file d/l expiration (epoch time)
	esddlexp	integer,
	-- file d/l count
	esddlcnt	integer,
	-- file d/l limit
	esddlmax	integer,
	-- version number
	esdversion	varchar(32),
	-- product name
	esdprodnam	varchar(64),
	-- serial number
	esdsernum	varchar(64),
	-- activation code
	esdactcod	varchar(64),
	-- file d/l filename (DocumentRoot relative path)
	esddlfile	varchar(255)
);

-- drop index esdid_INDEXID;
-- drop index esdolid_INDEXID;
-- drop index esdsernum_INDEXID;
-- drop index esdpurchid_INDEXID;
-- drop index esdact_INDEXID;
-- drop index esddlexp_INDEXID;

create unique index esdid_INDEXID on ESDTABLE ("esdid");
create unique index esdolid_INDEXID on ESDTABLE ("esdolid");
create unique index esdsernum_INDEXID on ESDTABLE ("esdsernum");
create unique index esdpurchid_INDEXID on ESDTABLE ("esdpurchid");
create        index esdact_INDEXID on ESDTABLE ("esdact");
create        index esddlexp_INDEXID on ESDTABLE ("esddlexp");

grant select,insert,update on ESDTABLE to USERID;
grant all on ESDTABLE to ADMID;


-- ********** Auxilliary Links Table *************
--
-- Additional links

-- drop sequence aux_SEQID;
create sequence aux_SEQID;

-- drop table AUXLINKTABLE;

create table AUXLINKTABLE (
	rid		integer DEFAULT NEXTVAL ('aux_SEQID'),
	seq		integer,
	loc		integer,
	title	varchar(64),
	url		varchar(255)
);

-- drop index ridlnk_INDEXID;

create unique index ridlnk_INDEXID on AUXLINKTABLE ("rid");

grant select,insert,update on AUXLINKTABLE to USERID;
grant all on AUXLINKTABLE to ADMID;


-- ********** Auxilliary Text Table *************
--
-- Additional text

-- drop sequence auxtext_SEQID;
create sequence auxtext_SEQID;

-- drop table AUXTXTTABLE;

create table AUXTXTTABLE (
	rid		integer DEFAULT NEXTVAL ('aux_SEQID'),
	seq		integer,
	loc		integer,
	title		varchar(64),
	text		varchar(255)
);

-- drop index ridtxt_INDEXID;

create unique index ridtxt_INDEXID on AUXLINKTABLE ("rid");

grant select,insert,update on AUXTXTTABLE to USERID;
grant all on AUXTXTTABLE to ADMID;

-- ********** Cart ID Sequence Control Table *************
--
-- Layout for the single row cart id sequence number table

-- drop table SEQTABLE;

create table SEQTABLE (
	-- the last allocated cart ID sequence number
	cartseq		integer,
	-- day of the month of the last cart ID assigned
	lastday		integer
);

grant select,insert,update on SEQTABLE to USERID;
grant all on SEQTABLE to ADMID;

insert into SEQTABLE (cartseq,lastday) values (1,0);



-- ********** Country ISO Code Table ************


-- drop table COUNTRYTABLE;
-- drop table COUNTRYLANG;

create table COUNTRYTABLE (
	ctryzid		integer,
	ctrylid		integer,
	ctryiso		char(3),
	ctryseq		integer,
	ctryactive	integer
);

create table COUNTRYLANG (
	ctrylangliso	char(3),
	ctrylangciso	char(3),
	ctrylangciso2	char(2),
	ctrylangname	varchar(64)
);

grant select on COUNTRYTABLE to USERID;
grant all on COUNTRYTABLE to ADMID;
grant select on COUNTRYLANG to USERID;
grant all on COUNTRYLANG to ADMID;

create index ctryzid_INDEXID on COUNTRYTABLE ("ctryzid");
create index ctrylid_INDEXID on COUNTRYTABLE ("ctrylid");
create index ctryiso_INDEXID on COUNTRYTABLE ("ctryiso");
create index ctryactive_INDEXID on COUNTRYTABLE ("ctryactive");

create index ctrylangliso_INDEXID on COUNTRYLANG ("ctrylangliso");
create index ctrylangciso_INDEXID on COUNTRYLANG ("ctrylangciso");
create index ctrylangciso2_INDEXID on COUNTRYLANG ("ctrylangciso2");

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
