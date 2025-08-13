/*	$Id: rollback02.sql,v 1.6 1999/02/21 02:54:59 josh Exp $	*/
/*
** RBS2.sql
**
** Create tablespace for rollback segments
*/
alter rollback segment r05 offline;
drop rollback segment r05;
commit;

alter rollback segment r06 offline;
drop rollback segment r06;
commit;

alter rollback segment r07 offline;
drop rollback segment r07;
commit;

alter rollback segment r08 offline;
drop rollback segment r08;
commit;

alter tablespace RBS2 offline;

drop tablespace RBS2 including contents;

/* delete unix file */

create tablespace RBS2
	datafile '/oracle07/ebay/oradata/rbsebay2.dbf'
	size 200M
	autoextend off;
commit;

CREATE ROLLBACK SEGMENT r05
TABLESPACE rbs2
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;

CREATE ROLLBACK SEGMENT r06
TABLESPACE rbs2
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;

CREATE ROLLBACK SEGMENT r07
TABLESPACE rbs2
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;

/* mirrored redo logs */
/* first create 3 temporary redo log groups 4, 5, and 6 and cycle through them 
to ensure they're useable; Then alter database drop logfile group 1, 2 and 3
before doing the change below.
Cycle through the 3 logfile groups to ensure they're useable before dropping
groups 4, 5 and 6. 
 */


alter database add logfile group 1 
('/oracle02/ebay/oradata/log1ebay.dbf',
 '/oracle03/ebay/oradata/log2ebay.dbf') size 25M;

alter database add logfile group 2
('/oracle05/ebay/oradata/log3ebay.dbf',
 '/oracle03/ebay/oradata/log4ebay.dbf') size 25M;

alter database add logfile group 3
('/oracle03/ebay/oradata/log5ebay.dbf',
 '/oracle07/ebay/oradata/log6ebay.dbf') size 25M;

alter database add logfile GROUP 6  (
    '/oracle09/ebay/log11ebay.dbf',
    '/oracle/rredo01/ebay/log12ebay.dbf'
  ) SIZE 50M;
alter database drop logfile group 6;

alter database drop logfile group 1;

alter database add logfile group 1 
('/oracle09/ebay/log1ebay.dbf',
 '/oracle/rredo01/ebay/log2ebay.dbf') size 50M;

alter database drop logfile group 2;

alter database add logfile group 2
('/oracle10/ebay/log3ebay.dbf',
 '/oracle/rredo01/ebay/log4ebay.dbf') size 50M;

alter database drop logfile group 3;

alter database add logfile group 3
('/oracle09/ebay/log5ebay.dbf',
 '/oracle/rredo01/ebay/log6ebay.dbf') size 50M;

alter database drop logfile group 4;

alter database add logfile group 4
('/oracle10/ebay/log7ebay.dbf',
 '/oracle/rredo01/ebay/log8ebay.dbf') size 50M;

alter database drop logfile group 5;

alter database add logfile group 5
('/oracle09/ebay/log9ebay.dbf',
 '/oracle/rredo01/ebay/log10ebay.dbf') size 50M;

/* 8-6-98 */

alter database add logfile GROUP 6  (
    '/oracle/rredo01/ebay/log11ebay.dbf',
    '/oracle/rredo01/ebay/log12ebay.dbf'
  ) SIZE 100M;
 alter database drop logfile group 6;

alter database add logfile GROUP 7  (
    '/oracle/rredo01/ebay/log13ebay.dbf',
    '/oracle/rredo01/ebay/log14ebay.dbf'
  ) SIZE 50M;

  alter database drop logfile group 7;

alter database add logfile GROUP 8  (
    '/oracle/rredo01/ebay/log15ebay.dbf',
    '/oracle/rredo01/ebay/log16ebay.dbf'
  ) SIZE 50M;

alter database drop logfile group 1;

alter database add logfile group 1 
('/oracle/rredo01/ebay/log01ebay.dbf',
 '/oracle/rredo01/ebay/log02ebay.dbf') size 100M;

alter database drop logfile group 2;

alter database add logfile group 2
('/oracle/rredo01/ebay/log03ebay.dbf',
 '/oracle/rredo01/ebay/log04ebay.dbf') size 100M;

/* did not drop this yet */
alter database drop logfile group 3;

alter database add logfile group 3
('/oracle/rredo01/ebay/log05ebay.dbf',
 '/oracle/rredo01/ebay/log06ebay.dbf') size 100M;

alter database drop logfile group 4;

alter database add logfile group 4
('/oracle/rredo01/ebay/log07ebay.dbf',
 '/oracle/rredo01/ebay/log08ebay.dbf') size 100M;

alter database drop logfile group 5;

alter database add logfile group 5
('/oracle/rredo01/ebay/log09ebay.dbf',
 '/oracle/rredo01/ebay/log10ebay.dbf') size 100M;

alter database add logfile group 8
('/oracle/rredo01/ebay/log15ebay.dbf',
 '/oracle/rredo01/ebay/log16ebay.dbf') size 100M;


