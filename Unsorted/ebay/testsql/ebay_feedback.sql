/*	$Id: ebay_feedback.sql,v 1.3 1999/02/21 02:56:17 josh Exp $	*/
/*
 * ebay_feedback.sql
 *
 * ebay_feedback contains summary information about
 * a user's feedback. 
 *
 */

/*  drop table ebay_feedback;
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
		not null,
	split			char
		default chr(0),
	constraint		feedback_pk
			primary key (id)
			using index	storage(initial 5m next 1m)
						tablespace tfeedbacki01,
	constraint		feedback_fk
			foreign key (id)
			references	ebay_users(id)
 )
tablespace tfeedbackd01
	storage (initial 10m next 2m)
;

alter table ebay_feedback
	add split			char
		default chr('0');
commit;
