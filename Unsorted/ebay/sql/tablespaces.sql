/*	$Id: tablespaces.sql,v 1.12 1999/05/19 02:35:09 josh Exp $	*/
/*
** tablespaces.sql
**
** Create tablespaces
*/

/* item tablespace on raid device; specifically for ebay_items only */

/* for ebay_items */
	create tablespace ritemd01
		datafile '/oracle-items/ebay/oradata/ritemd01.dbf'
		size 501M 
		autoextend on next 101M;

	create tablespace ritemi01
	    datafile '/oracle18/ebay/oradata/ritemi01.dbf'
		size 500M autoextend on next 100M;

/* for ebay_item_desc */
	create tablespace ritemd02
		datafile '/oracle-items/ebay/oradata/ritemd02.dbf'
		size 2001M 
		autoextend on next 101M;

	create tablespace ritemi02
	    datafile '/oracle18/ebay/oradata/ritemi02.dbf'
		size 101M autoextend on next 51M;
/* replace with itemi02 with quick i/o */

/* for ebay_item_info */
	create tablespace ritemd03
		datafile '/oracle-items/ebay/oradata/ritemd03.dbf'
		size 131M 
		autoextend on next 41M;

	create tablespace ritemi03
	    datafile '/oracle18/ebay/oradata/ritemi03.dbf'
		size 76M autoextend on next 26M;

/* bids index tablespace */
/* split up into 3 tablespaces -- 1 per index -- see below.
create tablespace bidi02
	datafile '/oracle18/ebay/oradata/bidi02.dbf'
	size 810M 
	autoextend on next 100M;

 alter tablespace bidi02 rename datafile 
 '/oracle18/ebay/oradata/bidi02.dbf' to '/oracle07/ebay/oradata/bidi02.dbf' ;
*/
/* tablespaces for misc. static tables */

create tablespace statmiscd
	datafile '/oracle-items/ebay/oradata/statmiscd.dbf'
	size 101M 
	autoextend on next 50M;

create tablespace statmisci
	datafile '/oracle18/ebay/oradata/statmisci.dbf'
	size 101M 
	autoextend on next 50M;

/* tablespaces for misc. dynamic tables */

create tablespace dynmiscd
	datafile '/oracle-items/ebay/oradata/dynmiscd.dbf'
	size 101M 
	autoextend on next 50M;

create tablespace dynmisci
	datafile '/oracle18/ebay/oradata/dynmisci.dbf'
	size 101M 
	autoextend on next 50M;

/* tablespaces for feedback */
/* feedback */
	create tablespace rfeedbackd01
		datafile '/oracle-items/ebay/oradata/rfeedbackd01.dbf'
		size 16M 
		autoextend on next 5M;

	create tablespace rfeedbacki01
	    datafile '/oracle18/ebay/oradata/rfeedbacki01.dbf'
		size 11M autoextend on next 5M;

/* feedback detail */
	create tablespace feedbackd02
		datafile '/oracle07/ebay/oradata/feedbackd02.dbf'
		size 410M autoextend on next 51M;

	create tablespace feedbacki02
		datafile '/oracle18/ebay/oradata/feedbacki02.dbf'
		size 255M autoextend on next 51M;

/* tablespaces for users */

	create tablespace ruserd01
		datafile '/oracle-items/ebay/oradata/ruserd01.dbf'
		size 61M 
		autoextend on next 11M;

	create tablespace ruseri01
	    datafile '/oracle18/ebay/oradata/ruseri01.dbf'
		size 76M autoextend on next 21M;

	create tablespace ruserd02
		datafile '/oracle-items/ebay/oradata/ruserd02.dbf'
		size 81M autoextend on next 21M;

	create tablespace ruseri02
		datafile '/oracle18/ebay/oradata/ruseri02.dbf'
		size 31M autoextend on next 2M;

	create tablespace ruserd03
		datafile '/oracle-items/ebay/oradata/ruserd03.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruseri03
		datafile '/oracle18/ebay/oradata/ruseri03.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruserd04
		datafile '/oracle-items/ebay/oradata/ruserd04.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruseri04
		datafile '/oracle18/ebay/oradata/ruseri04.dbf'
		size 10M autoextend on next 5M;

	create tablespace ruserd05
		datafile '/oracle-items/ebay/oradata/ruserd05.dbf'
		size 50M autoextend on next 10M;

	create tablespace ruseri05
		datafile '/oracle18/ebay/oradata/ruseri05.dbf'
		size 30M autoextend on next 10M;

	create tablespace ruserd06
		datafile '/oracle-items/ebay/oradata/ruserd06.dbf'
		size 101M 
		autoextend on next 30M;

	create tablespace ruseri06
		datafile '/oracle18/ebay/oradata/ruseri06.dbf'
		size 51M autoextend on next 20M;

	create tablespace ruserd07
		datafile '/oracle-items/ebay/oradata/ruserd07.dbf'
		size 101M 
		autoextend on next 30M;

	create tablespace ruseri07
		datafile '/oracle18/ebay/oradata/ruseri07.dbf'
		size 51M autoextend on next 20M;

vxmkcdev -o oracle_file -s 40m /oracle/rdata01/ebay/oradata/userd08.dbf

	create tablespace userd08
		datafile '/oracle/rdata01/ebay/oradata/userd08.dbf'
		size 40M 
		autoextend off;

