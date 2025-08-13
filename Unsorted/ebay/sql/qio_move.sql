/*	$Id: qio_move.sql,v 1.3 1999/02/21 02:54:49 josh Exp $	*/
--- feedback 02e move to quick io DONE NOV 30, 1998

host vxmkcdev -o oracle_file -s 500m /oracle/rdata01/ebay/oradata/feedbackd02e_q.dbf
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02e.dbf' offline;
host dd if=/oracle/rdata03/ebay/oradata/feedbackd02e.dbf of=/oracle/rdata01/ebay/oradata/feedbackd02e_q.dbf bs=102400
alter database rename file '/oracle/rdata03/ebay/oradata/feedbackd02e.dbf' to '/oracle/rdata01/ebay/oradata/feedbackd02e_q.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/feedbackd02e_q.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/feedbackd02e_q.dbf' online;

--- move fb02d to quick io DONE NOV 30, 1998

alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02d_q.dbf' offline;

cd /oracle/rdata03/ebay/oradata
~oracle7/bkup_kit/copyfile /oracle/rdata01/ebay/oradata feedbackd02d_q.dbf .feedbackd02d_q.dbf
~oracle7/bkup_kit/copyfile /oracle/rbackup03/ebay/old feedbackd02d_q.dbf .feedbackd02d_q.dbf

alter database rename file '/oracle/rdata03/ebay/oradata/feedbackd02d_q.dbf' to '/oracle/rdata01/ebay/oradata/feedbackd02d_q.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/feedbackd02d_q.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/feedbackd02d_q.dbf' online;

---- rdata06 DONE Nov 30, 1998

host vxmkcdev -o oracle_file -s 254m /oracle/rdata06/ebay/oradata/ritemd03_q.dbf
host vxmkcdev -o oracle_file -s 130m /oracle/rdata06/ebay/oradata/ritemd03a_q.dbf 
host vxmkcdev -o oracle_file -s 310m /oracle/rdata06/ebay/oradata/ritemd03b_q.dbf 

alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03a.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03b.dbf' offline;

host dd if=/oracle/rdata06/ebay/oradata/ritemd03.dbf of=/oracle/rdata06/ebay/oradata/ritemd03_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ritemd03a.dbf of=/oracle/rdata06/ebay/oradata/ritemd03a_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ritemd03b.dbf of=/oracle/rdata06/ebay/oradata/ritemd03b_q.dbf bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/ritemd03.dbf' to '/oracle/rdata06/ebay/oradata/ritemd03_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ritemd03a.dbf' to '/oracle/rdata06/ebay/oradata/ritemd03a_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ritemd03b.dbf' to '/oracle/rdata06/ebay/oradata/ritemd03b_q.dbf';

recover datafile '/oracle/rdata06/ebay/oradata/ritemd03_q.dbf'; 
recover datafile '/oracle/rdata06/ebay/oradata/ritemd03a_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/ritemd03b_q.dbf';

alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03_q.dbf' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03a_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/ritemd03b_q.dbf' online;


----- rdata05 done nov 30, 1998

host vxmkcdev -o oracle_file -s 60m /oracle/rdata05/ebay/oradata/raccounti5_q.dbf
host vxmkcdev -o oracle_file -s 60m /oracle/rdata05/ebay/oradata/raccounti6_q.dbf

alter database datafile '/oracle/rdata05/ebay/oradata/raccounti5.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/raccounti6.dbf' offline;

host dd if=/oracle/rdata05/ebay/oradata/raccounti5.dbf of=/oracle/rdata05/ebay/oradata/raccounti5_q.dbf bs=102400
host dd if=/oracle/rdata05/ebay/oradata/raccounti6.dbf of=/oracle/rdata05/ebay/oradata/raccounti6_q.dbf bs=102400

alter database rename file '/oracle/rdata05/ebay/oradata/raccounti5.dbf' to '/oracle/rdata05/ebay/oradata/raccounti5_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/raccounti6.dbf' to '/oracle/rdata05/ebay/oradata/raccounti6_q.dbf';

recover datafile '/oracle/rdata05/ebay/oradata/raccounti5_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/raccounti6_q.dbf';

alter database datafile '/oracle/rdata05/ebay/oradata/raccounti5_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/raccounti6_q.dbf' online;

--- move item to qio DONE NOV 30, 1998

host vxmkcdev -o oracle_file -s 804m /oracle/rdata02/ebay/oradata/ritemd01_q.dbf
host vxmkcdev -o oracle_file -s 310m /oracle/rdata02/ebay/oradata/ritemd01a_q.dbf
host vxmkcdev -o oracle_file -s 310m /oracle/rdata02/ebay/oradata/ritemd01b_q.dbf
host vxmkcdev -o oracle_file -s 500m /oracle/rdata02/ebay/oradata/ritemd01c_q.dbf

alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01.dbf' offline;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01a.dbf' offline;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01b.dbf' offline;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01c.dbf' offline;

host dd if=/oracle/rdata02/ebay/oradata/ritemd01.dbf of=/oracle/rdata02/ebay/oradata/ritemd01_q.dbf bs=102400
host dd if=/oracle/rdata02/ebay/oradata/ritemd01a.dbf of=/oracle/rdata02/ebay/oradata/ritemd01a_q.dbf bs=102400 
host dd if=/oracle/rdata02/ebay/oradata/ritemd01b.dbf of=/oracle/rdata02/ebay/oradata/ritemd01b_q.dbf bs=102400 
host dd if=/oracle/rdata02/ebay/oradata/ritemd01c.dbf of=/oracle/rdata02/ebay/oradata/ritemd01c_q.dbf bs=102400 

alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01.dbf' to '/oracle/rdata02/ebay/oradata/ritemd01_q.dbf';
alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01a.dbf' to '/oracle/rdata02/ebay/oradata/ritemd01a_q.dbf';
alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01b.dbf' to '/oracle/rdata02/ebay/oradata/ritemd01b_q.dbf';
alter database rename file '/oracle/rdata02/ebay/oradata/ritemd01c.dbf' to '/oracle/rdata02/ebay/oradata/ritemd01c_q.dbf';

recover datafile '/oracle/rdata02/ebay/oradata/ritemd01_q.dbf';
recover datafile '/oracle/rdata02/ebay/oradata/ritemd01a_q.dbf';
recover datafile '/oracle/rdata02/ebay/oradata/ritemd01b_q.dbf';
recover datafile '/oracle/rdata02/ebay/oradata/ritemd01c_q.dbf';

alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01_q.dbf' online;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01a_q.dbf' online;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01b_q.dbf' online;
alter database datafile '/oracle/rdata02/ebay/oradata/ritemd01c_q.dbf' online;


--- Done Nov 30, 1998

