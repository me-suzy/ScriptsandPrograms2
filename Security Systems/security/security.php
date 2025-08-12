<?php
 /* Security Library By Jeff Blanchette (JBlanch) of Http://JBlanch.us */
 
   /* Function SecurityError
       Prints out an error message formatted in SecurityLib style.  Used internally
    */
     function SecurityError($errmsg,$exit = true){
       echo "<b>PHP Security Error:</b> <u>". $errmsg ."</u><br>\n";
        if($exit) exit;
     }  



   /* Function GetVar
          Useage -
            This function is used for web applications that grab data from either the REQUEST, POST, or GET method. 
            Most developers don't protect the data that they grab from these places, leading to SQL injections, malacious data,
            XSS hacks, and many other things.  Along from annyoing errors from certian PHP.ini file setups.
            
            The function validates the data by taking out malcious characters using htmlspecialchars() and also makes sure
            the variable is set to NULL or "" before returning.  
 
            But it also can return another default value if specified, such as this example for getting a MySql ORDER BY statement.

            $ord = GetVar("ord","GET","DESC");
            
            Therefore if the value isn't found in the GET string, it's set to DESC automatically when returned.
 
            It's a practice PHP developers need to start using, and this function makes it easy to do!

              name: Tells the function the name of the variable which to get from the source
              source: Defines where PHP should grab the data from.  (REQUEST, POST, or GET)
              default: Tells the function what the value should be if the source data isn't found.  Default is ""
              protect: If true, will pass the data through the PHP function htmlspecialchars(). Default TRUE.
              
        */



  function GetVar($name,$source,$default = "",$protect = true){
   $var = "";
   $souce = strtoupper($source);
    switch($source){
     default:
       $var = isset($_REQUEST[$name]) ? ($protect ? htmlspecialchars($_REQUEST[$name]) : $_REQUEST[$name]) : $default;
     break;
     case "GET":
       $var = isset($_GET[$name]) ? ($protect ? htmlspecialchars($_GET[$name]) : $_GET[$name]) : $default;
     break;

     case "POST":
       $var = isset($_POST[$name]) ? ($protect ? htmlspecialchars($_POST[$name]) : $_POST[$name]) : $default;
     break;
    }
   return $var;
  }

  /* Database Functions
    
      Database functions are slightly specific to which database you can use, they are
      on a set of keywords used generally in MySql databases querys to determine how
      'secure' they are.
  */
 
  /* Database Keyword Security 
   
       This is the section which the dbQuery uses to find malcious words.
       DO NOT ALTER THIS SECTION UNLESS YOU KNOW WHAT YOU ARE DOING.
  
        Useage:
          To add a new keyword, use the function dbAddKeyword(keyword).  It will place the word
          into the array.
  */
  
  $SDB_KEYWORDS = array();

  function dbAddKeyword($keyword){
   global $SDB_KEYWORDS;
    $keyword = htmlspecialchars($keyword);
       $SDB_KEYWORDS[] = $keyword;
  }

   dbAddKeyword("mysql.user");      dbAddKeyword("SET PASSWORD FOR");
   dbAddKeyword("FLUSH");           dbAddKeyword("PRIVILEGES");
   dbAddKeyword("GRANT"); 	      dbAddKeyword("USEAGE");
   dbAddKeyword("USER()");	      dbAddKeyword("DATABASE()");
   dbAddKeyword("SHOW DATABASES");  dbAddKeyword("SHOW TABLES");
   dbAddKeyword("GRANT"); 	      dbAddKeyword("CREATE");
   dbAddKeyword("DESCRIBE"); 	      dbAddKeyword("LOAD");
   dbAddKeyword("INFINE"); 	      dbAddKeyword("LINES");
   dbAddKeyword("LOCAL"); 	      dbAddKeyword("DROP");
   dbAddKeyword("UNLOCK");

  function dbQuery($query,$dbId = ""){
   global $SDB_KEYWORDS;
   if(trim($query) == ""){
    SecurityError("Blank Query",false);
    return false;
   }
    $error = false;
   foreach($SDB_KEYWORDS as $keyword){
    if(preg_match("/$keyword/i",$query)){
     $error = true;
    } 
   }
   if($error == true){
    SecurityError("Query contains words not suitable for query.");
    exit; // backup
   }else{ 
    return $query;
   }
   return false;
  }
?>