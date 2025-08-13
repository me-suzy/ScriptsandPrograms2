/*	$Id: bbc_elvis.sql,v 1.3 1999/02/21 02:52:19 josh Exp $	*/
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
( 21,
  'Elvis',
  'elvis',
  'Elvis',
  1,
  0,
  0,
  1,
  10,
  sysdate,
  hextoraw('00')
);

/* then used http://localhost/aw-cgi/eBayISAPI.dll?AdminBoardChangeShow&boardname=glass to change the board description */
<font size="4" face="arial,helvetica">
<b>Chat with other Elvis collectors in the eBay community!</b>
</font>
<br>
<br>
<font size="3" face="arial,helvetica">
eBay encourages open communication between members of the eBay community. We provide this discussion board for collectors, experts and friends to chat with each other. We invite and encourage all members of the eBay community
to use this discussion board. This board is not connected in any way with the company, and any messages are solely the opinion and responsibility of the person posting the message.
<br><br>
<font color=red><b>No business please!</b></font> The use of this discussion board for buying, selling or trading will not be tolerated! Violations may result in the <i>elimination</i> of this discussion board and/or in the <i>suspension</i> of board privileges for offenders!
</font>