host vxmkcdev -o oracle_file -s 50m /oracle/rdata06/ebay/oradata/accounti02_q.dbf 
host vxmkcdev -o oracle_file -s 20m /oracle/rdata06/ebay/oradata/accounti02a_q.dbf
host vxmkcdev -o oracle_file -s 50m /oracle/rdata06/ebay/oradata/accounti02b_q.dbf
host vxmkcdev -o oracle_file -s 200m /oracle/rdata06/ebay/oradata/accounti02c_q.dbf 
host vxmkcdev -o oracle_file -s 200m /oracle/rdata06/ebay/oradata/accounti02d_q.dbf 
host vxmkcdev -o oracle_file -s 820m /oracle/rdata06/ebay/oradata/accounti02f_q.dbf 
host vxmkcdev -o oracle_file -s 841m /oracle/rdata06/ebay/oradata/accounti02e_q.dbf 

alter database datafile '/oracle/rdata06/ebay/oradata/accounti02.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02a.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02b.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02c.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02d.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02e.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02f.dbf' offline;

host dd if=/oracle/rdata06/ebay/oradata/accounti02.dbf of=/oracle/rdata06/ebay/oradata/accounti02_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/accounti02a.dbf of=/oracle/rdata06/ebay/oradata/accounti02a_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/accounti02b.dbf of=/oracle/rdata06/ebay/oradata/accounti02b_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/accounti02c.dbf of=/oracle/rdata06/ebay/oradata/accounti02c_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/accounti02d.dbf of=/oracle/rdata06/ebay/oradata/accounti02d_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/accounti02e.dbf of=/oracle/rdata06/ebay/oradata/accounti02e_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/accounti02f.dbf of=/oracle/rdata06/ebay/oradata/accounti02f_q.dbf bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/accounti02.dbf' to '/oracle/rdata06/ebay/oradata/accounti02_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/accounti02a.dbf' to '/oracle/rdata06/ebay/oradata/accounti02a_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/accounti02b.dbf' to '/oracle/rdata06/ebay/oradata/accounti02b_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/accounti02c.dbf' to '/oracle/rdata06/ebay/oradata/accounti02c_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/accounti02d.dbf' to '/oracle/rdata06/ebay/oradata/accounti02d_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/accounti02e.dbf' to '/oracle/rdata06/ebay/oradata/accounti02e_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/accounti02f.dbf' to '/oracle/rdata06/ebay/oradata/accounti02f_q.dbf';

recover datafile '/oracle/rdata06/ebay/oradata/accounti02_q.dbf'; 
recover datafile '/oracle/rdata06/ebay/oradata/accounti02a_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/accounti02b_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/accounti02c_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/accounti02d_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/accounti02e_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/accounti02f_q.dbf';

alter database datafile '/oracle/rdata06/ebay/oradata/accounti02_q.dbf' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02a_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02b_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02c_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02d_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02e_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/accounti02f_q.dbf' online;

--- Done Nov 30, 1998

host vxmkcdev -o oracle_file -s 25m /oracle/rdata06/ebay/oradata/ruseri03_q.dbf 
host vxmkcdev -o oracle_file -s 180m /oracle/rdata06/ebay/oradata/ruseri04_q.dbf
host vxmkcdev -o oracle_file -s 126m /oracle/rdata06/ebay/oradata/ruseri04a_q.dbf 
host vxmkcdev -o oracle_file -s 146m /oracle/rdata06/ebay/oradata/ruseri04b_q.dbf 
host vxmkcdev -o oracle_file -s 146m /oracle/rdata06/ebay/oradata/ruseri04c_q.dbf 
host vxmkcdev -o oracle_file -s 300m /oracle/rdata06/ebay/oradata/ruseri04e_q.dbf 
host vxmkcdev -o oracle_file -s 300m /oracle/rdata06/ebay/oradata/ruseri04d_q.dbf 
host vxmkcdev -o oracle_file -s 500m /oracle/rdata06/ebay/oradata/ruseri04f_q.dbf 
host vxmkcdev -o oracle_file -s 30m /oracle/rdata06/ebay/oradata/ruseri05_q.dbf 

alter database datafile '/oracle/rdata06/ebay/oradata/ruseri03.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri05.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04a.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04b.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04c.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04e.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04d.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04f.dbf' offline;

host dd if=/oracle/rdata06/ebay/oradata/ruseri03.dbf of=/oracle/rdata06/ebay/oradata/ruseri03_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri04.dbf of=/oracle/rdata06/ebay/oradata/ruseri04_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri04a.dbf of=/oracle/rdata06/ebay/oradata/ruseri04a_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri04b.dbf of=/oracle/rdata06/ebay/oradata/ruseri04b_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri04c.dbf of=/oracle/rdata06/ebay/oradata/ruseri04c_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri04e.dbf of=/oracle/rdata06/ebay/oradata/ruseri04e_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri04d.dbf of=/oracle/rdata06/ebay/oradata/ruseri04d_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri04f.dbf of=/oracle/rdata06/ebay/oradata/ruseri04f_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/ruseri05.dbf of=/oracle/rdata06/ebay/oradata/ruseri05_q.dbf bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/ruseri03.dbf' to '/oracle/rdata06/ebay/oradata/ruseri03_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri04.dbf' to '/oracle/rdata06/ebay/oradata/ruseri04_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri04a.dbf' to '/oracle/rdata06/ebay/oradata/ruseri04a_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri04b.dbf' to '/oracle/rdata06/ebay/oradata/ruseri04b_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri04c.dbf' to '/oracle/rdata06/ebay/oradata/ruseri04c_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri04e.dbf' to '/oracle/rdata06/ebay/oradata/ruseri04e_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri04d.dbf' to '/oracle/rdata06/ebay/oradata/ruseri04d_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri04f.dbf' to '/oracle/rdata06/ebay/oradata/ruseri04f_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/ruseri05.dbf' to '/oracle/rdata06/ebay/oradata/ruseri05_q.dbf';

recover datafile '/oracle/rdata06/ebay/oradata/ruseri03_q.dbf'; 
recover datafile '/oracle/rdata06/ebay/oradata/ruseri04_q.dbf'; 
recover datafile '/oracle/rdata06/ebay/oradata/ruseri04a_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/ruseri04b_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/ruseri04c_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/ruseri04e_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/ruseri04d_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/ruseri04f_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/ruseri05_q.dbf'; 

alter database datafile '/oracle/rdata06/ebay/oradata/ruseri03_q.dbf' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04_q.dbf' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04a_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04b_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04c_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04e_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04d_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri04f_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/ruseri05_q.dbf' online; 

--- Done nov 30, 98

host vxmkcdev -o oracle_file -s 15m /oracle/rdata06/ebay/oradata/feedbacki01_q.dbf
host vxmkcdev -o oracle_file -s 200m /oracle/rdata06/ebay/oradata/feedbacki01a_q.dbf

alter database datafile '/oracle/rdata06/ebay/oradata/feedbacki01.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/feedbacki01a.dbf' offline; 

host dd if=/oracle/rdata06/ebay/oradata/feedbacki01.dbf of=/oracle/rdata06/ebay/oradata/feedbacki01_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/feedbacki01a.dbf of=/oracle/rdata06/ebay/oradata/feedbacki01a_q.dbf bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/feedbacki01.dbf' to '/oracle/rdata06/ebay/oradata/feedbacki01_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/feedbacki01a.dbf' to '/oracle/rdata06/ebay/oradata/feedbacki01a_q.dbf';

