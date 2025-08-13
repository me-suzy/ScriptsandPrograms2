/*	$Id: ebay_feedback_detail.sql,v 1.3 1999/02/21 02:56:18 josh Exp $	*/
/*
 * ebay_feedback_detail.sql
 *
 * ebay_feedback_detail contains detail information
 * about user's feedback.
 */

/*  drop table ebay_feedback_detail;
 */


 create table ebay_feedback_detail
 (
	id						int
		constraint	feedback_detail_id_nn
		not null,
	time					date
		constraint	feedback_detail_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail_comment_nn
		not null,
	response			varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0,
	constraint		feedback_detail_fk1
			foreign key (id)
			references	ebay_users(id),
	constraint		feedback_detail_fk2
			foreign key (commenting_id)
			references	ebay_users(id)
 )
 tablespace tfeedbackd01
 storage (initial 1m next 50k);


 create index ebay_feedback_id_index
	on ebay_feedback_detail
	(id)
 storage(initial 500 next 250)
 tablespace tfeedbacki01;

 create index ebay_feedback_comment_index
	on ebay_feedback_detail
	(commenting_id)
 storage(initial 500 next 250)
 tablespace tfeedbacki01;

alter table ebay_feedback_detail
	add response			varchar2(255)
		default null;
commit;

alter table ebay_feedback_detail
	add followup			varchar2(255)
		default null;
commit;

alter table ebay_feedback_detail
	add	item				int
		default 0;
commit;

/*
 * ebay_feedback_detail0x.
 *
 * ebay_feedback_detail0x contains detail information
 * for spliting ebay_feedback_detail to 10 tables.
 */

 /* ebay_feedback_detail00 */

 create table ebay_feedback_detail00
 (
	id						int
		constraint	feedback_detail00_id_nn
		not null,
	time					date
		constraint	feedback_detail00_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail00_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail00_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail00_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail00_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail00_comment_nn
		not null,
	response		varchar2(255)
		default null,
	followup		varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index00
	on ebay_feedback_detail00
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index00
	on ebay_feedback_detail00
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail00
	add constraint		feedback_detail00_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail00
	add  constraint		feedback_detail00_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;

/* ebay_feedback_detail01 */

 create table ebay_feedback_detail01
 (
	id						int
		constraint	feedback_detail01_id_nn
		not null,
	time					date
		constraint	feedback_detail01_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail01_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail01_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail01_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail01_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail01_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index01
	on ebay_feedback_detail01
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index01
	on ebay_feedback_detail01
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail01
	add constraint		feedback_detail01_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail01
	add  constraint		feedback_detail01_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail02 */

 create table ebay_feedback_detail02
 (
	id						int
		constraint	feedback_detail02_id_nn
		not null,
	time					date
		constraint	feedback_detail02_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail02_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail02_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail02_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail02_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail02_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index02
	on ebay_feedback_detail02
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index02
	on ebay_feedback_detail02
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail02
	add constraint		feedback_detail02_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail02
	add  constraint		feedback_detail02_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail03 */

 create table ebay_feedback_detail03
 (
	id						int
		constraint	feedback_detail03_id_nn
		not null,
	time					date
		constraint	feedback_detail03_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail03_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail03_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail03_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail03_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail03_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index03
	on ebay_feedback_detail03
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index03
	on ebay_feedback_detail03
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail03
	add constraint		feedback_detail03_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail03
	add  constraint		feedback_detail03_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail04 */

 create table ebay_feedback_detail04
 (
	id						int
		constraint	feedback_detail04_id_nn
		not null,
	time					date
		constraint	feedback_detail04_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail04_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail04_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail04_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail04_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail04_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index04
	on ebay_feedback_detail04
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index04
	on ebay_feedback_detail04
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail04
	add constraint		feedback_detail04_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail04
	add  constraint		feedback_detail04_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail05 */

 create table ebay_feedback_detail05
 (
	id						int
		constraint	feedback_detail05_id_nn
		not null,
	time					date
		constraint	feedback_detail05_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail05_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail05_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail05_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail05_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail05_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index05
	on ebay_feedback_detail05
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index05
	on ebay_feedback_detail05
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail05
	add constraint		feedback_detail05_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail05
	add  constraint		feedback_detail05_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail06 */

 create table ebay_feedback_detail06
 (
	id						int
		constraint	feedback_detail06_id_nn
		not null,
	time					date
		constraint	feedback_detail06_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail06_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail06_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail06_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail06_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail06_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index06
	on ebay_feedback_detail06
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index06
	on ebay_feedback_detail06
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail06
	add constraint		feedback_detail06_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail06
	add  constraint		feedback_detail06_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail07 */

 create table ebay_feedback_detail07
 (
	id						int
		constraint	feedback_detail07_id_nn
		not null,
	time					date
		constraint	feedback_detail07_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail07_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail07_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail07_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail07_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail07_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index07
	on ebay_feedback_detail07
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index07
	on ebay_feedback_detail07
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail07
	add constraint		feedback_detail07_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail07
	add  constraint		feedback_detail07_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail08 */

 create table ebay_feedback_detail08
 (
	id						int
		constraint	feedback_detail08_id_nn
		not null,
	time					date
		constraint	feedback_detail08_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail08_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail08_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail08_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail08_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail08_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index08
	on ebay_feedback_detail08
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index08
	on ebay_feedback_detail08
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail08
	add constraint		feedback_detail08_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail08
	add  constraint		feedback_detail08_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


/* ebay_feedback_detail09 */

 create table ebay_feedback_detail09
 (
	id						int
		constraint	feedback_detail09_id_nn
		not null,
	time					date
		constraint	feedback_detail09_time_nn
		not null,
	commenting_id		int
		constraint	feedback_detail09_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	feedback_detail09_host_nn
		not null,
	comment_type		int
		constraint	feedback_detail09_type_nn
		not null,
	comment_score		int
		constraint	feedback_detail09_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	feedback_detail09_comment_nn
		not null,
	response				varchar2(255)
		default null,
	followup			varchar2(255)
		default null,
	item				int
		default 0
 )
 tablespace summaryd02
 storage (initial 1m next 50k);
						
 create index ebay_feedback_id_index09
	on ebay_feedback_detail09
	(id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable;
commit;

 create index ebay_feedback_comment_index09
	on ebay_feedback_detail09
	(commenting_id)
 storage(initial 500 next 250)
 tablespace summaryd02 unrecoverable parallel (degree 3);
commit;

alter table ebay_feedback_detail09
	add constraint		feedback_detail09_fk1
			foreign key (id)
			references	ebay_users(id);
commit;

alter table ebay_feedback_detail09
	add  constraint		feedback_detail09_fk2
			foreign key (commenting_id)
			references	ebay_users(id);
commit;


create view ebay_feedback_detail_view as
	select * from ebay_feedback_detail00
UNION
	select * from ebay_feedback_detail01
UNION
	select * from ebay_feedback_detail02
UNION
	select * from ebay_feedback_detail03
UNION
	select * from ebay_feedback_detail04
UNION
	select * from ebay_feedback_detail05
UNION
	select * from ebay_feedback_detail06
UNION
	select * from ebay_feedback_detail07
UNION
	select * from ebay_feedback_detail08
UNION
	select * from ebay_feedback_detail09;

