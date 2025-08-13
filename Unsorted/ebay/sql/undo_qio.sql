/*	$Id: undo_qio.sql,v 1.3 1999/02/21 02:55:13 josh Exp $	*/
--- move back ebay_accounts

alter database datafile '/oracle/rdata07/ebay/oradata/accountd01_q.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01d_q.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01a_q.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01b_q.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01c_q.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01e_q.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/accountd01f.dbf' offline;

host dd if=/oracle/rdata07/ebay/oradata/accountd01_q.dbf of=/oracle/rdata07/ebay/oradata/accountd01.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01d_q.dbf of=/oracle/rdata07/ebay/oradata/accountd01d.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01a_q.dbf of=/oracle/rdata07/ebay/oradata/accountd01a.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01b_q.dbf of=/oracle/rdata07/ebay/oradata/accountd01b.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01c_q.dbf of=/oracle/rdata07/ebay/oradata/accountd01c.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01e_q.dbf of=/oracle/rdata05/ebay/oradata/accountd01e.dbf bs=102400
host dd if=/oracle/rdata05/ebay/oradata/accountd01f.dbf of=/oracle/rdata05/ebay/oradata/accountd01fn.dbf bs=102400

alter database rename file '/oracle/rdata07/ebay/oradata/accountd01_q.dbf' to '/oracle/rdata07/ebay/oradata/accountd01.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01d_q.dbf' to '/oracle/rdata07/ebay/oradata/accountd01d.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01a_q.dbf' to '/oracle/rdata07/ebay/oradata/accountd01a.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01b_q.dbf' to '/oracle/rdata07/ebay/oradata/accountd01b.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01c_q.dbf' to '/oracle/rdata07/ebay/oradata/accountd01c.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01e_q.dbf' to '/oracle/rdata05/ebay/oradata/accountd01e.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/accountd01f.dbf' to '/oracle/rdata05/ebay/oradata/accountd01fn.dbf';

recover datafile '/oracle/rdata07/ebay/oradata/accountd01.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01d.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01a.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01b.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01c.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/accountd01e.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/accountd01fn.dbf';

alter database datafile '/oracle/rdata07/ebay/oradata/accountd01.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01d.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01a.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01b.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01c.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/accountd01e.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/accountd01fn.dbf' online;

--- move back ebay_item_info

alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03_q.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03a_q.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03b_q.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03c.dbf' offline;

host dd if=/oracle/rdata06/ebay/oradata/ritemd03_q.dbf of=/oracle/rdata06/ebay/oradata/itemd03.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ritemd03a_q.dbf of=/oracle/rdata06/ebay/oradata/itemd03a.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ritemd03b_q.dbf of=/oracle/rdata06/ebay/oradata/itemd03b.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ritemd03c.dbf of=/oracle/rdata06/ebay/oradata/itemd03c.dbf bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/ritemd03_q.dbf' to '/oracle/rdata06/ebay/oradata/itemd03.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ritemd03a_q.dbf' to '/oracle/rdata06/ebay/oradata/itemd03a.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ritemd03b_q.dbf' to '/oracle/rdata06/ebay/oradata/itemd03b.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ritemd03c.dbf' to '/oracle/rdata06/ebay/oradata/itemd03c.dbf';

recover datafile '/oracle/rdata06/ebay/oradata/itemd03.dbf'; 
recover datafile '/oracle/rdata06/ebay/oradata/itemd03a.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemd03b.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemd03c.dbf';

alter database datafile '/oracle/rdata06/ebay/oradata/itemd03.dbf' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/itemd03a.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemd03b.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemd03c.dbf' online;

--- move back ebay_items

alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01_q.dbf' offline;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01a_q.dbf' offline;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01b_q.dbf' offline;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01c_q.dbf' offline;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01d.dbf' offline;

