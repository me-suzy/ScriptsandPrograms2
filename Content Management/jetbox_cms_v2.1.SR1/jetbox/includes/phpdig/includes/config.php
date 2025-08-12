<?php
/*
--------------------------------------------------------------------------------
PhpDig 1.6.x
This program is provided under the GNU/GPL license.
See LICENSE file for more informations
All contributors are listed in the CREDITS file provided with this package

PhpDig Website : http://phpdig.toiletoine.net/
Contact email : phpdig@toiletoine.net
Author and main maintainer : Antoine Bajolet (fr) bajolet@toiletoine.net
--------------------------------------------------------------------------------
*/

//-------------CONFIGURATION FILE-------
//-------------PHP DIG------------------
define('PHPDIG_VERSION','1.6.2');
if (!isset($phpdig_language)) {
     $phpdig_language = "en"; // options en - fr and more if avaible
                              // can be set before
}
define('PHPDIG_ADM_AUTH','1');     //Activates/deactivates the authentification functions
define('PHPDIG_ADM_USER',''); //Username
define('PHPDIG_ADM_PASS',''); //Password

//template file and style
$template = "$relative_script_path/templates/simple.html";
define('HIGHLIGHT_BACKGROUND','#FFBB00');         //Highlighting background color
                                                 //Only for classic mode
define('HIGHLIGHT_COLOR','#000000');             //Highlighting text color
                                                 //Only for classic mode
define('LINK_TARGET','_blank');                  //Target for result links
define('WEIGHT_IMGSRC','./tpl_img/weight.gif');  //Baragraph image path
define('WEIGHT_HEIGHT','5');                     //Baragraph height
define('WEIGHT_WIDTH','50');                     //Max baragraph width
define('SEARCH_PAGE',$absolutepathfull.'view/search/');               //The name of the search page

define('SUMMARY_DISPLAY_LENGTH',150);          //Max chars displayed in
                                                 //description
define('DISPLAY_SNIPPETS',true);                 //Display text snippets
define('DISPLAY_SNIPPETS_NUM',3);                //Max snippets to display
define('DISPLAY_SUMMARY',false);                 //Display description
define('PHPDIG_DATE_FORMAT','\3-\2-\1');         // Date format for last update
                                                 // \1 is year, \2 month and \3 day

//---------DEFAULT VALUES
define('PHPDIG_ENCODING','iso-8859-1');  // encoding for interface,
                                         // search and indexing.
                                         // iso-8859-1 and iso-8859-2 supported
                                         // in this version
// replace strings for encodings (add more if needed)
// For accent/diadridic points insensitive
// read this file with the good encoding to complete it
$phpdig_string_subst['iso-8859-1'] = 'A:ÀÁÂÃÄÅ,a:àáâãäå,O:ÒÓÔÕÖØ,o:òóôõöø,E:ÈÉÊË,e:èéêë,C:Ç,c:ç,I:ÌÍÎÏ,i:ìíîï,U:ÙÚÛÜ,u:ùúûü,Y:Ý,y:ÿý,N:Ñ,n:ñ';
$phpdig_string_subst['iso-8859-2'] = 'A:ÁÂÄÃ¡,C:ÇÆÈ,D:ÏÐ,E:ÉËÊÌ,I:ÍÎ,L:Å¥£,N:ÑÒ,O:ÓÔÖÕ,R:ÀØ,S:¦ª©,T:Þ«,U:ÚÜÙÛ,Y:Ý,Z:¬¯®,a:áâäã±,c:çæè,d:ïð,e:éëêì,i:íî,l:åµ³,n:ñò,o:óôöõ,r:àø,s:¶º¹,t:þ»,u:úüùû,y:ý,z:¼¿¾';

// allowed chars in words lowercase and without accents only
$phpdig_words_chars['iso-8859-1'] = '[:alnum:]ðþß';
$phpdig_words_chars['iso-8859-2'] = '[:alnum:]ðþß';

define('SEARCH_DEFAULT_LIMIT',10);      //results per page
define('SEARCH_DEFAULT_MODE','start');  // default search mode (start|exact|any)

define('SPIDER_MAX_LIMIT',20);          //max recurse levels in sipder
define('SPIDER_DEFAULT_LIMIT',3);       //default value
define('RESPIDER_LIMIT',2);             //recurse limit for update

define('LIMIT_DAYS',7);                 //default days before reindex a page

define('SMALL_WORDS_SIZE',2);           //words to not index
define('MAX_WORDS_SIZE',30);            //max word size
define('PHPDIG_EXCLUDE_COMMENT','<!-- phpdigExclude -->');
                                        //comment to exclude a page part
define('PHPDIG_INCLUDE_COMMENT','<!-- phpdigInclude -->');
                                        //comment to include a page part

define('PHPDIG_DEFAULT_INDEX',true);    //phpDig considers /index or /default
                                        //html, htm, php, asp, phtml as the
                                        //same as '/'
define('PHPDIG_SESSID_REMOVE',true);    // remove SIDS from indexed URLS
define('PHPDIG_SESSID_VAR','PHPSESSID');// name of the SID variable

define('TITLE_WEIGHT',3);               //relative title weight
define('CHUNK_SIZE',2048);              //chunk size for regex processing

define('SUMMARY_LENGTH',500);           //length of results summary

define('TEXT_CONTENT_PATH','../../search_texts/'); //Text content files path
define('CONTENT_TEXT',1);                    //Activates/deactivates the
                                             //storage of text content.
