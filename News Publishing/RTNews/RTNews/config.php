<?php 

// Mysql database params

$db_host = '';
$db_name = '';
$db_user = '';
$db_password = '';

//How many news in a page:

$step_news = 10;

//Name of the page with the guestbook:

$page_news = 'index.php';

// INSERT HERE YOUR COSTUM HTML CODE.
// write %%DATE%% %%AUTHOR%% %%MAIL%% %%TEXT%% %%TITLE%% 
// where you want to insert date, author, mail, news and title..
// DO NOT CHANGE THIS ENTRY IF YOU'RE NOT SURE WHAT YOU'RE DOING

$news_template = '
<table width="85%" border="1">
  <tr align="center">
    <td width="33%"> %%DATE%%
</td>
<td width="34%"><b> %%TITLE%%
 </b></td>  
    <td width="33%"><a href="mailto: %%MAIL%%">
%%AUTHOR%% </a></td>
  </tr>
  <tr align="left">
    <td colspan="3">%%TEXT%%
	</td>
  </tr>
</table> <br>
';

?>