/* files moved around */

 alter tablespace feedbackd02 rename datafile 
 '/oracle07/ebay/oradata/feedbackd02.dbf' to '/oracle06/ebay/oradata/feedbackd02.dbf' ;
 alter tablespace statsd01 rename datafile 
 '/oracle07/ebay/oradata/statsd01.dbf' to '/oracle06/ebay/oradata/statsd01.dbf' ;
  alter tablespace bizdevd01 rename datafile 
 '/oracle03/ebay/oradata/bizdevd01.dbf' to '/oracle21/ebay/oradata/bizdevd01.dbf' ;
 alter tablespace bizdevi01 rename datafile 
 '/oracle05/ebay/oradata/bizdevi01.dbf' to '/oracle20/ebay/oradata/bizdevi01.dbf' ;
 alter tablespace summaryi01 rename datafile 
 '/oracle05/ebay/oradata/summaryi01.dbf' to '/oracle20/ebay/oradata/summaryi01.dbf' ;
 alter tablespace itemarci1 rename datafile 
 '/oracle05/ebay/oradata/itemarci1.dbf' to '/oracle20/ebay/oradata/itemarci1.dbf' ;

 /* split up bid index tablespace */

create tablespace bidi02
	datafile '/oracle18/ebay/oradata/bidi02.dbf'
	size 810M 
	autoextend on next 100M;

 alter tablespace bidi02 rename datafile 
 '/oracle18/ebay/oradata/bidi02.dbf' to '/oracle07/ebay/oradata/bidi02.dbf' ;

 	alter tablespace ritemd02 add datafile
		'/oracle-items/ebay/oradata/ritemd02a.dbf' size 101M;

 	alter tablespace itemarc1 add datafile
		'/oracle10/ebay/oradata/itemarc1b.dbf' size 800M;

 	alter tablespace ritemd02 add datafile
		'/oracle-items/ebay/oradata/ritemd02d.dbf' size 210M;


/* new tablespaces for ebay_user_past_aliases */
vxmkcdev -o oracle_file -s 100m /oracle/rdata01/ebay/oradata/userd03.dbf
vxmkcdev -o oracle_file -s 100m /oracle/rdata01/ebay/oradata/useri03.dbf

	create tablespace userd03
		datafile '/oracle/rdata01/ebay/oradata/userd03.dbf'
		size 100M autoextend off;

	create tablespace useri03
		datafile '/oracle/rdata01/ebay/oradata/useri03.dbf'
		size 100M autoextend off;

vxmkcdev -o oracle_file -s 75m /oracle/rdata01/ebay/oradata/boardd01.dbf
vxmkcdev -o oracle_file -s 50m /oracle/rdata01/ebay/oradata/boardi01.dbf

	create tablespace boardd01
		datafile '/oracle/rdata01/ebay/oradata/boardd01.dbf'
		size 75M autoextend off;

	create tablespace boardi01
		datafile '/oracle/rdata01/ebay/oradata/boardi01.dbf'
		size 50M autoextend off;

/* on algebra */
exp ebayqa/skippy tables=ebay_bulletin_board_control direct=Y indexes=N grants=Y constraints=N file=bbc.dmp

/* ftp file to python */
/* create tables */
imp scott/haw98 file = bbc.dmp commit=Y grants=Y ignore=Y Full=Y buffer = 122880


/* extensions */
alter tablespace ACCOUNTD01
add datafile '/oracle/rdata01/ebay/oradata/accountd01c.dbf'
size 820M

/* not created - not needed 
alter tablespace ACHISTORYD01
add datafile '/oracle/rdata01/ebay/oradata/achistoryd01a.dbf'
size 550M
*/

/* this is ebay_bids_arc2 - nothing should be going here - only to ebay_bids_arc
alter tablespace BIDD02
add datafile '/oracle/rdata01/ebay/oradata/bidd02a.dbf'
size 210M
*/

alter tablespace FEEDBACKD02
add datafile '/oracle/rdata01/ebay/oradata/feedbackd02a.dbf'
size 235M;

alter tablespace FEEDBACKI01
add datafile '/oracle/rdata01/ebay/oradata/feedbacki01a.dbf'
size 200M;

alter tablespace FEEDBACKI02
add datafile '/oracle/rdata01/ebay/oradata/feedbacki02a.dbf'
size 200M;

alter tablespace ITEMARCI1
add datafile '/oracle/rdata01/ebay/oradata/itemarci1a.dbf'
size 160M;

alter tablespace RBIDD01
add datafile '/oracle/rdata01/ebay/oradata/rbidd01a.dbf'
size 420M;

alter tablespace RBIDI01
add datafile '/oracle/rdata01/ebay/oradata/rbidi01a.dbf'
size 110M;

alter tablespace RBIDI02
add datafile '/oracle/rdata01/ebay/oradata/rbidi02a.dbf'
size 110M;

alter tablespace RBIDI03
add datafile '/oracle/rdata01/ebay/oradata/rbidi03a.dbf'
size 110M;

alter tablespace RITEMD01
add datafile '/oracle/rdata01/ebay/oradata/ritemd01a.dbf'
size 310M;

alter tablespace RITEMD03
add datafile '/oracle/rdata01/ebay/oradata/ritemd03a.dbf'
size 130M;

alter tablespace RITEMI03
add datafile '/oracle/rdata01/ebay/oradata/ritemi03a.dbf'
size 60M;

