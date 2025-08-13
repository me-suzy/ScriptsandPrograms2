/* script to move rdata05 to rdata09

accountd01e.dbf    qbidsi02.dbf       raccounti9.dbf     ritemd02j_q.dbf
accountd01fn.dbf   qpagesd01.dbf      ritemd02_q.dbf     ritemd02k.dbf
accountd03-02.dbf  qpagesd02.dbf      ritemd02a_q.dbf    ritemd02l.dbf
bidsarc001.dbf     qpagesd03.dbf      ritemd02b_q.dbf    ritemd02m.dbf
bidsarc002.dbf     qrbs3.dbf          ritemd02c_q.dbf    ritemd02n.dbf
bidsarc003.dbf     qruserd06b.dbf     ritemd02d_q.dbf    statsd01-02.dbf
bidsarc004.dbf     quserd01.dbf       ritemd02e_q.dbf    temp02b.dbf
bidsarci.dbf       raccounti5_q.dbf   ritemd02f_q.dbf    temp02c.dbf
bidsarci01_q.dbf   raccounti6_q.dbf   ritemd02g_q.dbf
itemarc3h.dbf      raccounti7.dbf     ritemd02h_q.dbf
itemarci1c.dbf     raccounti8.dbf     ritemd02i_q.dbf

*/

alter tablespace ACCOUNTD01 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata accountd01e.dbf .accountd01e.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata accountd01fn.dbf .accountd01fn.dbf

alter tablespace ACCOUNTD01 rename datafile 
'/oracle/rdata05/ebay/oradata/accountd01e.dbf' 
to '/oracle/rdata09/ebay/oradata/accountd01e.dbf';
alter tablespace ACCOUNTD01 rename datafile 
'/oracle/rdata05/ebay/oradata/accountd01fn.dbf' 
to '/oracle/rdata09/ebay/oradata/accountd01fn.dbf';
alter tablespace ACCOUNTD01 online;

alter tablespace ACCOUNTD03 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata accountd03-02.dbf .accountd03-02.dbf

alter tablespace ACCOUNTD03 rename datafile  
'/oracle/rdata05/ebay/oradata/accountd03-02.dbf' 
to '/oracle/rdata09/ebay/oradata/accountd03-02.dbf';

alter tablespace ACCOUNTD03 online;


alter tablespace BIDSARC offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata bidsarc001.dbf .bidsarc001.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata bidsarc002.dbf .bidsarc002.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata bidsarc003.dbf .bidsarc003.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata bidsarc004.dbf .bidsarc004.dbf

alter tablespace BIDSARC rename datafile 
'/oracle/rdata05/ebay/oradata/bidsarc001.dbf' 
to '/oracle/rdata09/ebay/oradata/bidsarc001.dbf';

alter tablespace BIDSARC rename datafile 
'/oracle/rdata05/ebay/oradata/bidsarc002.dbf' 
to '/oracle/rdata09/ebay/oradata/bidsarc002.dbf';

alter tablespace BIDSARC rename datafile 
'/oracle/rdata05/ebay/oradata/bidsarc003.dbf' 
to '/oracle/rdata09/ebay/oradata/bidsarc003.dbf';

alter tablespace BIDSARC rename datafile 
'/oracle/rdata05/ebay/oradata/bidsarc004.dbf' 
to '/oracle/rdata09/ebay/oradata/bidsarc004.dbf';

alter tablespace BIDSARC online;


alter tablespace BIDSARCI offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata bidsarci.dbf .bidsarci.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata bidsarci01_q.dbf .bidsarci01_q.dbf

alter tablespace BIDSARCI rename datafile 
'/oracle/rdata05/ebay/oradata/bidsarci.dbf' 
to '/oracle/rdata09/ebay/oradata/bidsarci.dbf';
alter tablespace BIDSARCI rename datafile 
'/oracle/rdata05/ebay/oradata/bidsarci01_q.dbf' 
to '/oracle/rdata09/ebay/oradata/bidsarci01_q.dbf';