host dd if=/oracle/rdata02/ebay/oradata/ritemd01_q.dbf of=/oracle/rdata02/ebay/oradata/itemd01.dbf bs=102400
host dd if=/oracle/rdata02/ebay/oradata/ritemd01a_q.dbf of=/oracle/rdata02/ebay/oradata/itemd01a.dbf bs=102400 
host dd if=/oracle/rdata02/ebay/oradata/ritemd01b_q.dbf of=/oracle/rdata02/ebay/oradata/itemd01b.dbf bs=102400 
host dd if=/oracle/rdata02/ebay/oradata/ritemd01c_q.dbf of=/oracle/rdata02/ebay/oradata/itemd01c.dbf bs=102400 
host dd if=/oracle/rdata02/ebay/oradata/ritemd01d.dbf of=/oracle/rdata02/ebay/oradata/itemd01d.dbf bs=102400 

alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01_q.dbf' to '/oracle/rdata02/ebay/oradata/itemd01.dbf';
alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01a_q.dbf' to '/oracle/rdata02/ebay/oradata/itemd01a.dbf';
alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01b_q.dbf' to '/oracle/rdata02/ebay/oradata/itemd01b.dbf';
alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01c_q.dbf' to '/oracle/rdata02/ebay/oradata/itemd01c.dbf';
alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01d.dbf' to '/oracle/rdata02/ebay/oradata/itemd01d.dbf';

recover datafile '/oracle/rdata02/ebay/oradata/itemd01.dbf';
recover datafile '/oracle/rdata02/ebay/oradata/itemd01a.dbf';
recover datafile '/oracle/rdata02/ebay/oradata/itemd01b.dbf';
recover datafile '/oracle/rdata02/ebay/oradata/itemd01c.dbf';
recover datafile '/oracle/rdata02/ebay/oradata/itemd01d.dbf';

alter database datafile '/oracle/rdata02/ebay/oradata/itemd01.dbf' online;
alter database datafile '/oracle/rdata02/ebay/oradata/itemd01a.dbf' online;
alter database datafile '/oracle/rdata02/ebay/oradata/itemd01b.dbf' online;
alter database datafile '/oracle/rdata02/ebay/oradata/itemd01c.dbf' online;
alter database datafile '/oracle/rdata02/ebay/oradata/itemd01d.dbf' online;


----- rdata01 -----
/* bring database down */
cd /oracle/rdata01/ebay/oradata

rm feedbackd02d_q.dbf
mv .feedbackd02d_q.dbf feedbackd02d_q.dbf

rm feedbackd02e_q.dbf
mv .feedbackd02e_q.dbf feedbackd02d_q.dbf

rm feedbackd03.dbf 
mv .feedbackd03.dbf feedbackd03.dbf

rm systebay02.dbf
mv .systebay02.dbf systebay02.dbf

rm titemi01_01.dbf
mv .titemi01_01.dbf titemi01_01.dbf

----- rdata03 -----

cd /oracle/rdata03/ebay/oradata
rm feedbackd02_q.dbf
mv .feedbackd02_q.dbf feedbackd02_q.dbf

rm feedbackd02a_q.dbf
mv .feedbackd02a_q.dbf feedbackd02a_q.dbf

rm feedbackd02b_q.dbf
mv .feedbackd02b_q.dbf feedbackd02b_q.dbf

rm feedbackd02c_q.dbf
mv .feedbackd02c_q.dbf feedbackd02c_q.dbf

----- rdata04 -----

cd /oracle/rdata04/ebay/oradata
rm feedbacki02c.dbf
mv .feedbacki02c.dbf feedbacki02c.dbf

rm feedbacki03.dbf
mv .feedbacki03.dbf feedbacki03.dbf

rm qbidsi01.dbf
mv .qbidsi01.dbf qbidsi01.dbf

rm qitemi01.dbf
mv .qitemi01.dbf qitemi01.dbf

rm qitemi17.dbf
mv .qitemi17.dbf qitemi17.dbf

rm qruserd07b.dbf
mv .qruserd07b.dbf qruserd07b.dbf

rm quseri01.dbf
mv .quseri01.dbf quseri01.dbf

rm quseri02.dbf
mv .quseri02.dbf quseri02.dbf

----- rdata06 -----

cd /oracle/rdata06/ebay/oradata

rm accountd03.dbf
mv .accountd03.dbf accountd03.dbf

