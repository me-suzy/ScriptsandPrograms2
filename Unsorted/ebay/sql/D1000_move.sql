/*	$Id: D1000_move.sql,v 1.3 1999/02/21 02:52:05 josh Exp $	*/
/* scripts to move files to the new D1000 volumes */

/* rdata02 */
alter tablespace RITEMD01 offline;
cp /oracle/ritemd01/ebay/oradata/ritemd01.dbf /oracle/rdata02/ebay/oradata/ritemd01.dbf 
cp /oracle/ritemd01/ebay/oradata/ritemd01a.dbf /oracle/rdata02/ebay/oradata/ritemd01a.dbf 
alter database rename file '/oracle/ritemd01/ebay/oradata/ritemd01.dbf' 
to '/oracle/rdata02/ebay/oradata/ritemd01.dbf';
alter database rename file '/oracle/ritemd01/ebay/oradata/ritemd01a.dbf' 
to '/oracle/rdata02/ebay/oradata/ritemd01a.dbf';
alter tablespace RITEMD01 online;

/* rdata04 */
alter tablespace RITEMI01 offline;
cp /oracle/ritemd02/ebay/oradata/ritemi01.dbf /oracle/rdata04/ebay/oradata/ritemi01.dbf 
alter database rename file '/oracle/ritemd02/ebay/oradata/ritemi01.dbf' 
to '/oracle/rdata04/ebay/oradata/ritemi01.dbf';
alter tablespace RITEMI01 online;

alter tablespace ITEMI01 offline;
cp /oracle/ritemd02/ebay/oradata/itemi01.dbf /oracle/rdata04/ebay/oradata/itemi01.dbf 
alter database rename file '/oracle/ritemd02/ebay/oradata/itemi01.dbf' 
to '/oracle/rdata04/ebay/oradata/itemi01.dbf';
alter tablespace ITEMI01 online;

alter tablespace QITEMI01 offline;
cd /oracle/ritemd02/ebay/oradata
/* didn't work in tcsh - had to do:
cpio -pdm /oracle/rdata04/ebay/oradata 
qitemi01.dbf
.qitemi01.dbf
^D
*/
echo "qitemi01.dbf .qitemi01.dbf" | cpio -pdm /oracle/rdata04/ebay/oradata
/* *cp /oracle/ritemd02/ebay/oradata/qitemi01.dbf /oracle/rdata04/ebay/oradata/qitemi01.dbf 
*/
alter database rename file '/oracle/ritemd02/ebay/oradata/qitemi01.dbf' 
to '/oracle/rdata04/ebay/oradata/qitemi01.dbf';
alter tablespace QITEMI01 online;

alter tablespace QITEMI17 offline;
cd /oracle/ritemd02/ebay/oradata
echo "qitemi17.dbf .qitemi17.dbf" | cpio -pdm /oracle/rdata04/ebay/oradata
/* *cp /oracle/ritemd02/ebay/oradata/qitemi17.dbf /oracle/rdata04/ebay/oradata/qitemi17.dbf 
*/
alter database rename file '/oracle/ritemd02/ebay/oradata/qitemi17.dbf' 
to '/oracle/rdata04/ebay/oradata/qitemi17.dbf';
alter tablespace QITEMI17 online;


alter tablespace FEEDBACKD02 offline;
cp /oracle/rdata01/ebay/oradata/feedbackd02.dbf /oracle/rdata03/ebay/oradata/feedbackd02.dbf 
cp /oracle/rdata01/ebay/oradata/feedbackd02a.dbf /oracle/rdata03/ebay/oradata/feedbackd02a.dbf 
cp /oracle/rdata01/ebay/oradata/feedbackd02b.dbf /oracle/rdata03/ebay/oradata/feedbackd02b.dbf 
alter database rename file '/oracle/rdata01/ebay/oradata/feedbackd02.dbf' 
to '/oracle/rdata03/ebay/oradata/feedbackd02.dbf';
alter database rename file '/oracle/rdata01/ebay/oradata/feedbackd02a.dbf' 
to '/oracle/rdata03/ebay/oradata/feedbackd02a.dbf';
alter database rename file '/oracle/rdata01/ebay/oradata/feedbackd02b.dbf' 
to '/oracle/rdata03/ebay/oradata/feedbackd02b.dbf';
alter tablespace FEEDBACKD02 online;