alter tablespace BIDSARCI online;


alter tablespace ITEMARC3 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata itemarc3h.dbf .itemarc3h.dbf

alter tablespace ITEMARC3 rename datafile 
'/oracle/rdata05/ebay/oradata/itemarc3h.dbf' to 
'/oracle/rdata09/ebay/oradata/itemarc3h.dbf';

alter tablespace ITEMARC3 online;


alter tablespace ITEMARCI1 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata itemarci1c.dbf .itemarci1c.dbf

alter tablespace ITEMARCI1 rename datafile 
'/oracle/rdata05/ebay/oradata/itemarci1c.dbf' 
to '/oracle/rdata09/ebay/oradata/itemarci1c.dbf';

alter tablespace ITEMARCI1 online;

alter tablespace QBIDSI02 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata qbidsi02.dbf .qbidsi02.dbf
cd /oracle/rdata04/ebay/oradata 
cp QbidsI03.dbf /oracle/rdata09/ebay/oradata/qbidsi02a.dbf

alter tablespace QBIDSI02 rename datafile 
'/oracle/rdata05/ebay/oradata/qbidsi02.dbf' 
to '/oracle/rdata09/ebay/oradata/qbidsi02.dbf';
alter tablespace QBIDSI02 rename datafile 
'/oracle/rdata04/ebay/oradata/QbidsI03.dbf' 
to '/oracle/rdata09/ebay/oradata/qbidsi02a.dbf';

alter tablespace QBIDSI02 online;

alter tablespace QPAGESD01 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata qpagesd01.dbf .qpagesd01.dbf

alter tablespace QPAGESD01 rename datafile 
'/oracle/rdata05/ebay/oradata/qpagesd01.dbf' 
to '/oracle/rdata09/ebay/oradata/qpagesd01.dbf';

alter tablespace QPAGESD01 online;

alter tablespace QPAGESD02 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata qpagesd02.dbf .qpagesd02.dbf

alter tablespace QPAGESD02 rename datafile 
'/oracle/rdata05/ebay/oradata/qpagesd02.dbf' 
to '/oracle/rdata09/ebay/oradata/qpagesd02.dbf';

alter tablespace QPAGESD02 online;



alter tablespace QPAGESD03 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata qpagesd03.dbf .qpagesd03.dbf

alter tablespace QPAGESD03 rename datafile 
'/oracle/rdata05/ebay/oradata/qpagesd03.dbf' 
to '/oracle/rdata09/ebay/oradata/qpagesd03.dbf';

alter tablespace QPAGESD03 online;

alter tablespace RBS3 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata qrbs3.dbf .qrbs3.dbf

alter tablespace RBS3 rename datafile 
'/oracle/rdata05/ebay/oradata/qrbs3.dbf' 
to '/oracle/rdata09/ebay/oradata/qrbs3.dbf';

alter tablespace RBS3 online;


alter tablespace QRUSERD06 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata qruserd06b.dbf .qruserd06b.dbf

alter tablespace QRUSERD06 rename datafile 
'/oracle/rdata05/ebay/oradata/qruserd06b.dbf' 
to '/oracle/rdata09/ebay/oradata/qruserd06b.dbf';

alter tablespace QRUSERD06 online;

alter tablespace QUSERD01 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata quserd01.dbf .quserd01.dbf

alter tablespace QUSERD01 rename datafile 
'/oracle/rdata05/ebay/oradata/quserd01.dbf' 
to '/oracle/rdata09/ebay/oradata/quserd01.dbf';

alter tablespace QUSERD01 online;



alter tablespace RACCOUNTI5 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata raccounti5_q.dbf .raccounti5_q.dbf

alter tablespace RACCOUNTI5 rename datafile 
'/oracle/rdata05/ebay/oradata/raccounti5_q.dbf' 
to '/oracle/rdata09/ebay/oradata/raccounti5_q.dbf';