rm accounti02_q.dbf
mv .accounti02_q.dbf accounti02_q.dbf

rm accounti02a_q.dbf
mv .accounti02a_q.dbf accounti02a_q.dbf

rm accounti02b_q.dbf
mv .accounti02b_q.dbf accounti02b_q.dbf

rm accounti02c_q.dbf 
mv .accounti02c_q.dbf accounti02c_q.dbf 

rm accounti02d_q.dbf
mv .accounti02d_q.dbf accounti02d_q.dbf

rm accounti02e_q.dbf
mv .accounti02e_q.dbf accounti02e_q.dbf

rm accounti02f_q.dbf
mv .accounti02f_q.dbf accounti02f_q.dbf

rm accounti02g_q.dbf
mv .accounti02g_q.dbf accounti02g_q.dbf

rm accounti04.dbf
mv .accounti04.dbf accounti04.dbf

rm feedbacki01_q.dbf 
mv .feedbacki01_q.dbf feedbacki01_q.dbf 

rm feedbacki01a_q.dbf 
mv .feedbacki01a_q.dbf  feedbacki01a_q.dbf 

rm itemarc3j.dbf 
mv .itemarc3j.dbf  itemarc3j.dbf 

rm itemarc3k.dbf
mv .itemarc3k.dbf itemarc3k.dbf

rm qboardd01.dbf
mv .qboardd01.dbf qboardd01.dbf

rm qruserd07.dbf 
mv .qruserd07.dbf  qruserd07.dbf 

rm qruserd07a.dbf
mv .qruserd07a.dbf qruserd07a.dbf

rm qruseri06.dbf 
mv .qruseri06.dbf  qruseri06.dbf 

rm quserd03.dbf
mv .quserd03.dbf quserd03.dbf

rm quseri03.dbf
mv .quseri03.dbf quseri03.dbf

rm ruseri03_q.dbf
mv .ruseri03_q.dbf ruseri03_q.dbf

rm ruseri04_q.dbf
mv .ruseri04_q.dbf ruseri04_q.dbf

rm ruseri04a_q.dbf
mv .ruseri04a_q.dbf ruseri04a_q.dbf

rm ruseri04b_q.dbf
mv .ruseri04b_q.dbf ruseri04b_q.dbf

rm ruseri04c_q.dbf
mv .ruseri04c_q.dbf ruseri04c_q.dbf

rm ruseri04d_q.dbf
mv .ruseri04d_q.dbf ruseri04d_q.dbf

rm ruseri04e_q.dbf
mv .ruseri04e_q.dbf ruseri04e_q.dbf

rm ruseri04f_q.dbf
mv .ruseri04f_q.dbf ruseri04f_q.dbf

rm ruseri05_q.dbf
mv .ruseri05_q.dbf ruseri05_q.dbf

rm tuseri01.dbf
mv .tuseri01.dbf tuseri01.dbf

----- rdata07 -----
cd /oracle/rdata07/ebay/oradata

rm accountd04.dbf
mv .accountd04.dbf accountd04.dbf

rm accounti03.dbf
mv .accounti03.dbf accounti03.dbf

rm feedbackd01-02.dbf
mv .feedbackd01-02.dbf feedbackd01-02.dbf

rm feedbackd01.dbf 
mv .feedbackd01.dbf  feedbackd01.dbf 

rm itemarc2e.dbf 
mv .itemarc2e.dbf itemarc2e.dbf 

rm partd02.dbf
mv .partd02.dbf partd02.dbf

rm qboardi01.dbf 
mv .qboardi01.dbf  qboardi01.dbf 

rm qruserd04.dbf 
mv .qruserd04.dbf  qruserd04.dbf 

rm qruserd06.dbf
mv .qruserd06.dbf qruserd06.dbf

rm qruserd06a.dbf 
mv .qruserd06a.dbf  qruserd06a.dbf 

rm qruseri07.dbf
mv .qruseri07.dbf qruseri07.dbf

rm quserd02.dbf
mv .quserd02.dbf quserd02.dbf