recover datafile '/oracle/rdata06/ebay/oradata/feedbacki01_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/feedbacki01a_q.dbf'; 

alter database datafile '/oracle/rdata06/ebay/oradata/feedbacki01_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/feedbacki01a_q.dbf' online; 

---

host vxmkcdev -o oracle_file -s 300m /oracle/rdata06/ebay/oradata/itemarci2_q.dbf 
host vxmkcdev -o oracle_file -s 310m /oracle/rdata06/ebay/oradata/itemarci2a_q.dbf

alter database datafile '/oracle/rdata06/ebay/oradata/itemarci2.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarci2a.dbf' offline; 

host dd if=/oracle/rdata06/ebay/oradata/itemarci2.dbf of=/oracle/rdata06/ebay/oradata/itemarci2_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarci2a.dbf of=/oracle/rdata06/ebay/oradata/itemarci2a_q.dbf bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/itemarci2.dbf' to '/oracle/rdata06/ebay/oradata/itemarci2_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarci2a.dbf' to '/oracle/rdata06/ebay/oradata/itemarci2a_q.dbf';

recover datafile '/oracle/rdata06/ebay/oradata/itemarci2_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarci2a_q.dbf'; 

alter database datafile '/oracle/rdata06/ebay/oradata/itemarci2_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarci2a_q.dbf' online; 

--- rdata05 arc

host vxmkcdev -o oracle_file -s 600m /oracle/rdata05/ebay/oradata/itemarc3h_q.dbf
alter database datafile '/oracle/rdata05/ebay/oradata/itemarc3h.dbf' offline;
host dd if=/oracle/rdata05/ebay/oradata/itemarc3h.dbf of=/oracle/rdata05/ebay/oradata/itemarc3h_q.dbf bs=102400
alter database rename file '/oracle/rdata05/ebay/oradata/itemarc3h.dbf' to '/oracle/rdata05/ebay/oradata/itemarc3h_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/itemarc3h_q.dbf';
alter database datafile '/oracle/rdata05/ebay/oradata/itemarc3h_q.dbf' online;

--- 

host vxmkcdev -o oracle_file -s 1100m /oracle/rdata06/ebay/oradata/itemarc3_q.dbf 
host vxmkcdev -o oracle_file -s 400m /oracle/rdata06/ebay/oradata/itemarc3a_q.dbf 
host vxmkcdev -o oracle_file -s 600m /oracle/rdata06/ebay/oradata/itemarc3d_q.dbf 
host vxmkcdev -o oracle_file -s 600m /oracle/rdata06/ebay/oradata/itemarc3f_q.dbf 
host vxmkcdev -o oracle_file -s 210m /oracle/rdata06/ebay/oradata/itemarc3b_q.dbf 
host vxmkcdev -o oracle_file -s 600m /oracle/rdata06/ebay/oradata/itemarc3c_q.dbf 
host vxmkcdev -o oracle_file -s 600m /oracle/rdata06/ebay/oradata/itemarc3e_q.dbf 
host vxmkcdev -o oracle_file -s 600m /oracle/rdata06/ebay/oradata/itemarc3g_q.dbf 
host vxmkcdev -o oracle_file -s 600m /oracle/rdata06/ebay/oradata/itemarc3i_q.dbf 

alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3a.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3d.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3f.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3b.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3c.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3e.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3g.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3i.dbf' offline;

host dd if=/oracle/rdata06/ebay/oradata/itemarc3a.dbf of=/oracle/rdata06/ebay/oradata/itemarc3a_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3.dbf of=/oracle/rdata06/ebay/oradata/itemarc3_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3d.dbf of=/oracle/rdata06/ebay/oradata/itemarc3d_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3f.dbf of=/oracle/rdata06/ebay/oradata/itemarc3f_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3b.dbf of=/oracle/rdata06/ebay/oradata/itemarc3b_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3c.dbf of=/oracle/rdata06/ebay/oradata/itemarc3c_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3e.dbf of=/oracle/rdata06/ebay/oradata/itemarc3e_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3g.dbf of=/oracle/rdata06/ebay/oradata/itemarc3g_q.dbf bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/itemarc3i.dbf of=/oracle/rdata06/ebay/oradata/itemarc3i_q.dbf bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3a.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3a_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3d.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3d_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3f.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3f_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3b.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3b_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3c.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3c_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3e.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3e_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3g.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3g_q.dbf';
alter database rename file '/oracle/rdata06/ebay/oradata/itemarc3i.dbf' to '/oracle/rdata06/ebay/oradata/itemarc3i_q.dbf';

recover datafile '/oracle/rdata06/ebay/oradata/itemarc3a_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3_q.dbf'; 
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3d_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3b_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3c_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3e_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3g_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3i_q.dbf';
recover datafile '/oracle/rdata06/ebay/oradata/itemarc3f_q.dbf';

alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3a_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3_q.dbf' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3d_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3f_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3b_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3c_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3e_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3g_q.dbf' online;
alter database datafile '/oracle/rdata06/ebay/oradata/itemarc3i_q.dbf' online;

--- TO BE FIXED

host vxmkcdev -o oracle_file -s 610m /oracle/rdata06/ebay/oradata/bidd02.dbfq
host vxmkcdev -o oracle_file -s 10m /oracle/rdata06/ebay/oradata/parti01.dbfq
host vxmkcdev -o oracle_file -s 101m /oracle/rdata06/ebay/oradata/statmiscd.dbfq 
host vxmkcdev -o oracle_file -s 10m /oracle/rdata06/ebay/oradata/discover01.dbfq 
host vxmkcdev -o oracle_file -s 101m /oracle/rdata06/ebay/oradata/dynmisci.dbfq
host vxmkcdev -o oracle_file -s 20m /oracle/rdata06/ebay/oradata/taccounti01.dbfq
host vxmkcdev -o oracle_file -s 130m /oracle/rdata06/ebay/oradata/statsd01.dbfq
host vxmkcdev -o oracle_file -s 100m /oracle/rdata06/ebay/oradata/bizdevi01.dbfq 

alter database datafile '/oracle/rdata06/ebay/oradata/bidd02.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/parti01.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/statmiscd.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/discover01.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/dynmisci.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/taccounti01.dbf' offline;
alter database datafile '/oracle/rdata06/ebay/oradata/statsd01.dbf' offline; 
alter database datafile '/oracle/rdata06/ebay/oradata/bizdevi01.dbf' offline;

host dd if=/oracle/rdata06/ebay/oradata/bidd02.dbf of=/oracle/rdata06/ebay/oradata/bidd02.dbfq bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/parti01.dbf of=/oracle/rdata06/ebay/oradata/parti01.dbfq bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/statmiscd.dbf of=/oracle/rdata06/ebay/oradata/statmiscd.dbfq bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/discover01.dbf of=/oracle/rdata06/ebay/oradata/discover01.dbfq bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/dynmisci.dbf of=/oracle/rdata06/ebay/oradata/dynmisci.dbfq bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/taccounti01.dbf of=/oracle/rdata06/ebay/oradata/taccounti01.dbfq bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/statsd01.dbf of=/oracle/rdata06/ebay/oradata/statsd01.dbfq bs=102400 
host dd if=/oracle/rdata06/ebay/oradata/bizdevi01.dbf of=/oracle/rdata06/ebay/oradata/bizdevi01.dbfq bs=102400 

