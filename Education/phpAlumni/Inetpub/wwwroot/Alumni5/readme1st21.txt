Welcome to KAB WEBs software!

phpAlumni v2.1 beta is Copyright(C) 2002, KAB WEBs

phpAlumni is designed for those sites that run a dedicated school alumni site. This script can be adapted to any site by modifying the CSS file. These instructions assume you have some knowledge of PHP 4.

v2.1 Updates
1) This version fixes v2.0 outstanding issues.
   - You can now log out from two places; if you are logged in as the admin you can
     logout from the admin screen, and you can logout a user from the main list page.
   - If you click on edit while viewing someone else's record, and you are logged in,
     you will get your record, otherwise you will be prompted to login.

v2.0 Outstanding Issues
None

v2.0 Updates
1) This version added the features of user security. Users may edit only their record. 
2) An administrator section was added to allow the administrator to update the Marital Status codes and the States.

v2.0 Outstanding Issues
1) When the administrator logs out, they are not actually logged out. 
2) If you try to edit a listing that is not yours you will get a blank screen.
These issues should be corrected in the next version update.

This software may be modified only if the the footer portion of the script retains the copyright and link the KAB WEBs. 

To run this script :

1) open the Alm.sql file and up load it to your MySQL server. 

2) open the file db_mysql.php and find the text :

  /* public: connection parameters */
  var $Host     = "localhost";
  var $Database = "";
  var $User     = "";
  var $Password = "";
 
  modify these parameters to your server settings.

3) open the file common.php and find this text:

  function Initialize()
    {
        $this->AbsolutePage = 0;
        $this->PageSize = 0;
        $this->Database = "";
        $this->Host = "localhost";
        $this->User = "";
        $this->Password = "";
        $this->Persistent = true;
        $this->RecordsCount = 0;
        $this->RecordNumber = 0;
        $this->DateFormat = Array("dd", "/", "mm", "/", "yyyy", " ", "HH", ":", "nn", ":", "ss");
        $this->BooleanFormat = Array(1, 0, "");
        $this->Errors = New clsErrors();
    }


  modify the database, host, user and password parameters only to your server settings.

4) Navigate to http://www.yourdomain.com/phpAlumni/alumni_list.php to start the application.

5) The administrator ID is "admin" and the password is "pw" without the quotes. It is strongly suggested that you change the password, but do not alter the ID.


That is all! Now enjoy!

If you need help enter the forum section at http://www.kabwebs.com and leave the admin a message. This is the quickest way to get help. This script cannot be bundles with any other products or sold without written permission from KAB WEBs. 