alter tablespace RUSERD01
add datafile '/oracle/rdata01/ebay/oradata/ruserd01a.dbf'
size 30M;

alter tablespace RUSERD02
add datafile '/oracle/rdata01/ebay/oradata/ruserd02a.dbf'
size 70M;

alter tablespace RUSERD04
add datafile '/oracle/rdata01/ebay/oradata/ruserd04a.dbf'
size 240M;

alter tablespace RUSERI01
add datafile '/oracle/rdata01/ebay/oradata/ruseri01a.dbf'
size 85M;

alter tablespace RUSERI02
add datafile '/oracle/rdata01/ebay/oradata/ruseri02a.dbf'
size 44M;

alter tablespace RUSERI04
add datafile '/oracle/rdata01/ebay/oradata/ruseri04a.dbf'
size 126M;

alter tablespace RUSERI06
add datafile '/oracle/rdata01/ebay/oradata/ruseri06a.dbf'
size 50M;

alter tablespace RUSERI07
add datafile '/oracle/rdata01/ebay/oradata/ruseri07a.dbf'
size 50M;

alter tablespace RUSERI04
add datafile '/oracle/rdata01/ebay/oradata/ruseri04b.dbf'
size 146M;

alter tablespace RUSERD06
add datafile '/oracle/rdata01/ebay/oradata/ruserd06a.dbf'
size 100M;

alter tablespace RUSERD07
add datafile '/oracle/rdata01/ebay/oradata/ruserd07a.dbf'
size 100M;

/* Not done - unused.
alter tablespace TACCOUNTI01
add datafile '/oracle/rdata01/ebay/oradata/taccounti01a.dbf'
size 30M
*/
alter tablespace RBIDI01
add datafile '/oracle/rdata01/ebay/oradata/rbidi01b.dbf'
size 220M;

alter tablespace RBIDI02
add datafile '/oracle/rdata01/ebay/oradata/rbidi02b.dbf'
size 220M;

alter tablespace RBIDI03
add datafile '/oracle/rdata01/ebay/oradata/rbidi03b.dbf'
size 220M;

alter tablespace RITEMI02
add datafile '/oracle/rdata01/ebay/oradata/ritemi02a.dbf'
size 220M;

alter tablespace RUSERI04
add datafile '/oracle/rdata01/ebay/oradata/ruseri04c.dbf'
size 146M;

/* rebuild item indices */
vxmkcdev -o oracle_file -s 300M /oracle/rdata01/ebay/oradata/itemi11.dbf

	create tablespace itemi11
	    datafile '/oracle/rdata01/ebay/oradata/itemi11.dbf'
		size 300M autoextend off;

alter index items_pk
	rebuild unrecoverable parallel (degree 3) tablespace itemi11;

/* once this is done, ritemi01 should be freed up and dropped */

vxmkcdev -o oracle_file -s 250M /oracle/rdata01/ebay/oradata/itemi12.dbf

	create tablespace itemi12
	    datafile '/oracle/rdata01/ebay/oradata/itemi12.dbf'
		size 250M autoextend off;

alter index ebay_items_seller_index
	rebuild unrecoverable parallel (degree 3) tablespace itemi12;

vxmkcdev -o oracle_file -s 200M /oracle/rdata01/ebay/oradata/itemi13.dbf

	create tablespace itemi13
	    datafile '/oracle/rdata01/ebay/oradata/itemi13.dbf'
		size 250M autoextend off;

alter index ebay_items_bidder_index
	rebuild unrecoverable parallel (degree 3) tablespace itemi13;

vxmkcdev -o oracle_file -s 200M /oracle/rdata01/ebay/oradata/itemi14.dbf

	create tablespace itemi14
	    datafile '/oracle/rdata01/ebay/oradata/itemi14.dbf'
		size 200M autoextend off;

alter index ebay_items_starting_index
	rebuild unrecoverable parallel (degree 3) tablespace itemi14;


vxmkcdev -o oracle_file -s 250M /oracle/rdata01/ebay/oradata/itemi15.dbf

	create tablespace itemi15
	    datafile '/oracle/rdata01/ebay/oradata/itemi15.dbf'
		size 250M autoextend off;

alter index ebay_items_ending_index
	rebuild unrecoverable parallel (degree 3) tablespace itemi15;


vxmkcdev -o oracle_file -s 250M /oracle/rdata01/ebay/oradata/itemi16.dbf

	create tablespace itemi16
	    datafile '/oracle/rdata01/ebay/oradata/itemi16.dbf'
		size 250M autoextend off;

alter index ebay_items_category_index
	rebuild unrecoverable parallel (degree 3) tablespace itemi16;

vxmkcdev -o oracle_file -s 800M /oracle/rdata01/ebay/oradata/itemi17.dbf

	create tablespace itemi17
	    datafile '/oracle/rdata01/ebay/oradata/itemi17.dbf'
		size 800M autoextend off;

alter index ebay_items_category_index
	rebuild unrecoverable parallel (degree 3) tablespace itemi17;


/* other item tables indices */

vxmkcdev -o oracle_file -s 500M /oracle/rdata01/ebay/oradata/itemi02.dbf

	create tablespace itemi02
	    datafile '/oracle/rdata01/ebay/oradata/itemi02.dbf'
		size 500M autoextend off;

alter index ritem_desc_pk rebuild parallel 6 tablespace itemi02;