alter tablespace RACCOUNTI5 online;




alter tablespace RACCOUNTI6 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata raccounti6_q.dbf .raccounti6_q.dbf

alter tablespace RACCOUNTI6 rename datafile 
'/oracle/rdata05/ebay/oradata/raccounti6_q.dbf' 
to '/oracle/rdata09/ebay/oradata/raccounti6_q.dbf';

alter tablespace RACCOUNTI6 online;



alter tablespace RACCOUNTI7 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata raccounti7.dbf .raccounti7.dbf

alter tablespace RACCOUNTI7 rename datafile 
'/oracle/rdata05/ebay/oradata/raccounti7.dbf' 
to '/oracle/rdata09/ebay/oradata/raccounti7.dbf';

alter tablespace RACCOUNTI7 online;


alter tablespace RACCOUNTI8 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata raccounti8.dbf .raccounti8.dbf

alter tablespace RACCOUNTI8 rename datafile 
'/oracle/rdata05/ebay/oradata/raccounti8.dbf' 
to '/oracle/rdata09/ebay/oradata/raccounti8.dbf';

alter tablespace RACCOUNTI8 online;


alter tablespace RACCOUNTI9 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata raccounti9.dbf .raccounti9.dbf

alter tablespace RACCOUNTI9 rename datafile 
'/oracle/rdata05/ebay/oradata/raccounti9.dbf' 
to '/oracle/rdata09/ebay/oradata/raccounti9.dbf';

alter tablespace RACCOUNTI9 online;

alter tablespace RITEMD02 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02_q.dbf .ritemd02_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02a_q.dbf .ritemd02a_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02b_q.dbf .ritemd02b_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02c_q.dbf .ritemd02c_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02d_q.dbf .ritemd02d_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02e_q.dbf .ritemd02e_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02f_q.dbf .ritemd02f_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02g_q.dbf .ritemd02g_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02h_q.dbf .ritemd02h_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02i_q.dbf .ritemd02i_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02j_q.dbf .ritemd02j_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02k.dbf .ritemd02k.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02l.dbf .ritemd02l.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02m.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata ritemd02n.dbf

alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02a_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02a_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02b_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02b_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02c_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02c_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02d_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02d_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02e_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02e_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02f_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02f_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02g_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02g_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02h_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02h_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02i_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02i_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02j_q.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02j_q.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02k.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02k.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02l.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02l.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02m.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02m.dbf';
alter tablespace RITEMD02 rename datafile 
'/oracle/rdata05/ebay/oradata/ritemd02n.dbf' 
to '/oracle/rdata09/ebay/oradata/ritemd02n.dbf';

alter tablespace RITEMD02 online;

alter tablespace STATSD01 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata statsd01-02.dbf .statsd01-02.dbf

alter tablespace STATSD01 rename datafile  
'/oracle/rdata05/ebay/oradata/statsd01-02.dbf' 
to '/oracle/rdata09/ebay/oradata/statsd01-02.dbf';

alter tablespace STATSD01 online;


alter tablespace TEMP02 offline;

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata temp02b.dbf .temp02b.dbf
~oracle7/bkup_kit/copyfile /oracle/rdata09/ebay/oradata temp02c.dbf .temp02c.dbf

alter tablespace TEMP02 rename datafile 
'/oracle/rdata05/ebay/oradata/temp02b.dbf' 
to '/oracle/rdata09/ebay/oradata/temp02b.dbf';
alter tablespace TEMP02 rename datafile 
'/oracle/rdata05/ebay/oradata/temp02c.dbf' 
to '/oracle/rdata09/ebay/oradata/temp02c.dbf';

alter tablespace TEMP02 online;

/* old files, don't copy */

cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritem02f.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritem02g.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02a.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02b.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02c.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02d.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02e.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02h.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02i.dbf 
cd /oracle/rdata05/ebay/oradata 
~oracle7/bkup_kit/copyfile /oracle/rdata05/ebay/oradata ritemd02j.dbf 


