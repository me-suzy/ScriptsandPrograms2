/*	$Id: ebay_feedback.sql,v 1.3 1999/02/21 02:53:36 josh Exp $	*/
/*
 * ebay_feedback.sql
 *
 * ebay_feedback contains summary information about
 * a user's feedback. 
 *
 */

 drop table ebay_feedback;
/* obsolete - new definition below 
 create table ebay_feedback
 (
	id				int
		constraint	feedback_id_nn
		not null,
	created		date
		constraint	feedback_date_nn
		not null,
	last_update	date
		constraint	feedback_last_update_nn
		not null,
	score			int
		constraint	feedback_score_nn
		not null,
	flags			int
		constraint	feedback_flags_nn
		not null,
	constraint		feedback_pk
			primary key (id)
			using index	storage(initial 5m next 1m)
						tablespace feedbacki01,
	constraint		feedback_fk
			foreign key (id)
			references	ebay_users(id)
 )
 tablespace feedbackd01
 storage (initial 10M next 2m);

*/

 create table ebay_feedback
 (
	id				int
		constraint	feedback_id_nn
		not null,
	created		date
		constraint	feedback_date_nn
		not null,
	last_update	date
		constraint	feedback_last_update_nn
		not null,
	score			int
		constraint	feedback_score_nn
		not null,
	flags			int
		constraint	feedback_flags_nn
		not null
 )
 tablespace rfeedbackd01
 storage (initial 15M next 5M);

 alter table ebay_feedback
   add constraint		feedback_pk
			primary key (id)
			using index	tablespace rfeedbacki01
			storage(initial 10M next 5m) unrecoverable;

alter table ebay_feedback
	add	constraint		feedback_fk
			foreign key (id)
			references	ebay_users(id);
commit;

/*
			mPositiveComments					= 0;
			mPositiveCommentsThatCount			= 0;
			mNegativeComments					= 0;
			mNegativeCommentsThatCount			= 0;
			mNeutralComments					= 0;
			mNeutralCommentsFromSuspendedUsers	= 0;
			mInterval1Boundry					= 0;
			mInterval2Boundry					= 0;
			mInterval3Boundry					= 0;
			mCommentsInInterval1				= 0;
			mCommentsInInterval2				= 0;
			mCommentsInInterval3				= 0;
			mPositiveCommentsInInterval1		= 0;
			mPositiveCommentsInInterval2		= 0;
			mPositiveCommentsInInterval3		= 0;
			mNegativeCommentsInInterval1		= 0;
			mNegativeCommentsInInterval2		= 0;
			mNegativeCommentsInInterval3		= 0;
			mNeutralCommentsInInterval1			= 0;
			mNeutralCommentsInInterval2			= 0;
			mNeutralCommentsInInterval3			= 0;

			valid_ext char(1);
			Ext_Calc_date date;
*/

alter table ebay_feedback
add (valid_ext char(1),
	ext_calc_date date,
	pos_comment int,
	pos_count int,
	neg_comment	int,
	neg_count	int,
	neut_comment int,
	neut_from_suspended int,
	interval1	int,
	interval2	int,
	interval3	int,
	comments_in_int1 int,
	comments_in_int2	int,
	comments_in_int3	int,
	pos_comment_in_int1	int,
	pos_comment_in_int2 int,
	pos_comment_in_int3 int,
	neg_comment_in_int1 int,
	neg_comment_in_int2 int,
	neg_comment_in_int3 int,
	neut_comment_in_int1 int,
	neut_comment_in_int2 int,
	neut_comment_in_int3 int
);


alter table ebay_feedback
	add split			char
		default chr('0');
commit;
