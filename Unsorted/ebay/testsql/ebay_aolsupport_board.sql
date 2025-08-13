/*	$Id: ebay_aolsupport_board.sql,v 1.2 1999/02/21 02:56:00 josh Exp $	*/
create table ebay_aolsupport_board
(	id				int
		constraint aolsup_board_id_nn
		not null,
	when			date
		constraint aolsup_board_date_nn
		not null,
	entry_len	int
		constraint aolsup_board_entry_len_nn
		not null,
	entry			long raw
		constraint aolsup_board_entry_nn
		not null,
	constraint		aolsup_board_fk
		foreign key (id)
		references	ebay_users(id)
)
tablespace tbbd01
storage ( initial 1K next 1K);
