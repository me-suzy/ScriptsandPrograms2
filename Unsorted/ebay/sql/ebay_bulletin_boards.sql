/*	$Id: ebay_bulletin_boards.sql,v 1.3 1999/02/21 02:53:25 josh Exp $	*/
/*
 * ebay_bulletin_boards.sql
 *
*/

drop table ebay_bulletin_boards;

create table ebay_bulletin_boards
(	board_id		int
		constraint bboard_board_id_nn
		not null,
	when			date
		constraint bboard_when_nn
		not null,
	user_id			int
		constraint bboard_user_id_nn
		not null,
	host			varchar(255)
		constraint bboard_host_nn
		not null,
	entry_code		number(1),
	entry_len		int
		constraint bboard_entry_len_nn
		not null,
	entry			long raw
		constraint bboard_entry_nn
		not null,
	constraint		bboard_board_fk
		foreign key (user_id)
		references	ebay_users(id)
)
tablespace qboardd01
storage ( initial 30M next 20M);


 create index ebay_bulletin_board_id_index
   on ebay_bulletin_boards(board_id)
   tablespace qboardi01
   storage(initial 20M next 10M);
