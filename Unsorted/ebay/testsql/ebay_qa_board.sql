/*	$Id: ebay_qa_board.sql,v 1.2 1999/02/21 02:56:40 josh Exp $	*/
/*
 * ebay_qa_board.sql
 *
 * Q&A Bulletin Board
*/

create table ebay_qa_board
(	id				int
		constraint qa_board_id_nn
		not null,
	when			date
		constraint qa_board_date_nn
		not null,
	entry_len	int
		constraint qa_board_entry_len_nn
		not null,
	entry			long raw
		constraint qa_board_entry_nn
		not null,
	constraint		qa_board_fk
		foreign key (id)
		references	ebay_users(id)
)
tablespace bbd01
storage
(	initial 1K
	next 1K
);