vxmkcdev -o oracle_file -s 250M /oracle/rdata01/ebay/oradata/itemi03.dbf

	create tablespace itemi03
	    datafile '/oracle/rdata01/ebay/oradata/itemi03.dbf'
		size 250M autoextend off;

alter index ritem_info_pk rebuild parallel 6 tablespace itemi03;


vxmkcdev -o oracle_file -s 160m /oracle/rdata01/ebay/oradata/accountd04.dbf

	create tablespace accountd04
		datafile '/oracle/rdata01/ebay/oradata/accountd04.dbf'
		size 160M 
		autoextend off;

vxmkcdev -o oracle_file -s 210m /oracle/rdata01/ebay/oradata/accounti04.dbf

	create tablespace accounti04
		datafile '/oracle/rdata01/ebay/oradata/accounti04.dbf'
		size 210M 
		autoextend off;


/* tablespaces */

vxmkcdev -o oracle_file -s 100m /oracle/rdata01/ebay/oradata/qdstatsi01a.dbf

        alter tablespace qdstatsi01
            add   datafile '/oracle/rdata01/ebay/oradata/qdstatsi01a.dbf'
                size 100M
                ;

vxmkcdev -o oracle_file -s 200M /oracle/rdata01/ebay/oradata/qruserd07a.dbf

        alter tablespace qRUSERD07
            add    datafile '/oracle/rdata01/ebay/oradata/qruserd07a.dbf'
                size 200M
                ;

vxmkcdev -o oracle_file -s 200M /oracle/rdata01/ebay/oradata/qruserd06a.dbf

        alter tablespace qRUSERD06
            add    datafile '/oracle/rdata01/ebay/oradata/qruserd06a.dbf'
                size 200M
                ;

        alter tablespace RUSERD02
            add    datafile '/oracle/rdata01/ebay/oradata/ruserd02b.dbf'
                size 200M ;

        alter tablespace ITEMARC3
            add    datafile '/oracle/rdata01/ebay/oradata/itemarc3c.dbf'
                size 600M ;

        alter tablespace BIDARCI1
            add    datafile '/oracle/rdata01/ebay/oradata/bidarci1b.dbf'
                size 600M ;

/* June 19 */

        alter tablespace ITEMARC2
            add    datafile '/oracle/rdata01/ebay/oradata/itemarc2c.dbf'
                size 395M ;

        alter tablespace ITEMARCI1
            add    datafile '/oracle/rdata01/ebay/oradata/itemarci1b.dbf'
                size 390M ;

        alter tablespace RITEMD03
            add    datafile '/oracle/rdata01/ebay/oradata/ritemd03b.dbf'
                size 310M ;

/* July 6, 1998 */
alter tablespace RITEMD01
add datafile '/oracle/rdata02/ebay/oradata/ritemd01b.dbf'
size 310M;

alter tablespace ACCOUNTD01
add datafile '/oracle/rdata01/ebay/oradata/accountd01d.dbf'
size 820M;


/* June 30 */
        alter tablespace BIDARC1
            add    datafile '/oracle/rdata01/ebay/oradata/bidarc1c.dbf'
                size 510M ;

        alter tablespace itemarci2
            add    datafile '/oracle/rdata01/ebay/oradata/itemarci2a.dbf'
                size 310M ;

        alter tablespace RUSERD01
            add    datafile '/oracle/rdata01/ebay/oradata/ruserd01b.dbf'
                size 310M ;

/* july 30 */
        alter tablespace ITEMARC3
            add    datafile '/oracle/rdata01/ebay/oradata/itemarc3e.dbf'
                size 600M ;

/* Aug 3, 98 */

        alter tablespace BIDARC1
            add    datafile '/oracle/rdata01/ebay/oradata/bidarc1d.dbf'
                size 1010M ;

alter tablespace ACCOUNTI02
add datafile '/oracle/rdata01/ebay/oradata/accounti02f.dbf'
size 820M;

vxmkcdev -o oracle_file -s 150m /oracle/rdata04/ebay/oradata/useri01.dbf

	create tablespace useri01
	    datafile '/oracle/rdata04/ebay/oradata/useri01.dbf'
		size 150M autoextend off;

/* Aug 10 98 */
alter tablespace RBIDI03
add datafile '/oracle/rdata04/ebay/oradata/rbidi03c.dbf'
size 220M;

/* Aug 17 98 */

       alter tablespace BIDARCI1
            add    datafile '/oracle/rdata07/ebay/oradata/bidarci1d.dbf'
                size 600M ;

       alter tablespace ITEMARC3
            add    datafile '/oracle/rdata06/ebay/oradata/itemarc3f.dbf'
                size 600M ;

/* Aug 19 */
alter tablespace FEEDBACKD02
add datafile '/oracle/rdata03/ebay/oradata/feedbackd02c.dbf'
size 500M;

alter tablespace RUSERI02
add datafile '/oracle/rdata07/ebay/oradata/ruseri02b.dbf'
size 44M;

alter tablespace RUSERI04
add datafile '/oracle/rdata06/ebay/oradata/ruseri04e.dbf'
size 300M;


/* July 9 */
        alter tablespace BIDARCI1
            add    datafile '/oracle/rdata01/ebay/oradata/bidarci1c.dbf'
                size 600M ;

        alter tablespace ITEMARC3
            add    datafile '/oracle/rdata01/ebay/oradata/itemarc3d.dbf'
                size 600M ;