alter database rename file '/oracle/rdata06/ebay/oradata/bidd02.dbf' to '/oracle/rdata06/ebay/oradata/bidd02.dbfq';
alter database rename file '/oracle/rdata06/ebay/oradata/parti01.dbf' to '/oracle/rdata06/ebay/oradata/parti01.dbfq';
alter database rename file '/oracle/rdata06/ebay/oradata/statmiscd.dbf' to '/oracle/rdata06/ebay/oradata/statmiscd.dbfq';
alter database rename file '/oracle/rdata06/ebay/oradata/discover01.dbf' to '/oracle/rdata06/ebay/oradata/discover01.dbfq';
alter database rename file '/oracle/rdata06/ebay/oradata/dynmisci.dbf' to '/oracle/rdata06/ebay/oradata/dynmisci.dbfq';
alter database rename file '/oracle/rdata06/ebay/oradata/taccounti01.dbf' to '/oracle/rdata06/ebay/oradata/taccounti01.dbfq';
alter database rename file '/oracle/rdata06/ebay/oradata/statsd01.dbf' to '/oracle/rdata06/ebay/oradata/statsd01.dbfq';
alter database rename file '/oracle/rdata06/ebay/oradata/bizdevi01.dbf' to '/oracle/rdata06/ebay/oradata/bizdevi01.dbfq';

recover datafile '/oracle/rdata06/ebay/oradata/bidd02.dbfq'; 
recover datafile '/oracle/rdata06/ebay/oradata/parti01.dbfq';
recover datafile '/oracle/rdata06/ebay/oradata/statmiscd.dbfq';
recover datafile '/oracle/rdata06/ebay/oradata/discover01.dbfq'; 
recover datafile '/oracle/rdata06/ebay/oradata/dynmisci.dbfq'; 
recover datafile '/oracle/rdata06/ebay/oradata/taccounti01.dbfq';
recover datafile '/oracle/rdata06/ebay/oradata/statsd01.dbfq'; 
recover datafile '/oracle/rdata06/ebay/oradata/bizdevi01.dbfq';

alter database datafile '/oracle/rdata06/ebay/oradata/bidd02.dbfq' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/parti01.dbfq' online;
alter database datafile '/oracle/rdata06/ebay/oradata/statmiscd.dbfq' online;
alter database datafile '/oracle/rdata06/ebay/oradata/discover01.dbfq' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/dynmisci.dbfq' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/taccounti01.dbfq' online;
alter database datafile '/oracle/rdata06/ebay/oradata/statsd01.dbfq' online; 
alter database datafile '/oracle/rdata06/ebay/oradata/bizdevi01.dbfq' online;

--------- part of rdata07 done nov 30, 1998

host vxmkcdev -o oracle_file -s 102m /oracle/rdata07/ebay/oradata/ritemi03_q.dbf
host vxmkcdev -o oracle_file -s 60m /oracle/rdata07/ebay/oradata/ritemi03a_q.dbf
host vxmkcdev -o oracle_file -s 200m /oracle/rdata07/ebay/oradata/ritemi03b_q.dbf 

alter database datafile '/oracle/rdata07/ebay/oradata/ritemi03.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/ritemi03a.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/ritemi03b.dbf' offline;

host dd if=/oracle/rdata07/ebay/oradata/ritemi03.dbf of=/oracle/rdata07/ebay/oradata/ritemi03_q.dbf bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/ritemi03a.dbf of=/oracle/rdata07/ebay/oradata/ritemi03a_q.dbf bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/ritemi03b.dbf of=/oracle/rdata07/ebay/oradata/ritemi03b_q.dbf bs=102400 

alter database rename file '/oracle/rdata07/ebay/oradata/ritemi03.dbf' to '/oracle/rdata07/ebay/oradata/ritemi03_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/ritemi03a.dbf' to '/oracle/rdata07/ebay/oradata/ritemi03a_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/ritemi03b.dbf' to '/oracle/rdata07/ebay/oradata/ritemi03b_q.dbf';

recover datafile '/oracle/rdata07/ebay/oradata/ritemi03_q.dbf'; 
recover datafile '/oracle/rdata07/ebay/oradata/ritemi03a_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/ritemi03b_q.dbf';

alter database datafile '/oracle/rdata07/ebay/oradata/ritemi03_q.dbf' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/ritemi03a_q.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/ritemi03b_q.dbf' online;

--- Done Nov 30, 1998

host vxmkcdev -o oracle_file -s 15m /oracle/rdata07/ebay/oradata/ruserd03_q.dbf 
host vxmkcdev -o oracle_file -s 355m /oracle/rdata07/ebay/oradata/ruserd04_q.dbf
host vxmkcdev -o oracle_file -s 240m /oracle/rdata07/ebay/oradata/ruserd04a_q.dbf 
host vxmkcdev -o oracle_file -s 500m /oracle/rdata07/ebay/oradata/ruserd04b_q.dbf 
host vxmkcdev -o oracle_file -s 500m /oracle/rdata07/ebay/oradata/ruserd04c_q.dbf 
host vxmkcdev -o oracle_file -s 50m /oracle/rdata07/ebay/oradata/ruserd05_q.dbf 

alter database datafile '/oracle/rdata07/ebay/oradata/ruserd03.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04a.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04b.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04c.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd05.dbf' offline; 

host dd if=/oracle/rdata07/ebay/oradata/ruserd03.dbf of=/oracle/rdata07/ebay/oradata/ruserd03_q.dbf bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/ruserd04.dbf of=/oracle/rdata07/ebay/oradata/ruserd04_q.dbf bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/ruserd04a.dbf of=/oracle/rdata07/ebay/oradata/ruserd04a_q.dbf bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/ruserd04b.dbf of=/oracle/rdata07/ebay/oradata/ruserd04b_q.dbf bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/ruserd04c.dbf of=/oracle/rdata07/ebay/oradata/ruserd04c_q.dbf bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/ruserd05.dbf of=/oracle/rdata07/ebay/oradata/ruserd05_q.dbf bs=102400 

alter database rename file '/oracle/rdata07/ebay/oradata/ruserd03.dbf' to '/oracle/rdata07/ebay/oradata/ruserd03_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/ruserd04.dbf' to '/oracle/rdata07/ebay/oradata/ruserd04_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/ruserd04a.dbf' to '/oracle/rdata07/ebay/oradata/ruserd04a_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/ruserd04b.dbf' to '/oracle/rdata07/ebay/oradata/ruserd04b_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/ruserd04c.dbf' to '/oracle/rdata07/ebay/oradata/ruserd04c_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/ruserd05.dbf' to '/oracle/rdata07/ebay/oradata/ruserd05_q.dbf';

recover datafile '/oracle/rdata07/ebay/oradata/ruserd03_q.dbf'; 
recover datafile '/oracle/rdata07/ebay/oradata/ruserd04_q.dbf'; 
recover datafile '/oracle/rdata07/ebay/oradata/ruserd05_q.dbf'; 
recover datafile '/oracle/rdata07/ebay/oradata/ruserd04a_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/ruserd04b_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/ruserd04c_q.dbf';

