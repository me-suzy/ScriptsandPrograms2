/*	$Id: itemd02qio.sql,v 1.3 1999/02/21 02:54:40 josh Exp $	*/
host vxmkcdev -o oracle_file -s 101m /oracle/rdata05/ebay/oradata/ritemd02a_q.dbf
host vxmkcdev -o oracle_file -s 2001m /oracle/rdata05/ebay/oradata/ritemd02_q.dbf
host vxmkcdev -o oracle_file -s 100m /oracle/rdata05/ebay/oradata/ritemd02b_q.dbf
host vxmkcdev -o oracle_file -s 300m /oracle/rdata05/ebay/oradata/ritemd02c_q.dbf
host vxmkcdev -o oracle_file -s 210m /oracle/rdata05/ebay/oradata/ritemd02d_q.dbf
host vxmkcdev -o oracle_file -s 301m /oracle/rdata05/ebay/oradata/ritemd02e_q.dbf
host vxmkcdev -o oracle_file -s 500m /oracle/rdata05/ebay/oradata/ritemd02f_q.dbf
host vxmkcdev -o oracle_file -s 2000m /oracle/rdata05/ebay/oradata/ritemd02g_q.dbf
host vxmkcdev -o oracle_file -s 510m /oracle/rdata05/ebay/oradata/ritemd02h_q.dbf
host vxmkcdev -o oracle_file -s 500m /oracle/rdata05/ebay/oradata/ritemd02i_q.dbf
host vxmkcdev -o oracle_file -s 500m /oracle/rdata05/ebay/oradata/ritemd02j_q.dbf

alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02a.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02b.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02c.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02d.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02e.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritem02f.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritem02g.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02h.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02i.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02j.dbf' offline;

host dd if=/oracle/rdata05/ebay/oradata/ritemd02a.dbf of=/oracle/rdata05/ebay/oradata/ritemd02a_q.dbf
host dd if=/oracle/rdata05/ebay/oradata/ritemd02.dbf of=/oracle/rdata05/ebay/oradata/ritemd02_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritemd02b.dbf of=/oracle/rdata05/ebay/oradata/ritemd02b_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritemd02c.dbf of=/oracle/rdata05/ebay/oradata/ritemd02c_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritemd02d.dbf of=/oracle/rdata05/ebay/oradata/ritemd02d_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritemd02e.dbf of=/oracle/rdata05/ebay/oradata/ritemd02e_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritem02f.dbf of=/oracle/rdata05/ebay/oradata/ritemd02f_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritem02g.dbf of=/oracle/rdata05/ebay/oradata/ritemd02g_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritemd02h.dbf of=/oracle/rdata05/ebay/oradata/ritemd02h_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritemd02i.dbf of=/oracle/rdata05/ebay/oradata/ritemd02i_q.dbf bs=102400 
host dd if=/oracle/rdata05/ebay/oradata/ritemd02j.dbf of=/oracle/rdata05/ebay/oradata/ritemd02j_q.dbf bs=102400 

alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02a.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02a_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02b.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02b_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02c.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02c_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02d.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02d_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02e.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02e_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritem02f.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02f_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritem02g.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02g_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02h.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02h_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02i.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02i_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/ritemd02j.dbf' to '/oracle/rdata05/ebay/oradata/ritemd02j_q.dbf';

recover datafile '/oracle/rdata05/ebay/oradata/ritemd02a_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02b_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02c_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02d_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02e_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02f_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02g_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02h_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02i_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/ritemd02j_q.dbf';

alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02a_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02b_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02c_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02d_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02e_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02f_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02g_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02h_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02i_q.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/ritemd02j_q.dbf' online;