define('PHPDIG_IN_DOMAIN',false);            //allows phpdig jump hosts in the same
                                             //domain. If the host is "www.mydomain.tld",
                                             //domain is "mydomain.tld"

define('PHPDIG_LOGS',true);           //write logs

//---------EXTERNAL TOOLS
// for catdoc please put the good encoding
define('PHPDIG_INDEX_MSWORD',false);
define('PHPDIG_PARSE_MSWORD','/usr/local/bin/catdoc');
define('PHPDIG_OPTION_MSWORD','-s 8859-1');

define('PHPDIG_INDEX_PDF',false);
define('PHPDIG_PARSE_PDF','/usr/local/bin/pstotext');
define('PHPDIG_OPTION_PDF','-cork');

define('PHPDIG_INDEX_MSEXCEL',false);
define('PHPDIG_PARSE_MSEXCEL','/usr/local/bin/xls2csv');
define('PHPDIG_OPTION_MSEXCEL','');

//---------FTP SETTINGS
define('FTP_ENABLE',0);//enable ftp content for distant PhpDig
define('FTP_HOST','<ftp host>'); //if distant PhpDig, ftp host;
define('FTP_PORT',21); //ftp port
define('FTP_PASV',1); //passive mode
define('FTP_PATH','<path to phpdig directory>'); //distant path from the ftp root
define('FTP_TEXT_PATH','text_content');//ftp path to text-content directory
define('FTP_USER','<ftp usename>');
define('FTP_PASS','<ftp password>');

// regular expression to ban useless external links in index
define('BANNED','^ad\.|banner|doubleclick');

// regexp forbidden extensions (return sometimes text/html mime-type !!!
define('FORBIDDEN_EXTENSIONS','\.(gz|z|tar|zip|tgz|msi|arj|zoo|rar|r[0-9]+|exe|bin|pkg|rpm|deb|bz2)$');

//----------HTML ENTITIES
$spec = array( "&amp" => "&",
               "&agrave" => "à",
               "&egrave" => "è",
               "&ugrave" => "ù",
               "&oacute;" => "ó",
               "&eacute" => "é",
               "&icirc" => "î",
               "&ocirc" => "ô",
               "&ucirc" => "û",
               "&ecirc" => "ê",
               "&ccedil" => "ç",
               "&#156" => "oe",
               "&gt" => " ",
               "&lt" => " ",
               "&deg" => " ",
               "&apos" => "'",
               "&quot" => " ",
               "&acirc" => "â",
               "&iuml" => "ï",
               "&euml" => "ë",
               "&auml" => "ä",
               "&ouml" => "ö",
               "&uuml" => "ü",
               "&nbsp" => " ",
               "&szlig" => "ß",
               "&iacute" => "í",
               "&reg" => " ",
               "&copy" => " ",
               "&aacute" => "á",
               "&Aacute" => "Á",
               "&eth" => "ð",
               "&ETH" => "Ð",
               "&Eacute" => "É",
               "&Iacute" => "Í",
               "&Oacute" => "Ó",
               "&uacute" => "ú",
               "&Uacute" => "Ú",
               "&THORN" => "Þ",
               "&thorn" => "þ",
               "&Ouml" => "Ö",
               "&aelig" => "æ",
               "&AELIG" => "Æ",
               "&aring" => "å",
               "&Aring" => "Å",
               "&oslash" => "ø",
               "&Oslash" => "Ø"
               );

//month names in iso dates
$month_names = array ('jan'=>1,
                      'feb'=>2,
                      'mar'=>3,
                      'apr'=>4,
                      'may'=>5,
                      'jun'=>6,
                      'jul'=>7,
                      'aug'=>8,
                      'sep'=>9,
                      'oct'=>10,
                      'nov'=>11,
                      'dec'=>12
                      );

//apache multi indexes parameters
$apache_indexes = array (  "?N=A" => 1,
                           "?N=D" => 1,
                           "?M=A" => 1,
                           "?M=D" => 1,
                           "?S=A" => 1,
                           "?S=D" => 1,
                           "?D=A" => 1,
                           "?D=D" => 1,
                           "?C=N&amp;O=A" => 1,
                           "?C=M&amp;O=A" => 1,
                           "?C=S&amp;O=A" => 1,
                           "?C=D&amp;O=A" => 1,
                           "?C=N&amp;O=D" => 1,
                           "?C=M&amp;O=D" => 1,
                           "?C=S&amp;O=D" => 1,
                           "?C=D&amp;O=D" => 1);


//includes language file
if (is_file("$relative_script_path/locales/$phpdig_language-language.php"))
    {include "$relative_script_path/locales/$phpdig_language-language.php";}
else
    {include "$relative_script_path/locales/en-language.php";}

//connection to database
if (is_file("$relative_script_path/includes/connect.php"))
    {
    include "$relative_script_path/includes/connect.php";
    }
else if(!isset($no_connect))
    {
    header("location:$relative_script_path/admin/install.php");
    }

//includes of librairies
include "$relative_script_path/libs/phpdig_functions.php";
include "$relative_script_path/libs/function_phpdig_form.php";
include "$relative_script_path/libs/mysql_functions.php";

// parse encodings (create global $phpdigEncode);
phpdigCreateSubstArrays($phpdig_string_subst);
// Send encoding if needed
if (!headers_sent()) {
   header('Content-type:text/html; Charset='.PHPDIG_ENCODING);
}
if (!isset($no_connect)) {
     phpdigCheckTables($id_connect,array('engine',
                                    'excludes',
                                    'keywords',
                                    'sites',
                                    'spider',
                                    'tempspider',
                                    'logs'));
}
?>