alter tablespace FEEDBACKI02 offline;
cp /oracle/rdata01/ebay/oradata/feedbacki02.dbf /oracle/rdata04/ebay/oradata/feedbacki02.dbf 
cp /oracle/rdata01/ebay/oradata/feedbacki02a.dbf /oracle/rdata04/ebay/oradata/feedbacki02a.dbf 
alter database rename file '/oracle/rdata01/ebay/oradata/feedbacki02.dbf' 
to '/oracle/rdata04/ebay/oradata/feedbacki02.dbf'; 
alter database rename file '/oracle/rdata01/ebay/oradata/feedbacki02a.dbf' 
to '/oracle/rdata04/ebay/oradata/feedbacki02a.dbf'; 
alter tablespace FEEDBACKI02 online;

alter tablespace RBIDI01 offline;
cp /oracle/rdata01/ebay/oradata/rbidi01.dbf /oracle/rdata04/ebay/oradata/rbidi01.dbf 
cp /oracle/rdata01/ebay/oradata/rbidi01b.dbf /oracle/rdata04/ebay/oradata/rbidi01b.dbf 
cp /oracle/rdata01/ebay/oradata/rbidi01a.dbf /oracle/rdata04/ebay/oradata/rbidi01a.dbf 

alter tablespace RBIDI01 rename datafile 
'/oracle/rdata01/ebay/oradata/rbidi01.dbf' 
to '/oracle/rdata04/ebay/oradata/rbidi01.dbf';
alter tablespace RBIDI01 rename datafile 
'/oracle/rdata01/ebay/oradata/rbidi01b.dbf' 
to '/oracle/rdata04/ebay/oradata/rbidi01b.dbf';
alter tablespace RBIDI01 rename datafile 
'/oracle/rdata01/ebay/oradata/rbidi01a.dbf' 
to '/oracle/rdata04/ebay/oradata/rbidi01a.dbf';
alter tablespace RBIDI01 online;

alter tablespace RBIDI03 offline;
cp /oracle/rdata01/ebay/oradata/rbidi03.dbf /oracle/rdata04/ebay/oradata/rbidi03.dbf 
cp /oracle/rdata01/ebay/oradata/rbidi03a.dbf /oracle/rdata04/ebay/oradata/rbidi03a.dbf 
cp /oracle/rdata01/ebay/oradata/rbidi03b.dbf /oracle/rdata04/ebay/oradata/rbidi03b.dbf 
alter tablespace RBIDI03 rename datafile 
'/oracle/rdata01/ebay/oradata/rbidi03.dbf' 
to '/oracle/rdata04/ebay/oradata/rbidi03.dbf';
alter tablespace RBIDI03 rename datafile 
'/oracle/rdata01/ebay/oradata/rbidi03a.dbf' 
to '/oracle/rdata04/ebay/oradata/rbidi03a.dbf';
alter tablespace RBIDI03 rename datafile 
 '/oracle/rdata01/ebay/oradata/rbidi03b.dbf' 
 to '/oracle/rdata04/ebay/oradata/rbidi03b.dbf';
alter tablespace RBIDI03 online;

alter tablespace RITEMI02 offline;
cp /oracle/rdata01/ebay/oradata/ritemi02.dbf /oracle/rdata04/ebay/oradata/ritemi02.dbf 
cp /oracle/rdata01/ebay/oradata/ritemi02a.dbf /oracle/rdata04/ebay/oradata/ritemi02a.dbf 
alter tablespace RITEMI02 rename datafile 
'/oracle/rdata01/ebay/oradata/ritemi02.dbf' 
to '/oracle/rdata04/ebay/oradata/ritemi02.dbf'; 
alter tablespace RITEMI02 rename datafile 
'/oracle/rdata01/ebay/oradata/ritemi02a.dbf' 
to '/oracle/rdata04/ebay/oradata/ritemi02a.dbf'; 
alter tablespace RITEMI02 online;

alter tablespace RUSERI01 offline;
cp /oracle/rdata01/ebay/oradata/ruseri01.dbf /oracle/rdata04/ebay/oradata/ruseri01.dbf 
cp /oracle/rdata01/ebay/oradata/ruseri01a.dbf /oracle/rdata04/ebay/oradata/ruseri01a.dbf 
alter tablespace RUSERI01 rename datafile 
'/oracle/rdata01/ebay/oradata/ruseri01.dbf' 
to '/oracle/rdata04/ebay/oradata/ruseri01.dbf'; 
alter tablespace RUSERI01 rename datafile 
 '/oracle/rdata01/ebay/oradata/ruseri01a.dbf' 
 to '/oracle/rdata04/ebay/oradata/ruseri01a.dbf'; 
alter tablespace RUSERI01 online;

