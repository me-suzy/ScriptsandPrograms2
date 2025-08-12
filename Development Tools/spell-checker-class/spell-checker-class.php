<?php

/**
* Simple PHP MySQL Spell checker class.
* MySQL SQL to make the word table: 
* CREATE TABLE `words` (
*  `WORD` char(50) NOT NULL default '',
*  PRIMARY KEY  (`WORD`)
* ) TYPE=MyISAM;
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @author Sam Clarke <admin@free-php.org.uk>
* @version 1.0
* @package MySQL PHP spell checker
* @example example.php
* @copyright Copyright (C) 2005, Sam Clarke.
*/

/*
+----------------------------------------------+
|                                              |
|        PHP MySQL Spell checker class         |
|                                              |
+----------------------------------------------+
| Filename   : spell-checker-class.php         |
| Created    : 22-Sep-05 15:54 GMT             |
| Created By : Sam Clarke                      |
| Email      : admin@free-php.org.uk           |
| Version    : 1.0                             |
|                                              |
+----------------------------------------------+
*/

class spell_checker
{
  /**
  * @access private
  */
  var $mysql_link;

  /**
  * MySQL database password
  * @access private
  * @var string
  */
  var $pass;

  /**
  * MySQL database username
  * @access private
  * @var string
  */
  var $user;

  /**
  * MySQL host name
  * @access private
  * @var string
  */
  var $host = 'localhost';

  /**
  * MySQL database name
  * @access private
  * @var string
  */
  var $db;

  /**
  * MySQL table name
  * @access private
  * @var string
  */
  var $table = 'words';

  /**
  * Stores the array of words to check
  * @access private
  */
  var $words = array();

  /**
  * Place in the array of words
  * @access private
  */
  var $place = 0;

  /**
  * Stores words to ignore
  * @access private
  */
  var $ignore = array();



  /**
  * connects to the db and selects the db
  *
  * Opens a connection with MySQL and selects the database.
  * You must call set_mysql_info() first!
  *
  * @return bool returns true if can open a connection and sellect the db or it returns false
  */
  function db_connect()
  {
    $this->mysql_link = mysql_connect($this->host, $this->user, $this->pass) or die(mysql_error()); // connect to MySQL
    if (!$this->mysql_link) // if the connection is false
    {
      return false; // return false
    }

    if (mysql_select_db($this->db, $this->mysql_link) or die(mysql_error())) // if the connection is OK select the db
    {
      return true; // if it select the db OK return true
    }
    return false; // other wise return false
  }


  /**
  * Closes the link to MySQL
  *
  * Closes the link to MySQL that was opened by db_connect()
  */
  function close_db()
  {
    mysql_close($this->mysql_link);
  }


  /**
  * matches all the words and puts them in the word array
  *
  * Matches all the words in the string it is passed
  * and puts them in the word array ready for checking.
  *
  * @param string $str // the string to get the words from
  * @return bool returns true if it can match some words or it returns false
  */
  function get_words_from_str($str)
  {
    $pattern = "/[a-zA-Z']+/i"; // pattern to make only word chars
    if (preg_match_all($pattern, $str, $word)) // match them
    {
      for($i=0;isset($word[0][$i]);$i++)
      {
         $this->words[$i] = $word[0][$i]; // store them in the word array
      }
      return true;
    }
    return false;
  }


  /**
  * Checks if a word is in the dictionary table
  *
  * Checks if a word is in the MySQL dictionary table.
  *
  * @param string $word the word to check
  * @return bool returns true if it can match some words or it returns false
  */
  function check_word($word)
  {
    $word = strtolower($word); // make the word lower case
    // make sure theres no nasty stuff in the word
    $word = mysql_real_escape_string($word, $this->mysql_link);
    $sql = 'SELECT `WORD` FROM `' . $this->table . '` WHERE `WORD` = \'' . $word . '\' LIMIT 1';
    $check = mysql_query($sql, $this->mysql_link); // check the word

    if (!$check) // if there was an error return true
    {
      return false;
    }

    // if 1 rows came back then that word does not exist in the db so return true
    if (mysql_num_rows($check) === 1)
    {
      return true;
    }
    return false; // other wise return false
  }


  /**
  * Gets the next wrong word
  *
  * Gets the next wrong word from the array of words made
  * by get_words_from_str().
  *
  * @return mixed returns the wrong word or returns false if there are no more words to check
  */
  // gets the next wrong word
  function get_next_wrong_word()
  {
    for (;isset($this->words[$this->place]);) // while there are still words
    {
      if (!$this->check_word($this->words[$this->place])) // check it
      {
        if (in_array($this->words[$this->place], $this->ignore)) // check it in the ignore list
        {
          $this->place++; // if it's in the ignore array goto next word
        }
        else
        {
          $this->place++; // add 1 to place so we don't check it again
          return $this->words[$this->place-1]; // return the wrong word
        }
      }
      $this->place++; // if it's right goto next word
    }
    return false; // there are no words left so return false
  }