/* July 14 */
alter tablespace FEEDBACKI02
add datafile '/oracle/rdata04/ebay/oradata/feedbacki02b.dbf'
size 400M;

/* july 23 */
alter tablespace RBIDD01
add datafile '/oracle/rdata05/ebay/oradata/rbidd01b.dbf'
size 420M;


/* Sep 4, 98 */
        alter tablespace ITEMARC3
            add    datafile '/oracle/rdata06/ebay/oradata/itemarc3g.dbf'
                size 600M ;



/* July 9 */
        alter tablespace BIDARCI1
            add    datafile '/oracle/rdata01/ebay/oradata/bidarci1c.dbf'
                size 600M ;

        alter tablespace ITEMARC3
            add    datafile '/oracle/rdata01/ebay/oradata/itemarc3d.dbf'
                size 600M ;

/* July 14 */
alter tablespace FEEDBACKI02
add datafile '/oracle/rdata04/ebay/oradata/feedbacki02b.dbf'
size 400M;

/* july 23 */
alter tablespace RBIDD01
add datafile '/oracle/rdata05/ebay/oradata/rbidd01b.dbf'
size 420M;

/* Sep 14, 98 */
alter tablespace RITEMI03
add datafile '/oracle/rdata07/ebay/oradata/ritemi03b.dbf'
size 200M;


/* July 9 */
        alter tablespace BIDARCI1
            add    datafile '/oracle/rdata01/ebay/oradata/bidarci1c.dbf'
                size 600M ;

        alter tablespace ITEMARC3
            add    datafile '/oracle/rdata01/ebay/oradata/itemarc3d.dbf'
                size 600M ;

/* July 14 */
alter tablespace FEEDBACKI02
add datafile '/oracle/rdata04/ebay/oradata/feedbacki02b.dbf'
size 400M;

/* july 23 */
alter tablespace RBIDD01
add datafile '/oracle/rdata05/ebay/oradata/rbidd01b.dbf'
size 420M;


/* Sep 4, 98 */
        alter tablespace ITEMARC3
            add    datafile '/oracle/rdata06/ebay/oradata/itemarc3g.dbf'
                size 600M ;


/* sept 9, 1998 */

alter tablespace ACCOUNTD01
add datafile '/oracle/rdata07/ebay/oradata/accountd01e.dbf'
size 820M;

alter tablespace ritemd02 add datafile
		'/oracle/rdata05/ebay/oradata/ritemd02h.dbf' size 510M;

/* Sep 21, 1998 */
alter tablespace bidarci1
add datafile '/oracle/rdata07/ebay/oradata/bidarci1e.dbf' size 500M;

alter tablespace ITEMARC3
add datafile '/oracle/rdata06/ebay/oradata/itemarc3h.dbf' size 600M;


vxmkcdev -o oracle_file -s 160m /oracle/rdata02/ebay/oradata/accountd05.dbf

	create tablespace accountd05
		datafile '/oracle/rdata02/ebay/oradata/accountd05.dbf'
		size 160M 
		autoextend off;

vxmkcdev -o oracle_file -s 160m /oracle/rdata04/ebay/oradata/accounti05.dbf

	create tablespace accounti05
		datafile '/oracle/rdata04/ebay/oradata/accounti05.dbf'
		size 160M 
		autoextend off;

/* Sept 7, 1998 */
/* aboutme tablespaces */

vxmkcdev -o oracle_file -s 310m /oracle/rdata05/ebay/oradata/qpagesd01.dbf
vxmkcdev -o oracle_file -s 160m /oracle/rdata08/ebay/oradata/qpagesi01.dbf

create tablespace qpagesd01 datafile '/oracle/rdata05/ebay/oradata/qpagesd01.dbf'
		size 310M autoextend off;

create tablespace qpagesi01 datafile '/oracle/rdata08/ebay/oradata/qpagesi01.dbf'
		size 160M autoextend off;

vxmkcdev -o oracle_file -s 310m /oracle/rdata05/ebay/oradata/qpagesd03.dbf
vxmkcdev -o oracle_file -s 310m /oracle/rdata08/ebay/oradata/qpagesi03.dbf

create tablespace qpagesd03 datafile '/oracle/rdata05/ebay/oradata/qpagesd03.dbf'
		size 310M autoextend off;

create tablespace qpagesi03 datafile '/oracle/rdata08/ebay/oradata/qpagesi03.dbf'
		size 310M autoextend off;

vxmkcdev -o oracle_file -s 1200m /oracle/rdata05/ebay/oradata/qpagesd02.dbf
vxmkcdev -o oracle_file -s 310m /oracle/rdata08/ebay/oradata/qpagesi02.dbf

create tablespace qpagesd02 datafile '/oracle/rdata05/ebay/oradata/qpagesd02.dbf'
		size 1200M autoextend off;

create tablespace qpagesi02 datafile '/oracle/rdata08/ebay/oradata/qpagesi02.dbf'
		size 310M autoextend off;

vxmkcdev -o oracle_file -s 500m /oracle/rdata01/ebay/oradata/systebay02.dbf 
alter tablespace system add datafile
'/oracle/rdata01/ebay/oradata/systebay02.dbf' size 500m;

vxmkcdev -o oracle_file -s 500m /oracle/rdata06/ebay/oradata/ritemd03c.dbf