alter database datafile '/oracle/rdata07/ebay/oradata/ruserd03_q.dbf' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04_q.dbf' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd05_q.dbf' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04a_q.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04b_q.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04c_q.dbf' online;

---

host vxmkcdev -o oracle_file -s 170m /oracle/rdata07/ebay/oradata/itemarc2.dbfq
host vxmkcdev -o oracle_file -s 100m /oracle/rdata07/ebay/oradata/itemarc2a.dbfq 
host vxmkcdev -o oracle_file -s 200m /oracle/rdata07/ebay/oradata/itemarc2b.dbfq 
host vxmkcdev -o oracle_file -s 395m /oracle/rdata07/ebay/oradata/itemarc2c.dbfq 
host vxmkcdev -o oracle_file -s 395m /oracle/rdata07/ebay/oradata/itemarc2d.dbfq 
host vxmkcdev -o oracle_file -s 300m /oracle/rdata07/ebay/oradata/itemarci2b.dbfq

host vxmkcdev -o oracle_file -s 140m /oracle/rdata07/ebay/oradata/itemarci1.dbfq 
host vxmkcdev -o oracle_file -s 160m /oracle/rdata07/ebay/oradata/itemarci1a.dbfq
host vxmkcdev -o oracle_file -s 390m /oracle/rdata07/ebay/oradata/itemarci1b.dbfq

host vxmkcdev -o oracle_file -s 10m /oracle/rdata07/ebay/oradata/systebay.dbfq 
host vxmkcdev -o oracle_file -s 30m /oracle/rdata07/ebay/oradata/partd01.dbfq
host vxmkcdev -o oracle_file -s 10m /oracle/rdata07/ebay/oradata/cc.dbfq 
host vxmkcdev -o oracle_file -s 250m /oracle/rdata07/ebay/oradata/summary.dbfq 
host vxmkcdev -o oracle_file -s 20m /oracle/rdata07/ebay/oradata/systebay01.dbfq 
host vxmkcdev -o oracle_file -s 101m /oracle/rdata07/ebay/oradata/statmisci.dbfq 
host vxmkcdev -o oracle_file -s 151m /oracle/rdata07/ebay/oradata/dynmiscd.dbfq
host vxmkcdev -o oracle_file -s 30m /oracle/rdata07/ebay/oradata/statsi01.dbfq 
host vxmkcdev -o oracle_file -s 1300m /oracle/rdata07/ebay/oradata/achistoryd01.dbfq 
host vxmkcdev -o oracle_file -s 700m /oracle/rdata07/ebay/oradata/bizdevd01.dbfq 
host vxmkcdev -o oracle_file -s 10m /oracle/rdata07/ebay/oradata/cca.dbfq
host vxmkcdev -o oracle_file -s 50m /oracle/rdata07/ebay/oradata/usrebay1.dbfq 

alter database datafile '/oracle/rdata07/ebay/oradata/systebay.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/partd01.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/cc.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/summary.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/systebay01.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/statmisci.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/dynmiscd.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2a.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/statsi01.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/achistoryd01.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci1.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/bizdevd01.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci1a.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2b.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/cca.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/usrebay1.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2c.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci1b.dbf' offline; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2d.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci2b.dbf' offline; 

host dd if=/oracle/rdata07/ebay/oradata/systebay.dbf of=/oracle/rdata07/ebay/oradata/systebay.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/partd01.dbf of=/oracle/rdata07/ebay/oradata/partd01.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/cc.dbf of=/oracle/rdata07/ebay/oradata/cc.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/summary.dbf of=/oracle/rdata07/ebay/oradata/summary.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/systebay01.dbf of=/oracle/rdata07/ebay/oradata/systebay01.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/statmisci.dbf of=/oracle/rdata07/ebay/oradata/statmisci.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/dynmiscd.dbf of=/oracle/rdata07/ebay/oradata/dynmiscd.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarc2a.dbf of=/oracle/rdata07/ebay/oradata/itemarc2a.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/statsi01.dbf of=/oracle/rdata07/ebay/oradata/statsi01.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarc2.dbf of=/oracle/rdata07/ebay/oradata/itemarc2.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/achistoryd01.dbf of=/oracle/rdata07/ebay/oradata/achistoryd01.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarci1.dbf of=/oracle/rdata07/ebay/oradata/itemarci1.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/bizdevd01.dbf of=/oracle/rdata07/ebay/oradata/bizdevd01.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarci1a.dbf of=/oracle/rdata07/ebay/oradata/itemarci1a.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarc2b.dbf of=/oracle/rdata07/ebay/oradata/itemarc2b.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/cca.dbf of=/oracle/rdata07/ebay/oradata/cca.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/usrebay1.dbf of=/oracle/rdata07/ebay/oradata/usrebay1.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarc2c.dbf of=/oracle/rdata07/ebay/oradata/itemarc2c.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarci1b.dbf of=/oracle/rdata07/ebay/oradata/itemarci1b.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarc2d.dbf of=/oracle/rdata07/ebay/oradata/itemarc2d.dbfq bs=102400 
host dd if=/oracle/rdata07/ebay/oradata/itemarci2b.dbf of=/oracle/rdata07/ebay/oradata/itemarci2b.dbfq bs=102400 

alter database rename file '/oracle/rdata07/ebay/oradata/systebay.dbf' to '/oracle/rdata07/ebay/oradata/systebay.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/partd01.dbf' to '/oracle/rdata07/ebay/oradata/partd01.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/cc.dbf' to '/oracle/rdata07/ebay/oradata/cc.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/summary.dbf' to '/oracle/rdata07/ebay/oradata/summary.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/systebay01.dbf' to '/oracle/rdata07/ebay/oradata/systebay01.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/statmisci.dbf' to '/oracle/rdata07/ebay/oradata/statmisci.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/dynmiscd.dbf' to '/oracle/rdata07/ebay/oradata/dynmiscd.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarc2a.dbf' to '/oracle/rdata07/ebay/oradata/itemarc2a.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/statsi01.dbf' to '/oracle/rdata07/ebay/oradata/statsi01.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarc2.dbf' to '/oracle/rdata07/ebay/oradata/itemarc2.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/achistoryd01.dbf' to '/oracle/rdata07/ebay/oradata/achistoryd01.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarci1.dbf' to '/oracle/rdata07/ebay/oradata/itemarci1.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/bizdevd01.dbf' to '/oracle/rdata07/ebay/oradata/bizdevd01.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarci1a.dbf' to '/oracle/rdata07/ebay/oradata/itemarci1a.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarc2b.dbf' to '/oracle/rdata07/ebay/oradata/itemarc2b.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/cca.dbf' to '/oracle/rdata07/ebay/oradata/cca.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/usrebay1.dbf' to '/oracle/rdata07/ebay/oradata/usrebay1.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarc2c.dbf' to '/oracle/rdata07/ebay/oradata/itemarc2c.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarci1b.dbf' to '/oracle/rdata07/ebay/oradata/itemarci1b.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarc2d.dbf' to '/oracle/rdata07/ebay/oradata/itemarc2d.dbfq';
alter database rename file '/oracle/rdata07/ebay/oradata/itemarci2b.dbf' to '/oracle/rdata07/ebay/oradata/itemarci2b.dbfq';