CREATE ROLLBACK SEGMENT r08
TABLESPACE rbs2
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;


alter rollback segment r05 online;
commit;

alter rollback segment r06 online;
commit;

alter rollback segment r07 online;
commit;

alter rollback segment r08 online;
commit;


/* the following is done separately and only once to reset the size of the
 * rollback segment rbs; also its done after a shutdown without the segments
 * in the initebay.ora so we can drop them and recreate with a bigger space */

alter rollback segment r01 offline;
drop rollback segment r01;
commit;

alter rollback segment r02 offline;
drop rollback segment r02;
commit;

alter rollback segment r03 offline;
drop rollback segment r03;
commit;

alter rollback segment r04 offline;
drop rollback segment r04;
commit;

alter tablespace RBS offline;

drop tablespace RBS including contents;

/* delete or rename unix file */

create tablespace RBS1
	datafile '/oracle01/ebay/oradata/rbsebay1.dbf'
	size 200M
	autoextend off;
commit;

CREATE ROLLBACK SEGMENT r01
TABLESPACE rbs1
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;

CREATE ROLLBACK SEGMENT r02
TABLESPACE rbs1
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;

CREATE ROLLBACK SEGMENT r03
TABLESPACE rbs1
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;

CREATE ROLLBACK SEGMENT r04
TABLESPACE rbs1
STORAGE (INITIAL 2M NEXT 2M MINEXTENTS 2 MAXEXTENTS 100 OPTIMAL 20M);
commit;


alter rollback segment r01 online;
commit;

alter rollback segment r02 online;
commit;

alter rollback segment r03 online;
commit;

alter rollback segment r04 online;
commit;

/* at this point, change initebay.ora to interleave all the rollback segs and
 * restart */

/* bigger redo log and no groups */

alter database drop logfile group 1;

alter database add logfile group 1 
('/oracle/rredo01/ebay/log1ebay.dbf') size 200M;

alter database drop logfile group 2;

alter database add logfile group 2
('/oracle/rredo01/ebay/log2ebay.dbf') size 200M;

alter database drop logfile group 3;

alter database add logfile group 3
('/oracle/rredo01/ebay/log3ebay.dbf') size 200M;

alter database drop logfile group 4;

alter database add logfile group 4
('/oracle/rredo01/ebay/log4ebay.dbf') size 200M;

alter database drop logfile group 5;

alter database add logfile group 5
('/oracle/rredo01/ebay/log5ebay.dbf') size 200M;

alter database drop logfile group 6;

alter database add logfile group 6
('/oracle/rredo01/ebay/log6ebay.dbf') size 200M;

alter database drop logfile group 7;

alter database add logfile group 7
('/oracle/rredo01/ebay/log7ebay.dbf') size 200M;

alter database drop logfile group 8;
alter database add logfile group 8
('/oracle/rredo01/ebay/log8ebay.dbf') size 200M;

 /* new rollback volume size 2000m+2k */

vxmkcdev -o oracle_file -s 2048002k /oracle/rollback01/ebay/oradata/rbs05-01.dbf
vxmkcdev -o oracle_file -s 2048002k /oracle/rollback01/ebay/oradata/rbs05-02.dbf
vxmkcdev -o oracle_file -s 2048002k /oracle/rollback01/ebay/oradata/rbs05-03.dbf
vxmkcdev -o oracle_file -s 2048002k /oracle/rollback01/ebay/oradata/rbs05-04.dbf
vxmkcdev -o oracle_file -s 2048002k /oracle/rollback01/ebay/oradata/rbs05-05.dbf
vxmkcdev -o oracle_file -s 2048002k /oracle/rollback01/ebay/oradata/rbs05-06.dbf

create tablespace RBS05 datafile
'/oracle/rollback01/ebay/oradata/rbs05-01.dbf' size 2048002k,
'/oracle/rollback01/ebay/oradata/rbs05-02.dbf' size 2048002k,
'/oracle/rollback01/ebay/oradata/rbs05-03.dbf' size 2048002k,
'/oracle/rollback01/ebay/oradata/rbs05-04.dbf' size 2048002k,
'/oracle/rollback01/ebay/oradata/rbs05-05.dbf' size 2048002k,
'/oracle/rollback01/ebay/oradata/rbs05-06.dbf' size 2048002k
default storage (initial 100m next 100m minextents 10 maxextents 10
pctincrease 0);

create rollback segment rbs10 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);
create rollback segment rbs11 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);
create rollback segment rbs12 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);
create rollback segment rbs13 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);
create rollback segment rbs14 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);
create rollback segment rbs15 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);
create rollback segment rbs16 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);
create rollback segment rbs17 tablespace rbs05 storage (initial 100m
next 100m minextents 10 maxextents 10);

/* Then update initebay.ora to set
rollback_segments=(rbs10,rbs11,rbs12,rbs13,rbs14,rbs15,rbs16,rbs17)
so on the next db bouce we pick up the new rollback segs.
*/

/* TURN ON SLOW ARC; start hot backup second part */
