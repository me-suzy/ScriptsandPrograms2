/*	$Id: over_extended.sql,v 1.2 1999/02/21 02:54:46 josh Exp $	*/
rem  file:  over_extended.sql
rem  parameters:  multiplier
rem
rem  The "multiplier" value should always be greater than 1.
rem  Example:  To see segments that are within 20 per cent of their
rem      maximum extension, set the multiplier to 1.2.
rem  Example call:
rem  @over_extended 1.2

select
   owner,		   /*owner of segment*/
   segment_name,	   /*name of segment*/
   segment_type,	   /*type of segment*/
   extents,		   /*number of extents already acquired*/
   blocks		   /*number of blocks already acquired*/
from dba_segments s
where			   /*for cluster segments*/
(s.segment_type = 'CLUSTER' and exists
(select 'x' from dba_clusters c
where c.owner = s.owner
and c.cluster_name = s.segment_name
and c.max_extents <= s.extents*&&1))
or			   /*for table segments*/
(s.segment_type = 'TABLE' and exists
(select 'x' from dba_tables t
where t.owner = s.owner
and t.table_name = s.segment_name
and t.max_extents <= s.extents*&&1))
or			   /*for index segments*/
(s.segment_type = 'INDEX' and exists
(select 'x' from dba_indexes i
where i.owner = s.owner
and i.index_name = s.segment_name
and i.max_extents <= s.extents))
or			   /*for rollback segments*/
(s.segment_type = 'ROLLBACK' and exists
(select 'x' from dba_rollback_segs r
where r.owner = s.owner
and r.segment_name = s.segment_name
and r.max_extents <= s.extents*&&1))
order by 1,2

spool over_extended.lst
/
spool off
undefine 1
undefine 2
