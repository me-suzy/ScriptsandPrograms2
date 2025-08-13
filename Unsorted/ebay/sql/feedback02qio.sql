/*	$Id: feedback02qio.sql,v 1.3 1999/02/21 02:54:24 josh Exp $	*/
host vxmkcdev -o oracle_file -s 665m /oracle/rdata03/ebay/oradata/feedbackd02_q.dbf
host vxmkcdev -o oracle_file -s 235m /oracle/rdata03/ebay/oradata/feedbackd02a_q.dbf
host vxmkcdev -o oracle_file -s 500m /oracle/rdata03/ebay/oradata/feedbackd02c_q.dbf
host vxmkcdev -o oracle_file -s 470m /oracle/rdata03/ebay/oradata/feedbackd02b_q.dbf
host vxmkcdev -o oracle_file -s 500m /oracle/rdata03/ebay/oradata/feedbackd02d.dbf

alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02.dbf' offline;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02a.dbf' offline;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02c.dbf' offline;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02b.dbf' offline;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02d.dbf' offline;

host dd if=/oracle/rdata03/ebay/oradata/feedbackd02.dbf of=/oracle/rdata03/ebay/oradata/feedbackd02_q.dbf bs=102400                                                                                                                                                                                                                                                                                                                                                                                                                                          
host dd if=/oracle/rdata03/ebay/oradata/feedbackd02a.dbf of=/oracle/rdata03/ebay/oradata/feedbackd02a_q.dbf bs=102400                                                                                                                                                                                                                                                                                                                                                                                                                                        
host dd if=/oracle/rdata03/ebay/oradata/feedbackd02c.dbf of=/oracle/rdata03/ebay/oradata/feedbackd02c_q.dbf bs=102400                                                                                                                                                                                                                                                                                                                                                                                                                                        
host dd if=/oracle/rdata03/ebay/oradata/feedbackd02b.dbf of=/oracle/rdata03/ebay/oradata/feedbackd02b_q.dbf bs=102400                                                                                                                                                                                                                                                                                                                                                                                                                                        
host dd if=/oracle/rdata03/ebay/oradata/feedbackd02d.dbf of=/oracle/rdata03/ebay/oradata/feedbackd02d_q.dbf bs=102400                                                                                                                                                                                                                                                                                                                                                                                                                                        

alter database rename file '/oracle/rdata03/ebay/oradata/feedbackd02.dbf' to '/oracle/rdata03/ebay/oradata/feedbackd02_q.dbf';
alter database rename file '/oracle/rdata03/ebay/oradata/feedbackd02a.dbf' to '/oracle/rdata03/ebay/oradata/feedbackd02a_q.dbf';
alter database rename file '/oracle/rdata03/ebay/oradata/feedbackd02c.dbf' to '/oracle/rdata03/ebay/oradata/feedbackd02c_q.dbf';
alter database rename file '/oracle/rdata03/ebay/oradata/feedbackd02b.dbf' to '/oracle/rdata03/ebay/oradata/feedbackd02b_q.dbf';
alter database rename file '/oracle/rdata03/ebay/oradata/feedbackd02d.dbf' to '/oracle/rdata03/ebay/oradata/feedbackd02d_q.dbf';

recover datafile '/oracle/rdata03/ebay/oradata/feedbackd02_q.dbf';
recover datafile '/oracle/rdata03/ebay/oradata/feedbackd02a_q.dbf';
recover datafile '/oracle/rdata03/ebay/oradata/feedbackd02c_q.dbf';
recover datafile '/oracle/rdata03/ebay/oradata/feedbackd02b_q.dbf';
recover datafile '/oracle/rdata03/ebay/oradata/feedbackd02d_q.dbf';

alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02_q.dbf' online;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02a_q.dbf' online;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02c_q.dbf' online;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02b_q.dbf' online;
alter database datafile '/oracle/rdata03/ebay/oradata/feedbackd02d_q.dbf' online;

