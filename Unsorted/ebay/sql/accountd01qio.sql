/*	$Id: accountd01qio.sql,v 1.3 1999/02/21 02:52:08 josh Exp $	*/
host vxmkcdev -o oracle_file -s 800m /oracle/rdata07/ebay/oradata/accountd01_q.dbf
host vxmkcdev -o oracle_file -s 820m /oracle/rdata07/ebay/oradata/accountd01d_q.dbf
host vxmkcdev -o oracle_file -s 1081m /oracle/rdata07/ebay/oradata/accountd01a_q.dbf
host vxmkcdev -o oracle_file -s 200m /oracle/rdata07/ebay/oradata/accountd01b_q.dbf
host vxmkcdev -o oracle_file -s 820m /oracle/rdata07/ebay/oradata/accountd01c_q.dbf
host vxmkcdev -o oracle_file -s 820m /oracle/rdata07/ebay/oradata/accountd01e_q.dbf
host vxmkcdev -o oracle_file -s 2000m /oracle/rdata05/ebay/oradata/accountd01f_q.dbf

alter database datafile '/oracle/rdata07/ebay/oradata/accountd01.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01d.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01a.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01b.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01c.dbf' offline;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01e.dbf' offline;
alter database datafile '/oracle/rdata05/ebay/oradata/accountd01f.dbf' offline;

host dd if=/oracle/rdata07/ebay/oradata/accountd01.dbf of=/oracle/rdata07/ebay/oradata/accountd01_q.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01d.dbf of=/oracle/rdata07/ebay/oradata/accountd01d_q.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01a.dbf of=/oracle/rdata07/ebay/oradata/accountd01a_q.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01b.dbf of=/oracle/rdata07/ebay/oradata/accountd01b_q.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01c.dbf of=/oracle/rdata07/ebay/oradata/accountd01c_q.dbf bs=102400
host dd if=/oracle/rdata07/ebay/oradata/accountd01e.dbf of=/oracle/rdata07/ebay/oradata/accountd01e_q.dbf bs=102400
host dd if=/oracle/rdata05/ebay/oradata/accountd01f.dbf of=/oracle/rdata05/ebay/oradata/accountd01f_q.dbf bs=102400

alter database rename file '/oracle/rdata07/ebay/oradata/accountd01.dbf' to '/oracle/rdata07/ebay/oradata/accountd01_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01d.dbf' to '/oracle/rdata07/ebay/oradata/accountd01d_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01a.dbf' to '/oracle/rdata07/ebay/oradata/accountd01a_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01b.dbf' to '/oracle/rdata07/ebay/oradata/accountd01b_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01c.dbf' to '/oracle/rdata07/ebay/oradata/accountd01c_q.dbf';
alter database rename file '/oracle/rdata07/ebay/oradata/accountd01e.dbf' to '/oracle/rdata07/ebay/oradata/accountd01e_q.dbf';
alter database rename file '/oracle/rdata05/ebay/oradata/accountd01f.dbf' to '/oracle/rdata05/ebay/oradata/accountd01f_q.dbf';

recover datafile '/oracle/rdata07/ebay/oradata/accountd01_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01d_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01a_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01b_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01c_q.dbf';
recover datafile '/oracle/rdata07/ebay/oradata/accountd01e_q.dbf';
recover datafile '/oracle/rdata05/ebay/oradata/accountd01f_q.dbf';

alter database datafile '/oracle/rdata07/ebay/oradata/accountd01.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01d.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01a.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01b.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01c.dbf' online;
alter database datafile '/oracle/rdata07/ebay/oradata/accountd01e.dbf' online;
alter database datafile '/oracle/rdata05/ebay/oradata/accountd01f.dbf' online;

