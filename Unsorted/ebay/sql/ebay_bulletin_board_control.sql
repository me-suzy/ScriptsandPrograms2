/*	$Id: ebay_bulletin_board_control.sql,v 1.4 1999/02/21 02:53:24 josh Exp $	*/
/*
 * ebay_bulletin_board_control.sql
 *
 *	This table is the "control point" for all of the eBay
 *	Bulletin boards. 
 *
 *	Flag Usage:
 *	0x01		- HTML Enable
 *	0x02		- Posting restricted to ebay employees
 *	0x04		- Invisible (don't display in board selection menus)
 *
 *	Type Usage:
 *	0x01		- General
 *	0x02		- Customer support
 *	0x04		- News
 *	0x08		- Category Specific
 *
 */

	drop table ebay_bulletin_board_control;

	create table ebay_bulletin_board_control
	(
		board_id				number(6) 
			constraint			bb_control_id_nn
			not null,
		board_name				varchar(256)
			constraint			bb_control_name_nn
			not null,
		board_short_name		varchar(256)
			constraint			bb_control_short_name_nn
			not null,
		board_short_description	varchar(256)
			constraint			bb_control_short_desc_nn
			not null,
		board_description_len	number
			constraint			bb_control_desc_len_nn
			not null,
		board_pic				varchar(256),
		board_max_post_count	number(6)
			constraint			bb_control_max_post_count_nn
			not null,
		board_max_post_age		number(3)
			constraint			bb_control_max_post_age_nn
			not null,
		board_flags				number(3)
			constraint			bb_control_flags_nn
			not null,
		board_type				number(3)
			constraint			bb_control_type_nn
			not null,
		board_last_post_time	date
			constraint			bb_control_last_post_nn
			not null,
		board_description		long raw
			constraint			bb_control_desc_nn
			not null,
		constraint		bb_control_pk
			primary key (board_id)
			using index tablespace boardi01
			storage (initial 100K next 50K)
	)
	tablespace boardd01
	storage (initial 1M next 500K);

	commit;


/* create a new board */
/* when creating a new board, choose a board_id by seeing what exists first */
/* then use http://localhost/aw-cgi/eBayISAPI.dll?AdminBoardChangeShow&boardname=board_short_name to change the board */
INSERT INTO ebay_bulletin_board_control
( board_id,
  board_name,
  board_short_name,
  board_short_description,
  board_description_len,
  board_max_post_count,
  board_max_post_age,
  board_flags,
  board_type,
  board_last_post_time,
  board_description
)
values
( 17,
  'Antiques',
  'antiques',
  'Antiques',
  1,
  0,
  0,
  1,
  8,
  sysdate,
  hextoraw('00')
);

