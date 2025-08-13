/*	$Id: create_monitoring_tables.sql,v 1.2 1999/02/21 02:52:45 josh Exp $	*/
drop table DBS;

rem * This table will store descriptive information about instances*/

create table DBS
(	db_nm        varchar2(8),		/*instance name*/
	host_nm      varchar2(8),		/*host (server) name*/
		description  varchar2(80)	/*instance description*/
)     
tablespace CC;

drop table FILES;

rem /*This table will store information about datafiles*/

create table FILES
(	db_nm        varchar2(8),		/*instance name*/
	ts            varchar2(30),	/*tablespace name*/
	check_date    date,				/*date entry was made into table*/
	file_nm       varchar2(80),	/*file name*/
	blocks	      number,			/*size of the file, in blocks*/
	primary key(db_nm, ts, check_date,file_nm)
)
tablespace CC;

drop view FILES_TS_VIEW;

rem /*This view groups the file sizes by tablespace*/

create view FILES_TS_VIEW as
select	db_nm,									/*instance name*/
			ts,									/*tablespace name*/
			check_date,							/*date entry was made into table*/
			sum(blocks) sum_file_blocks	/*blocks allocated for the ts*/
from files
group by	db_nm,
			ts,
			check_date;

drop table SPACES;

rem /*This table will store information about free space*/

create table SPACES
(db_nm        varchar2(8),      /*instance name*/
ts            varchar2(30),	/*tablespace name*/
check_date    date,		/*date entry was made into table*/
count_free_blocks number,	/*number of free extents*/
sum_free_blocks   number,	/*free space, in Oracle blocks*/
max_free_blocks  number,	/*largest free extent, in Oracle blocks*/
primary key (db_nm, ts, check_date))
tablespace CC;

drop table EXTENTS;

rem /*This table will store information about extent concerns*/

create table EXTENTS
(	db_nm			varchar2(8),	/*instance name*/
	ts				varchar2(30),	/*tablespace name*/
	seg_owner	varchar2(30),	/*segment owner*/
	seg_name		varchar2(32),	/*segment name*/
	seg_type		varchar2(17),	/*segment type*/
	extents		number,			/*number of extents allocated*/
	blocks		number,			/*number of blocks allocated*/
	check_date	date,				/*date entry was made into table*/
	primary key (db_nm, ts, seg_owner, seg_name, check_date)
)
tablespace CC;
