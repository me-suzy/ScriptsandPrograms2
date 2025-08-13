/*	$Id: ebay_bb_board.sql,v 1.2 1999/02/21 02:53:19 josh Exp $	*/
/*
 * ebay_bb_board.sql
 *
 * Bulletin Board (cafe)
*/

create table ebay_bb_board
(	id				int
		constraint bb_board_id_nn
		not null,
	when			date
		constraint bb_board_date_nn
		not null,
	entry_len	int
		constraint bb_board_entry_len_nn
		not null,
	entry			long raw
		constraint bb_board_entry_nn
		not null,
	constraint		bb_board_fk
		foreign key (id)
		references	ebay_users(id)
)
tablespace bbd01
storage ( initia 1M next 1M);