alter tablespace RUSERD01 offline;
cp /oracle/rdata01/ebay/oradata/ruserd01.dbf /oracle/rdata05/ebay/oradata/ruserd01.dbf 
cp /oracle/rdata01/ebay/oradata/ruserd01a.dbf /oracle/rdata05/ebay/oradata/ruserd01a.dbf 
cp /oracle/rdata01/ebay/oradata/ruserd01b.dbf /oracle/rdata05/ebay/oradata/ruserd01b.dbf 
alter tablespace RUSERD01 rename datafile 
'/oracle/rdata01/ebay/oradata/ruserd01.dbf' 
to '/oracle/rdata05/ebay/oradata/ruserd01.dbf';
alter tablespace RUSERD01 rename datafile 
 '/oracle/rdata01/ebay/oradata/ruserd01a.dbf' 
 to '/oracle/rdata05/ebay/oradata/ruserd01a.dbf';
alter tablespace RUSERD01 rename datafile 
 '/oracle/rdata01/ebay/oradata/ruserd01b.dbf' 
 to '/oracle/rdata05/ebay/oradata/ruserd01b.dbf';
alter tablespace RUSERD01 online;

alter tablespace RITEMD02 offline;
cp /oracle/rdata01/ebay/oradata/ritemd02.dbf /oracle/rdata05/ebay/oradata/ritemd02.dbf 
cp /oracle/rdata01/ebay/oradata/ritemd02a.dbf /oracle/rdata05/ebay/oradata/ritemd02a.dbf 
cp /oracle/rdata01/ebay/oradata/ritemd02b.dbf /oracle/rdata05/ebay/oradata/ritemd02b.dbf 
cp /oracle/rdata01/ebay/oradata/ritemd02c.dbf /oracle/rdata05/ebay/oradata/ritemd02c.dbf 
cp /oracle/rdata01/ebay/oradata/ritemd02d.dbf /oracle/rdata05/ebay/oradata/ritemd02d.dbf
cp /oracle/rdata01/ebay/oradata/ritemd02e.dbf /oracle/rdata05/ebay/oradata/ritemd02e.dbf 
cp /oracle/rdata01/ebay/oradata/ritem02f.dbf /oracle/rdata05/ebay/oradata/ritem02f.dbf 
cp /oracle/rdata01/ebay/oradata/ritem02g.dbf /oracle/rdata05/ebay/oradata/ritem02g.dbf 
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata01/ebay/oradata/ritemd02.dbf' 
to '/oracle/rdata05/ebay/oradata/ritemd02.dbf';
alter tablespace RITEMD02 rename datafile 
 '/oracle/rdata01/ebay/oradata/ritemd02a.dbf' 
 to '/oracle/rdata05/ebay/oradata/ritemd02a.dbf';
alter tablespace RITEMD02 rename datafile 
 '/oracle/rdata01/ebay/oradata/ritemd02b.dbf' 
 to '/oracle/rdata05/ebay/oradata/ritemd02b.dbf';
alter tablespace RITEMD02 rename datafile 
 '/oracle/rdata01/ebay/oradata/ritemd02c.dbf' 
 to '/oracle/rdata05/ebay/oradata/ritemd02c.dbf';
alter tablespace RITEMD02 rename datafile 
 '/oracle/rdata01/ebay/oradata/ritemd02d.dbf' 
 to '/oracle/rdata05/ebay/oradata/ritemd02d.dbf';
alter tablespace RITEMD02 rename datafile 
 '/oracle/rdata01/ebay/oradata/ritemd02e.dbf' 
 to '/oracle/rdata05/ebay/oradata/ritemd02e.dbf';
alter tablespace RITEMD02 rename datafile 
 '/oracle/rdata01/ebay/oradata/ritem02f.dbf' 
 to '/oracle/rdata05/ebay/oradata/ritem02f.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata01/ebay/oradata/ritem02g.dbf' 
to '/oracle/rdata05/ebay/oradata/ritem02g.dbf';
alter tablespace RITEMD02 online;

alter tablespace RBIDD01 offline;
cp /oracle/rdata01/ebay/oradata/rbidd01.dbf /oracle/rdata05/ebay/oradata/rbidd01.dbf 
cp /oracle/rdata01/ebay/oradata/rbidd01a.dbf /oracle/rdata05/ebay/oradata/rbidd01a.dbf 
alter tablespace RBIDD01 rename datafile 
'/oracle/rdata01/ebay/oradata/rbidd01.dbf' 
to '/oracle/rdata05/ebay/oradata/rbidd01.dbf';
alter tablespace RBIDD01 rename datafile 
 '/oracle/rdata01/ebay/oradata/rbidd01a.dbf' 
 to '/oracle/rdata05/ebay/oradata/rbidd01a.dbf';
alter tablespace RBIDD01 online;



