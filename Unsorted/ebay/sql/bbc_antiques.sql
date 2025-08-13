/*	$Id: bbc_antiques.sql,v 1.3 1999/02/21 02:52:17 josh Exp $	*/
/* create a new board */
/* when creating a new board, choose a unique board_id by seeing what exists first */

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

/* then used http://localhost/aw-cgi/eBayISAPI.dll?AdminBoardChangeShow&boardname=antiques to change the board description */
<font size="4"><b>Chat with other antique collectors in the eBay community!</b></font><br><br>
<font size="2">eBay encourages open communication between members of the eBay community. We provide this discussion board for collectors, experts and friends to chat with each other. No business please! The use of this discussion board for buying or selling, trading, tax evasion, money laundering, theft, bank robbery, fraud, embezzlement or bribery will not be tolerated! Violations may result in the elimination of this discussion board and/or in the suspension of board privileges for offenders!</font>
