<?php

require_once ('dbconfig.php');

/* Ratings
	The rating values should start with and continue to
	a maximum number of your choice but without skipping any numbers.
	The names of each can be whatever you want though there have to 
	exactly five numbered one through five.
*/
$ratings = array (
	'1 Star' => 1,
	'2 Stars' => 2,
	'3 Stars' => 3,
	'4 Stars' => 4,
	'5 Stars' => 5
);

/* Classifications/Genres
	These are static classifications.  Changing them
	after scenes and stories have been written and
	classified will cause unpredictable problems.
	That said, you can add new ones and rearrange
	the existing ones any way you wish. 
*/
$classifications = array (
	'Comedy',
	'Drama',
	'Romance',
	'Suspense',
	'Horror',
	'Sci-Fi',
	'Biography',
	'Fantasy',
	'Mystery',
	'Documentary'
);

/* Adult classificiations
	Note classificiations that can contain adult
	content.
*/
$adult = array (
	'Drama',
	'Romance'
);

/* Themes
	Themes allow you to change the entire look and
	behaviour of the site. The name of the theme
	must match the name of the folder in which
	the theme files are located.  Also, the theme
	folder must be in the root of the 'themes'
	folder which, itself, is in the root of the
	site.
*/
$global_theme = 'default';

/* The name of your StoryStream powered website */
$site_name = 'StoryStream';

/* The email to which status and server error messages are sent automatically */
$admin_email = 'me@example.com';

/* If this is set to 'true' then anytime a PHP error is encountered the administrator will receive a detailed error report in their email inbox */
$admin_email_errors = true;

/** Choose an appropriate language.  Possible languages (with codes) are:
	English - en (default)
*/
$language = 'en';

?>