vxmkcdev -o oracle_file -s 1000M /oracle/rdata01/ebay/oradata/feedbackd03.dbf
vxmkcdev -o oracle_file -s 500M /oracle/rdata04/ebay/oradata/feedbacki03.dbf

create tablespace feedbackd03 datafile '/oracle/rdata01/ebay/oradata/feedbackd03.dbf'
		size 1000M autoextend off;

create tablespace feedbacki03 datafile '/oracle/rdata04/ebay/oradata/feedbacki03.dbf'
		size 500M autoextend off;

/* Nov 29 */
vxmkcdev -o oracle_file -s 820M /oracle/rdata06/ebay/oradata/accounti02g_q.dbf

alter tablespace ACCOUNTI02
add datafile '/oracle/rdata06/ebay/oradata/accounti02g_q.dbf'
size 820M;

alter tablespace accountd04
add datafile '/oracle/rdata07/ebay/oradata/accountd04a.dbf'
size 300M;

alter tablespace ruseri04
add datafile '/oracle/rdata06/ebay/oradata/ruseri04g.dbf'
size 500M;

vxmkcdev -o oracle_file -s 1024M /oracle/rdata05/ebay/oradata/ritemd02l.dbf

 	alter tablespace ritemd02 add datafile
		'/oracle/rdata05/ebay/oradata/ritemd02l.dbf' size 1024M;


alter tablespace RITEMD03
add datafile '/oracle/rdata06/ebay/oradata/ritemd03c.dbf'
size 500M;


 	alter tablespace ritemd02 add datafile
		'/oracle/rdata05/ebay/oradata/ritemd02n.dbf' size 1024M;

 	alter tablespace qbidsi01 add datafile
		'/oracle/rdata04/ebay/oradata/qbidsi01a.dbf' size 1024M;

 	alter tablespace ruserd04 add datafile
		'/oracle/rdata07/ebay/oradata/ruserd04d.dbf' size 500M;

/* 12/20/98 */
 	alter tablespace itemarc3 add datafile
		'/oracle/rdata06/ebay/oradata/itemarc3l.dbf' size 600M;

 	alter tablespace itemarci2 add datafile
		'/oracle/rdata07/ebay/oradata/itemarci2c.dbf' size 300M;

create tablespace ritemd04 datafile '/oracle/rdata09/ebay/oradata/ritemd04.dbf'
		size 1000M autoextend off;

create tablespace ritemi04 datafile '/oracle/rdata08/ebay/oradata/ritemi04.dbf'
		size 400M autoextend off;


 	alter tablespace ritemd02 add datafile
		'/oracle/rdata09/ebay/oradata/ritemd02o.dbf' size 1024M;

 	alter tablespace ritemd01 add datafile
		'/oracle/rdata02/ebay/oradata/itemd01e.dbf' size 500M;

 	alter tablespace qruserd06 add datafile
		'/oracle/rdata09/ebay/oradata/qruserd06c.dbf' size 500M;

 	alter tablespace feedbacki03 add datafile
		'/oracle/rdata04/ebay/oradata/feedbacki03a.dbf' size 500M;

/* 12/21/98 */

alter tablespace ACCOUNTD01
add datafile '/oracle/rdata09/ebay/oradata/accountd01g.dbf'
size 1024M;

alter tablespace accountd03
add datafile '/oracle/rdata09/ebay/oradata/accountd03a.dbf'
size 1024M;

alter tablespace accounti02
add datafile '/oracle/rdata06/ebay/oradata/accounti02h.dbf'
size 500M;

alter tablespace ritemd03 add datafile
'/oracle/rdata06/ebay/oradata/itemd03d.dbf' size 300M;

alter tablespace ritemi02 add datafile
'/oracle/rdata04/ebay/oradata/itemd02c.dbf' size 300M;

alter tablespace qitemsi add datafile
'/oracle/rdata04/ebay/oradata/qitemsi01a.dbf' size 500M;

alter tablespace qitemsi02 add datafile
'/oracle/rdata04/ebay/oradata/qitemsi02a.dbf' size 500M;

alter tablespace qitemsi03 add datafile
'/oracle/rdata04/ebay/oradata/qitemsi03a.dbf' size 500M;

alter tablespace qitemsi04 add datafile
'/oracle/rdata04/ebay/oradata/qitemsi04a.dbf' size 500M;

alter tablespace qitemsi05 add datafile
'/oracle/rdata04/ebay/oradata/qitemsi05-03.dbf' size 1024M;

alter tablespace qitemsi06 add datafile
'/oracle/rdata04/ebay/oradata/qitemsi06a.dbf' size 500M;

alter tablespace qitemsi07 add datafile
'/oracle/rdata04/ebay/oradata/qitemsi07a.dbf' size 500M;

/* urk misnamed itemd02c.dbf - should be ritemi02c.dbf */
/* to change hot_backup01 also */

alter tablespace ritemi02 offline;
cp /oracle/rdata04/ebay/oradata/itemd02c.dbf /oracle/rdata04/ebay/oradata/ritemi02c.dbf
alter tablespace ritemi02 rename datafile
'/oracle/rdata04/ebay/oradata/itemd02c.dbf' to
'/oracle/rdata04/ebay/oradata/ritemi02c.dbf';
alter tablespace ritemi02 online;

alter tablespace ruseri04
add datafile '/oracle/rdata06/ebay/oradata/ruseri04h.dbf'
size 500M;

