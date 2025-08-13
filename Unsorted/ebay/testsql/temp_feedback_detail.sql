/*	$Id: temp_feedback_detail.sql,v 1.2 1999/02/21 02:57:13 josh Exp $	*/
/*
 * temp_feedback_detail.sql
 *
 * temp_feedback_detail contains detail information
 * about user's feedback.
 */

/*  drop table temp_feedback_detail;
 */

 create table temp_feedback_detail
 (
	id						int
		constraint	temp_fb_det_id_nn
		not null,
	time					date
		constraint	temp_fb_det_time_nn
		not null,
	commenting_id		int
		constraint	temp_fb_det_ci_nn
		not null,
	commenting_host	varchar2(255)
		constraint	temp_fb_det_host_nn
		not null,
	comment_type		int
		constraint	temp_fb_det_type_nn
		not null,
	comment_score		int
		constraint	temp_fb_det_score_nn
		not null,
	comment_text		varchar2(255)
		constraint	temp_fb_det_comment_nn
		not null,
	constraint		temp_fb_det_fk1
			foreign key (id)
			references	ebay_users(id),
	constraint		temp_fb_det_fk2
			foreign key (commenting_id)
			references	ebay_users(id)
 )
 tablespace feedbackd01
 storage (initial 10K next 5K);



