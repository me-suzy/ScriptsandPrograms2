<?php
include 'spell-checker-class.php'; // include the spell checker class


$spell_checker = new spell_checker; // Creat an instance of spell checker class
// set the info to use when doing things with MySQL
$spell_checker->set_mysql_info ('username', 'password', 'database_name');
$spell_checker->db_connect(); // connect to MySQL
 
 
if ($spell_checker->check_word('test')) // find out if test is in the dic
{
  echo 'Test is in the dic'."\n";
}
else
{
  echo 'You need a better dic'."\n";
}
 
 
$str = 'this is a string with a tiepoo.'."\n";
// get all the words from the string and store them in an array in the class
$spell_checker->get_words_from_str($str);
for(;$wrong = $spell_checker->get_next_wrong_word();)
{
  echo 'This word from the var str is wrong:' . $wrong . "\n";
}
 
echo $spell_checker->replace_all($str); // replace all the wrong words in $str
 
echo 'the words in the array below have a soundex like the word test'."\n";
print_r($spell_checker->suggest('test'));
 
$spell_checker->close_db(); // close db link
?>
