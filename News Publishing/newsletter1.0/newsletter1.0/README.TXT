**
*    newsletter script 
*
*    version:1.0
*    (c) www.sourceworkshop.com
*    author:Konstantin Atanasov
*    email: konstantin.atanasov@gmail.com
**


simple newsletter script, use MySQL database to store user list and news articles



    
    
USE:

  edit config.php and set database connection parameters, admin user name 
  and password .
  
 
  use this code your page 
  
    include_once "newsletter.php";
    include_once "config.php";
     
    
    global $con; // $con is set  in config.php
    
        
    $news = new newsletter($con); // create object
    // set newsletter page url
    $news->url = "your page url"; // url installed
    
    $news->parseCommands(); // parse commands from newssletter panel
                            // subscribe,confirm or unsubscribe command
                            
    
    
    
    see:    example_page.php 


NO WARRANTY
11. BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, 
THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, 
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, 
REPAIR OR CORRECTION.
  
  
  
    
    