  /**
  * Resets the place for get_next_wrong_word()
  *
  * Resets the place for get_next_wrong_word() so it starts
  * at the begining.
  *
  * @return void
  */
  function reset_get_next_wrong_word()
  {
    $this->place=0;
  }


  /**
  * Sets the MySQL infomation
  *
  * Sets the MySQL infomation used in this spellcheck class.
  *
  * @param string $user The username to use for MySQL
  * @param string $pass The password to use for MySQL
  * @param string $db The db name to use for MySQL
  * @param string $table The MySQL table name with the spellcheck words in.
  * @param string $host The MySQL hostname to use
  * @return void
  */
  function set_mysql_info($user, $pass, $db, $table='words', $host='localhost')
  {
    $this->pass  = $pass;
    $this->user  = $user;
    $this->host  = $host;
    $this->db    = $db;
    $this->table = $table;
  }


  /**
  * Gets all the worng words and puts them in an array.
  *
  * Gets all the worng words from the array of words made
  * by get_words_from_str() and returns them in an array.
  *
  * @return void
  */
  function get_all_wrong_words()
  {
    $wrong_words = array();
    $place = 0;
    for (;isset($this->words[$place]);) // while there are still words
    {
      if (!$this->check_word($this->words[$place])) // check it
      {
        if (in_array($this->words[$place], $this->ignore)) // check it in the ignore list
        {
          $place++; // if it's in the ignore array goto next word
        }
        $this->place++; // add 1 to place so we don't check it again
        $wrong_words[] = $this->words[$place]; // return the wrong word
      }
      $place++; // if it's right goto next word
    }
    return $wrong_words; // there are no words left so return the array
  }


  /**
  * Replaces all the words in str.
  *
  * Replaces all the words in str from the array of words made
  * by get_words_from_str() that this function calls. It
  * will add what ever you put as $rf at the front of of all the
  * wrong words and add what ever you put as $re to the end of
  * them so you could make $rf = '<span style="color:#FF0000">' 
  * and $re = '</span>' so that people can see the spelling mistakes.
  *
  * @param string $str the string you want the wrong words replaced in
  * @param string $rf what you want to put infront of the wrong words
  * @param string $re what you want to put at the end of the wrong words
  * @return string returns the string with the wring wirds replaced
  */
  function replace_all($str, $rf = '<span style="color:#FF0000">', $re = '</span>')
  {
     if (!$this->get_words_from_str($str)) // make sure could get the words
     {
        return $str; // return the string
     }
     $wrongwords = $this->get_all_wrong_words(); // get the wrong words

     for($i=0;isset($wrongwords[$i]);$i++)
     {
        $str = preg_replace("/\b".$wrongwords[$i]."\b/si", $rf.$wrongwords[$i].$re, $str);
     }
     return $str; // return the string
  }


  /**
  * Adds a word to the ignore array.
  *
  * @param string $word the word to ignore
  * @return void
  */
  function add_word_to_ignore($word)
  {
    $this->ignore[] = $word; // add the word to the ignore array
  }


  /**
  * Finds any words with the same soundex as the word and returns them
  * in an array.
  *
  * Finds any words with the same soundex as the word and returns them
  * in an array it takes a secound param the limits how many results are
  * returned.
  *
  * @param string $word word to find words with the same soundex of
  * @return mixed returns flase if there was an error searching or returns an array
  * of words that have the same soundex of $word
  */
  function suggest($word, $max=20)
  {
    $suggestions = array(); // make an array to store the suggestions in
    $word = strtolower($word); // make the word lower case
    // make sure theres no nasty stuff in the word
    $word = mysql_real_escape_string($word, $this->mysql_link);
    $sql = 'SELECT `WORD` FROM `' . $this->table . '` WHERE SOUNDEX(`WORD`)';
    $sql .= ' = SOUNDEX(\'' . $word . '\') LIMIT ' . $max . '';
    $results = mysql_query($sql, $this->mysql_link); // run the sql
    if (!$results) // if there was an error runing the sql return false
    {
      return false;
    }
    for(;$row = mysql_fetch_array($results, MYSQL_ASSOC);) // while there are still words
    {
      $suggestions[] = $row['WORD']; // add the word to the suggestion array
    }
    return $suggestions; // return the suggestion
  }
}
?>