alter tablespace users
add datafile '/oracle/rdata07/ebay/oradata/usrebay1b.dbf'
size 100M;

alter tablespace itemarc3
add datafile '/oracle/rdata06/ebay/oradata/itemarc3m.dbf'
size 1024M;


/* 12/28/98 dropping ebay_bids_arc2 to reclaim file */
/* make sure hot backup is done on the tablespace, and copy it to save directory */


/* cd to a volume which has plenty of space - about 600MB */

cd /oracle/rbackup03/ebay/save
exp scott/eif99 tables=EBAY_BIDS_ARC2 direct=Y indexes=N constraints=N grants=Y buffer=1228800 rows=Y file=bidsarc2.dmp log=bidsarc2.log

/* verify file size is approx 600MB */ 
/* as scott */
drop table ebay_bids_arc2;

/* get the datafiles to be rm'd */
SQL> select substr(file_name,1,50), tablespace_name from
  2  dba_data_files where tablespace_name = 'BIDD02';

SUBSTR(FILE_NAME,1,50)
--------------------------------------------------
TABLESPACE_NAME
------------------------------
/oracle/rdata06/ebay/oradata/bidd02_a.dbf
BIDD02

/oracle/rdata06/ebay/oradata/bidd02.dbf
BIDD02

/* query dba_segments to see if anything else is in it - as sys */
select substr(segment_name,1,30), tablespace_name from dba_segments
where tablespace_name = 'BIDD02';

drop tablespace BIDD02 including contents;

/* unix cmd prompt - not veritas quick io files */
cd /oracle/rdata06/ebay/oradata
rm bidd02_a.dbf
rm bidd02.dbf

/* add datafile for feedbackd02 */

alter tablespace FEEDBACKD02
add datafile '/oracle/rdata01/ebay/oradata/feedbackd02f.dbf'
size 1024M;


create tablespace notesd01
	datafile '/oracle/rdata01/ebay/oradata/notesd01.dbf'
	size 500M autoextend off;

create tablespace notesd02
	datafile '/oracle/rdata01/ebay/oradata/notesd02.dbf'
	size 1024M autoextend off;

create tablespace notesi01
	datafile '/oracle/rdata06/ebay/oradata/notesi01.dbf'
	size 500M autoextend off;

alter table ebay_notes add constraint	notes_id_pk
		primary key		(id)
		using index tablespace	notesi01
		storage (initial 50M next 50M pctincrease 0);

 create index ebay_notes_user_item_index
   on ebay_notes(user_about, item_about)
   tablespace notesi01
   storage(initial 100m next 50M);

   /* create this tablespace for reporting purposes */

create tablespace playd01
	datafile '/oracle/rdata10/ebay/oradata/playd01.dbf'
	size 500M autoextend off;

create tablespace playi01
	datafile '/oracle/rdata05/ebay/oradata/playi01.dbf'
	size 500M autoextend off;

alter tablespace playd01 add datafile
'/oracle/rdata10/ebay/oradata/playd01a.dbf'
size 500M autoextend off;

alter tablespace ITEMARCI02 add datafile
'/oracle/rdata05/ebay/oradata/itemarci02b.dbf' size 601M autoextend off;

alter tablespace ITEMARCD02 add datafile
'/oracle/rdata10/ebay/oradata/itemarcd02b.dbf' size 601M autoextend off;

alter tablespace ritemd04 add datafile
'/oracle/rdata02/ebay/oradata/ritemd04a.dbf' size 201M autoextend off;

alter tablespace ritemi04 add datafile
'/oracle/rdata08/ebay/oradata/ritemi04a.dbf' size 201M autoextend off;

alter tablespace feedbacki03 add datafile
'/oracle/rdata04/ebay/oradata/feedbacki03b.dbf'
		size 1001M autoextend off;

alter tablespace ITEMARCD01 add datafile
'/oracle/rdata10/ebay/oradata/itemarcd01b.dbf' size 1001M autoextend off;

alter tablespace BIDARCD01 add datafile
'/oracle/rdata10/ebay/oradata/bidarcd01b.dbf' size 1001M autoextend off;

alter tablespace ITEMARCD01 add datafile
'/oracle/rdata10/ebay/oradata/itemarcd01c.dbf' 
size 2001M autoextend off;

alter tablespace BIDARCD01 add datafile
'/oracle/rdata10/ebay/oradata/bidarcd01c.dbf' 
size 2001M autoextend off;

create tablespace ITEMARCD03
	datafile '/oracle/rdata10/ebay/oradata/itemarcd03.dbf'
	size 2001M autoextend off;

alter tablespace FEEDBACKLD01 add datafile
'/oracle/rdata01/ebay/oradata/feedbackld01b.dbf' 
size 1001M autoextend off;

/* 2/14/99 */
alter tablespace ritemd02 add datafile
'/oracle/rdata09/ebay/oradata/ritemd02s.dbf' size 2001M;

alter tablespace RITEMD01 offline;
!cd /oracle/rdata12/ebay/oradata;
/oracle04/export/home/oracle7/bkup_kit/copyfile /oracle/rdata02/ebay/oradata  itemd01d.dbf .itemd01d.dbf
alter database rename file '/oracle/rdata02/ebay/oradata/itemd01d.dbf'
to  '/oracle/rdata12/ebay/oradata/itemd01d.dbf';
alter tablespace RITEMD01 online;

vxmkcdev -h -s 1001M /oracle/rdata04/ebay/oradata/qbidsi01b_q.dbf

