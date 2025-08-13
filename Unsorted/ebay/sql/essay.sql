/*	$Id: essay.sql,v 1.3 1999/02/21 02:54:20 josh Exp $	*/
/* create a new board for essay */
/* when creating a new board, choose a unique board_id by seeing what exists first */
/* change the short name for each essay */

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
( 32,
  'essay9',
  'essay9',
  'essay9',
  1,
  0,
  0,
  4,
  16,
  sysdate,
  hextoraw('00')
);