recover datafile '/oracle/rdata07/ebay/oradata/systebay.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/partd01.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/cc.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/summary.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/systebay01.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/statmisci.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/dynmiscd.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/itemarc2a.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/statsi01.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/itemarc2.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/achistoryd01.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/itemarci1.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/bizdevd01.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/itemarci1a.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/itemarc2b.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/cca.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/usrebay1.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/itemarc2c.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/itemarci1b.dbfq'; 
recover datafile '/oracle/rdata07/ebay/oradata/itemarc2d.dbfq';
recover datafile '/oracle/rdata07/ebay/oradata/itemarci2b.dbfq'; 

alter database datafile '/oracle/rdata07/ebay/oradata/systebay.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/partd01.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/cc.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/summary.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/systebay01.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/statmisci.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/dynmiscd.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2a.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/statsi01.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/achistoryd01.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci1.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/bizdevd01.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci1a.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2b.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/cca.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/usrebay1.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2c.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci1b.dbfq' online; 
alter database datafile '/oracle/rdata07/ebay/oradata/itemarc2d.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/ruserd04c.dbfq' online;
alter database datafile '/oracle/rdata07/ebay/oradata/itemarci2b.dbfq' online; 
/

-- new qio creation command
qiomkfile -h -s 2047M /oracle/rdata01/ebay/oradata/userd07.dbf

create tablespace userd07
	datafile '/oracle/rdata01/ebay/oradata/userd07.dbf'
	size 2000M autoextend off;

create tablespace useri07
	datafile '/oracle/rdata07/ebay/oradata/useri07.dbf'
	size 300M autoextend off;

// login as tini

 CREATE TABLE "EBAY_BIDDER_ITEM_LISTS" ("ID" NUMBER(*,0) CONSTRAINT
"ITEM_BLISTS_ID_NN" NOT NULL, "ITEM_COUNT" NUMBER(*,0) CONSTRAINT
"ITEM_BLISTS_ITEM_COUNT_NN" NOT NULL, "ITEM_LIST_SIZE" NUMBER(*,0)
CONSTRAINT "ITEM_BLISTS_LIST_SIZE_NN" NOT NULL, "ITEM_LIST_SIZE_USED"
NUMBER(*,0) CONSTRAINT "ITEM_BLISTS_LIST_USED_NN" NOT NULL,
"ITEM_LIST_VALID" CHAR(1) CONSTRAINT "ITEM_BLISTS_VALID_NN" NOT NULL,
"ITEM_LIST" LONG RAW)  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255
STORAGE(INITIAL 1024M NEXT 500M MINEXTENTS 1 MAXEXTENTS 121
PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1) TABLESPACE "USERD07";
GRANT ALTER ON "EBAY_BIDDER_ITEM_LISTS" TO "ENG";
GRANT DELETE ON "EBAY_BIDDER_ITEM_LISTS" TO "ENG";
GRANT INDEX ON "EBAY_BIDDER_ITEM_LISTS" TO "ENG";
GRANT INSERT ON "EBAY_BIDDER_ITEM_LISTS" TO "ENG";
GRANT SELECT ON "EBAY_BIDDER_ITEM_LISTS" TO "ENG";
GRANT UPDATE ON "EBAY_BIDDER_ITEM_LISTS" TO "ENG";
GRANT REFERENCES ON "EBAY_BIDDER_ITEM_LISTS" TO "ENG";
GRANT ALTER ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";
GRANT DELETE ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";
GRANT INDEX ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";
GRANT INSERT ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";
GRANT SELECT ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";
GRANT UPDATE ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";
GRANT REFERENCES ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";
GRANT ALL ON "EBAY_BIDDER_ITEM_LISTS" TO "SCOTT";

Then imp scott file=ebay.exp ignore=y

Note the constraint create failed as expected...then

ALTER TABLE "EBAY_BIDDER_ITEM_LISTS" ADD  CONSTRAINT "ITEM_BLISTS_PK"
PRIMARY KEY ("ID") USING INDEX STORAGE (INITIAL 78643200 NEXT 10485760
MINEXTENTS 1 MAXEXTENTS 121 PCTINCREASE 0 FREELISTS 1 ) TABLESPACE
"QRUSERI07N";

// 01-04-99 move off qio

 cd /oracle/rdata08/ebay/oradata
mv .qitemsi01.dbf qitemsi01.dbf
 mv .qitemsi02.dbf qitemsi02.dbf
 mv .qitemsi03.dbf qitemsi03.dbf
 mv .qitemsi04.dbf qitemsi04.dbf
 mv .qitemsi05.dbf qitemsi05.dbf
 mv .qitemsi05-01.dbf qitemsi05-01.dbf
 mv .qitemsi05-02.dbf qitemsi05-02.dbf
 mv .qitemsi06.dbf qitemsi06.dbf
 mv .qitemsi07.dbf qitemsi07.dbf

cd /oracle/rdata09/ebay/oradata
mv .qbidsi02.dbf qbidsi02.dbf
cd /oracle/rdata04/ebay/oradata
mv .qbidsi01.dbf qbidsi01.dbf

cd /oracle/rdata09/ebay/oradata
mv .quserd01.dbf quserd01.dbf

// 01-05-99 move back to qio

==== QRUSERD06c - Done

host qiomkfile -h -s 500M /oracle/rdata09/ebay/oradata/qruserd06c_q.dbf
alter tablespace qruserd06 offline;
OR
alter database datafile '/oracle/rdata09/ebay/oradata/qruserd06c.dbf' offline;
host dd if=/oracle/rdata09/ebay/oradata/qruserd06c.dbf of=/oracle/rdata09/ebay/oradata/qruserd06c_q.dbf bs=102400
alter database rename file '/oracle/rdata09/ebay/oradata/qruserd06c.dbf' to 
'/oracle/rdata09/ebay/oradata/qruserd06c_q.dbf';
alter tablespace qruserd06 online;
OR
recover datafile '/oracle/rdata09/ebay/oradata/qruserd06c_q.dbf';
alter database datafile '/oracle/rdata09/ebay/oradata/qruserd06c_q.dbf' online;

==== QRUSERD07N Done

/oracle/rdata09/ebay/oradata/qruserd07n.dbf        QRUSERD07N 2001M
/oracle/rdata07/ebay/oradata/qruseri07n.dbf        QRUSERI07N 301M


host qiomkfile -h -s 2001M /oracle/rdata09/ebay/oradata/qruserd07n_q.dbf
alter tablespace qruserd07n offline;
OR
alter database datafile '/oracle/rdata09/ebay/oradata/qruserd07n.dbf' offline;
host dd if=/oracle/rdata09/ebay/oradata/qruserd07n.dbf of=/oracle/rdata09/ebay/oradata/qruserd07n_q.dbf bs=102400
alter database rename file '/oracle/rdata09/ebay/oradata/qruserd07n.dbf' to 
'/oracle/rdata09/ebay/oradata/qruserd07n_q.dbf';
alter tablespace qruserd07n online;
OR
recover datafile '/oracle/rdata09/ebay/oradata/qruserd07n_q.dbf';
alter database datafile '/oracle/rdata09/ebay/oradata/qruserd07n_q.dbf' online;

