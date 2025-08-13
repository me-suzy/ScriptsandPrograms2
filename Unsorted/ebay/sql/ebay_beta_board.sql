/*	$Id: ebay_beta_board.sql,v 1.2 1999/02/21 02:53:20 josh Exp $	*/
/*
 * ebay_bb_board.sql
 *
 * Bulletin Board (cafe)
*/

create table ebay_beta_board
(	id				int
		constraint beta_board_id_nn
		not null,
	when			date
		constraint beta_board_date_nn
		not null,
	entry_len	int
		constraint beta_board_entry_len_nn
		not null,
	entry			long raw
		constraint beta_board_entry_nn
		not null,
	constraint		beta_board_fk
		foreign key (id)
		references	ebay_users(id)
)
tablespace bbd01
storage
(	initial 1M
	next 1M
);
