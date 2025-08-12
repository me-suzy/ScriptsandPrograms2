<?php

/* D.E. Classifieds v1.04 
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

function cnfg($the_key){


  $cnfg = array();



  /*****************************************
   *
   * DO NOT CHANGE ANYTHING 
   * ABOVE THIS POINT, 
   * UNLESS YOU KNOW WHAT YOU'RE
   * DOING.
   */

   
  /* This if the info that you'll need for 
   * connecting to and talking to you database.
   * You may have to get this info from your 
   * web hosting provider.
   */
  $cnfg['dbHost']         = ""; 
  $cnfg['dbUser']         = "";
  $cnfg['dbPass']         = ""; 
  $cnfg['dbName']         = "";


  /* deHome - path to index file.
   */
  $cnfg['deHome']         = "http://domain.com/path/to/declassifieds/index.php"; 

  /* deDir - path to classifieds directory. 
   * Include trailing slash (/) 
   */
  $cnfg['deDir']          = "http://domain.com/path/to/declassifieds/"; 


  /* This is the name of the site.
   * This shouln't be confused with the domain name 
   * of the site, although the two could be the same.
   * For example if the name of your classified ads 
   * site is "Big Joe's Classifieds", then you should 
   * set the value of 'siteName' to "Big Joe's Classifieds" .
   * The name of the site may be used in outgoing emails, and 
   * in other situations when the application needs to 
   * refer to the site that it belongs to.
   */
  $cnfg['siteName'] = "D.E. Classifieds Demo"; 


  
  /* These values determine which template files 
   * are used for a particular page.
   * All the keys are prefixed with "tmplt_".
   * After the "tmplt_" prefix is the exact 
   * name of the php file(minus the .php extension 
   * that uses the template file.
   * 
   * Example: 
   * $cnfg['tmplt_add_item'] = "html_template1.php"; 
   * 
   * In the example "add_item.php" uses the template 
   * file named "html_template1.php" .
   *
   * Caution:
   *  The value should only be the file name of the 
   *  template file.  It should not contain the path 
   *  to the file itself. The path to the file should 
   *  be supplied in the "path_cnfg.php" file in the 
   *  'pathToTemplatesDir' value.  
   *  Whatever template file you use should
   *  be kept in your "templates" directory.
   *
   */
  $cnfg['tmplt_add_item'] = "html_template1.php"; 

  $cnfg['tmplt_add_item_results'] = "html_template1.php"; 
   
  $cnfg['tmplt_details'] = "html_template1.php";   

  $cnfg['tmplt_edit'] = "html_template1.php"; 
  
  $cnfg['tmplt_index'] = "html_template1.php"; 

  $cnfg['tmplt_log_in'] = "html_template1.php"; 

  $cnfg['tmplt_log_out'] = "html_template1.php"; 
  
  $cnfg['tmplt_register'] = "html_template1.php"; 
 
  $cnfg['tmplt_search'] = "html_template1.php"; 

  $cnfg['tmplt_select_to_add'] = "html_template1.php"; 

  $cnfg['tmplt_showCat'] = "html_template1.php"; 

  $cnfg['tmplt_verify_registration'] = "html_template1.php"; 


  /* replyEmail -  This is the email address shown when new users
   * registered.  
   * If this value is not a valid, deliverable email address it could cause 
   * problems with some ISPs.
   */
  $cnfg['replyEmail']     = "webmaster@domain.com"; 


  /******************************
   SETTING THE VALUES BELOW THIS POINT IS OPTIONAL, 
   ALTHOUGH DOING SO WILL GIVE YOU MUCH MORE CONTROL 
   OVER HOW YOUR SITE LOOKS ANS BEHAVES.
   ****************/

  /* logOutMessage - The message the user recieves when 
   * they log off of the site.
   * I'll probably move this to another file in future versions.
   */
  $cnfg['logOutMessage']  = "You have successfully logged out.<BR>"; 


  /* expireAdsDays - The number of days the ad 
   * will expire in after it's been posted.
   * Do not quote.
   */
  $cnfg['expireAdsDays'] = 30; 

  $cnfg['expireTempUsersDays'] = 7; 

  
  /* rowsOfAdsTableWidth - 
   * This sets the width of the table 
   * where rows of results are displayed 
   * after searching; and when browsing 
   * categories.
   * Width is relative to the element that 
   * this table is contained in.(ie relative to its parent table)
   */
  $cnfg['rowsOfAdsTableWidth'] = "80%"; 

  /* viewAdsRowColor1 -
   * viewAdsRowColor2 -
   * These are the colors of the rows 
   * where people are browsing/searching ads.
   */
  $cnfg['viewAdsRowColor1'] = "#CCCC88"; 

  $cnfg['viewAdsRowColor2'] = "#FFFF66"; 


  /* rowsOfEditAdsTableWidth - 
   * This sets the width of the table 
   * on the edit page.
   */
  $cnfg['rowsOfEditAdsTableWidth'] = "100%";   


  /* editAdsRowColor1 -
   * editAdsRowColor2 -
   * These are the colors of the rows 
   * on the page where the user edits their ads.
   */
  $cnfg['editAdsRowColor1'] = "#CC6668"; 

  $cnfg['editAdsRowColor2'] = "#FF6666"; 


  /* mainCatsCols - Number of columns 
   * for category navigation. 
   * This number should not be quoted. 
   */
  $cnfg['mainCatsCols'] = 1; 

  /* mainCatsBufferCols - 
   * Space between columns for category navigation. 
   *
   * Right way: 'mainCatsBufferCols' = "20";
   * Right way: 'mainCatsBufferCols' = "20%";
   * Wrong way: 'mainCatsBufferCols' = 20px;
   * 
   * This value has no effect is mainCatsCols is set to 1 .
   */
  $cnfg['mainCatsBufferCols'] = "10"; 


  /* Width of table that top-level categories will 
   * be displayed in. 
   * Should be quoted.
   */
  $cnfg['mainCatsTableWidth'] = "100%"; 

  $cnfg['mainCatsTableSpace'] = "1"; 

  $cnfg['mainCatsTablePad'] = "1"; 

  /* The max width for each column.
   * 
   */
  $cnfg['mainCatsTdWidth'] = "100%"; 

  /* Set to true If you want the list of top-level categories to be 
   * centered inside of their parent table, false if you don't.  
   * This should not be quoted.
   */
  $cnfg['mainCatsCenter'] = true;

  /* Number of columns for sub-category navigation. 
   * Should NOT be quoted. 
   */
  $cnfg['subCatsCols'] = 3; 


  /* Same rules apply for subCatsBufferCols 
   * as for mainCatsBufferCols.
   */
  $cnfg['subCatsBufferCols'] = "12%"; 


  /* Width of table that sub categories will 
   * be displayed in. 
   * Should be quoted.
   */
  $cnfg['subCatsTableWidth'] = "100%"; 

  $cnfg['subCatsTableSpace'] = "3"; 

  $cnfg['subCatsTablePad'] = "3"; 

  /* The max width for each sub-cat column
   * Just a bare number.
   */
  $cnfg['subCatsTdWidth'] = "25%"; 

  /* Set to true If you want the sub categories to be 
   * centered inside of their parent table, false if you don't.  
   * This should not be quoted.
   */
  $cnfg['subCatsCenter'] = true;

  $cnfg['logInFormCaption'] = "LogIn"; 

  $cnfg['logInFormCaptionFontColor'] = "#000000"; 

  $cnfg['logInFormCaptionBgColor'] = "#FFFF66"; 
  

  $cnfg['logInFormWidth'] = "120";

  $cnfg['logInFormBgColor'] = "#CCCCCC"; 

  $cnfg['logInStatusWidth'] = "150";

  $cnfg['logInStatusBgColor'] = "#FFFFFF"; 


  /* userPassMinLength -
   * userPassMaxLength -
   * User will get an error if he/she tries to 
   * choose a password shorter than 'userPassMinLength' 
   * or longer than 'userPassMaxLength' .
   */
  $cnfg['userPassMinLength'] = 5;
   
  /* If you want user passwords to be able 
   * to be longer than 20 characters then 
   * you'll need to change the definition of the 
   * password field in the std_users table.
   */
  $cnfg['userPassMaxLength'] = 20; 


  /* userNameMinLength -
   * userNameMaxLength -
   * Same rules apply as for the above. 
   */
  $cnfg['userNameMinLength'] = 5;

  $cnfg['userNameMaxLength'] = 20;



  /* DO NOT CHANGE ANYTHING 
   * BELOW THIS POINT, 
   * UNLESS YOU KNOW WHAT YOU'RE
   * DOING.
   *
   ******************************************/


  return $cnfg[$the_key];


} // end function get_cnfg_varrs()


?>