rm ritemi03_q.dbf 
mv .ritemi03_q.dbf  ritemi03_q.dbf 

rm ritemi03a_q.dbf
mv .ritemi03a_q.dbf ritemi03a_q.dbf

rm ritemi03b_q.dbf 
mv .ritemi03b_q.dbf  ritemi03b_q.dbf 

rm ruserd03_q.dbf
mv .ruserd03_q.dbf ruserd03_q.dbf

rm ruserd04_q.dbf
mv .ruserd04_q.dbf ruserd04_q.dbf

rm ruserd04a_q.dbf
mv .ruserd04a_q.dbf ruserd04a_q.dbf

rm ruserd04b_q.dbf
mv .ruserd04b_q.dbf ruserd04b_q.dbf

rm ruserd04c_q.dbf
mv .ruserd04c_q.dbf ruserd04c_q.dbf

rm ruserd05_q.dbf
mv .ruserd05_q.dbf ruserd05_q.dbf

rm tuserd01.dbf
mv .tuserd01.dbf tuserd01.dbf

rm userd08.dbf
mv .userd08.dbf userd08.dbf

----- rdata08 -----
cd /oracle/rdata08/ebay/oradata

rm qbidsd01.dbf
mv .qbidsd01.dbf qbidsd01.dbf

rm qbidsd02.dbf
mv .qbidsd02.dbf qbidsd02.dbf

rm qitemsi01.dbf 
mv .qitemsi01.dbf  qitemsi01.dbf 

rm qitemsi02.dbf
mv .qitemsi02.dbf qitemsi02.dbf

rm qitemsi03.dbf 
mv .qitemsi03.dbf  qitemsi03.dbf 

rm qitemsi04.dbf 
mv .qitemsi04.dbf  qitemsi04.dbf 

rm qitemsi05-01.dbf
mv .qitemsi05-01.dbf qitemsi05-01.dbf

rm qitemsi05-02.dbf
mv .qitemsi05-02.dbf qitemsi05-02.dbf

rm qitemsi05.dbf 
mv .qitemsi05.dbf qitemsi05.dbf 

rm qitemsi06.dbf
mv .qitemsi06.dbf qitemsi06.dbf

rm qitemsi07.dbf
mv .qitemsi07.dbf qitemsi07.dbf

rm qpagesi01.dbf
mv .qpagesi01.dbf qpagesi01.dbf

rm qpagesi02.dbf 
mv .qpagesi02.dbf  qpagesi02.dbf 

rm qpagesi03.dbf
mv .qpagesi03.dbf qpagesi03.dbf

rm titemd01_01.dbf
mv .titemd01_01.dbf titemd01_01.dbf

rm titemd01_02.dbf
mv .titemd01_02.dbf titemd01_02.dbf

rm titemd01_03.dbf
mv .titemd01_03.dbf titemd01_03.dbf

rm titemd01_04.dbf
mv .titemd01_04.dbf titemd01_04.dbf

----- rdata08 -----
cd /oracle/rdata09/ebay/oradata

rm accountd03-02.dbf 
mv .accountd03-02.dbf  accountd03-02.dbf 

rm itemarci1c.dbf 
mv .itemarci1c.dbf  itemarci1c.dbf 

rm qbidsi02.dbf 
mv .qbidsi02.dbf  qbidsi02.dbf 

rm qpagesd01.dbf 
mv .qpagesd01.dbf qpagesd01.dbf 

rm qpagesd02.dbf 
mv .qpagesd02.dbf  qpagesd02.dbf 

rm qpagesd03.dbf
mv .qpagesd03.dbf qpagesd03.dbf

rm qrbs3.dbf  
mv .qrbs3.dbf  qrbs3.dbf  

rm qruserd06b.dbf 
mv .qruserd06b.dbf qruserd06b.dbf 

rm quserd01.dbf 
mv .quserd01.dbf quserd01.dbf 

rm statsd01-02.dbf
mv .statsd01-02.dbf statsd01-02.dbf

rm temp02b.dbf
mv .temp02b.dbf temp02b.dbf

rm temp02c.dbf
mv .temp02c.dbf temp02c.dbf