==== Done

host qiomkfile -h -s 301M /oracle/rdata07/ebay/oradata/qruseri07n_q.dbf
alter tablespace qruseri07n offline;
OR
alter database datafile '/oracle/rdata07/ebay/oradata/qruseri07n.dbf' offline;
host dd if=/oracle/rdata07/ebay/oradata/qruseri07n.dbf of=/oracle/rdata07/ebay/oradata/qruseri07n_q.dbf bs=102400
alter database rename file '/oracle/rdata07/ebay/oradata/qruseri07n.dbf' to 
'/oracle/rdata07/ebay/oradata/qruseri07n_q.dbf';
alter tablespace qruseri07n online;
OR
recover datafile '/oracle/rdata07/ebay/oradata/qruseri07n_q.dbf';
alter database datafile '/oracle/rdata07/ebay/oradata/qruseri07n_q.dbf' online;

==== Done

Bids indices:
/oracle/rdata09/ebay/oradata/qbidsi02.dbf  1000M
/oracle/rdata09/ebay/oradata/qbidsi02a.dbf 500M

host qiomkfile -h -s 1000M /oracle/rdata09/ebay/oradata/qbidsi02_q.dbf
host qiomkfile -h -s 500M /oracle/rdata09/ebay/oradata/qbidsi02a_q.dbf
alter tablespace qbidsi02 offline;
OR
alter database datafile '/oracle/rdata09/ebay/oradata/qbidsi02.dbf' offline;
alter database datafile '/oracle/rdata09/ebay/oradata/qbidsi02a.dbf' offline;
host dd if=/oracle/rdata09/ebay/oradata/qbidsi02.dbf of=/oracle/rdata09/ebay/oradata/qbidsi02_q.dbf bs=102400
host dd if=/oracle/rdata09/ebay/oradata/qbidsi02a.dbf of=/oracle/rdata09/ebay/oradata/qbidsi02a_q.dbf bs=102400
alter database rename file '/oracle/rdata09/ebay/oradata/qbidsi02.dbf' to 
'/oracle/rdata09/ebay/oradata/qbidsi02_q.dbf';
alter database rename file '/oracle/rdata09/ebay/oradata/qbidsi02a.dbf' to 
'/oracle/rdata09/ebay/oradata/qbidsi02a_q.dbf';
alter tablespace qbidsi02 online;
OR
recover datafile '/oracle/rdata09/ebay/oradata/qbidsi02_q.dbf';
recover datafile '/oracle/rdata09/ebay/oradata/qbidsi02a_q.dbf';
alter database datafile '/oracle/rdata09/ebay/oradata/qbidsi02_q.dbf' online;
alter database datafile '/oracle/rdata09/ebay/oradata/qbidsi02a_q.dbf' online;

==== Done

/oracle/rdata04/ebay/oradata/qbidsi01.dbf  1000M
/oracle/rdata04/ebay/oradata/qbidsi01a.dbf 1024M

host qiomkfile -h -s 1000M /oracle/rdata04/ebay/oradata/qbidsi01_q.dbf
host qiomkfile -h -s 1024M /oracle/rdata04/ebay/oradata/qbidsi01a_q.dbf
alter tablespace qbidsi01 offline;
OR
alter database datafile '/oracle/rdata04/ebay/oradata/qbidsi01.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qbidsi01a.dbf' offline;
host dd if=/oracle/rdata04/ebay/oradata/qbidsi01.dbf of=/oracle/rdata04/ebay/oradata/qbidsi01_q.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qbidsi01a.dbf of=/oracle/rdata04/ebay/oradata/qbidsi01a_q.dbf bs=102400
alter database rename file '/oracle/rdata04/ebay/oradata/qbidsi01.dbf' to 
'/oracle/rdata04/ebay/oradata/qbidsi01_q.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qbidsi01a.dbf' to 
'/oracle/rdata04/ebay/oradata/qbidsi01a_q.dbf';
alter tablespace qbidsi01 online;
OR
recover datafile '/oracle/rdata04/ebay/oradata/qbidsi01_q.dbf';
recover datafile '/oracle/rdata04/ebay/oradata/qbidsi01a_q.dbf';
alter database datafile '/oracle/rdata04/ebay/oradata/qbidsi01_q.dbf' online;
alter database datafile '/oracle/rdata04/ebay/oradata/qbidsi01a_q.dbf' online;

==== Done

/oracle/rdata08/ebay/oradata/qitemsi07.dbf
QITEMSI07                      1048576000
/oracle/rdata04/ebay/oradata/qitemsi07a.dbf
QITEMSI07                       524288000

host qiomkfile -h -s 1000M /oracle/rdata01/ebay/oradata/qitemsi07.dbf
host qiomkfile -h -s 500M /oracle/rdata01/ebay/oradata/qitemsi07a.dbf
alter tablespace QITEMSI07 offline;
OR
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi07.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qitemsi07a.dbf' offline;
host dd if=/oracle/rdata08/ebay/oradata/qitemsi07.dbf of=/oracle/rdata01/ebay/oradata/qitemsi07.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qitemsi07a.dbf of=/oracle/rdata01/ebay/oradata/qitemsi07a.dbf bs=102400
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi07.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi07.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qitemsi07a.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi07a.dbf';
alter tablespace qitemsi07 online;
OR
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi07.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi07a.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi07.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi07a.dbf' online;

==== DONE

/oracle/rdata08/ebay/oradata/qitemsi06.dbf
QITEMSI06                      1048576000
/oracle/rdata04/ebay/oradata/qitemsi06a.dbf
QITEMSI06                       524288000

host qiomkfile -h -s 1000M /oracle/rdata01/ebay/oradata/qitemsi06.dbf
host qiomkfile -h -s 500M /oracle/rdata01/ebay/oradata/qitemsi06a.dbf
alter tablespace QITEMSI06 offline;
OR
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi06.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qitemsi06a.dbf' offline;
host dd if=/oracle/rdata08/ebay/oradata/qitemsi06.dbf of=/oracle/rdata01/ebay/oradata/qitemsi06.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qitemsi06a.dbf of=/oracle/rdata01/ebay/oradata/qitemsi06a.dbf bs=102400
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi06.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi06.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qitemsi06a.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi06a.dbf';
alter tablespace qitemsi06 online;
OR
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi06.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi06a.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi06.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi06a.dbf' online;

==== Done

/oracle/rdata08/ebay/oradata/qitemsi05.dbf
QITEMSI05                      1048576000
/oracle/rdata08/ebay/oradata/qitemsi05-01.dbf
QITEMSI05                       524288000
/oracle/rdata08/ebay/oradata/qitemsi05-02.dbf 2000M
QITEMSI05                      2097152000
/oracle/rdata04/ebay/oradata/qitemsi05-03.dbf
QITEMSI05                      1073741824 1024M

