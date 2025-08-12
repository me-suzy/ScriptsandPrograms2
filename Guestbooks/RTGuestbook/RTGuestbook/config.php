<?php 

// Mysql database params

$db_host = '';
$db_name = '';
$db_user = '';
$db_password = '';

//How many news in a page:

$step_guestbook = 10;

//Name of the page with the guestbook:

$page_guestbook = 'index.php';

// INSERT HERE YOUR COSTUM HTML CODE.
// write %%DATE%% %%AUTHOR%% %%MAIL%% %%TEXT%% 
// where you want to insert date, author, mail and comment..
// DO NOT CHANGE THIS ENTRY IF YOU'RE NOT SURE WHAT YOU'RE DOING

$guestbook_template = '
<br><b>Author: </b>
<a href="mailto:%%MAIL%%">%%AUTHOR%%</a>
<br><b>Date: </b>
%%DATE%%
</a><br><b>Comments: </b>
%%TEXT%%
<br>
';

?>
