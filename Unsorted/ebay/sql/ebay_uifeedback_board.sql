/*	$Id: ebay_uifeedback_board.sql,v 1.2 1999/02/21 02:54:11 josh Exp $	*/
/*
 * ebay_uifeedback_board.sql
 *
 * Uifeedback Bulletin Board
*/

create table ebay_uifeedback_board
(	id				int
		constraint uifeedback_board_id_nn
		not null,
	when			date
		constraint uifeedback_board_date_nn
		not null,
	entry_len	int
		constraint uifeedback_board_entry_len_nn
		not null,
	entry			long raw
		constraint uifeedback_board_entry_nn
		not null,
	constraint		uifeedback_board_fk
		foreign key (id)
		references	ebay_users(id)
)
tablespace bbd01
storage (initial 1M next 1M);