host qiomkfile -h -s 1000M /oracle/rdata01/ebay/oradata/qitemsi05.dbf
host qiomkfile -h -s 500M /oracle/rdata01/ebay/oradata/qitemsi05-01.dbf
host qiomkfile -h -s 2000M /oracle/rdata01/ebay/oradata/qitemsi05-02.dbf
host qiomkfile -h -s 1024M /oracle/rdata01/ebay/oradata/qitemsi05-03.dbf
alter tablespace QITEMSI05 offline;
OR
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi05.dbf' offline;
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi05-01.dbf' offline;
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi05-02.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qitemsi05-03.dbf' offline;
host dd if=/oracle/rdata08/ebay/oradata/qitemsi05.dbf of=/oracle/rdata01/ebay/oradata/qitemsi05.dbf bs=102400
host dd if=/oracle/rdata08/ebay/oradata/qitemsi05-01.dbf of=/oracle/rdata01/ebay/oradata/qitemsi05-01.dbf bs=102400
host dd if=/oracle/rdata08/ebay/oradata/qitemsi05-02.dbf of=/oracle/rdata01/ebay/oradata/qitemsi05-02.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qitemsi05-03.dbf of=/oracle/rdata01/ebay/oradata/qitemsi05-03.dbf bs=102400
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi05.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi05.dbf';
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi05-01.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi05-01.dbf';
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi05-02.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi05-02.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qitemsi05-03.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi05-03.dbf';
alter tablespace qitemsi05 online;
OR
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi05.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi05-01.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi05-02.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi05-03.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi05.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi05-01.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi05-02.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi05-03.dbf' online;

==== Jon starts here !!!

/oracle/rdata08/ebay/oradata/qitemsi04.dbf
QITEMSI04                      1048576000
/oracle/rdata04/ebay/oradata/qitemsi04a.dbf
QITEMSI04                       524288000

host qiomkfile -h -s 1000M /oracle/rdata01/ebay/oradata/qitemsi04.dbf
host qiomkfile -h -s 500M /oracle/rdata01/ebay/oradata/qitemsi04a.dbf
alter tablespace QITEMSI04 offline;
OR
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi04.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qitemsi04a.dbf' offline;
host dd if=/oracle/rdata08/ebay/oradata/qitemsi04.dbf of=/oracle/rdata01/ebay/oradata/qitemsi04.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qitemsi04a.dbf of=/oracle/rdata01/ebay/oradata/qitemsi04a.dbf bs=102400
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi04.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi04.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qitemsi04a.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi04a.dbf';
alter tablespace qitemsi04 online;
OR
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi04.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi04a.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi04.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi04a.dbf' online;

====

/oracle/rdata08/ebay/oradata/qitemsi03.dbf
QITEMSI03                      1048576000
/oracle/rdata04/ebay/oradata/qitemsi03a.dbf
QITEMSI03                       524288000

host qiomkfile -h -s 1000M /oracle/rdata01/ebay/oradata/qitemsi03.dbf
host qiomkfile -h -s 500M /oracle/rdata01/ebay/oradata/qitemsi03a.dbf
alter tablespace QITEMSI03 offline;
OR
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi03.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qitemsi03a.dbf' offline;
host dd if=/oracle/rdata08/ebay/oradata/qitemsi03.dbf of=/oracle/rdata01/ebay/oradata/qitemsi03.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qitemsi03a.dbf of=/oracle/rdata01/ebay/oradata/qitemsi03a.dbf bs=102400
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi03.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi03.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qitemsi03a.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi03a.dbf';
alter tablespace qitemsi03 online;
OR
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi03.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi03a.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi03.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi03a.dbf' online;

====

/oracle/rdata08/ebay/oradata/qitemsi02.dbf
QITEMSI02                      1048576000
/oracle/rdata04/ebay/oradata/qitemsi02a.dbf
QITEMSI02                       524288000

host qiomkfile -h -s 1000M /oracle/rdata01/ebay/oradata/qitemsi02.dbf
host qiomkfile -h -s 500M /oracle/rdata01/ebay/oradata/qitemsi02a.dbf
alter tablespace QITEMSI02 offline;
OR
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi02.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qitemsi02a.dbf' offline;
host dd if=/oracle/rdata08/ebay/oradata/qitemsi02.dbf of=/oracle/rdata01/ebay/oradata/qitemsi02.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qitemsi02a.dbf of=/oracle/rdata01/ebay/oradata/qitemsi02a.dbf bs=102400
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi02.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi02.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qitemsi02a.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi02a.dbf';
alter tablespace qitemsi02 online;
OR
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi02.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi02a.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi02.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi02a.dbf' online;

====

/oracle/rdata08/ebay/oradata/qitemsi01.dbf
QITEMSI                        2097152000
/oracle/rdata04/ebay/oradata/qitemsi01a.dbf
QITEMSI                         524288000

host qiomkfile -h -s 2000M /oracle/rdata01/ebay/oradata/qitemsi01.dbf
host qiomkfile -h -s 500M /oracle/rdata01/ebay/oradata/qitemsi01a.dbf
alter tablespace QITEMSI offline;
OR
alter database datafile '/oracle/rdata08/ebay/oradata/qitemsi01.dbf' offline;
alter database datafile '/oracle/rdata04/ebay/oradata/qitemsi01a.dbf' offline;
host dd if=/oracle/rdata08/ebay/oradata/qitemsi01.dbf of=/oracle/rdata01/ebay/oradata/qitemsi01.dbf bs=102400
host dd if=/oracle/rdata04/ebay/oradata/qitemsi01a.dbf of=/oracle/rdata01/ebay/oradata/qitemsi01a.dbf bs=102400
alter database rename file '/oracle/rdata08/ebay/oradata/qitemsi01.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi01.dbf';
alter database rename file '/oracle/rdata04/ebay/oradata/qitemsi01a.dbf' to 
'/oracle/rdata01/ebay/oradata/qitemsi01a.dbf';
alter tablespace qitemsi online;
OR
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi01.dbf';
recover datafile '/oracle/rdata01/ebay/oradata/qitemsi01a.dbf';
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi01.dbf' online;
alter database datafile '/oracle/rdata01/ebay/oradata/qitemsi01a.dbf' online;



====

test:

create tablespace play
	datafile '/oracle/rdata01/ebay/oradata/play.dbf'
	size 1M autoextend off;

create table test  (id number(38))
tablespace play
storage(initial 10K next 1K);

insert into test values (1);
insert into test values (2);
commit;
select * from test;

host qiomkfile -h -s 1M /oracle/rdata01/ebay/oradata/play_q.dbf
alter tablespace play offline;
host dd if=/oracle/rdata01/ebay/oradata/play.dbf of=/oracle/rdata01/ebay/oradata/play_q.dbf bs=102400
alter database rename file '/oracle/rdata01/ebay/oradata/play.dbf' to
'/oracle/rdata01/ebay/oradata/play_q.dbf';
alter tablespace play online;

select * from test;

--- clean up
drop table test;
drop tablespace play including contents;
cd /oracle/rdata01/ebay/oradata
rm play.dbf
rm .play_q.dbf
rm play_q.dbf

====