alter tablespace QBIDSI01 add datafile
'/oracle/rdata04/ebay/oradata/qbidsi01b_q.dbf' size 1001M;

12/19/99
ACCOUNTD01           TABLE      EBAY_ACCOUNTS                     262144000    0
FEEDBACKD02          TABLE      EBAY_FEEDBACK_DETAIL              262141952    0
ITEMARCD03           TABLE      EBAY_ITEM_DESC_ARC                525334528    0

alter tablespace ACCOUNTD01 add datafile
'/oracle/rdata09/ebay/oradata/accountd01h.dbf' size 2001M;

host vxmkcdev -h -s 501M /oracle/rdata03/ebay/oradata/feedbackd02g.dbf
alter tablespace FEEDBACKD02 add datafile
'/oracle/rdata03/ebay/oradata/feedbackd02g.dbf' size 501M;

alter tablespace ITEMARCD03 add datafile
'/oracle/rdata10/ebay/oradata/itemarcd03b.dbf' size 2001M;

FEEDBACKD03          TABLE      EBAY_TRANSACT_RECORD              104857600    0
host vxmkcdev -h -s 1001M /oracle/rdata05/ebay/oradata/feedbackd03a.dbf
alter tablespace FEEDBACKD03 add datafile
'/oracle/rdata05/ebay/oradata/feedbackd03a.dbf' size 1001M;

create tablespace itemarci03
	datafile '/oracle/rdata05/ebay/oradata/itemarci03.dbf'
	size 501M autoextend off;

/* to move files around */
alter tablespace RITEMD01 offline;
!cp /oracle/rdata10/ebay/oradata/itemd01b.dbf  /oracle/rdata12/ebay/oradata/itemd01b.dbf 
!cp /oracle/rdata10/ebay/oradata/itemd01.dbf /oracle/rdata12/ebay/oradata/itemd01.dbf 
alter database rename file '/oracle/rdata10/ebay/oradata/itemd01b.dbf' to  '/oracle/rdata12/ebay/oradata/itemd01b.dbf';
alter database rename file '/oracle/rdata10/ebay/oradata/itemd01.dbf' to  '/oracle/rdata12/ebay/oradata/itemd01.dbf';
alter tablespace RITEMD01 online;

alter tablespace FEEDBACKD02 offline;
!cd  /oracle/rdata10/ebay/oradata;/oracle04/export/home/oracle7/bkup_kit/copyfile /oracle/rdata02/ebay/oradata feedbackd02d_q.dbf .feedbackd02d_q.dbf 
alter database rename file '/oracle/rdata10/ebay/oradata/feedbackd02d_q.dbf' to  '/oracle/rdata02/ebay/oradata/feedbackd02d_q.dbf';
alter tablespace FEEDBACKD02 online;

/* April 3rd, 1999 */
/* tablespaces for ended stuff */
create tablespace itemed02 datafile
'/oracle/rdata12/ebay/oradata/itemed02a.dbf' size 2001m,
'/oracle/rdata12/ebay/oradata/itemed02b.dbf' size 2001m;

!qiomkfile -h -s 501m /oracle/rdata12/ebay/oradata/itemei06a.dbf
create tablespace itemei06 datafile
'/oracle/rdata12/ebay/oradata/itemei06a.dbf' size 501m;

!qiomkfile -h -s 1001m /oracle/rdata08/ebay/oradata/bidsed01a.dbf
create tablespace bidsed01 datafile
'/oracle/rdata08/ebay/oradata/bidsed01a.dbf' size 1001m;

!qiomkfile -h -s 501m /oracle/rdata12/ebay/oradata/bidsei01a.dbf
create tablespace bidsei01 datafile
'/oracle/rdata12/ebay/oradata/bidsei01a.dbf' size 501m;

!qiomkfile -h -s 501m /oracle/rdata12/ebay/oradata/bidsei02a.dbf
create tablespace bidsei02 datafile
'/oracle/rdata12/ebay/oradata/bidsei02a.dbf' size 501m;

create table ebay_item_desc_ended
 (
	MARKETPLACE			NUMBER(38),
	ID						NUMBER(38)
		constraint		item_desc_end_id_nn
			not null,
 	DESCRIPTION_LEN	NUMBER
		constraint		item_desc_end_len_nn
			not null,
	DESCRIPTION			LONG RAW
	)
	tablespace itemed02 
	pctfree 0 storage (initial 1000M next 500M);


create table ebay_bids_ended tablespace bidsed01 pctfree 0 storage
(initial 500m next 500m pctincrease 0) as select * from ebay_bids
where 1=2;

alter table ebay_item_desc_ended
	add constraint		ritem_desc_end_pk
		primary key		(marketplace, id)
		using index tablespace itemei06
		storage (initial 200M next 50M);

create index ebay_bids_item_user_end_index
	on ebay_bids_ended(item_id, user_id)
   PCTFREE 10 INITRANS 2 MAXTRANS 255 STORAGE (INITIAL
200m NEXT 50m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0
FREELISTS 1) tablespace bidsei01 unrecoverable;
 
 create index ebay_bids_user_end_index
   on ebay_bids_ended(user_id)
      PCTFREE 10 INITRANS 2 MAXTRANS 255 STORAGE (INITIAL
200m NEXT 50m MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0
FREELISTS 1) tablespace bidsei02 unrecoverable;
