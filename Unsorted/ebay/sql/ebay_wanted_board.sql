/*	$Id: ebay_wanted_board.sql,v 1.2 1999/02/21 02:54:19 josh Exp $	*/
/*
 * ebay_wanted_board.sql
 *
 * Wanted Bulletin Board
*/

create table ebay_wanted_board
(	id				int
		constraint wanted_board_id_nn
		not null,
	when			date
		constraint wanted_board_date_nn
		not null,
	entry_len	int
		constraint wanted_board_entry_len_nn
		not null,
	entry			long raw
		constraint wanted_board_entry_nn
		not null,
	constraint		wanted_board_fk
		foreign key (id)
		references	ebay_users(id)
)
tablespace bbd01
storage (initial 1M next 1M);

