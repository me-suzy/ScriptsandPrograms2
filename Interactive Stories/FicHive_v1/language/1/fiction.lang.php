<?php

define( "LANG_FIC_ERR1" , "There are currently no categories" );
define( "LANG_FIC_ERR2" , "This category is closed" );
define( "LANG_FIC_ERR3" , "You do not have permission to view this category" );
define( "LANG_FIC_ERR4" , "Cannot find story" );
define( "LANG_FIC_ERR5" , "Cannot find author" );
define( "LANG_FIC_ERR6" , "There are currently no stories in this category" );
define( "LANG_SEARCH_ERR1" , "There are no authors beginning with " );
define( "LANG_SEARCH_ERR2", "You must wait <%TIME%> seconds between searches");

define( "LANG_FIC_CHAPTERS" , "Chapters: ");
define( "LANG_FIC_WORDS" , "Words: ");
define( "LANG_FIC_REVIEWS" , "Reviews: ");
define( "LANG_FIC_HITS" , "Hits: ");
define( "LANG_FIC_PUBLISHED" , "Published: ");
define( "LANG_FIC_UPDATED" , "Updated: ");
define( "LANG_FIC_WIP" , "Work In Progress");
define( "LANG_FIC_COMPLETE" , "Complete");

define( "LANG_FIC_REVIEW" , "Review");
define( "LANG_FIC_ADD2FAVORITES" , "Add To Favorites");
define( "LANG_FIC_PRINT" , "Print");
define( "LANG_FIC_BOOKMARK" , "Bookmark");

define( "LANG_SEARCH_FOR" , "Search For");
define( "LANG_SEARCH_TITLE", "Title");
define( "LANG_SEARCH_SUMMARY", "Summary");
define( "LANG_SEARCH_CHAPTER", "Chapter");
define( "LANG_SEARCH_AUTHORS", "Author List");

define( "LANG_SEARCH_AUTHORSTARTED", "Joined");
define( "LANG_SEARCH_AUTHORGROUP", "Group");

define("LANG_SEARCH_BOOEX" , "+ The word is mandatory 
- The word cannot appear  
< The word that follows has a lower relevance than other words 
> The word that follows has a higher relevance than other words  
() Used to group words into subexpressions. 
~ The word following contributes negatively to the relevance of the row
* The wildcard, indicating zero or more characters. It can only appear at the end of a word. 
&quot; Anything enclosed in the double quotes is taken as a whole"); 


define("LANG_SEARCH_BOOLEAN" , "<a href='#boolean'>Boolean search allowed</a>");

define("LANG_SEARCH_RESULTS", "<%C%> results found");

define( "LANG_FIC_ADDSTORY" , "Add Story" );
define( "LANG_FIC_EDITSTORY" , "Edit Story" );
define( "LANG_FIC_DELETESTORY" , "Delete Story" );
define( "LANG_FIC_ADDCHAPTER" , "Add Chapter" );
define( "LANG_FIC_EDITCHAPTER" , "Edit Chapter" );
define( "LANG_FIC_DELETECHAPTER" , "Delete Chapter" );
define( "LANG_FIC_DELETEFAVORITES" , "Delete Favorites" );

define( "LANG_FIC_APPROVECHAPTER" , "Approve Chapter" );
define( "LANG_FIC_MOVESTORY" , "Move Story" );
define( "LANG_FIC_REMOVEREVIEW" , "Remove Review" );

define( "LANG_FIC_REMOVESTORY" , "Remove Story" );

define( "LANG_FIC_MODERATORS" , "Moderated by: " );
define( "LANG_FIC_MODAPPROVE" , "Waiting approval: " );
define( "LANG_FIC_MODAPPROVECHAPTER" , "Approve" );
define( "LANG_FIC_MODREJECTCHAPTER" , "Reject" );
define( "LANG_FIC_MODDELETE" , "Delete Story" );
define( "LANG_FIC_MODMOVE" , "Move Story" );
define( "LANG_FIC_MODCHOOSESTORY" , "Choose Story" );

define( "LANG_FIC_SORTBY" , "Sort by: " );

define( "LANG_FIC_P2PPAGE" , "Page " );
define( "LANG_FIC_P2PNEXT" , "&raquo;&raquo;" );
define( "LANG_FIC_P2PBACK" , "&laquo;&laquo;" );

define( "LANG_FIC_CHAPTER" , "Chapter " );

define( "LANG_FIC_NC17" , "I am old enough to legally read this story" );

define( "LANG_FIC_RREVIEW" , "Review " );
define( "LANG_FIC_RREVIEWS" , "Reviews" );
define( "LANG_FIC_REVIEWNAME" , "Name " );
define( "LANG_FIC_REVIEW_ERR1" , "Cannot find story" );
define( "LANG_FIC_REVIEW_ERR2" , "There are no reviews for this story" );
define( "LANG_FIC_REVIEW_SUCC1" , "Thank you for taking the time to comment" );

define( "LANG_FIC_GENRE_ALL" , "Genre: All" );
define( "LANG_FIC_CHARACTER_ALL" , "Character: All" );

define( "LANG_FIC_A2F_ERR1" , "Guests cannot log favorites" );

$ficrating[] = "G";
$ficrating[] = "PG";
$ficrating[] = "PG-13";
$ficrating[] = "R";
$ficrating[] = "NC-17";

$ficratingdesc[] = " - General";
$ficratingdesc[] = " - Parental Guidance Suggested";
$ficratingdesc[] = " - Parental Guidance Strongly Cautioned";
$ficratingdesc[] = " - Restricted";
$ficratingdesc[] = " - No One 17 Or Under";

$ficgenre[] = LANG_FIC_GENRE_ALL;
$ficgenre[] = "General";
$ficgenre[] = "Action/Adventure";
$ficgenre[] = "Comedy";
$ficgenre[] = "Drama";
$ficgenre[] = "Fantasy";
$ficgenre[] = "Horror";
$ficgenre[] = "Mystery";
$ficgenre[] = "Parody";
$ficgenre[] = "Romance";
$ficgenre[] = "Sci-Fi";

$ficsort[0]['option'] = "Latest";
$ficsort[0]['sort'] = "latest DESC, stitle ASC";

$ficsort[1]['option'] = "Author";
$ficsort[1]['sort'] = "uname ASC, stitle ASC";

$ficsort[2]['option'] = "Title";
$ficsort[2]['sort'] = "stitle ASC, stitle ASC";

$ficsort[3]['option'] = "Earliest";
$ficsort[3]['sort'] = "earliest ASC, stitle ASC";

$ficsort[4]['option'] = "Finished";
$ficsort[4]['sort'] = "swip DESC, stitle ASC";

$ficsort[5]['option'] = "Hits";
$ficsort[5]['sort'] = "shits DESC, stitle ASC";

$ficsort[6]['option'] = "Reviews";
$ficsort[6]['sort'] = "revs DESC, stitle ASC";

$ficsort[7]['option'] = "Word Count";
$ficsort[7]['sort'] = "words DESC, stitle ASC";
?>