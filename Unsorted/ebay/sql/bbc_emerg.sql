/*	$Id: bbc_emerg.sql,v 1.3 1999/02/21 02:52:20 josh Exp $	*/
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
( 22,
  'Emergency Contact',
  'emerg',
  'Emergency Contact',
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
<b>Emergency User Contact Board</b>
</font>
<br>
<br>
<font size="3" face="arial,helvetica">
Need to get in touch with someone? Can't find someone? Leave emergency messages on this board for other members of the eBay community!<br><br>
And don't forget to check this board for people who might be trying to get in touch with you!<br><br>
Important: Messages postsed to this board by eBay community members are solely the opinion and responsibility of the person posting the message. eBay is not responsible for messages left by individuals who are not eBay staff.
</font>
